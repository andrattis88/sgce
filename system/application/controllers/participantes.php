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
 * Controller para a funcao de Cadastro de participantes
 * @author Pedro Jr     <pedro.junior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 */

class Participantes extends Controller {

    /**
     * Construtor da Classe.
     *
     * Inicializa helpers e bibliotecas do CodeIgniter e verifica se o usuario
     * tem permissao para abrir o controller.
     *
     */
    function Participantes() {
        parent::Controller();        
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('data');
        $this->load->helper('retorno_operacoes');
        $this->load->helper('progresso_execucao_helper');
        $this->load->library('session');
        $this->load->library('pagination');
        $this->lang->load('msg');
        $this->load->model('eventos_model');
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
        $this->session->set_userdata('valor_pesq',  null);
        $this->session->set_userdata('tipo_pesq',   null);
        $this->session->set_userdata('ordem_valor', null);
        $this->session->set_userdata('ordem_tipo',  null);
        $this->listar();
    }    

    /**
     * Metodo chamado na pesquisa de registros da view, na paginacao e no index
     */

    function listar() {
        $resultado = array(); 
        $this->load->model('participantes_model');

        if(@$_POST['hdnPesquisa'] == 'pesquisa') {
             //se digitou um valor para pesquisa, armazena-o numa sessao.
            $this->session->set_userdata('valor_pesq', $_POST['txtPesquisa']);
            $this->session->set_userdata('tipo_pesq', $_POST['cmbPesquisa']);
        }

        $key  = $this->session->userdata('valor_pesq');
        $tipo = $this->session->userdata('tipo_pesq');

        //parametros paginacao
        $this->load->library('pagination');
        $url               = 'participantes/listar';
        $total             = $this->participantes_model->getTotal($key, $tipo);
        $pag               = $this->pagination->configPagination($url, $total, LIMITE_PESQUISA_PAGINA);
        $maximo            = $pag["maximo"];
        $inicio            = $pag["inicio"];
        $data['paginacao'] = $pag["links"];

        $resultado = $this->participantes_model->search($key, $tipo, $maximo, $inicio,
                                                 $this->session->userdata('ordem_valor'),
                                                 $this->session->userdata('ordem_tipo'));
        
        if ($resultado ==null) {
            $data['mensagem'] = 'N&atilde;o h&aacute; registros para exibir';
            $data['participantes'] = null;
        }
        else {
            $data['participantes'] = $resultado;
        }

        $data['corpo_pagina'] = "participantes_view";
        $this->load->view('includes/templates/template', $data);
        
    }

    /**
     * Metodo para criar um novo registro de participantes, exibindo um formulario com
     * campos para preenchimento do cadastro.
     * @param Array $errors - Especifica se houve erros de validacao em passos
     * anteriores.
     */
    function novo($errors=null) {
        $data['errors'] = $errors;
        $data['corpo_pagina']  = "cad_participantes_view";
        $data['titulo_pagina'] = "Novo Participante";
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
        $this->form_validation->set_rules('txtDeEmail', 'E-mail', 'required');
        //$this->form_validation->set_rules('txtNrDocumento', 'Documento', 'required');
        $this->_configureFormErrorMessage();

        $id    = isset($dados["txtId"]) ? $dados["txtId"] : null;        
        if ($this->form_validation->run() == TRUE) {
            $this->load->model('participantes_model');
            $data["id_participante"]             = $id;
            $data["nm_participante"]             = $dados['txtNome'];
            $data["de_email"]                    = $dados['txtDeEmail'];
            $data["nr_documento"]                = $dados['txtNrDocumento'];
            $data["dtInclusao"]                  = $dados['txtDtInclusao'];
            $data["dtAlteracao"]                 = $dados['txtDtAlteracao'];
            
            if($id) {
                $this->participantes_model->update($data);
                $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'participantes');
            } else {
                $regInsert = $this->participantes_model->insert($data);
                if(!$regInsert) {
                    $data["mensagem"] = "Nao foi posivel inserir o registro.";
                    $data['corpo_pagina']  = "mensagem_view";
                    $this->load->view('includes/templates/template', $data);
                } else {
                    $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'participantes');
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
     * Abre formulario para edicao de um registro de participantes.
     * @param Integer $id   - Especifica o ID que sera editado.
     * @param Array $errors - Especifica se existem erros de validacao ou nao.
     */
    function editar($id,$errors=null) {
        if($id > 0) {
            $this->load->model('participantes_model');
            $data['participante']         = $this->participantes_model->getById($id);
            $data['error']         = $errors;
            $data['corpo_pagina']  = "cad_participantes_view";
            $data['titulo_pagina'] = "Alterar Participante";
            $data['operacao']      = "editar";
            $this->load->view('includes/templates/template', $data);
        }
    }

    /**
     * Permite excluir os dados de um formando apos consulta e confirmacao do banco de dados.
     * @param Integer $idparticipante  - Especifica o ID do participante a excluir.
     */
    function excluir($idParticipante) {
        if($idParticipante > 0) {
            $this->load->model('participantes_model');
            $resultado = $this->participantes_model->delete($idParticipante);
            if(!$resultado) {
                $data['corpo_pagina']  = "mensagem_view";
                $data['mensagem']      = "Não foi possível excluir o registro. <br/>
                                          Este participante pode ter certificados gerados em seu nome, portanto, não pode ser excluído.";

                $this->load->view('includes/templates/template', $data);
            } else {
                $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'participantes');
            }
        }

    }


    /**
     * Cancela a operacao corrente e retorna para a tela inicial
     */
    function cancelar() {
        redirect('participantes');
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
     * Metodo que executa a importacao da relacao de participantes, chamando
     * o metodo equivalente na camada de modelo. Apos, exibe na tela o resultado
     * da importacao.
     *
     * @param String  $arquivoOrigem - nome do arquivo com a relacao de participantes
     * @param Integer $idModelo      - id do modelo de certificado
     */
    function importar($arquivoOrigem, $idModelo, $idEvento, $flDuplicados) {
        $this->load->model('participantes_model');
        $this->load->model('modelos_certificado_model');
        if ($arquivoOrigem) {
            $this->load->library('CSVReader');
            $dados         = $this->csvreader->parse_file($arquivoOrigem, true);
       
            $linhasArq     = count($dados);
            $colunasModelo = $this->modelos_certificado_model
                                  ->getColunasModelo($idModelo);
       
            $result        = $this->participantes_model
                                      ->importaParticipantes($dados, $colunasModelo,
                                                                     $idModelo, $idEvento, $flDuplicados);            
            
            $totImp      = isset($result['totImp'])      ? $result['totImp']      : 0;
            $certGerados = isset($result['certGerados']) ? $result['certGerados'] : 0;
            $notImp      = isset($result['notImp'])      ? $result['notImp'] : 0;
            $logImp      = isset($result['logImp'])      ? $result['notImp'] : 0;
            
            if(($totImp == 0) && ($certGerados == 0)) {
                $data["mensagem"] = "<b>Atenção! </b><br />".
                                    "Nenhum participante importado e nenhum certificado gerado. Possíveis causas: <br /><br />".
                                    "O arquivo anexado não possui as colunas exigidas pelo modelo de certificado. <br />".
                                    "Os participantes do arquivo anexado já estão cadastrados no sistema. <br />".
                                    "O endereço de e-mail dos participantes do arquivo anexado não é válido. <br /> ".
                                    "Os certificados que constam neste arquivo já foram gerados. Caso queira gerar duplicatas, marque a opção correspondente na tela de importação.";
            } else {
                $data["mensagem"] = "<b>Importação Concluída! </b> <br /><br />".
                                    "Linhas lidas do arquivo: $linhasArq <br />".
                                    "Participantes importados: $totImp <br />".
                                    "Certificados gerados: $certGerados <br />";
            }
        } else {
            $data["mensagem"] = "Erro na leitura do arquivo de importação para o servidor. <br />";
        }
        
        $idLog      = $this->geraLog($arquivoOrigem, $idModelo, $data["mensagem"], $logImp);
        $urlDestino = base_url() . "participantes/exibeResultadoImportacao/$idLog";
        direcionarURL($urlDestino);
    }


    /**
     * Apresenta o formulario de importacao de participantes
     */
    function formImporta() {
        $data['eventos']      = $this->eventos_model->listarEventos();
        $data['corpo_pagina'] = 'cad_importador_view';
        $this->load->view('includes/templates/template', $data);
    }


    /**
     * Action executada na submissao do formulario de importacao de participantes.
     * Faz a validacao dos campos;
     * Chama metodo para anexar arquivo;
     * Chama metodo para importacao dos participantes.
     */
    function uploadRetorno() {
        $this->load->helper('replace_ascii');
        $errors = '';
        $dados  = $_POST;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('txtEvento', 'Evento', 'required');
        $this->form_validation->set_rules('txtModelo', 'Modelo', 'required');
        $this->_configureFormErrorMessage();
        $this->removeArquivo($dados["txtModelo"]);

        if ($this->form_validation->run() == TRUE) {
            $data['corpo_pagina']  = "progresso_execucao_view";
            $view = $this->load->view('includes/templates/template', $data, true);
            descarregaBufferProgressoExecucao($view);
            $_FILES['txtArquivo']['name'] = $_POST['txtModelo'].'.csv';            
            $txtArquivo = $_FILES;
            $idModelo = $dados["txtModelo"];

            // Criando a variável para armazenar o id do evento a ser passado para o método de 'importar'
            $idEvento = $dados["txtEvento"];
            if ($_FILES) {
                $resultado = $this->doUpload('txtArquivo');
                if ($resultado) {
                    $nomeArq  = $this->config->item('upload_path').$_FILES['txtArquivo']['name'];
                    $tipoArq  = $_FILES['txtArquivo']['type'];

                    $flDuplicados = $dados['txtDuplicados'];
                    $this->importar($nomeArq, $idModelo, $idEvento, $flDuplicados);
                    $flNotificarControladores = $dados['txtNotificarControladores'];
                    $this->notificarControladores($idModelo, $flNotificarControladores);
                } else {
                    $nomeArq          = isset($_FILES['txtArquivo']['name']) ?
                                              $_FILES['txtArquivo']['name'] : 'não informado';
                    $data["mensagem"] = "Erro no envio do arquivo de importação para o servidor.";
                    $idLog            = $this->geraLog($nomeArq, $idModelo, $data["mensagem"]);
                    $urlDestino       = base_url() . "participantes/exibeResultadoImportacao/$idLog";
                    direcionarURL($urlDestino);
                }
            }
        } else
            $this->formImporta();
    }


    /**
     * Executa upload do arquivo passado
     * @param string $arquivo - input name do arquivo
     * @return Boolean        - True em caso de sucesso, false caso contrario.
     */
    function doUpload($arquivo) {
        if($arquivo) {
            $config['upload_path']   = $this->config->item('upload_path');
            $config['allowed_types'] = $this->config->item('allowed_types');
            $config['max_size']      = $this->config->item('max_size');

            $this->load->library('upload', $config);
            $files = $this->upload->do_upload($arquivo);

            if (!$files) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Remocao fisica do arquivo do disco rigido.
     * 
     * @param String $nmArquivo  - nome do arquivo a ser excluido
     */
    function removeArquivo($idModelo) {
        if($idModelo) {
            $nomeArq = $idModelo.'.csv';
            $arquivo = $this->config->item('upload_path') . $nomeArq;
            if(is_file($arquivo)) {
                unlink($arquivo);
                $log = $this->geraLog($nomeArq, $idModelo,
                               "O arquivo $nomeArq já existia no servidor e foi removido.");
            }
        }
    }
    
    /**
     * Funcao para repetir importacao do ultimo arquivo enviado
     * para o modelo
     * 
     * @param Integer $modelo - ID do Modelo
     */
    
    function repetirImportacao($idModelo=null) {
        if ($idModelo) {
           $this->importar($this->config->item('upload_path').$idModelo.'.csv', 
                   $idModelo, $flDuplicados='S');             
        }
        else {
            $data['link_voltar']   = base_url() . 'participantes/formImporta';
            $data['corpo_pagina']  = "mensagem_view";
            $data['mensagem']      = "Selecione um evento e um modelo de certificado para executar esta operação";
            $this->load->view('includes/templates/template', $data);   
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

    }


    /**
     * Gera log de importacao de participantes, chamando o metodo equivalente
     * na camada de modelo para a gravacao em banco de dados.
     *
     * @param String  $arquivoOrigem - nome do arquivo analisado
     */
    function geraLog($arquivoOrigem, $idModelo, $msg, $notImp = null) {
        $this->load->model('log_importacao_model');
        $this->load->helper('date');
        
        $dados['nm_usuario']            = $this->session->userdata('uid');
        $dados['ip_usuario']            = $this->input->ip_address();
        $dados['id_certificado_modelo'] = $idModelo;
        $dados['msg_log']               = $msg;

        $idLog = $this->log_importacao_model->insert($dados);

        if(count(@$notImp) > 0)
            foreach ($notImp as $linha => $descr) {
                if($linha > 0 )
                    $this->log_importacao_model
                         ->insertDetalhe($idLog, $linha, $descr);
            }

        return $idLog;
    }
   
    /**
     * Exibe uma view com o resultado da importacao
     * 
     * @param Integer $idLog 
     */
    function exibeResultadoImportacao($idLog = null) {
        if($idLog) {
            $this->load->model('log_importacao_model');
            $regLog = $this->log_importacao_model->getById($idLog);
            $data['mensagem']      = $regLog->msg_log;
            $data['id_log']        = $regLog->id_log; 
        } else {
            $data['mensagem']      = "Ocorreu um erro no servidor. Contate o suporte.";
        }
        $data['link_voltar']   = base_url() . 'participantes/formImporta';
        $data['corpo_pagina']  = "resultado_importacao_view";
        $this->load->view('includes/templates/template', $data);
    }

    /**
     * Visualizacao do log de importacao
     *
     * @param Integer $idModelo - Especifica a ID do modelo de certificado
     */
    function historicoImportacao($idModelo = null) {
        if ($idModelo) {
            $this->load->model('log_importacao_model');
            $historico =  $this->log_importacao_model->listarLogModelo($idModelo);
            if ($historico == null) {
                $data['mensagem'] = 'Nenhum registro encontrado.';
                $data['historico'] = null;
            } else {
                $data['historico'] = $historico;
            }
        } else {
            $data['mensagem'] = 'Nenhum registro encontrado. Selecione o evento 
                e o modelo de certificado correspondente para visualizar os registros.';
            $data['historico'] = null;
        }
        $data['titulo_pagina'] = "Histórico de Importações";
        $this->load->view('historico_importacao_view', $data);
    }

    

    /**
     * Carrega os detalhes do log de importacao, acionado por ajax.
     */
    function carregaDetalhesLogTableAjax() {
        $idLog = $_POST['id_log'];
        $this->load->helper('tabela_detalhes_log');       
        $this->load->model('log_importacao_model');
                
        $res    = $this->log_importacao_model->obtemDetalhesLog($idLog);
        $linhas = null;
        if($res) {
            $linhas = geraCabecTblDetalheLog($idLog);
            foreach ($res as $linha) {
                $linhas .= geraLinhaTblDetalheLog($linha->nr_linha,
                                                  $linha->de_descricao);
            }            
        }
        if($linhas)
            echo $linhas;
    }
    
    /**
     * Carrega dados dos controladores e notifica-os em caso de 
     * importacao de dados
     * 
     * @param Integer $idModelo
     * @param String  $flNotificarControladores
     * @return Boolean
     */
    function notificarControladores($idModelo, $flNotificarControladores) {
        $this->load->model('certificados_model');
        $this->load->helper('template_mail');
                
        if (!$idModelo) {
            return false;
        }
        if ($flNotificarControladores!='S') {
            return false;
        }
        
        $certificadosControlar = $this->certificados_model 
                                      ->listarCertificadosAguardando($idModelo);
        
        if (!$certificadosControlar) {
            return false;
        }
        
        $qtdCertificados = count($certificadosControlar);                              
        $idEvento        = $certificadosControlar[0]->id_evento;
        $nomeEvento      = $certificadosControlar[0]->nm_evento;
        $nomeModelo      = $certificadosControlar[0]->nm_modelo;

        // Busca emails de avaliadores específicos
        // carrega lista de controladores em array.

        $this->load->model('organizadores_evento_model');
        $listaControladores = $this->organizadores_evento_model
                                   ->buscaControladorEspecifico($idEvento);

        // Caso não existam avaliadores específicos, busca globais, e 
        if (!count($listaControladores) > 0) {
            $this->load->model('organizadores_model');
            $listaControladores = $this->organizadores_model
                                       ->listarControladoresGlobais();                        
        }

        // Testa se vai enviar ou não, e 
        // Monta a mensagem de e-mail a enviar                    
        if (!(count($listaControladores) > 0)) {
            return false;
        }
        foreach($listaControladores as $controlador) {
            $this->load->helper('email_helper');
            $this->load->helper('template_mail');
            $this->load->library('email');
            $this->load->library('gerenciador_de_email');                            

            if (valid_email($controlador->de_email)) {
                $assuntoEmail = NOME_SISTEMA. " - Notificação de 
                    Certificados pendentes de Avaliação";

                $textoEmail  = "Prezado ".$controlador->nm_organizador;
                $textoEmail .= "<br><br>Foram importados 
                <b>$qtdCertificados</b> certificados do evento 
                <b>$nomeEvento</b> do modelo <b>$nomeModelo</b> 
                para serem conferidos e liberados.<br><br>
                Para conferir estes certificados e liberá-los para notificação, 
                acesse o ".NOME_SISTEMA." através do endereço: 
                <br><br>".anchor(base_url())."<br><br>Atenciosamente,
                <br>Comissão Organizadora do $nomeEvento";

                // Envia a mensagem aos controladores globais ou específicos                                                               
               $emailOk = $this->gerenciador_de_email
                               ->enviaEmailPessoa($controlador->de_email, $assuntoEmail, 
                                                  $textoEmail);
            }
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

}
?>
