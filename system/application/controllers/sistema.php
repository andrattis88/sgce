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

<?php
/**
 * Controller - sistema
 * @author Pedro Jr     <pedro.junior@unipampa.edu.br>
 * @author Sergio Jr    <sergiojunior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 */

class Sistema extends Controller {

    /**
     * Construtor da Classe.
     *
     * Inicializa helpers e bibliotecas do CodeIgniter e verifica se o usuario
     * tem permissao para abrir o controller.
     *
     */
    function Sistema() {
        parent::Controller();        
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('data');
        $this->load->library('session');
        $this->load->library('pagination');
        $this->config->load_db_items();     
        $this->load->library('gerenciador_de_acesso');
        $this->lang->load('msg');

    }


    function index() {        
        $this->showLogin();
    }

    function principal() {
        if ($this->session->userdata('logado')=='0') {
            $retorno = "<br/><span class=\"aviso\">
                          <b>Você não possui a permissão de acesso necessária. 
                             Entre em contato com a Comissão Organizadora do seu evento.</b></span>";
           $this->logout($retorno);            
        } else {
            $data['corpo_pagina'] = "principal_view";
            $this->load->view('includes/templates/template', $data);
        }
    }

    function bloqueado() {
        $data["mensagem"] = "No momento, você não tem permissão para realizar esta ação.";
        $data['corpo_pagina']  = "bloqueado_view";
        $this->load->view('includes/templates/template', $data);
    }


    /**
     * Mostra tela de login
     * @param array $data = Dados retornados em case de falha no login
     */
    function showLogin($data=null) {
        $this->session->set_userdata('logado','0');
        $data['corpo_pagina'] = "login_view";
        $this->load->view('includes/templates/template', $data);
    }

     /**
      *  Metodo para login 
      */
    function login() {
        $dados = $_POST;
        $login = (isset($dados["login"]))? trim($dados["login"]) : "";
        $senha = (isset($dados["senha"]))? trim($dados["senha"]) : "";

        $result = $this->gerenciador_de_acesso->login($login,$senha);        
        //var_dump($result); die();
        
        switch ($result) {
            case $this->gerenciador_de_acesso->LOGIN_FEEDBACK_ACESSO_NAO_AUTORIZADO:
                    $data['retorno'] =  "<br /><b>Usuário não autenticado! Verifique se
                                         seu login está correto e se você está
                                         cadastrado como organizador de algum evento.</b>";
                    $this->showLogin($data);
                    break;
            case $this->gerenciador_de_acesso->LOGIN_FEEDBACK_SENHA_ERRADA:
                     $data['retorno'] =  "Usuario não autenticado. ";
                     $this->showLogin($data);
                 break;
            case $this->gerenciador_de_acesso->LOGIN_FEEDBACK_ACESSO_AUTORIZADO:

                 // loga usuario
                 $this->session->set_userdata('logado', '1');
                 $this->session->set_userdata('login', $login);
              
                 //resgata dados de usuario armaz. na sessao
                 $dadosSessao['de_usuario'] = $this->session->userdata('uid');
                 $dadosSessao['nm_usuario'] = $this->session->userdata('cn');
                 $dadosSessao['de_email']   = $this->session->userdata('mail');
                 
                 // Verifica se o usuario existe no cadastro de banco de dados
                 
                 $this->load->model('organizadores_model');
                 if (!$this->organizadores_model->getByUser($login)) {
                     $this->logout("Usuário não liberado. Verifique suas permissões de acesso junto aos responsáveis pelo evento.");
                 }
                 
                 // Verifica se o usuario e administrador do sistema
                 if ($this->isAdmin($dadosSessao['de_usuario'])) {
                     $this->session->set_userdata('admin', '1');
                 }
                 
                 // Verifica se o usuario e administrador limitado (flag 2)
                 if ($this->isAdminLimitado($dadosSessao['de_usuario'])) {
                     $this->session->set_userdata('admin', '2');
                 }                 
                 // Identifica como usuario normal
                 if ((!$this->isAdmin($dadosSessao['de_usuario'])) 
                  && (!$this->isAdminLimitado($dadosSessao['de_usuario']))) {
                     $this->session->set_userdata('admin', '0');
                 }                
                
                //verifica se o usuario e controlador global de qualidade
                if ($this->isControlador($dadosSessao['de_usuario'])) {
                     $this->session->set_userdata('controlador', '1');
                 }
                 else {
                     $this->session->set_userdata('controlador', '0');
                 }
                 
                //verifica permissoes em eventos do usuario
                $eventosOrganizador = $this->retornaEventos($dadosSessao['de_usuario']);
                if ($eventosOrganizador) {
                    $this->session->set_userdata('eventos_permitidos', $eventosOrganizador);                    
                }
                
                //verifica permissoes de controlador do usuario
                $eventosControlador = $this->retornaControlador($dadosSessao['de_usuario']);
                if ($eventosControlador) {
                   $this->session->set_userdata('eventos_controlador', $eventosControlador);                     
                }

                // caso o usuario nao tenha eventos no cadastro e nao seja controlador 
                // nem administrador (ou administrador limitado)...
                // faz o logout do usuario

                if ((!$eventosOrganizador) &&( 
                    ($this->isAdmin($dadosSessao['de_usuario'])) ||
                        ($this->isAdminLimitado($dadosSessao['de_usuario']))
                   || ($this->isControlador($dadosSessao['de_usuario'])))) {
                    redirect('sistema/principal');
                }
                
                // Verifica se o usuario nao eh administrador, limitado ou organizador de evento.
                // caso nao seja, faz o logout
                
                if ((empty($eventosOrganizador)) && 
                        (!$this->isAdmin($dadosSessao['de_usuario'])) && 
                        (!$this->isAdminLimitado($dadosSessao['de_usuario'])) && 
                        (!$this->isControlador($dadosSessao['de_usuario']))) {
                    
                    $this->logout("<br /><b>Usuário não autorizado! Verifique se
                                   seu login está correto e se você está
                                   cadastrado como organizador de algum evento.</b>");
                }
                                 
                if (!($this->session->userdata('referrer'))) {
                   redirect('sistema/principal');
                }
                else {
                   redirect($this->session->userdata('referrer'));
                }
                 break;
        }

    }

    /**
     * Metodo para logout 
     */
    function logout($mensagem=null) {           
        $this->gerenciador_de_acesso->logout();
        $this->session->set_userdata('logado','0');
        $this->session->set_userdata('admin','0');
        $this->session->sess_destroy();
        $data['retorno'] = $mensagem;        
        $data['corpo_pagina'] = "login_view";
        $this->load->view('includes/templates/template', $data);
    }
    
    /**
     * Lista os eventos onde o usuário é organizador
     * @param type $usuario
     * @return type 
     */

    function retornaEventos($usuario){
        $this->load->model('organizadores_model');
        $this->load->model('organizadores_evento_model');
        
        $dadosUsuario = $this->organizadores_model->getByUser($usuario);
        
        if ($dadosUsuario) {            
            $idUsuario = $dadosUsuario->id_organizador;   
            if ($idUsuario) {            
               return $this->organizadores_evento_model->listarEventosOrganizador($idUsuario);
            }
        }
    }
    
    /**
     * Lista os eventos onde o usuario é controlador de qualidade especifico
     * @param type $usuario
     * @return type 
     */
    
    function retornaControlador($usuario) {
        $this->load->model('organizadores_model');
        $this->load->model('organizadores_evento_model');
        
        $dadosUsuario = $this->organizadores_model->getByUser($usuario);
        
        if ($dadosUsuario) {            
            $idUsuario = $dadosUsuario->id_organizador;   
            if ($idUsuario) {            
                $controla = $this->organizadores_evento_model->listarEventosControlador($idUsuario);
                return $controla;                
            }
        }
        
    }

    function isAdmin($usuario) {
        $this->load->model('organizadores_model');
        if ($usuario) {            
            $dadosUsuario = $this->organizadores_model->getByUser($usuario);
            if ($dadosUsuario->fl_admin == 'S') {                
                return true;
            }
            else {
                return false;
            }             
        }
    }
    
    function isAdminLimitado($usuario) {
        $this->load->model('organizadores_model');
        if ($usuario) {            
            $dadosUsuario = $this->organizadores_model->getByUser($usuario);
            if ($dadosUsuario->fl_admin == 'L') {                
                return true;
            }
            else {
                return false;
            }             
        }
    }
    
    function isControlador($usuario) {
        $this->load->model('organizadores_model');
        if ($usuario) {            
            $dadosUsuario = $this->organizadores_model->getByUser($usuario);
            if ($dadosUsuario->fl_controlador == 'S') {                
                return true;
            }
            else {
                return false;
            }             
        }
    }

    /**
     * Carrega a view de recuperacao de senha
     */
    function recuperarSenha() {
        if($this->config->item('tipo_de_autenticacao') == 'banco') {
            $data['corpo_pagina'] = "recuperar_senha_view";
            $this->load->view('includes/templates/template', $data);
        } else {
            $data['mensagem']     = "<b>Atenção:</b> <br><br>A recuperação de senha não está disponível para ".
                                    "a configuração de autenticação atual. <br>";
            $data['corpo_pagina'] = "mensagem_view";
            $this->load->view('includes/templates/template', $data);
        }
    }


    /**
     * Gera uma senha aleatoria para o organizador que solicitou a recuperacao de
     * senha. Sera inserida uma solicitacao de recuperacao de senha para o id
     * do organizador, chamando um metodo da camada de modelo. Apos, sera enviado
     * um email ao organizador com a nova senha gerada, e um link para efetiva-la.
     */
    function gerarSenha() {
        $documento = $_POST["txtDocumento"];
        if($documento) {
            $this->load->model('organizadores_model');
            $organizador  = $this->organizadores_model->getByDocumento($documento);

            if(!$organizador) {
                $data["mensagem"] = "Não foi possível gerar a solicitação de ".
                                    "recuperação de senha. ".
                                    "O documento $documento não foi encontrado.";
            }

            if($organizador) {
                $this->load->helper('senha_aleatoria');
                $senhaAuth = gerarSenha(8, false, true, true, false);
                $idAcesso  = $this->organizadores_model
                                  ->insereSolicitacaoRecSenha($documento, $senhaAuth,
                                                              $organizador->id_organizador,
                                                              $organizador->de_email);
                if($idAcesso) {
                    $this->load->library('email');
                    $this->load->library('gerenciador_de_email');
                    $assunto = "Sistema de Emissão de Certificados - ".
                               "Recuperação de Senha";

                    $novaSenha = $senhaAuth;
                    $link     = anchor("sistema/alterarSenha/$idAcesso");

                    $texto    = "Foi realizada uma solicitação de recuperação de senha ".
                                "para seu usuário no Sistema de Emissão de Certificados ".
                                "- UNIPAMPA.<br /><br />".
                                "<b>Nova senha</b>: $novaSenha <br /><br />".
                                "Para efetivar a nova senha clique no link abaixo:<br />".
                                "$link";

                    $this->gerenciador_de_email->enviaEmailPessoa($organizador->de_email,
                                                                  $assunto,
                                                                  $texto);

                    $data["mensagem"] = "Sua solicitação de recuperação de senha foi ".
                                        "realizada com sucesso.<br />".
                                        "Uma nova senha, gerada aleatoriamente, ".
                                        "foi enviada para ".$organizador->de_email;
                } else {
                    $data["mensagem"] = "Não foi possível gerar a solicitação de ".
                                        "recuperação de senha.";
                }
            }

            $data['corpo_pagina']  = "mensagem_senha_email_view";
            $this->load->view('includes/templates/template', $data);
        } else {
            redirect("sistema/recuperarSenha");
        }
    }


    /**
     * Apartir do idAcesso, enviado no link de alteracao de senha recebido por email,
     * faz a alteracao da senha do organizador
     *
     * @param Integer $idAcesso
     *
     */
    function alterarSenha($idAcesso) {
        if($idAcesso) {
            $this->load->model('organizadores_model');
            $alterou = $this->organizadores_model->alterarSenha($idAcesso);

            if($alterou)
                $data["mensagem"] = "Sua nova senha foi efetivada com sucesso.<br />";
            else
                $data["mensagem"] = "Não foi possível efetivar a nova senha. <br />";

            $data['corpo_pagina']  = "mensagem_alteracao_senha_view";
            $this->load->view('includes/templates/template', $data);
        }
    }
  

}
?>
