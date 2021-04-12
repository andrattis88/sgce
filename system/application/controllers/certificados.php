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
 * Controller para a funcao de Cadastro de Certificados
 * @author Pedro Jr     <pedro.junior@unipampa.edu.br>
 * @author Sergio Jr     <sergiojunior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 */

include APPPATH. "/libraries/phpqrcode/qrlib.php";


class Certificados extends Controller {

    /**
     * Construtor da Classe.
     *
     * Inicializa helpers e bibliotecas do CodeIgniter e verifica se o usuario
     * tem permissao para abrir o controller.
     *
     */
    function Certificados() {
        parent::Controller();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('data');
        $this->load->helper('progresso_execucao_helper');
        $this->load->helper('retorno_operacoes_helper');
        $this->load->library('session');
        $this->load->library('pagination');
        $this->lang->load('msg');
        $this->config->load_db_items();

        $this->load->library('Gerenciador_de_acesso');
    }

    /**
     * Metodo padrao chamado quando invocada a controller.
     *
     * Responsavel por chamar a pagina principal para o usuario
     *
     */
    function index() {
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }
        $this->session->set_userdata('valor_pesq', null);
        $this->session->set_userdata('tipo_pesq', null);
        $this->session->set_userdata('ordem_valor', null);
        $this->session->set_userdata('ordem_tipo', null);
        $this->session->set_userdata('id_evento', null);
        $this->selecionarEvento();
    }

    /**
     * Metodo chamado na pesquisa de registros da view, na paginacao e no index
     */
    function listar() {
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }

        $resultado = array();
        $this->load->model('certificados_model');

        if (@$_POST["txtEvento"]) {
            $this->session->set_userdata('id_evento', $_POST['txtEvento']);
        }

        if (@$_POST['hdnPesquisa'] == 'pesquisa') {
            //se digitou um valor para pesquisa, armazena-o numa sessao.
            $this->session->set_userdata('valor_pesq', $_POST['txtPesquisa']);
            $this->session->set_userdata('tipo_pesq', $_POST['cmbPesquisa']);
        }

        $key      = $this->session->userdata('valor_pesq');
        $tipo     = $this->session->userdata('tipo_pesq');
        $idEvento = $this->session->userdata('id_evento');

        //parametros paginacao
        $this->load->library('pagination');
        $url               = 'certificados/listar';
        $total             = $this->certificados_model->getTotal($key, $tipo, $idEvento);
        $pag               = $this->pagination->configPagination($url, $total, LIMITE_PESQUISA_PAGINA);
        $maximo            = $pag["maximo"];
        $inicio            = $pag["inicio"];
        $data['paginacao'] = $pag["links"];


        $this->load->model('eventos_model');
        $dadosEvento = $this->eventos_model->getById($idEvento);
        $nomeEvento  = $dadosEvento->nm_evento;

        $resultado = $this->certificados_model->search($key, $tipo, $idEvento, $maximo, $inicio, $this->session->userdata('ordem_valor'), $this->session->userdata('ordem_tipo'));

        if ($resultado == null) {
            $data['nome_evento']  = $nomeEvento;
            $data['mensagem']     = 'N&atilde;o h&aacute; registros para exibir';
            $data['certificados'] = null;
        } else {
            $data['certificados'] = $resultado;
            $data['nome_evento']  = $nomeEvento;
        }

        $data['corpo_pagina'] = "certificados_view";
        $this->load->view('includes/templates/template', $data);
    }

    /**
     * Metodo para criar um novo registro de cursos, exibindo um formulario com
     * campos para preenchimento do cadastro.
     * @param Array $errors - Especifica se houve erros de validacao em passos
     * anteriores.
     */
    function novo($errors = null) {
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }
        $data['errors']        = $errors;
        $data['corpo_pagina']  = "cad_certificados_view";
        $data['titulo_pagina'] = "Novo Certificado";
        $data['operacao']      = "novo";
        $this->load->view('includes/templates/template', $data);
    }

    /**
     * Grava os dados do formulario que chamou o metodo em um banco de dados.
     */
    function salvar() {
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }
        $errors = '';
        $dados  = $_POST;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('txtTextoCertificado', 'Texto', 'required');
        $this->_configureFormErrorMessage();

        $id = isset($dados["txtId"]) ? $dados["txtId"] : null;
        if ($this->form_validation->run() == TRUE) {
            $this->load->model('certificados_model');
            $data["id_certificado"]       = $id;
            $data["de_texto_certificado"] = $dados['txtTextoCertificado'];
            if ($id) {
                $this->certificados_model->update($data);
                $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'certificados');
            } else {
                $regInsert = $this->certificados_model->insert($data);
                if (!$regInsert) {
                    $data["mensagem"]     = "Nao foi posivel inserir o registro.";
                    $data['corpo_pagina'] = "mensagem_view";
                    $this->load->view('includes/templates/template', $data);
                } else {
                    $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'certificados');
                }
            }
        } else {
            if ($id)
                $this->editar($id, $errors);
            else
                $this->novo();
        }
    }

    /**
     * Abre formulario para edicao de um registro de cursos.
     * @param Integer $id   - Especifica o ID que sera editado.
     * @param Array $errors - Especifica se existem erros de validacao ou nao.
     */
    function editar($id, $errors = null) {
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }

        if ($id > 0) {
            $this->load->model('certificados_model');
            $data['certificado'] = $this->certificados_model->recuperaCertificado($id);

            $data['error']         = $errors;
            $data['corpo_pagina']  = "cad_certificados_view";
            $data['titulo_pagina'] = "Alterar Certificado";
            $data['operacao']      = "editar";
            $this->load->view('includes/templates/template', $data);
        }
    }

    /**
     * Permite excluir os dados de um formando apos consulta e confirmacao do banco de dados.
     * @param Integer $idCurso  - Especifica o ID do formando a excluir.
     */
    function excluir($idCertificado) {
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }
        if ($idCurso > 0) {
            $this->load->model('certificados_model');
            $resultado = $this->certificados_model->delete($idCertificado);
            if (!$resultado) {
                $data['corpo_pagina'] = "mensagem_view";
                $data['mensagem']     = "Não foi possível excluir o registro. ";
                $this->load->view('includes/templates/template', $data);
            } else {
                $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'certificados');
            }
        }
    }

    /**
     * Cancela a operacao corrente e retorna para a tela inicial
     */
    function cancelar() {
        redirect('certificados');
    }

    /**
     * Action de ordenacao. Recebe um valor da view referente ao campo, e chama
     * a model para executar a ordenacao.
     *
     * @param String $campo - nome do campo para ordenar
     */
    function ordenar($campo) {
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }
        $ordemTipo = 'ASC';
        if ($this->session->userdata('ordem_valor') == $campo) {
            if ($this->session->userdata('ordem_tipo') == 'ASC') {
                $ordemTipo = 'DESC';
            } else {
                $ordemTipo = 'ASC';
            }
        }

        $this->session->set_userdata('ordem_valor', $campo);
        $this->session->set_userdata('ordem_tipo', $ordemTipo);
        $this->listar();
    }

    /**
     * Emite o certificado especificado      *
     * @param String $hashCertificado
     * @param Boolean $pdf - True para PDF, False para HTML (testes)
     */
    function emiteCertificado($hashCertificado = null, $pdf = true) {
        $this->load->model('certificados_model');
        $this->load->model('modelos_certificado_model');
        $this->load->model('participantes_model');

        // how to save PNG codes to server
         
        $codeContents = base_url() . "validar/" . $hashCertificado;
       
        $fileName = md5($codeContents).'.png';

       
        
        $pngAbsoluteFilePath = APPPATH. "views/includes/images/certificado/qrcodes/" . $fileName;
        $urlRelativeFilePath = APPPATH.$fileName;

        // generating
        if (!file_exists($pngAbsoluteFilePath)) {
           QRcode::png($codeContents, $pngAbsoluteFilePath);          
        }
  
        $data = array();

        if ($hashCertificado) {
            $idCertificado       = $this->certificados_model->existeHash($hashCertificado);
            $data['certificado'] = $this->certificados_model->recuperaCertificado($idCertificado);
            $data['file_name'] = $fileName;

            if (($data['certificado'])) {
                $html = $this->load->view('includes/templates/certificado_view', $data, TRUE);
                if ($data['certificado']->de_texto_verso) {                    
                    $htmlVerso = $this->load->view('includes/templates/certificado_verso_view', $data, TRUE);                    
                    $html      = $html . $htmlVerso;
                }
                if ($pdf) {
                    $this->load->plugin('to_pdf');
                    pdf_create($html, $hashCertificado, true, 'a4', 'landscape');
                } else {
                    echo $html;
                }
            } else {
                $data["mensagem"]     = "<h1>Emissão de Certificados</h1><br><br>
                                      Os dados do certificado informado não estão
                                      disponíveis no momento,<br> ou o certificado foi revogado.<br/>";
                $data['corpo_pagina'] = "mensagem_view";
                $this->load->view('includes/templates/template', $data);
            }
        } 
    }

    /**
     * Funcao direcionadora para processamento de certificados
     */
    function processar() {
        $data['corpo_pagina'] = "processa_certificado_view";
        $this->load->view('includes/templates/template', $data);
    }

    /**
     * Recebe dados do formulario de validacao de certificados
     * e direciona para a funcao apropriada
     */
    function recebeCodigo() {
        if ($_POST) {
            $dados = $_POST;
            if ($dados['txtHash']) {
                if ($dados['txtOperacao'] == 'emitir') {
                    $this->emiteCertificado($dados['txtHash']);
                }
                if ($dados['txtOperacao'] == 'validar') {
                    $this->validar($dados['txtHash']);
                }
            } else {
                redirect('certificados/processar');
            }
        }
    }

    /**
     * Funcao para validar certificado
     *
     * @param String $hashCertificado
     */
    function validar($hashCertificado = null) {
        $this->load->model('certificados_model');
        $this->load->model('modelos_certificado_model');
        $this->load->model('participantes_model');

        if ($hashCertificado) {
            $idCertificado       = @$this->certificados_model->existeHash($hashCertificado);
            $data['certificado'] = @$this->certificados_model->recuperaCertificado($idCertificado);
            if ($data['certificado']) {
                $data['corpo_pagina'] = "validacao_view";
                $this->load->view('includes/templates/template', $data);
            } else {
                $data["mensagem"]     = "<h1>Validação de Certificado</h1><br><br>Os dados do certificado informado não estão disponíveis no momento.
                                     <br>Tente novamente mais tarde.<br><br>";
                $data['corpo_pagina'] = "mensagem_view";
                $this->load->view('includes/templates/template', $data);
            }
        }
    }

    /**
     * Altera a situacao entre ativo e inativo do certificado
     * @param Integer $idCertificado
     */
    function situacao($idCertificado) {
        $this->load->model('certificados_model');
        $this->load->model('modelos_certificado_model');
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }
        if ($idCertificado) {
            $this->certificados_model->alteraSituacao($idCertificado);
        }
        redirect('certificados');
    }

    /**
     * Carrega tela para notificacao de titulados
     * para enviar aviso por e-mail da disponibilidade
     * dos certificados
     */
    function notificar($errors = null) {
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }
        $this->load->model('eventos_model');
        $this->load->model('modelos_certificado_model');

        $data['eventos']      = $this->eventos_model->listarEventos();
        $data['corpo_pagina'] = "notificador_view";

        $this->load->view('includes/templates/template', $data);
    }

    /**
     * Carrega os participantes vinculados ao modelo de certificado passado
     *
     */
    function carregaDestinatariosModeloAjax() {
        $idModelo = $_POST["id_modelo"];
        if ($idModelo) {
            $this->load->model('certificados_model');
            $this->load->helper('combo_helper');
            $dadosDestinatarios = $this->certificados_model
                    ->carregaDestinatariosModelo($idModelo);
            if ($dadosDestinatarios) {
                $options = '';
                foreach ($dadosDestinatarios as $dest) {
                    $options .= retornaOptionCombo($dest->id_participante, $dest->nm_participante);
                }
                echo $options;
            }
        }
    }

    /**
     * Notificação por email aos participantes
     */
    function enviarNotificacao() {
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }
        set_time_limit(0);
        $logEmail = array();
        $errors   = '';
        $dados    = $_POST;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('txtEvento', 'Evento', 'required');
        $this->form_validation->set_rules('txtModelo', 'Modelo de Certificado', 'required');
        $this->form_validation->set_rules('txtDestinatarios', 'Destinatários', 'required');
        $this->_configureFormErrorMessage();

        if ($this->form_validation->run() == TRUE) {

            $this->load->model('certificados_model');
            $this->load->helper('email_helper');
            $this->load->library('email');
            $this->load->library('Gerenciador_de_email');

            $participantes = $this->certificados_model
                    ->carregaDadosEmail($dados['txtModelo'], $dados['txtDestinatarios']);

            if (!valid_email($participantes[0]->email_evento)) {
                $data['mensagem']     = "O email do evento não é válido, por favor verifique o cadastro do evento.";
                $data['corpo_pagina'] = "mensagem_view";
                $this->load->view('includes/templates/template', $data);
            } else {
                $data['corpo_pagina'] = "progresso_execucao_view";
                $view                 = $this->load->view('includes/templates/template', $data, true);
                descarregaBufferProgressoExecucao($view);
                $enviados             = 0;   // contador de email enviados
                $totReg               = count($participantes);

                if ($totReg > 0) {
                    $limiteMensagens  = 80;  // disparar soneca prolongada a cada n mensagens
                    $sonecaPadrao     = 1;   // intervalo de envio em segundos de cada email
                    $sonecaProlongada = 5;  // intervalo maximo de envio em segundos apos $limiteMensagens

                    $i = 1;
                    foreach ($participantes as $participante) {
                        $this->gravaLogEmail($dados['txtEvento'], $dados['txtModelo']);
                        exibeProgresso($i++, $totReg);
                        if (valid_email($participante->de_email)) {

                            $textoEmail = $this->gerarTextoEmailNotificacao($participante->nm_participante, $participante->nm_evento, $participante->site_evento, $participante->email_evento, $participante->de_hash);

                            $testeOk = $this->_testarEnvio($participante->de_email, $participante->email_evento);

                            if ($testeOk) {

                                $emailOk = $this->gerenciador_de_email
                                        ->enviaEmailPessoa($participante->de_email, 'Emissão de Certificado Digital - ' . $participante->nm_evento, $textoEmail, 'Sistema de Gestão de Certificados Eletrônicos', $participante->email_evento);

                                if ($emailOk) {
                                    $enviados++;
                                    $logEmail[$i] = "Email para $participante->nm_participante ($participante->de_email) enviado com sucesso.";
                                    sleep($sonecaPadrao);
                                    if ($enviados % $limiteMensagens == 0)
                                        sleep(rand(1, $sonecaProlongada));
                                } else {
                                    $logEmail[$i] = "Email para $participante->nm_participante ($participante->de_email) não foi enviado.";
                                }
                            } else {
                                $logEmail[$i] = "O servidor de e-mail recusou o envio para $participante->nm_participante ($participante->de_email).";
                            }

                            $this->gravaLogEmail($dados['txtEvento'], $dados['txtModelo'], $logEmail[$i], $participante->de_hash);
                        }
                    }
                    $this->exibeResultadoNotificacao($logEmail);
                } else {
                    $logEmail[0] = "Não há mensagens para enviar. ";
                }
            }
        } else {
            $this->notificar($errors);
        }
    }

    /**
     * Gera o texto de notificacao de participante
     *
     * @param String $participante
     * @param String $evento
     * @param String $hash
     * @return string
     */
    function gerarTextoEmailNotificacao($participante, $evento, $site_evento, $email_evento, $hash) {
        $url = URL_CERTIFICADO . ENDERECO_EMISSAO . $hash;
        $msg = $this->config->item('msg_notificacao');
        $msg = str_replace('NOME_PARTICIPANTE', $participante, $msg);
        $msg = str_replace('NOME_EVENTO', $evento, $msg);
        $msg = str_replace('SITE_EVENTO', $site_evento, $msg);
        $msg = str_replace('EMAIL_EVENTO', $email_evento, $msg);
        $msg = str_replace('LINK_CERTIFICADO', $url, $msg);
        return $msg;
    }

    /**
     * Carrega uma view com o resultado da notificacao de participantes
     * @param Integer $enviados - Total de e-mails enviados
     */
    function exibeResultadoNotificacao($enviados) {
        
        $this->load->helper('tabela_detalhes_log');

        if ($enviados) {
            $linhas = geraCabecTblDetalheEmail();
            $i      = 0;
            foreach ($enviados as $linha) {
                $i++;
                $linhas .= geraLinhaTblDetalheEmail($i, $linha);
            }
            if ($linhas)
                $dadosEnvio = $linhas;

            $data["detalhes"] = $dadosEnvio;
        }
        else {
            $data["detalhes"] = "Nenhum e-mail foi enviado.";
        }


        $data['link_voltar']  = base_url() . 'certificados/notificar';
        $data['corpo_pagina'] = "resultado_notificacao_view";
        $view                 = $this->load->view('includes/templates/template', $data, true);
        geraSaida($view);
    }

    /**
     * Carrega uma view com o resultado da avaliacao de certificados
     * 
     * @param integer $total - total de certificados avaliados
     */
    function exibeResultadoAvaliacao($total) {
        $data["mensagem"]     = "Avaliação concluída.<br /> " .
                "Total de certificados avaliados: $total";
        $data['link_voltar']  = base_url() . 'sistema/principal';
        $data['corpo_pagina'] = "resultado_avaliacao_view";
        $this->load->view('includes/templates/template', $data);
    }

    /**
     * Personaliza mensagens de erro do sistema.
     */
    function _configureFormErrorMessage() {
        $this->form_validation->set_message('required', 'O campo <span class="message_field">%s</span> &eacute; obrigat&oacute;rio.');

        $this->form_validation->set_message('valid_email', 'O campo <span class="message_field">%s</span> n&atilde;o &eacute; um e-mail v&aacute;lido.');

        $this->form_validation->set_message('max_leght', 'O campo <span class="message_field">%s</span> apresenta um tamanho m&aacute;ximo de 12 caracteres.');

        $this->form_validation->set_message('numeric', 'O campo <span class="message_field">%s</span> deve possuir somente valor num&eacute;rico.');

        $this->form_validation->set_message('is_natural_no_zero', 'O campo <span class="message_field">%s</span> &eacute; obrigat&oacute;rio.');
    }

    /**
     * Faz a alteracao do status do certificado. Metodo acionado por ajax.
     */
    function alterarStatusCertificadoAjax() {
        $idCertificado = trim($_POST["id_certificado"]);
        $justificativa = trim($_POST["justificativa"]);
        $envMail       = trim($_POST["envia_email"]);
        $status        = trim($_POST["status"]);

        if ($idCertificado && $justificativa && $envMail && $status) {
            $this->load->model('certificados_model');
            $altOk = $this->certificados_model->alterarStatus($idCertificado, $justificativa, $envMail, $status);

            if ($altOk)
                echo "Certificado alterado com sucesso.";
            else
                echo "Ocorreu um erro. Operação não efetuada.";
        }
    }

    /**
     * Exibe numa popup o historico de alteracoes no status do certificado
     *
     * @param Integer $idCertificado
     */
    function historicoStatus($idCertificado) {
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }
        if ($idCertificado) {
            $this->load->model('certificados_model');
            $historico = $this->certificados_model
                    ->listarHistoricoStatus($idCertificado);
            if ($historico == null) {
                $data['mensagem']  = 'Nenhum registro encontrado.';
                $data['historico'] = null;
            } else {
                $data['historico'] = $historico;
            }
        } else {
            $data['mensagem']  = 'Nenhum registro encontrado.';
            $data['historico'] = null;
        }
        $data['titulo_pagina'] = "Histórico de Alterações no Status";
        $this->load->view('historico_status_certificado_view', $data);
    }

    /**
     * Apresenta uma view para selecao do evento
     */
    function selecionarEvento() {
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }

        $this->load->model('eventos_model');
        $data['eventos']       = $this->eventos_model->listarEventos();
        $data['titulo_pagina'] = "Consulta Certificados de Evento";
        $data['corpo_pagina']  = "selecao_evento_view";
        $this->load->view('includes/templates/template', $data);
    }

    /**
     * Apresenta uma view com os eventos e modelos de certificados para 
     * avaliacao. Visivel apenas para os administradores do sistema.
     */
    function selecionarModeloParaAvaliacao() {        
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }
        $this->load->model('eventos_model');
        $data['eventos'] = $this->eventos_model
                ->listarEventosControlador($this->session->userdata('uid'));
        if ($data['eventos']) {
            $data['titulo_pagina'] = "Selecionar Modelo Para Avaliação";
            $data['corpo_pagina']  = "selecao_modelo_avaliacao_view";
            $this->load->view('includes/templates/template', $data);
            $this->session->set_userdata('chkPendente', 'true');
        } else {
            redirect('sistema/bloqueado');
        }
    }

    /**
     * Listagem dos certificados para avaliacao.
     * Visivel apenas para os administradores do sistema.
     * 
     * @return type 
     */
    function obterEventoModeloAvaliacao() {
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }

        $idEvento = isset($_POST["txtEvento"]) ? $_POST["txtEvento"] : null;
        $idModelo = isset($_POST["txtModelo"]) ? $_POST["txtModelo"] : null;

        if (!$idEvento || !$idModelo)
            redirect('sistema/bloqueado');

        $this->session->set_userdata('evento_avaliacao', $idEvento);
        $this->session->set_userdata('modelo_avaliacao', $idModelo);

        redirect("certificados/novaAvaliacao/");
    }

    /**
     * Cancela a avaliacao de certificados
     */
    function cancelarAvaliacao() {
        redirect('sistema/principal');
    }

    /**
     * Salva a avaliacao do certificado
     */
    function salvarAvaliacao() {
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }
        $dados = $_POST;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('txtJustificativa', 'Justificativa', 'required');
        $this->form_validation->set_rules('txtStatus', 'Status', 'required');
        $this->_configureFormErrorMessage();

        $idEvento = $dados["txtEvento"];
        $idModelo = $dados["txtModelo"];
        if ($this->form_validation->run() == TRUE) {
            $justificativa = $dados["txtJustificativa"];
            $envMail       = $dados["txtEnvMail"];
            $status        = $dados["txtStatus"];
            $avaliados     = $dados["txtAvaliados"];

            if ($status == 'D') {
                $this->_apagarSelecionados($dados);
            }

            $this->load->model('certificados_model');
            $total = 0;
            foreach ($avaliados as $idCertificado) {
                if ($idCertificado > 0)
                    $altOk = $this->certificados_model
                            ->alterarStatus($idCertificado, $justificativa, $envMail, $status);
                if ($altOk)
                    $total++;
            }
            redirect('certificados/exibeResultadoAvaliacao/' . $total);
        } else
            $this->novaAvaliacao();
    }

    /**
     * Carrega um formulario para avaliacao de certificado
     * 
     * @param integer $idEvento
     * @param integer $idModelo
     * @return type 
     */
    function novaAvaliacao() {
        if ($this->session->userdata('logado') != 1) {
            $this->gerenciador_de_acesso->usuarioAuth();
        }
        $idEvento = $this->session->userdata('evento_avaliacao');
        $idModelo = $this->session->userdata('modelo_avaliacao');

        if (!$idEvento || !$idModelo)
            redirect('sistema/bloqueado');

        if (@$_POST['hdnPesquisa'] == 'pesquisa') {
            //se digitou um valor para pesquisa, armazena-o numa sessao.
            $this->session->set_userdata('valor_pesq', $_POST['txtPesquisa']);
            $this->session->set_userdata('tipo_pesq', $_POST['cmbPesquisa']);
        }

        $key  = $this->session->userdata('valor_pesq');
        $tipo = $this->session->userdata('tipo_pesq');

        $this->load->model('certificados_model');
        $this->load->model('eventos_model');
        $dadosEvento = $this->eventos_model->getById($idEvento);
        $nomeEvento  = $dadosEvento->nm_evento;

        //parametros paginacao
        $this->load->library('pagination');
        $url               = 'certificados/novaAvaliacao/';
        $total             = $this->certificados_model->totalCertificadosAvaliacao($idModelo, $key, $tipo);
        $pag               = $this->pagination->configPagination($url, $total, LIMITE_PESQUISA_PAGINA);
        $maximo            = $pag["maximo"];
        $inicio            = $pag["inicio"];
        $data['paginacao'] = $pag["links"];

        $resultado = $this->certificados_model
                ->listarCertificadosAvaliacao($idModelo, $key, $tipo, $maximo, $inicio);

        if ($resultado == null) {
            $data['nome_evento']  = $nomeEvento;
            $data['certificados'] = null;
            $data['mensagem']     = 'N&atilde;o h&aacute; registros para exibir';
        } else {
            $data['nome_evento']  = $nomeEvento;
            $data['certificados'] = $resultado;
        }

        $data['idEvento']     = $idEvento;
        $data['idModelo']     = $idModelo;
        $data['corpo_pagina'] = "certificados_avaliacao_view";
        $this->load->view('includes/templates/template', $data);
    }

    /**
     * Gera log de notificacao de participantes, chamando o metodo equivalente
     * na camada de modelo para a gravacao em banco de dados.
     */
    function geraLogEmail($id_modelo, $msg) {
        $this->load->model('log_importacao_model');
        $this->load->helper('date');

        $dados['nm_usuario']            = $this->session->userdata('uid');
        $dados['ip_usuario']            = $this->input->ip_address();
        $dados['id_certificado_modelo'] = $idModelo;
        $dados['msg_log']               = $msg;

        $idLog = $this->log_importacao_model->insert($dados);

        if (count(@$notImp) > 0)
            foreach ($notImp as $linha => $descr) {
                if ($linha > 0)
                    $this->log_importacao_model
                            ->insertDetalhe($idLog, $linha, $descr);
            }

        return $idLog;
    }

    function atualizaFiltroAvaliacao() {
        if ($_POST) {
            $idEvento = $_POST['evento'];
            $idModelo = $_POST['modelo'];
            $campo    = $_POST['campo'];
            $valor    = $_POST['valor'];
            if ($valor == 'false')
                $this->session->unset_userdata($campo);
            else
                $this->session->set_userdata($campo, $valor);

            echo "OK";
            $this->novaAvaliacao();
        }
    }

    function _testarEnvio($emailDestinatario, $emailRemetente) {
        $this->load->library('email');
        $this->load->library('Gerenciador_de_email');

        if ($emailDestinatario && $emailRemetente) {
            $teste = $this->gerenciador_de_email->testarEmail($emailDestinatario, $emailRemetente);

            if ($teste == 1) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Recebe array de certificados a serem apagados do sistema.
     * @param type $dados 
     */
    function _apagarSelecionados($dados) {
        if ($dados) {
            $idEvento      = $dados["txtEvento"];
            $idModelo      = $dados["txtModelo"];
            $justificativa = $dados["txtJustificativa"];
            $envMail       = $dados["txtEnvMail"];
            $status        = $dados["txtStatus"];
            $avaliados     = $dados["txtAvaliados"];
            if ($status == 'D') {
                $this->load->model('certificados_model');
                $total = 0;
                foreach ($avaliados as $idCertificado) {
                    if ($idCertificado > 0)
                        $altOk = $this->certificados_model
                                ->delete($idCertificado);
                    if ($altOk)
                        $total++;
                }
            }
            redirect('certificados/exibeResultadoAvaliacao/' . $total);
        }
    }

    function _montaCertificado($dadosCertificado) {
        if (!$dadosCertificado) {
            return false;
        }
        $this->load->model('modelos_certificado_model');
        $this->load->model('certificados_model');

        $dadosFrente   = $dadosCertificado->de_campos_frente;
        $colunasFrente = unserialize($dadosFrente);

        $dadosVerso   = $dadosCertificado->de_campos_verso;
        $colunasVerso = unserialize($dadosVerso);

        $colunasCertificado = array_merge($colunasFrente, $colunasVerso);

        // Parei aqui... recuperar dados do modelo de certificado
        $dados = $this->modelos_certificado_model->getById($dadosCertificado->id_modelo_certificado);

        // Efetua substituicao de texto no campo de texto
        $textoCertificado = $dados->de_texto;
        $textoVerso       = $dados->de_texto_verso;

        foreach (array_keys($colunasCertificado) as $colunaModelo) {

            $textoCertificado = str_replace($colunaModelo, utf8_encode($colunasCertificado[$colunaModelo]), $textoCertificado);

            $textoVerso = str_replace($colunaModelo, utf8_encode($colunasCertificado[$colunaModelo]), $textoVerso);
        }

        // Monta matriz de gravacao de  certificado no banco de dados
        $dataCert['id_certificado']       = $dadosCertificado->id_certificado;
        $dataCert['de_texto_certificado'] = $textoCertificado;
        $dataCert['de_complementar']      = $textoVerso;
        $atualizacao                      = $this->certificados_model->atualizaCertificado($dataCert);
        return $atualizacao;
    }

    /**
     * Exibe o retorno de uma operacao, com a mensagem passada e a url de direcio
     * namento
     *
     * @param String $mensagem - mensagem de retorno
     * @param String $url      - url de direcionamento
     */
    function exibeRetorno($mensagem, $url) {
        $data['mensagem']     = $mensagem;
        $data['corpo_pagina'] = "retorno_operacoes_view";
        $view                 = $this->load->view('includes/templates/template', $data, true);
        exibeRetornoOperacao($view, $url);
    }

    /**
     * Lista publica de eventos para obtenção de certificados 
     */
    function listaPublica() {
        $this->load->model('eventos_model');
        $data['eventos']       = $this->eventos_model->listarEventosPublicos();
        $data['corpo_pagina']  = "selecao_listaPublica_view";
        $data['titulo_pagina'] = "Busca de Certificados";
        $this->load->view('includes/templates/template', $data);

        //zera a pesquisa corrente
        $this->session->unset_userdata('valor_pesq');
        $this->session->unset_userdata('tipo_pesq');
    }

    /**
     * Lista de certificados aprovados do evento selecionado  
     * (visualizacao publica)
     */
    function listaCertificadosPublicos() {
        $this->load->model('eventos_model');
        $this->load->model('certificados_model');

        if (@$_POST['hdnPesquisa'] == 'pesquisa') {
            //se digitou um valor para pesquisa, armazena-o numa sessao.
            $this->session->set_userdata('valor_pesq', $_POST['txtPesquisa']);
            $this->session->set_userdata('tipo_pesq', $_POST['cmbPesquisa']);
        }

        $key  = $this->session->userdata('valor_pesq');
        $tipo = $this->session->userdata('tipo_pesq');

        if (isset($_POST['txtEvento'])) {
            $this->session->set_userdata('id_evento_publico', $_POST['txtEvento']);
        }

        $idEvento = $this->session->userdata('id_evento_publico');
        if ($idEvento) {
            $dadosEvento         = $this->eventos_model->getById($idEvento);
            $data['nome_evento'] = $dadosEvento->nm_evento;

            //parametros paginacao
            $this->load->library('pagination');
            $url               = 'certificados/listaCertificadosPublicos';
            $total             = $this->certificados_model->getTotalPublicos($idEvento, $key, $tipo);
            $pag               = $this->pagination->configPagination($url, $total, LIMITE_PESQUISA_PAGINA);
            $maximo            = $pag["maximo"];
            $inicio            = $pag["inicio"];
            $data['paginacao'] = $pag["links"];

            $listaCertificados = $this->certificados_model->listaCertificadosPublicos($idEvento, $key, $tipo, $maximo, $inicio);
            if ($listaCertificados) {
                $data['certificados'] = $listaCertificados;
            } else {
                $data['mensagem'] = "Não foram encontrados certificados válidos para emissão";
            }

            $data['corpo_pagina']  = "lista_certificados_publicos_view";
            $data['titulo_pagina'] = "Lista de Certificados";
            $this->load->view('includes/templates/template', $data);
        } else {
            $data['mensagem']     = "Não foram encontrados dados para o evento especificado. Tente novamente mais tarde!";
            $data['corpo_pagina'] = "mensagem_view";
            $this->load->view('includes/templates/template', $data);
        }
    }

    function gravaLogEmail($idEvento, $idModelo, $mensagem = null, $hash = null) {
        $this->load->helper('file');
        if ($mensagem) {
            $textoLog = "";
            $textoLog .= "[" . date('d-m-Y H:i:s') . "]";
            $textoLog .= " $hash: " . $mensagem . "\n";
        } else {
            $textoLog = "";
            $textoLog .= "[" . date('d-m-Y H:i:s') . "]";
            $textoLog .= " ..............................\n";
        }

        $caminho = $this->config->item('upload_path') . 'logs';

        if (!file_exists($caminho))
            mkdir($caminho, 0775);

        $arquivo = "$caminho/log-notif-$idModelo.log";
        write_file($arquivo, $textoLog, 'a');
        return true;
    }
    
    /**
     * Lista os certificados de determinada pessoa
     */
    function listaCertificadosPessoa() {
        $this->load->helper('email');
        
        $this->load->model('certificados_model');
        $this->load->model('participantes_model');
        
        if (isset($_POST['txtEmail'])) {
            $this->session->unset_userdata('email_pessoa');
            $this->session->set_userdata('email_pessoa', $_POST['txtEmail']);
        } 

        if (@$_POST['hdnPesquisa'] == 'pesquisa') {
            //se digitou um valor para pesquisa, armazena-o numa sessao.
            $this->session->set_userdata('valor_pesq', $_POST['txtPesquisa']);
            $this->session->set_userdata('tipo_pesq', $_POST['cmbPesquisa']);
        }

        $key  = $this->session->userdata('valor_pesq');
        $tipo = $this->session->userdata('tipo_pesq');

        //Faz a pesquisa apenas se o e-mail for válido
        $dadosPessoa = null;
        $emailPessoa = $this->session->userdata('email_pessoa');        
        if (valid_email($emailPessoa))
            $dadosPessoa = $this->participantes_model->getByEmail($emailPessoa);
        
        if ($dadosPessoa) {
            $idPessoa            = @$dadosPessoa->id_participante;
            $data['nome_pessoa'] = @$dadosPessoa->nm_participante;
        } else {
            $idPessoa            = null;
            $data['nome_pessoa'] = null;
        }

        //parametros paginacao
        $this->load->library('pagination');
        $url               = 'certificados/listaCertificadosPessoa';
        $total             = $this->certificados_model->getTotalPessoa($idPessoa, $key, $tipo);
        $pag               = $this->pagination->configPagination($url, $total, LIMITE_PESQUISA_PAGINA);
        $maximo            = $pag["maximo"];
        $inicio            = $pag["inicio"];
        $data['paginacao'] = $pag["links"];

        $listaCertificados = null;
        
        if ($idPessoa)
            $listaCertificados = $this->certificados_model->listaCertificadosPessoa($idPessoa, $key, $tipo, $maximo, $inicio);

        if ($listaCertificados) {
            $data['certificados'] = $listaCertificados;                
        } else {
            if (!valid_email($emailPessoa)) {
                $data['mensagem'] = "Digite um endereço de e-mail válido.";
            }else{
                $data['mensagem'] = "Não foram encontrados certificados válidos para emissão.";
            }
            
            $data['corpo_pagina'] = "mensagem_view";                
        }

        $data['corpo_pagina']  = "lista_certificados_pessoa_view";
        $data['titulo_pagina'] = "Lista de Certificados";

        $this->load->view('includes/templates/template', $data);
        
    }
}

?>