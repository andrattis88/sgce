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
 * Controller para a funcao de Cadastro de Organizadores
 * @author Pedro Jr     <pedro.junior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 */

class Organizadores extends Controller {

    /**
     * Construtor da Classe.
     *
     * Inicializa helpers e bibliotecas do CodeIgniter e verifica se o usuario
     * tem permissao para abrir o controller.
     *
     */
    function Organizadores() {
        parent::Controller();        
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('data');
        $this->load->helper('retorno_operacoes');
        $this->load->library('session');
        $this->load->library('pagination');
        $this->lang->load('msg');
        $this->config->load_db_items();
        
        $this->load->library('Gerenciador_de_acesso');
        $this->gerenciador_de_acesso->usuarioAuth();
                
        if ($this->session->userdata('admin')==0) {
            redirect('sistema/bloqueado');
        }
    }

    
    
    /**
     * Metodo padrao chamado quando invocada a controller.
     *
     * Responsavel por chamar a pagina principal para o usuario
     *
     */
    function index() {
        $this->session->set_userdata('valor_pesq', null);
        $this->session->set_userdata('tipo_pesq', null);
        $this->session->set_userdata('ordem_valor', null);
        $this->session->set_userdata('ordem_tipo', null);
        $this->listar();
    }    

    /**
     * Metodo chamado na pesquisa de registros da view, na paginacao e no index
     */
    function listar() {
        $resultado = array(); 
        $this->load->model('organizadores_model');

        if(@$_POST['hdnPesquisa'] == 'pesquisa') {
             //se digitou um valor para pesquisa, armazena-o numa sessao.
            $this->session->set_userdata('valor_pesq', $_POST['txtPesquisa']);
            $this->session->set_userdata('tipo_pesq', $_POST['cmbPesquisa']);
        }

        $key  = $this->session->userdata('valor_pesq');
        $tipo = $this->session->userdata('tipo_pesq');

        //parametros paginacao
        $this->load->library('pagination');
        $url               = 'organizadores/listar';
        $total             = $this->organizadores_model->getTotal($key, $tipo);
        $pag               = $this->pagination->configPagination($url, $total, LIMITE_PESQUISA_PAGINA);
        $maximo            = $pag["maximo"];
        $inicio            = $pag["inicio"];
        $data['paginacao'] = $pag["links"];

        $resultado = $this->organizadores_model->search($key, $tipo, $maximo, $inicio,
                                                 $this->session->userdata('ordem_valor'),
                                                 $this->session->userdata('ordem_tipo'));
        
        if ($resultado ==null) {
            $data['mensagem']      = 'N&atilde;o h&aacute; registros para exibir';
            $data['organizadores'] = null;
        }
        else {
            $data['organizadores'] = $resultado;
        }

        $data['corpo_pagina'] = "organizadores_view";
        $this->load->view('includes/templates/template', $data);
        
    }

    /**
     * Metodo para criar um novo registro de Organizadores, exibindo um formulario com
     * campos para preenchimento do cadastro.
     * @param Array $errors - Especifica se houve erros de validacao em passos
     * anteriores.
     */
    function novo($errors=null) {
        $data['errors'] = $errors;
        $data['corpo_pagina']  = "cad_organizadores_view";
        $data['titulo_pagina'] = "Novo Usuário";
        $data['operacao']      = "novo";
        $this->load->view('includes/templates/template', $data);
    }

    /**
     * Grava os dados do formulario que chamou o metodo em um banco de dados.
     */    
    function salvar() {
        $errors = '';
        $dados = $_POST;   
        $this->load->library('form_validation');
        $this->form_validation->set_rules('txtNome', 'Nome', 'required');
        $this->form_validation->set_rules('txtDocumento', 'Documento', 'required');
        $this->form_validation->set_rules('txtEmail', 'Email', 'required');
        $this->form_validation->set_rules('txtTelefone', 'Telefone', 'required');
        $this->form_validation->set_rules('txtUsuario', 'Usuario', 'required');
        $this->form_validation->set_rules('txtFlAdmin', 'Administrador', 'required');
        $this->form_validation->set_rules('txtFlControlador', 'Controlador Global', 'required');

        $this->_configureFormErrorMessage();

        $documentoExiste = false;
        $loginExiste     = false;
        $id    = isset($dados["txtId"]) ? $dados["txtId"] : null;        
        if ($this->form_validation->run() == TRUE) {
            $this->load->model('organizadores_model');

            $documentoExiste = $this->documentoExistente($dados['txtDocumento'], $id);
            if($documentoExiste) {
                $data["mensagem"]      = "O cadastro <b>não</b> foi realizado pois o documento ".
                                         "informado já está cadastrado.";
                $data['corpo_pagina']  = "mensagem_view";
                $this->load->view('includes/templates/template', $data);
            } else {
                $loginExiste = $this->loginExistente($dados['txtUsuario'], $id);
                if($loginExiste) {
                    $data["mensagem"]      = "O cadastro <b>não</b> foi realizado pois o login de usuário ".
                                             "informado já está cadastrado.";
                    $data['corpo_pagina']  = "mensagem_view";
                    $this->load->view('includes/templates/template', $data);
                }
            }

            if(!$documentoExiste && !$loginExiste) {
                $id                          = $dados['txtId'];
                $data["nm_organizador"]      = $dados['txtNome'];
                $data["nr_documento"]        = $dados['txtDocumento'];
                $data["de_email"]            = $dados['txtEmail'];
                $data["nr_telefone"]         = $dados['txtTelefone'];
                $data["de_usuario"]          = $dados['txtUsuario'];
                if(@$dados['txtSenha'])
                    $data["de_senha"]        = $dados['txtSenha'];
                
                $data["fl_admin"]            = $dados['txtFlAdmin'];
                $data["fl_controlador"]      = $dados['txtFlControlador'];                
                
                if($id) {
                    $data["id_organizador"]  = $dados['txtId'];
                    $this->organizadores_model->update($data);
                    $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'organizadores');
                } else {
                    $regInsert = $this->organizadores_model->insert($data);
                    if(!$regInsert) {
                        $data["mensagem"] = "Nao foi posivel inserir o registro.";
                        $data['corpo_pagina']  = "mensagem_view";
                        $this->load->view('includes/templates/template', $data);
                    } else {
                        $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'organizadores');
                    }
                }
            }
        } else {
            if($id)
                $this->editar($id,$errors);
            else
                $this->novo();
        }                
    }

    
    /**
     * Abre formulario para edicao de um registro de Organizadores.
     * @param Integer $id   - Especifica o ID que sera editado.
     * @param Array $errors - Especifica se existem erros de validacao ou nao.
     */
    function editar($id,$errors=null) {
        if($id > 0) {
            $this->load->model('organizadores_model');
            $data['organizador']         = $this->organizadores_model->getById($id);

            $data['error']         = $errors;
            $data['corpo_pagina']  = "cad_organizadores_view";
            $data['titulo_pagina'] = "Alterar Usuário";
            $data['operacao']      = "editar";
            $this->load->view('includes/templates/template', $data);
        }
    }

    /**
     * Permite excluir os dados de um formando apos consulta e confirmacao do banco de dados.
     * @param Integer $idorganizador  - Especifica o ID do formando a excluir.
     */
    function excluir($idOrganizador) {
        if(($idOrganizador > 0) && ($this->session->userdata('admin')==1)) {
            $this->load->model('organizadores_model');
            $resultado = $this->organizadores_model->delete($idOrganizador);
            if(!$resultado) {
                $data['corpo_pagina']  = "mensagem_view";
                $data['mensagem']      = "Não foi possível excluir o registro. <br />".
                                         "Verifique se o registro selecionado está ".
                                         "vinculado a outros cadastros.";
                $this->load->view('includes/templates/template', $data);
            } else {
                $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'organizadores');
            }
        } else {
            $data['corpo_pagina']  = "mensagem_view";
                $data['mensagem']      = "Não foi possível excluir o registro. <br />".
                                         "Você não tem permissão para executar esta operação.";
                $this->load->view('includes/templates/template', $data);
        }
    }


    /**
     * Cancela a operacao corrente e retorna para a tela inicial
     */
    function cancelar() {
        redirect('organizadores');
    }

    /**
     * Action de ordenacao. Recebe um valor da view referente ao campo, e chama
     * a model para executar a ordenacao.
     *
     * @param String $campo - nome do campo para ordenar
     */
    function ordenar($campo) {
        $ordemTipo = 'ASC';
        if ($this->session->userdata('ordem_valor') == $campo) {
            if ($this->session->userdata('ordem_tipo')=='ASC') {
                $ordemTipo = 'DESC';
            } else {
                $ordemTipo = 'ASC';
            }
        }

        $this->session->set_userdata('ordem_valor', $campo);
        $this->session->set_userdata('ordem_tipo',  $ordemTipo);
        $this->listar();
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

    }

    /**
     * True caso exista, false caso contrario
     * @param String  $documento - nr do doc
     * @param Integer $id        - id do organizador
     * @return boolean
     */
    function documentoExistente($documento, $id) {
        $id = isset($id) ? $id : 0;
        $this->load->model('organizadores_model');
        $total = $this->organizadores_model
                      ->getTotalOrganPorDocumento($documento, $id);
        if($total > 0)
            return true;
        else
            return false;
    }

    /**
     * True caso exista, false caso contrario
     * @param String  $login     - login do organizador
     * @param Integer $id        - id do organizador
     * @return boolean
     */
    function loginExistente($login, $id) {
        $id = isset($id) ? $id : 0;
        $this->load->model('organizadores_model');
        $total = $this->organizadores_model
                      ->getTotalOrganPorLogin($login, $id);
        if($total > 0)
            return true;
        else
            return false;
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
