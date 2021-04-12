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
 * Model para a funcao de participantes
 * @author Pedro Jr     <pedro.junior@unipampa.edu.br>
 * @author Sergio Jr     <sergiojunior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 * @var $table   - variavel que indica a tabela padrao para operacao.
 * @var $schema  - variavel para configuracao do esquema de banco de dados.
 *
 */

class Participantes_model extends Model {
    public $table = 'participantes';
    
    /**
     * Construtor da Classe
     *
     * Inicializa o Model de banco de dados
     */

    function Participantes_model() {
        parent::Model();        
    }

    /**
     * Metodo Insert
     *
     * Recebe os dados que serao inseridos no banco de dados e faz a gravacao de
     * registro na tabela apropriada
     *
     * @param array $dados
     */
    function insert($dados) {
        $data["nm_participante"]      = $dados['nm_participante'];
        $data["de_email"]             = $dados['de_email'];
        $data["nr_documento"]         = $dados["nr_documento"];
        $data["dt_inclusao"]          = date('Y-m-d');
        $data["dt_alteracao"]         = date('Y-m-d');

        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Metodo Update
     *
     * Recebe os dados que serao atualizados no banco de dados e faz a gravacao de
     * registro na tabela apropriada
     *
     * @param array $dados
     */    
    function update($dados) {
        $id                           = $dados['id_participante'];
        $data["nm_participante"]      = $dados['nm_participante'];
        $data["de_email"]             = $dados['de_email'];
        $data["nr_documento"]         = $dados["nr_documento"];
        $data["dt_inclusao"]          = date('Y-m-d');
        $data["dt_alteracao"]         = date('Y-m-d');

        $this->db->where('id_participante',$id);
        
        if($this->db->update($this->table, $data))
            return true;
        else
            return false;
    }

    /**
     * Remove o registro
     * @param  Integer $idparticipante
     * @return Boolean 
     */
    function delete($idparticipante) {
        $sql  = "SELECT certificados_participante.id_participante,
                        organizadores_evento.id_organizador
                 FROM certificados_participante, organizadores_evento ";
        $sql .= "WHERE certificados_participante.id_participante = $idparticipante OR
                       organizadores_evento.id_organizador = $idparticipante";
        $origem = $this->db->query($sql);
        $dados = $origem->result();

        if(!$dados) {   
            $dados["id_participante"]  = $idparticipante;
            $this->db->where('id_participante', $idparticipante);
            if($this->db->delete($this->table))
                return true;
             else
                return false;
        } else {
            return false;
        }
    }

    /**
     * Faz a busca de um registro no banco de dados e retorna seus dados.
     * @param Integer $id - ID a buscar.
     * @return Array      - Dados recuperados.
     */    
    function getById($id) {
        $record = '';
        if (isset($id)) {
            $this->db->where('id_participante',$id);
            $record = $this->db->get($this->table);
        }        
        $dados = $record->result();
        return $dados[0];   
    }

    /**
     * Recupera um ou mais registros do banco de dados conforme os criterios
     * especificados.
     * @param Array $where  - Especifica os criterios de busca
     * @param String $order - Especifica criterios de ordenacao
     * @param Integer $min  - Especifica quantidade minima de registros.
     * @param Integer $max  - Especifica quantidade maxima de registros.
     * @return Array        - Dados recuperados.
     */
    function search($key, $tipo, $maximo, $inicio=0,  $ordem=null, $tipoOrdem=null) {
        $return = null;        
        $this->db->join('certificados_participante', 'participantes.id_participante = certificados_participante.id_participante');
        $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
        $this->db->join('eventos', 'certificados_modelo.id_evento = eventos.id_evento');
        
        if($key)
            $this->filtrarPesquisa($key, $tipo);

        if (($ordem) && !is_numeric($ordem)) {

            if ($ordem == 'codigo')
                $ordem = 'participantes.id_participante';

            if ($ordem == 'nome')
                $ordem = 'participantes.nm_participante';
            
            if ($ordem == 'evento')
                $ordem = 'eventos.nm_evento';
            
            $this->db->orderby($ordem, $tipoOrdem);
        }
        
        $query = $this->db->get($this->table, $maximo, $inicio);
        return $query->result();

    }


    /**
     * Obtem o total de registros da tabela
     * @param String $key  - valor a ser pesquisado
     * @param String $tipo - tipo do valor
     * @return Integer - total de registros
     */
    function getTotal($key, $tipo) {
        $return = null;
        $this->db->join('certificados_participante', 'participantes.id_participante = certificados_participante.id_participante');
        $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
        $this->db->join('eventos', 'certificados_modelo.id_evento = eventos.id_evento');
        
        if($key)
            $this->filtrarPesquisa($key, $tipo);
        return $this->db->count_all_results($this->table);
    }

    /**
     * Filtra os registros pesquisados
     * @param String $key  - valor a ser pesquisado
     * @param String $tipo - tipo do valor
     */
    function filtrarPesquisa($key, $tipo) {                    
            
        if (($tipo == "C") && (is_numeric($key)))
            $this->db->where('participantes.id_participante',$key);

        if ($tipo == "D")
            $this->db->like('participantes.nm_participante',$key);
        
        if ($tipo =="E") {            
            $this->db->like('eventos.nm_evento', $key);
        }
    }


    /**
     * Busca do participante pelo numero de documento
     *
     * @param  String $nome      -  nome do participante
     * @param  String $email     - e-mail do participante
     * @return Integer           - id do participante
     */
    function buscaParticipante($nome, $email) {
        $existe = null;
        $this->db->where('nm_participante',utf8_encode($nome));
        $this->db->where('de_email',utf8_encode($email));
        $dados = $this->db->get($this->table);
        if ($dados) {
            $existe = $dados->result();            
        }
        return @$existe[0]->id_participante;
    }


    /**
     * Importacao dos participantes.
     * Caso exista o registro do participante ele nao sera importado.
     *
     * @param  Array   $dados         - linhas do arquivo de participantes
     * @param  Array   $colunasModelo - colunas do modelo de certificado (campos)
     * @param  Integer $idModelo      - id do modelo de certificado
     * @param  String  $flDuplicados  - opcao para dizer se importa registros duplicados (S ou N)
     * @return Array   $retornop      - retorna um array com o total de registros
     *                                  importados e o total de certificados gerados.
     */
    function importaParticipantes($dados, $colunasModelo, $idModelo, $idEvento, $flDuplicados) {
        $this->load->helper('email_helper');
  
        if(!$dados)
            return null;

        $cabecOk = $this->validaCabecalhoArquivo($dados[0], $colunasModelo);



        /* Buscando evento para inclusão do nome do evento no array associativo contendo dados a serem importados */
        $evento = $this->eventos_model->getById($idEvento);

        

        if(!$cabecOk) {            
            return null;
        }

        $totImp      = 0;
        $certGerados = 0;
        $i           = 0;
        $linhasNaoImp = array();        
        $logImp = array();
        $totReg      = count($dados) - 1;
        $this->load->helper('progresso_execucao_helper');
        foreach ($dados as $participante) {    
            
            $participante['NOME_EVENTO'] = $evento->nm_evento;
            
            exibeProgresso($i++, $totReg);
            if (valid_email(trim($participante['EMAIL_PARTICIPANTE']))) {
                $existe = @$this->buscaParticipante($participante['NOME_PARTICIPANTE'], $participante['EMAIL_PARTICIPANTE']);
                if (!$existe) {
                    $data['nm_participante'] = strip_tags(utf8_encode($participante['NOME_PARTICIPANTE']));
                    $data['de_email']        = strip_tags(trim($participante['EMAIL_PARTICIPANTE']));
                    $data['nr_documento']    = strip_tags($participante['DOCUMENTO_PARTICIPANTE']);
                    $idParticipante          = $this->insert($data);
                    if($idParticipante) {
                        $totImp += 1;
                        $logImp[$i+1] = "Participante cadastrado na linha $i:".$data['nm_participante'];
                    }
                    else {
                        $linhasNaoImp[$i+1] = 'Erro na inclusão do registro de participante no banco de dados.';
                        $logImp[$i+1] = "Participante não cadastrado na linha $i:".$data['nm_participante'];
                    }
                } else {
                    $idParticipante = $existe;
                    $linhasNaoImp[$i+1] = "Participante já existente.";
                    $logImp[$i+1] = "Participante já existe na linha $i:".strip_tags(utf8_encode($participante['NOME_PARTICIPANTE']));
                }

                // mapeamento de colunas do arquivo para certificados....
                $dadosCertificado['id_participante']        = $idParticipante;
                $dadosCertificado['id_modelo_certificado']  = $idModelo;
                $dadosCertificado['baseTexto']              = $participante;
                $dadosCertificado['baseTextoVerso']         = $participante;
                $dadosCertificado['fl_ativo']               = 'P';  // colocado como 'PENDENTE' ou 'PROVA'              
                if($this->montaCertificado($dadosCertificado, $colunasModelo, $flDuplicados)) {
                    $certGerados += 1;
                    $logImp[$i+1] = "Certificado de participante gerado na linha $i:".strip_tags(utf8_encode($participante['NOME_PARTICIPANTE']));
                }
            } else {
                $linhasNaoImp[$i+1] = 'E-mail inválido ' . $participante['EMAIL_PARTICIPANTE'];
                $logImp[$i+1] = 'E-mail inválido ' . $participante['EMAIL_PARTICIPANTE'];
            }
        }

        if($certGerados > 0)
            $retorno['certGerados'] = $certGerados;

        if($totImp > 0 )
            $retorno['totImp']      = $totImp;

        if(count($linhasNaoImp) > 0)
            $retorno['notImp']      = $linhasNaoImp;
        
        $retorno['logImp'] = $logImp;

        if($certGerados > 0 || $totImp > 0)
            return $retorno;
        else 
            return null;        
    }

    /**
     * Valida se o arquivo anexado contem as colunas exigidas pelo modelo de
     * certificado
     *
     * @param  Array   $dados         - colunas do arquivo anexado
     * @param  Array   $colunasModelo - colunas do modelo de certificado
     * @return Boolean                - true em caso de sucesso, false caso contrario.
     */
    function validaCabecalhoArquivo($dados, $colunasModelo) {

        $colunasArq = array_keys($dados);

        // adicionando a coluna NOME_EVENTO ao cabecalho do arquivo importado
        $colunasArq[]= "NOME_EVENTO";

        //verifica se a coluna do modelo está no arquivo importado
        foreach($colunasModelo as $colunaModelo) {
             if(!in_array($colunaModelo, $colunasArq)) {
                return false;
            }
        }
        return true;
    }



    /**
     * Funcao usada para retornar o certificado montado e enviar ao banco de dados
     *
     * @param Array $dadosCertificado
     * @param Array $colunasModelo
     * @return Boolean
     */
    function montaCertificado($dadosCertificado, $colunasModelo, $flDuplicados) {
        // Recupera dados do modelo do certificado
        $this->db->where('id_certificado_modelo', $dadosCertificado['id_modelo_certificado']);
        $this->db->join('eventos', 'certificados_modelo.id_evento = eventos.id_evento');
        $record = $this->db->get('certificados_modelo');
        $dados = $record->result();

        // Efetua substituicao de texto no campo de texto
        $textoCertificado = $dados[0]->de_texto;
        $textoVerso       = $dados[0]->de_texto_verso;
        foreach ($colunasModelo as $colunaModelo) {
            $textoCertificado = str_replace($colunaModelo,
                                            utf8_encode($dadosCertificado['baseTexto'][$colunaModelo]),
                                            $textoCertificado);

            $textoVerso       = str_replace($colunaModelo,
                                            utf8_encode($dadosCertificado['baseTextoVerso'][$colunaModelo]),
                                            $textoVerso);
            
            $dadosOrigemFrente[$colunaModelo] = utf8_encode($dadosCertificado['baseTexto'][$colunaModelo]);
            $dadosOrigemVerso[$colunaModelo]  = utf8_encode($dadosCertificado['baseTextoVerso'][$colunaModelo]);

        }

        $dadosCertificado['de_texto']             = $textoCertificado;
        $dadosCertificado['de_complementar']      = $textoVerso;
        

        // Monta matriz de gravacao de  certificado no banco de dados

        $dataCert['id_modelo_certificado'] = $dadosCertificado['id_modelo_certificado'];
        $dataCert['fl_ativo']              = $dadosCertificado['fl_ativo'];
        $dataCert['id_participante']       = $dadosCertificado['id_participante'];
        $dataCert['de_texto_certificado']  = trim($dadosCertificado['de_texto']);
        $dataCert['de_complementar']       = trim($dadosCertificado['de_complementar']);
        $dataCert['de_campos_frente']      = serialize($dadosOrigemFrente); 
        $dataCert['de_campos_verso']       = serialize($dadosOrigemVerso); 

        if ($flDuplicados =='N') {
           $this->db->where('certificados_participante.de_texto_certificado', $dataCert['de_texto_certificado']);           
           $this->db->where('certificados_participante.id_participante', $dataCert['id_participante']);
           $this->db->where('certificados_participante.id_modelo_certificado', $dataCert['id_modelo_certificado']);

           if ($this->db->count_all_results('certificados_participante')>0) {
               return false;
           }           
        }

        // Insere, recupera o id, gera o hash e grava o hash no banco.
        $this->db->insert('certificados_participante', $dataCert);
        $idCertificado = $this->db->insert_id();
        $data['de_hash'] = $this->geraHash($idCertificado);

        $this->db->where('id_certificado', $idCertificado);

        if($this->db->update('certificados_participante', $data))
            return true;
        else
            return false;
    }


    /**
     * Geracao de Hash de certificados por HMAC.
     * @param  integer $idCertificado
     * @return string
     */
    function geraHash($idCertificado=null) {
        $retorno = null;
        if ($idCertificado) {
            // Chave calculada por HMAC, com autenticacao de chave no final,
            // usando a chave de encriptacao do config.
            $retorno = strtoupper(hash_hmac('crc32', $idCertificado,
                                 $this->config->item('encryption_key')));
        }       
       return strtoupper($retorno);
    }
    
    /**
     * Faz a busca de um registro no banco de dados e retorna seus dados.
     * @param String $email - Email a buscar.
     * @return Array      - Dados recuperados.
     */    
    function getByEmail($email) {
        $record = '';
        if (isset($email)) {
            $this->db->where('de_email',trim($email));
            $record = $this->db->get($this->table);
            
            $dados = $record->result();
            if ($dados) {
                return $dados[0];   
            }else{
                return null;
            }
        } else {
            return null;
        }        
        
    }

}
?>
