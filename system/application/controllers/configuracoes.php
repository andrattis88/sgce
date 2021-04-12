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
 * Controller - Configuracoes
 * @author Pedro Jr     <pedro.junior@unipampa.edu.br>
 * @author Sergio Jr    <sergiojunior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 */

class Configuracoes extends Controller {

    function Configuracoes() {
        parent::Controller();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('data');
        $this->load->helper('retorno_operacoes');
        $this->load->library('session');
        $this->load->library('pagination');
        $this->load->library('gerenciador_de_acesso');
        $this->lang->load('msg');
        $this->config->load_db_items();        
        $this->gerenciador_de_acesso->usuarioAuth();
    }


    function index() {
        if ($this->session->userdata('admin')!=1) {
            redirect('sistema/bloqueado');
        } else {
            $this->editar();
        }        
    }

    function editar($errors = null) {
        $this->load->model('configuracoes_model');
        $data = $this->configuracoes_model->obtemTodosParametrosArray();
        $data['corpo_pagina'] = "configuracoes_view";
        $this->load->view('includes/templates/template', $data);
    }



    function salvar() {
        if ($this->session->userdata('logado') != 1 ) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }

        $errors = '';
        $dados = $_POST;
        $this->load->library('form_validation');

        //autenticacao
        $this->form_validation->set_rules('txtModoAuth',    'Modo de Autenticação', 'required');
        $this->form_validation->set_rules('txtServerLDAP',  'Servidor LDAP', 'required');
        $this->form_validation->set_rules('txtPortaLDAP',   'Porta LDAP', 'required');
        $this->form_validation->set_rules('txtDNBase',      'DN Base LDAP', 'required');
        $this->form_validation->set_rules('txtDNMaster',    'DN Master LDAP', 'required');
        $this->form_validation->set_rules('txtDNSearch',    'DN Search LDAP', 'required');
        $this->form_validation->set_rules('txtSenhaLDAP',   'Senha LDAP', 'required');

        //upload
        $this->form_validation->set_rules('txtTamanhoUpload',   'Tamanho máximo de Upload', 'required');
        $this->form_validation->set_rules('txtCaminhoUpload',   'Caminho de Upload', 'required');
        $this->form_validation->set_rules('txtExtensoesUpload', 'Extensões Permitidas', 'required');

        //email
        //$this->form_validation->set_rules('txtSMTPServer',  'Servidor SMTP', 'required');
        //$this->form_validation->set_rules('txtSMTPPort',    'Porta SMTP', 'required');
        //$this->form_validation->set_rules('txtFromName',    'Nome do Remetente', 'required');
        //$this->form_validation->set_rules('txtFromMail',    'Endereço do Remetente', 'required');

        //mensagens
        $this->form_validation->set_rules('txtEmailPadrao',      'Endereço de e-mail padrão do sistema', 'required');
        $this->form_validation->set_rules('txtServidorDNS',      'IP Servidor DNS', 'required');
        
        $this->form_validation->set_rules('txtMsgNotificacao',      'Texto Mensagem de Notificação', 'required');
        $this->form_validation->set_rules('txtMsgAlteracaoStatus',  'Texto Mensagem de Notificação/Revogação', 'required');
        $this->form_validation->set_rules('txtMsgNotifQualidade',  'Texto Mensagem de Notificação de Qualidade', 'required');        

        $this->_configureFormErrorMessage();
        if ($this->form_validation->run() == TRUE) {
            $this->load->model('configuracoes_model');

            //autenticacao
            $data["tipo_de_autenticacao"] = $dados['txtModoAuth'];
            $data["server_ldap"]          = $dados['txtServerLDAP'];
            $data["porta_ldap"]           = $dados['txtPortaLDAP'];
            $data["base_dn"]              = $dados['txtDNBase'];
            $data["master_dn"]            = $dados['txtDNMaster'];
            $data["search_dn"]            = $dados['txtDNSearch'];
            $data["senha_ldap"]           = $dados['txtSenhaLDAP'];

            //upload
            $data["max_size"]             = $dados['txtTamanhoUpload'];
            $data["upload_path"]          = $dados['txtCaminhoUpload'];
            $data["allowed_types"]        = $dados['txtExtensoesUpload'];

            //email
            //$data["smtp_host"]            = $dados['txtSMTPServer'];
            //$data["smtp_port"]            = $dados['txtSMTPPort'];
            //$data["mail_from_name"]       = $dados['txtFromName'];
            //$data["mail_from_address"]    = $dados['txtFromMail'];
            //$data["smtp_user"]            = isset($dados['txtSMTPUser']) ?
            //                                      $dados['txtSMTPUser']  : null;
            //$data["smtp_pass"]            = isset($dados['txtSMTPPass']) ?
            //                                      $dados['txtSMTPPass']  : null;

            //mensagens
            $data["email_from_address"]   = $dados['txtEmailPadrao'];
            $data["servidor_dns"]         = $dados['txtServidorDNS'];
            
            $data["msg_notificacao"]      = $dados['txtMsgNotificacao'];
            $data["msg_alteracao_status"] = $dados['txtMsgAlteracaoStatus'];
            $data["msg_notificacao_qualidade"]  = $dados['txtMsgNotifQualidade'];
            
            $this->configuracoes_model->update($data);
            $this->_configurarSistema($data);
            $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'configuracoes/editar/');            
        } else             
            $this->editar($errors);

    }
    
    function cancelar() {
        redirect('sistema/principal');
    }

    function restaurar() {
        $this->load->model('configuracoes_model');
        $this->configuracoes_model->restaurarPadrao();
        $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'configuracoes/editar/');
    }

    function _configurarSistema($data) {
        if ($data) {
            $this->config->load('email');
            $i=0;
            $chaves = array_keys($data);
            $valores = array_values($data);
            for($i=0;$i<count($data);$i++) {                
                $this->config->set_item($chaves[$i], $valores[$i]);
            }            
        }
    }

     /**
     * Personaliza mensagens de erro do sistema.
     */
    function _configureFormErrorMessage() {
        $this->form_validation->set_message('required',
                'O campo <span class="message_field">%s</span> &eacute; obrigat&oacute;rio.');

        $this->form_validation->set_message('valid_email',
                'O campo <span class="message_field">%s</span> n&atilde;o &eacute; um e-mail v&aacute;lido.');

        $this->form_validation->set_message('max_leght',
                'O campo <span class="message_field">%s</span> apresenta um tamanho m&aacute;ximo de 12 caracteres.');

        $this->form_validation->set_message('numeric',
                'O campo <span class="message_field">%s</span> deve possuir somente valor num&eacute;rico.');

        $this->form_validation->set_message('is_natural_no_zero',
                'O campo <span class="message_field">%s</span> &eacute; obrigat&oacute;rio.');

    }
    
    
    /**
     * Exibe o retorno de uma operacao, com a mensagem passada e a url de direcio
     * namento
     *
     * @param String $mensagem - mensagem de retorno
     * @param String $url      - url de direcionamento
     */
    function exibeRetorno($mensagem, $url) {
        $data['mensagem']      = $mensagem;
        $data['corpo_pagina']  = "retorno_operacoes_view";
        $view = $this->load->view('includes/templates/template', $data, true);
        exibeRetornoOperacao($view, $url);
    }




}
?>

