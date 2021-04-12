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
 * Controller para a funcao de Cadastro de eventos
 * @author Pedro Jr     <pedro.junior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 */

class Eventos extends Controller {

    /**
     * Construtor da Classe.
     *
     * Inicializa helpers e bibliotecas do CodeIgniter e verifica se o usuario
     * tem permissao para abrir o controller.
     *
     */
    function Eventos() {
        parent::Controller();        
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('data');
        $this->load->helper('combo_helper');
        $this->load->helper('retorno_operacoes');
        $this->load->helper('eventos');
        $this->load->library('session');
        $this->load->library('pagination');
        $this->lang->load('msg');
        $this->config->load_db_items();
        
        $this->load->library('Gerenciador_de_acesso');
        $this->gerenciador_de_acesso->usuarioAuth();
        
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
        
        // Permissao de listagem
        $lista = false;
        $listaLocal = false;
        $listaLimitado = false;
        
        if ($this->session->userdata('admin')!='1') {            
            if (!$this->session->userdata('controlador')=='1') {
                if (!$this->session->userdata('eventos_controlador')) {                    
                    $lista = false;                    
                    $listaLimitado = false;
                    $listaLocal = false;
                } 
                if ($this->session->userdata('eventos_controlador')){ // Se for controlador local, filtra eventos                                        
                    $listaLocal = true;                    
                    $listaLimitado = false;
                    $lista = false;
                }
                if ($this->session->userdata('admin')=='2') {                    
                    $listaLimitado = true;
                    $listaLocal = false;
                    $lista = false;
                }
            } else { // Se for controlador global, habilita lista                
                $lista = true;
                $listaLimitado = false;
                $listaLocal = false;
            }            
        } else {
            $lista = true;
        }
        
        // Verifica em qual das situacoes o usuario se enquadra
        if ($lista) {
            $this->listar(); // Se for administrador ou controlador global, lista todos
        }
        else {
            if ($listaLocal) {
                $this->selecionarEventoControle();
            }
            if ($listaLimitado) {                
                $this->selecionarEventosLimitado();
            }
            if ((!$lista) && (!$listaLocal) && (!$listaLimitado)){
                redirect("sistema/bloqueado");
            }            
        }
    }    

    /**
     * Metodo chamado na pesquisa de registros da view, na paginacao e no index
     */
    function listar() {
        $resultado = array(); 
        $this->load->model('eventos_model');

        if(@$_POST['hdnPesquisa'] == 'pesquisa') {
             //se digitou um valor para pesquisa, armazena-o numa sessao.
            $this->session->set_userdata('valor_pesq', $_POST['txtPesquisa']);
            $this->session->set_userdata('tipo_pesq', $_POST['cmbPesquisa']);
        }

        $key  = $this->session->userdata('valor_pesq');
        $tipo = $this->session->userdata('tipo_pesq');

        //parametros paginacao
        $this->load->library('pagination');
        $url               = 'eventos/listar';
        $total             = $this->eventos_model->getTotal($key, $tipo);
        $pag               = $this->pagination->configPagination($url, $total, LIMITE_PESQUISA_PAGINA);
        $maximo            = $pag["maximo"];
        $inicio            = $pag["inicio"];
        $data['paginacao'] = $pag["links"];

        $resultado = $this->eventos_model->search($key, $tipo, $maximo, $inicio,
                                                 $this->session->userdata('ordem_valor'),
                                                 $this->session->userdata('ordem_tipo'));
        
        if ($resultado ==null) {
            $data['mensagem'] = 'N&atilde;o h&aacute; registros para exibir';
            $data['eventos'] = null;
        }
        else {
            $data['eventos'] = $resultado;
        }

        if (($this->session->userdata('admin')==0) && ($this->session->userdata('controlador')==0)){
            redirect('sistema/bloqueado');
        }

        $data['corpo_pagina'] = "eventos_view";
        $this->load->view('includes/templates/template', $data);
        
    }

    /**
     * Metodo para criar um novo registro de eventos, exibindo um formulario com
     * campos para preenchimento do cadastro.
     * @param Array $errors - Especifica se houve erros de validacao em passos
     * anteriores.
     */
    function novo($errors=null) {
        if ($this->session->userdata('admin')!='0') {
            $data['errors'] = $errors;
            $data['corpo_pagina']  = "cad_eventos_view";
            $data['titulo_pagina'] = "Novo Evento";
            $data['operacao']      = "novo";
            $this->load->view('includes/templates/template', $data);
        } else {
            redirect("sistema/bloqueado");
        }
    }

    /**
     * Grava os dados do formulario que chamou o metodo em um banco de dados.
     */    
    function salvar() {        
        $errors = '';
        $dados = $_POST;   
        $this->load->library('form_validation');
        $this->form_validation->set_rules('txtNome', 'Nome', 'required');
        $this->form_validation->set_rules('txtSigla', 'Sigla', 'required|max_length[20]');
        $this->form_validation->set_rules('txtPeriodo', 'Período', 'required');
        $this->form_validation->set_rules('txtCarga', 'Carga Horária', 'required');
        $this->form_validation->set_rules('txtLocal', 'Local de Realização', 'required');
        $this->form_validation->set_rules('txtEmail', 'E-mail do Evento', 'required');
        $this->_configureFormErrorMessage();

        $id    = isset($dados["txtId"]) ? $dados["txtId"] : null;        
        if ($this->form_validation->run() == TRUE) {
            $this->load->model('eventos_model');
            $data["id_evento"]             = $id;
            $data["nm_evento"]             = $dados['txtNome'];
            $data["sg_evento"]             = $dados['txtSigla'];
            $data["de_carga"]              = $dados['txtCarga'];
            $data["de_local"]              = $dados['txtLocal'];
            $data["de_periodo"]            = $dados['txtPeriodo'];
            $data["de_url"]                = $dados['txtURL'];
            $data["de_email"]              = $dados['txtEmail'];
            $data["dtInclusao"]            = $dados['txtDtInclusao'];
            $data["dtAlteracao"]           = $dados['txtDtAlteracao'];

            if(@$dados['idsOrganizadores']) {
                $data['idsOrganizadores']  = $dados['idsOrganizadores'];
                $data['idsControladores']  = $dados['idsControladores'];
            }
            
            if($id) {
                $this->eventos_model->update($data);
                $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'eventos/editar/'.$id);                
            } else {
                $regInsert = $this->eventos_model->insert($data);
                if(!$regInsert) {
                    $data["mensagem"]      = "Nao foi posivel inserir o registro.";
                    $data['corpo_pagina']  = "mensagem_view";
                    $this->load->view('includes/templates/template', $data);
                } else {
                    // busca dados do usuario que inseriu o registro
                    $this->load->model('organizadores_model');
                    $dadosOrganizador = $this->organizadores_model->getByUser($this->session->userdata('uid'));
                    $idOrganizador = $dadosOrganizador->id_organizador;
                    // Adiciona usuario como organizador e controlador
                    $this->gravaPrimeiroOrganizador($regInsert, $idOrganizador);                    
                    $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'eventos/editar/'.$regInsert);
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
     * Abre formulario para edicao de um registro de eventos.
     * @param Integer $id   - Especifica o ID que sera editado.
     * @param Array $errors - Especifica se existem erros de validacao ou nao.
     */
    function editar($id=null,$errors=null) {
        if ((!$id) && (@$_POST['txtEvento'])) {
            $id = $_POST['txtEvento'];
        }        
        if(($id > 0)) {
            $permitido = $this->buscarPermissaoControle($id);
            if ($permitido) {
                $this->load->model('eventos_model');
                $data['evento']        = $this->eventos_model->getById($id);
                $data['organizadores'] = $this->eventos_model
                                              ->listarOrganizadoresEvento($id);
                $data['error']         = $errors;
                $data['corpo_pagina']  = "cad_eventos_view";
                $data['titulo_pagina'] = "Alterar Evento";
                $data['operacao']      = "editar";
                $this->load->view('includes/templates/template', $data);
            } else {
                redirect("sistema/bloqueado");
            }
        } else {
            redirect("eventos");
        }         
    }

    /**
     * Permite excluir os dados de um formando apos consulta e confirmacao do banco de dados.
     * @param Integer $idEvento  - Especifica o ID do Evento a excluir.
     */
    function excluir($idEvento) {
        if($idEvento > 0) {
            if ($this->session->userdata('admin')!='0') {
                $this->load->model('eventos_model');
                $resultado = $this->eventos_model->delete($idEvento);
                if(!$resultado) {
                    $data['corpo_pagina']  = "mensagem_view";
                    $data['mensagem']      = "Não foi possível excluir o registro. <br />".
                                             "Verifique se o registro selecionado está ".
                                             "vinculado a algum modelo de certificado.";

                    $this->load->view('includes/templates/template', $data);
                } else {
                    $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'eventos');
                }
            } else {
                    $data['corpo_pagina'] = "mensagem_view";
                    $data['mensagem'] = "Não foi possível excluir o registro. <br />".
                                        "Seu usuário atual não tem permissão para executar esta operação.";
                    $this->load->view('includes/templates/template', $data);
            }
        }

    }


    /**
     * Cancela a operacao corrente e retorna para a tela inicial
     */
    function cancelar() {
        redirect('eventos');
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
     * Carrega os organizadores associados ao evento
     */
    function carregaOrganizadoresEventoAjax() {
        $this->load->model('organizadores_model');        

        $idEvento        = @$_POST['evento'];
        $organizadores   = @$_POST['organizadores'];
        if ($organizadores) {
            foreach($organizadores as $organizador) {
                $arrayOrg[] = $this->organizadores_model->getById($organizador);
            }
            echo geraTabelaOrganizadores(@$arrayOrg, $idEvento);
        }

    }

    /**
     * Carrega em tabela todas os organizadores
     */
    function carregaOrganizadoresAjax() {
        $this->load->model('organizadores_model');
        $res = $this->organizadores_model->listarOrganizadores();

        foreach($res as $organizador) {
            echo retornaOptionCombo($organizador->id_organizador,
                                    $organizador->nm_organizador);

        }
    }
    
    
    /**
     * Carrega os modelos de certificado do evento passado, gerando
     * as options de combobox.
     */
    function carregaModelosEventoAjax() {
        $idEvento = $_POST['id_evento'];
        if($idEvento > 0) {
            $this->load->model('modelos_certificado_model');
            $modelos = $this->modelos_certificado_model->listarModelosEvento($idEvento);
            echo retornaSelecione();
            foreach($modelos as $modelo) {
                echo retornaOptionCombo($modelo->id_certificado_modelo,
                                        $modelo->nm_modelo);
            }
        }
    }
    
   
    /**
     * Atribui controladores ao evento via AJAX
     */
    function atribuiControladorAjax() {        
        $idEvento      = $_POST['evento'];
        $idOrganizador = $_POST['organizador'];        
        
        if ($idEvento && $idOrganizador) {
            $this->load->model('organizadores_evento_model');
            $resultado = $this->organizadores_evento_model->atribuiControlador($idEvento, $idOrganizador);
            if ($resultado) {
                echo "OK";
            } else {
                echo null;
            }
        } else {
            echo null;
        }
        
    }
    
    /**
     * Grava os dados do primeiro organizador do evento para conceder acesso.
     * @param integer $idEvento         - ID do evento
     * @param integer $idOrganizador    - ID do organizador
     * @return Boolean
     */
    
    function gravaPrimeiroOrganizador($idEvento, $idOrganizador) {        
        if ($idEvento && $idOrganizador) {
            $this->load->model('organizadores_evento_model');
            $resultadoOrganizador = $this->organizadores_evento_model->gravaOrganizadorEvento($idEvento, $idOrganizador);
            if ($resultadoOrganizador) {                
                $resultadoControlador = $this->organizadores_evento_model->atribuiControlador($idEvento, $idOrganizador);                
            }            
            if ($resultadoOrganizador && $resultadoControlador) {                
                // Recarrega permissoes de organizador e controlador               
                $this->recarregaPermissoes($idOrganizador);
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }
    
    /**
     * Grava organizadores vinculados ao evento via AJAX
     */
    function gravaOrganizadorAjax() {
        $idEvento      = $_POST['evento'];
        $idOrganizador = $_POST['organizador'];        
        
        if ($idEvento && $idOrganizador) {
            $this->load->model('organizadores_evento_model');
            $resultado = $this->organizadores_evento_model->gravaOrganizadorEvento($idEvento, $idOrganizador);
            if ($resultado) {
                echo "OK";
            } else {
                echo null;
            }
        } else {
            echo null;
        }
    }
    
     /**
     * Remove organizadores vinculados ao evento via AJAX
     */
    
    function removeOrganizadorAjax() {
        $idEvento      = $_POST['evento'];
        $idOrganizador = $_POST['organizador'];        
        
        if ($idEvento && $idOrganizador) {
            $this->load->model('organizadores_evento_model');
            $resultado = $this->organizadores_evento_model->removeOrganizador($idEvento, $idOrganizador);
            if ($resultado) {
                echo "OK";
            } else {
                echo null;
            }
        } else {
            echo null;
        }
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
    
     /**
     * Permite selecionar eventos associados a um controlador
     */
    
    function selecionarEventoControle() {
        $this->load->model('eventos_model');
        $eventosControlador = $this->eventos_model->listarEventosControlador($this->session->userdata('uid'));
        
        if ($eventosControlador) {
           $data['eventos']      =  $eventosControlador;
           $data['corpo_pagina'] = "selecao_evento_edicao_view";
           $this->load->view('includes/templates/template', $data); 
        } else {
            redirect('sistema/bloqueado');
        }
        
    }
    
    /**
     * Verifica se um determinado usuario tem permissao de controlador no evento
     * @param Integer $idEvento - ID do Evento
     * @return Boolean - Retorna True ou False
     */
    function buscarPermissaoControle($idEvento) {
        $retorno = null;
        $controladorGlobal  = $this->session->userdata('controlador');
        $admin = $this->session->userdata('admin');
        if ($admin=='1') {
            return true;
        }
        if ($controladorGlobal=='1') {
            return true;            
        } 
        if ($admin!='1') {
            $eventosControlador = $this->session->userdata('eventos_controlador');
            foreach($eventosControlador as $evento) {
                if ($evento == $idEvento) {
                    $retorno = true;
                } 
            }        
        }
        return $retorno;        
    }
    /**
     * Permite selecionar eventos associados a um administrador limitado
     */
    function selecionarEventosLimitado() {
        $this->load->model('eventos_model');
        // Para visualizar os eventos, deve estar associado a eles 
        $eventosLimitado = $this->eventos_model->listarEventos();
        
        if ($eventosLimitado) {
           $data['eventos']      =  $eventosLimitado;
           $data['corpo_pagina'] = "selecao_evento_edicao_view";
           $this->load->view('includes/templates/template', $data); 
        } else {
            redirect('sistema/bloqueado');
        }
    }
    
    /**
     * Busca permissao para usuarios limitados para o evento especificado
     * @param Integer $idEvento
     * @return boolean  - retorna true/false se conseguiu buscar as permissoes
     */
    function buscarPermissaoLimitado($idEvento) {        
        $retorno = null;
        $controladorGlobal  = $this->session->userdata('controlador');
        if ($controladorGlobal=='1') {
            $retorno = true;
        } else {
            $eventosControlador = $this->session->userdata('eventos');
            foreach($eventosControlador as $evento) {
                if ($evento == $idEvento) {
                    $retorno = true;
                } 
            }        
        }
        return $retorno;            
    
    }
    
    /**
     * Recarrega as permissoes do organizador especificado
     * @param integer $idOrganizador - ID do Organizador
     * @return Boolean - Retorna true/false
     */
    function recarregaPermissoes($idOrganizador) {
        if ($idOrganizador) {
            $this->load->model('organizadores_evento_model');
            $this->session->unset_userdata('eventos_permitidos');
            $this->session->unset_userdata('eventos_controlador');
            $eventosPermitidos = $this->organizadores_evento_model->listarEventosOrganizador($idOrganizador);                
            $eventosControlador = $this->organizadores_evento_model->listarEventosControlador($idOrganizador);
            $this->session->set_userdata('eventos_permitidos', $eventosPermitidos);                               
            $this->session->set_userdata('eventos_controlador', $eventosControlador);                               
            return true;
        } else {
            return false;
        }
    }

}
?>