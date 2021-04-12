<?php
/*
Copyright 2010 UNIPAMPA - Universidade Federal do Pampa

Este arquivo é parte do programa SGCE - Sistema de Gestão de Certificados Eletrônicos

O SGCE é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos
termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre
(FSF); na versão 2 da Licença.

Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA;
sem uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR.
Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.

Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
junto com este programa, se não, acesse o Portal do Software Público Brasileiro no
endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA
*/
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Gerenciador de acesso ao sistema. Possui validacao nativa no LDAP
 *
 * @author Daniel Michelon De Carli <daniel.de.carli@gmail.com>
 */

class Gerenciador_de_acesso {
    var $SESSAO_ACESSO_NEGADO = 0;
    var $SESSAO_ACESSO_LOGADO = 1;
    var $ACESSO_PALAVRA_CHAVE = "logado";

    var $LOGIN_FEEDBACK_ACESSO_NAO_AUTORIZADO = 0;
    var $LOGIN_FEEDBACK_SENHA_ERRADA = 1;
    var $LOGIN_FEEDBACK_ACESSO_AUTORIZADO = 2;


    var $tipoDeAutenticacao;
    var $AUTENTICACAO_LDAP = 'ldap';
    var $AUTENTICACAO_BANCO = 'banco';
    var $AUTENTICACAO_MISTA = 'mista';

    var $CI;

    function Gerenciador_de_acesso() {
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->config->load_db_items();
    }

    function login($login,$senha) {

      
        $CI =& get_instance();
        $userAllowed = false;
        $usersAllowed = $CI->config->item('usersAllowed');
        $this->tipoDeAutenticacao = $CI->config->item('tipo_de_autenticacao');


        if($usersAllowed) {
            foreach ($usersAllowed as $user) {
                if($login == $user) {
                    $userAllowed = true;
                }
            }

            if(!$userAllowed) {
                $autenticou = false;
                return $this->LOGIN_FEEDBACK_ACESSO_NAO_AUTORIZADO;
            }
        }
        
        switch ($this->tipoDeAutenticacao){
            case $this->AUTENTICACAO_LDAP:
                $autenticou = $this->ldap_login($login,$senha);                
                break;
            case $this->AUTENTICACAO_BANCO:
                $autenticou = $this->banco_login($login,$senha);
                break;
            case $this->AUTENTICACAO_MISTA:
                $autenticou = $this->login_misto($login, $senha);                
                break;
            
        } 

        if($autenticou) {
            $CI->session->set_userdata($this->ACESSO_PALAVRA_CHAVE,
                   $this->SESSAO_ACESSO_LOGADO);

            return $this->LOGIN_FEEDBACK_ACESSO_AUTORIZADO;
        }
        else {
            return $this->LOGIN_FEEDBACK_ACESSO_NAO_AUTORIZADO;
        }
    }

    function ldap_login($login,$senha){
        $CI =& get_instance();
        $server_ldap   =  $CI->config->item('server_ldap');
        $porta_ldap    =  $CI->config->item('porta_ldap');
        $base_dn       =  $CI->config->item('base_dn');        
        
        $ldapconn = ldap_connect($server_ldap,$porta_ldap);
        $ldapbind = @ldap_bind($ldapconn, "uid=$login,".$base_dn, $senha);

        if($ldapbind) {            
            $dadosUsuario            = $this->buscaDadosUsuario($login);                        
            if ($dadosUsuario) {
                $autenticou = true;
            }
        }
        else {
            $autenticou = false;
        }

        return $autenticou;
    }

    /**
     * Efetua login pelo banco de dados.
     *
     * @param String $login - login do usuario
     * @param String $senha - senha aberta
     * @return boolean - true caso logou, false caso contrario
     */
    function banco_login($login, $senha) {
        $CI =& get_instance();
        $autenticouBanco = false;        
        $CI->load->model('organizadores_model');
        $senha  = md5($senha);
        $result = $CI->organizadores_model->getByLoginSenha($login, $senha);
        
        if($result) {
            $CI->session->set_userdata('uid',  $result->de_usuario);
            $CI->session->set_userdata('cn',   $result->nm_organizador);
            $CI->session->set_userdata('mail', $result->de_email);
            $autenticouBanco = true;
        } else {
             $autenticouBanco = false;
        }
        return $autenticouBanco;
    }

    function usuarioLogado() {
        if ($CI->session->userdata($this->ACESSO_PALAVRA_CHAVE)!=
                $this->SESSAO_ACESSO_LOGADO) {
            return false;
        }
        return true;
    }

    function logout() {
        $CI =& get_instance();
        $CI->session->set_userdata($this->ACESSO_PALAVRA_CHAVE,
               $this->SESSAO_ACESSO_NEGADO);
    }
    
    /**
     * Verifica se o usuario esta autenticado no sistema. Caso nao esteja, � feito um direcionamento para a view de login
     */
    function usuarioAuth() {
        $CI =& get_instance();
        if(!($CI->session->userdata('logado') == '1')) {
                redirect('sistema/showLogin');
        }
    }
    
    /**
     * Funcao para login misto
     * 
     * @param type $login
     * @param type $senha
     * @return boolean 
     */
    
    function login_misto($login, $senha) {        
        $autenticouMisto = false;
        $autenticouBANCO = $this->banco_login($login,$senha);
        if (!$autenticouBANCO) {
            $autenticouLDAP =  $this->ldap_login($login,$senha);                
        }        
        if ($autenticouLDAP || $autenticouBANCO) {
            $autenticouMisto=true;
        } else {
            $autenticouMisto=false;                    
        }                
        return $autenticouMisto;
    }
    
    /**
     * Funcao para buscar dados do usuario entre os habilitados no banco
     * @param type $login
     * @return type 
     */
    
    function buscaDadosUsuario($login) {
        $CI =& get_instance();
        $CI->load->model('organizadores_model');
        
        $result = $CI->organizadores_model->getByUser($login);        
        
        if($result) {
            $CI->session->set_userdata('uid',  $result->de_usuario);
            $CI->session->set_userdata('cn',   $result->nm_organizador);
            $CI->session->set_userdata('mail', $result->de_email);            
            
            return true;
        } else {
            return false;
        }         
    }


}
?>