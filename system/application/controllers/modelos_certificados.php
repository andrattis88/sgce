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
 * Controller para a funcao de Cadastro de modelos_certificado
 * @author Pedro Jr     <pedro.junior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 */

class modelos_certificados extends Controller {

    /**
     * Construtor da Classe.
     *
     * Inicializa helpers e bibliotecas do CodeIgniter e verifica se o usuario
     * tem permissao para abrir o controller.
     *
     */
    function modelos_certificados() {
        parent::Controller();        
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('data');
        $this->load->helper('replace_ascii');        
        $this->load->helper('retorno_operacoes');
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
        $this->listar();
    }    

    /**
     * Metodo chamado na pesquisa de registros da view, na paginacao e no index
     */
    function listar() {
        $resultado = array(); 
        $this->load->model('modelos_certificado_model');

        if(@$_POST['hdnPesquisa'] == 'pesquisa') {
             //se digitou um valor para pesquisa, armazena-o numa sessao.
            $this->session->set_userdata('valor_pesq', $_POST['txtPesquisa']);
            $this->session->set_userdata('tipo_pesq', $_POST['cmbPesquisa']);
        }

        $key  = $this->session->userdata('valor_pesq');
        $tipo = $this->session->userdata('tipo_pesq');

        //parametros paginacao
        $this->load->library('pagination');
        $url               = 'modelos_certificados/listar';
        $total             = $this->modelos_certificado_model->getTotal($key, $tipo);
        $pag               = $this->pagination->configPagination($url, $total, LIMITE_PESQUISA_PAGINA);
        $maximo            = $pag["maximo"];
        $inicio            = $pag["inicio"];
        $data['paginacao'] = $pag["links"];

        $resultado = $this->modelos_certificado_model->search($key, $tipo, $maximo, $inicio,
                                                 $this->session->userdata('ordem_valor'),
                                                 $this->session->userdata('ordem_tipo'));
        
        if ($resultado ==null) {
            $data['mensagem'] = 'N&atilde;o h&aacute; registros para exibir';
            $data['modelos_certificado'] = null;
        }
        else {
            $data['modelos_certificados'] = $resultado;
        }

        $data['corpo_pagina'] = "modelos_certificado_view";
        $this->load->view('includes/templates/template', $data);
        
    }

    /**
     * Metodo para criar um novo registro de modelos_certificado, exibindo um formulario com
     * campos para preenchimento do cadastro.
     * @param Array $errors - Especifica se houve erros de validacao em passos
     * anteriores.
     */
    function novo($errors=null) {
        $this->load->model('eventos_model');
        $data['eventos']       = $this->eventos_model->listarEventos();        
        $data['errors'] = $errors;        
        $data['corpo_pagina']  = "cad_modelos_certificado_view";
        $data['titulo_pagina'] = "Novo Modelo de Certificado";
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
        $this->form_validation->set_rules('txtEvento', 'Evento', 'required');                
        $this->form_validation->set_rules('txtTexto', 'Texto', 'required');
        $this->form_validation->set_rules('txtEstilo', 'Estilo de Texto', 'required');
        $this->form_validation->set_rules('txtInstrucoes', 'Instruções de Importação', 'required');
        $this->_configureFormErrorMessage();

        $id    = isset($dados["txtId"]) ? $dados["txtId"] : null;        
        if ($this->form_validation->run() == TRUE) {
            $txtArquivo = $_FILES;
            if (($_FILES) && ($dados['modoArquivo'] == 'upload')) {
                $resultado = $this->doUpload('txtArquivo');
                if ($resultado) {
                    $data["nm_fundo"] = replaceAscii($_FILES['txtArquivo']['name']);                    
                }                
            }

            if ($dados['modoArquivo']=='atual') {
                $data["nm_fundo"] = $dados['nomeModelo'];
            }

            $this->load->model('modelos_certificado_model');
            $data["id_certificado_modelo"] = $id;
            $data["nm_modelo"]             = $dados['txtNome'];
            $data["de_titulo"]             = $dados['txtTitulo'];
            $data["id_evento"]             = $dados['txtEvento'];
            $data["nm_fonte"]              = $dados['txtEstilo'];
            $data["de_texto"]              = $dados['txtTexto'];
            $data["de_instrucoes"]         = $dados['txtInstrucoes'];
            $data["de_carga"]              = $dados['txtCarga'];
            $data["dtInclusao"]            = $dados['txtDtInclusao'];
            $data["dtAlteracao"]           = $dados['txtDtAlteracao'];

            // Texto Verso
            $data["de_titulo_verso"]       = $dados['txtTituloVerso'];
            $data["de_texto_verso"]        = $dados['txtTextoVerso'];
            
            // Layout

            $data["de_posicao_titulo"]     = intval($dados['txtDistanciaTopoTitulo']);
            $data["de_posicao_texto"]      = intval($dados['txtDistanciaTopoTexto']);
            $data["de_posicao_validacao"]  = intval($dados['txtDistanciaTopoValidacao']);

            $data["de_alinhamento_titulo"]    = $dados['txtSelAlinSecaoTitulo'];
            $data["de_alinhamento_texto"]     = $dados['txtSelAlinSecaoTexto'];
            $data["de_alinhamento_validacao"] = $dados['txtSelAlinSecaoValidacao'];

            $data["de_alin_texto_titulo"]       = $dados['txtSelAlinTextoTitulo'];
            $data["de_alin_texto_texto"]        = $dados['txtSelAlinTextoTexto'];
            $data["de_alin_texto_validacao"]    = $dados['txtSelAlinTextoValidacao'];

            $data["de_cor_texto_titulo"]       = $dados['txtSelCorTitulo'];
            $data["de_cor_texto_texto"]        = $dados['txtSelCorTexto'];
            $data["de_cor_texto_validacao"]    = $dados['txtSelCorValidacao'];

            $data["de_tamanho_titulo"]         = $dados['txtTamanhoTitulo'];
            $data["de_tamanho_texto"]          = $dados['txtTamanhoTexto'];
            
            $data["modoArquivo"]               = $dados['modoArquivo'];
            
            if($id) {
                $this->modelos_certificado_model->update($data);
                $this->load->model('certificados_model');
                $certificadosModelo = $this->certificados_model->listarCertificadosDoModelo($id);
                if ($certificadosModelo) {
                    foreach($certificadosModelo as $cert) {
                        $this->_atualizaCertificado($cert->de_hash);
                    }
                    
                    // Retorna para a tela anterior depois de atualizar os certificados (todos)
                    $this->exibeRetorno('Operação executada com sucesso. Os certificados existentes foram atualizados.', 'modelos_certificados/editar/'.$id);
                    
                } else {
                    $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'modelos_certificados/editar/'.$id);
                }
            } else {
                $regInsert = $this->modelos_certificado_model->insert($data);
                if(!$regInsert) {
                    $data["mensagem"] = "Nao foi posivel inserir o registro.";
                    $data['corpo_pagina']  = "mensagem_view";
                    $this->load->view('includes/templates/template', $data);
                } else {
                  $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'modelos_certificados/editar/'.$regInsert);
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
     * Abre formulario para edicao de um registro de modelos_certificado.
     * @param Integer $id   - Especifica o ID que sera editado.
     * @param Array $errors - Especifica se existem erros de validacao ou nao.
     */
    function editar($id,$errors=null) {
        if($id > 0) {
            $this->load->model('eventos_model');
            $this->load->model('modelos_certificado_model');
            $data['eventos']            = $this->eventos_model->listarEventos();
            $data['modelo_certificado'] = $this->modelos_certificado_model->getById($id);
            $data['error']              = $errors;
            $data['corpo_pagina']       = "cad_modelos_certificado_view";
            $data['titulo_pagina']      = "Alterar Modelo de Certificado";
            $data['operacao']           = "editar";
            $this->load->view('includes/templates/template', $data);
        }
    }

    /**
     * Permite excluir os dados de um formando apos consulta e confirmacao do banco de dados.
     * @param Integer $idEvento  - Especifica o ID do Evento a excluir.
     */
    function excluir($idEvento) {
        if($idEvento > 0) {
            $this->load->model('modelos_certificado_model');
            $resultado = $this->modelos_certificado_model->delete($idEvento);
            if(!$resultado) {
                $data['corpo_pagina']  = "mensagem_view";
                $data['mensagem']      = "Não foi possível excluir o registro. <br/>
                                          Verifique se este modelo não possui certificados gerados no sistema.";
                $this->load->view('includes/templates/template', $data);
            } else {
                $this->exibeRetorno('Operação executada com sucesso. Aguarde...', 'modelos_certificados');
            }
        }

    }


    /**
     * Cancela a operacao corrente e retorna para a tela inicial
     */
    function cancelar() {
        redirect('modelos_certificados');
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
     * Envia o arquivo com o desenho do fundo do certificado para o servidor.
     * @param Array $arquivo - Dados do arquivo que deve ser enviado.
     * @return Booblean      - Especifica se o arquivo foi enviado corretamente.
     */

    function doUpload($arquivo) {
        if($arquivo) {                        
            $config['upload_path']   = $this->config->item('upload_path').'/modelos';
            $config['allowed_types'] = 'jpg|jpeg';            
            $config['max_size']      = $this->config->item('max_size');
            $config['file_name']     = replaceAscii($_FILES[$arquivo]['name']);

            $this->load->library('upload', $config);
            $files = $this->upload->do_upload($arquivo);
            
            if (!$files ) {
                $errors = array('error' => $this->upload->display_errors());
                $data['errors'] = $errors;                
                $data["mensagem"] = "Erro no envio do arquivo de espelho para o servidor.";
                $data['corpo_pagina']  = "mensagem_view";
                $data['link_voltar']  = "javascript:history.back();";
                $this->load->view('includes/templates/template', $data);
                return false;
            } else {
                return true;
            }

        }
    }

    /**
     * Abre a visualização do modelo especificado em PDF
     * @param type $idModelo
     */
    function verModelo($idModelo) {
        $this->load->model('modelos_certificado_model');

        if ($idModelo) {
            $dadosCertificado    = @$this->modelos_certificado_model->getById($idModelo);
            $data['certificado'] = @$dadosCertificado;
            if ($dadosCertificado) {
                $html = $this->load->view('includes/templates/certificado_previa_view', $data, TRUE);                
                if ($dadosCertificado->de_texto_verso) {
                    $htmlVerso = $this->load->view('includes/templates/certificado_previa_verso_view', $data, TRUE);                    
                    $html = $html.$htmlVerso;
                }                
                $this->load->plugin('to_pdf');
                pdf_create($html,'modelo'.$idModelo, true, 'a4', 'landscape');
                
            }            
        }
    }   
    
    
    /**
     * Obtem colunas do texto no padrao.
     * @param type $texto
     * @return type 
     */
    
    function getColunasTexto($texto) {
        $pattern     = '/\b[A-Z]+_+(_*[A-Z]*)+\b/';

        if(preg_match_all($pattern, $texto, $matches))
            return $matches[0];
        else
            return null;
    
    }
    
    
    
    /**
     * Clona um modelo de certificado
     * 
     * @param integer $idModelo
     */
    function clonar($idModelo) {
        if(!$idModelo)
            return null;      
        
        $this->load->model('modelos_certificado_model'); 
        $modelo = $this->modelos_certificado_model->getById($idModelo);
        
        if($modelo) {
            $data["nm_modelo"]       = $modelo->nm_modelo . 
                                       " - Clone (Código: $modelo->id_certificado_modelo)";
            $data["de_titulo"]       = $modelo->de_titulo;
            $data["id_evento"]       = $modelo->id_evento;
            $data["nm_fonte"]        = $modelo->nm_fonte;
            $data["de_texto"]        = $modelo->de_texto;
            $data["de_instrucoes"]   = $modelo->de_instrucoes;
            $data["de_carga"]        = $modelo->de_carga;

            // Texto Verso
            $data["de_titulo_verso"]       = $modelo->de_titulo_verso;
            $data["de_texto_verso"]        = $modelo->de_texto_verso;
            
            // Layout
            $data["de_posicao_titulo"]     = $modelo->de_posicao_titulo;
            $data["de_posicao_texto"]      = $modelo->de_posicao_texto;
            $data["de_posicao_validacao"]  = $modelo->de_posicao_validacao;

            $data["de_alinhamento_titulo"]    = $modelo->de_alinhamento_titulo;
            $data["de_alinhamento_texto"]     = $modelo->de_alinhamento_texto;
            $data["de_alinhamento_validacao"] = $modelo->de_alinhamento_validacao;

            $data["de_alin_texto_titulo"]     = $modelo->de_alin_texto_titulo;
            $data["de_alin_texto_texto"]      = $modelo->de_alin_texto_texto;
            $data["de_alin_texto_validacao"]  = $modelo->de_alin_texto_validacao;

            $data["de_cor_texto_titulo"]       = $modelo->de_cor_texto_titulo;
            $data["de_cor_texto_texto"]        = $modelo->de_cor_texto_texto;
            $data["de_cor_texto_validacao"]    = $modelo->de_cor_texto_validacao;

            $data["de_tamanho_titulo"]         = $modelo->de_tamanho_titulo;
            $data["de_tamanho_texto"]          = $modelo->de_tamanho_texto;
            $data["nm_fundo"]                  = $modelo->nm_fundo;            
            
            $id = $this->modelos_certificado_model->insert($data);
            if($id) 
                $data['mensagem']  = "O modelo de certificado foi clonado com sucesso. <br />".
                                     "Código do novo modelo: $id. <br />";
             else 
                $data['mensagem']  = "Ocorreu um erro ao clonar o modelo de certificado.<br />";  
             
        } else 
            $data['mensagem']   = "Modelo de certificado não encontrado. <br />";  
        
            $data['corpo_pagina'] = "resultado_clonagem_modelo_view";
            $this->load->view('includes/templates/template', $data);           
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
     * Funcao utilizada para atualizacao individual de certificados.
     * 
     * @param type $hashCertificado
     * @return type 
     */
    
    function _atualizaCertificado($hashCertificado) {        
	$this->load->model('certificados_model');
	$this->load->model('modelos_certificado_model');
	$this->load->model('participantes_model');
	
	if ($hashCertificado) {
            $idCertificado       = @$this->certificados_model->existeHash($hashCertificado);
            $dadosCertificado    = @$this->certificados_model->recuperaCertificado($idCertificado);            
            $atualizaCertificado = $this->_montaCertificado($dadosCertificado);
            return $atualizaCertificado;
	} else {
            return false;
        }
    }
    
    /**
     * Funcao utilizada para receber os dados de um certificado e remontar 
     * o texto para atualizacao
     * 
     * @param type $dadosCertificado
     * @return type 
     */


    function _montaCertificado($dadosCertificado) {
	if (!$dadosCertificado) {
            return false;
	}
        
        $this->load->model('modelos_certificado_model');
	$this->load->model('certificados_model');        
	
	$dadosFrente   = $dadosCertificado->de_campos_frente;        
	$colunasFrente = unserialize($dadosFrente);
        
	$dadosVerso    = $dadosCertificado->de_campos_verso;
	$colunasVerso  = unserialize($dadosVerso);

	$colunasCertificado = array_merge($colunasFrente, $colunasVerso);
        	
	// Recuperar dados do modelo de certificado
	$dados = $this->modelos_certificado_model->getById($dadosCertificado->id_modelo_certificado);
        
	// Efetua substituicao de texto no campo de texto
	$textoCertificado = $dados->de_texto;
	$textoVerso       = $dados->de_texto_verso;
	
	foreach (array_keys($colunasCertificado) as $colunaModelo) {            
		
	   $textoCertificado = str_replace($colunaModelo,utf8_encode($colunasCertificado[$colunaModelo]),
                                           $textoCertificado);

           $textoVerso = str_replace($colunaModelo,utf8_encode($colunasCertificado[$colunaModelo]),
                                     $textoVerso);

	}
        
	// Monta matriz de gravacao de  certificado no banco de dados
	$dataCert['id_certificado']        = $dadosCertificado->id_certificado;
	$dataCert['de_texto_certificado']  = $textoCertificado;
	$dataCert['de_complementar']       = $textoVerso;
        
	$atualizacao = $this->certificados_model->atualizaCertificado($dataCert);                
        return $atualizacao;
    }
    
    /**
     * Carrega as instrucoes de importacao do modelo
     */
    function carregaInstrucoesImportacaoAjax() {      
        $idModelo = $_POST['id_modelo'];
        if($idModelo > 0) {
            $this->load->model('modelos_certificado_model');
            $instrucoes = $this->modelos_certificado_model
                               ->getInstrucaoModelo($idModelo);
            echo $instrucoes;
        }                 
    } 
}
?>
