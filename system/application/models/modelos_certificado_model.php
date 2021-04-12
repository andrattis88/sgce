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
 * Model para a funcao de modelos_certificado
 * @author Pedro Jr     <pedro.junior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 * @var $table   - variavel que indica a tabela padrao para operacao.
 * @var $schema  - variavel para configuracao do esquema de banco de dados.
 *
 */

class modelos_certificado_model extends Model {
    public $table = 'certificados_modelo';
    
    /**
     * Construtor da Classe
     *
     * Inicializa o Model de banco de dados
     */

    function modelos_certificado_model() {
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
        $data["nm_modelo"]      = $dados["nm_modelo"];
        $data["de_titulo"]      = $dados["de_titulo"];
        $data["id_evento"]      = $dados["id_evento"];
        $data["nm_fonte"]       = $dados["nm_fonte"];
        $data["de_instrucoes"]  = $dados["de_instrucoes"];
        $data["nm_fundo"]       = @$dados["nm_fundo"];
        $data["de_texto"]       = $dados["de_texto"];
        $data["de_carga"]       = $dados["de_carga"];
        $data["dt_inclusao"]    = date('Y-m-d');
        $data["dt_alteracao"]   = date('Y-m-d');

        // Texto Verso
        $data["de_titulo_verso"] = $dados['de_titulo_verso'];
        $data["de_texto_verso"]  = $dados['de_texto_verso'];

        // Layout

        $data["de_posicao_titulo"]     = $dados["de_posicao_titulo"];
        $data["de_posicao_texto"]      = $dados["de_posicao_texto"];
        $data["de_posicao_validacao"]  = $dados["de_posicao_validacao"];

        $data["de_alinhamento_titulo"]    = $dados["de_alinhamento_titulo"];
        $data["de_alinhamento_texto"]     = $dados["de_alinhamento_texto"];
        $data["de_alinhamento_validacao"] = $dados["de_alinhamento_validacao"];

        $data["de_alin_texto_titulo"]       = $dados["de_alin_texto_titulo"];
        $data["de_alin_texto_texto"]        = $dados["de_alin_texto_texto"];
        $data["de_alin_texto_validacao"]    = $dados["de_alin_texto_validacao"];

        $data["de_cor_texto_titulo"]       = $dados["de_cor_texto_titulo"];
        $data["de_cor_texto_texto"]        = $dados["de_cor_texto_texto"];
        $data["de_cor_texto_validacao"]    = $dados["de_cor_texto_validacao"];

        $data["de_tamanho_titulo"]         = $dados["de_tamanho_titulo"];
        $data["de_tamanho_texto"]          = $dados["de_tamanho_texto"];

       if($this->db->insert($this->table, $data))
           return $this->db->insert_id();
       else
           return null;
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
        $id                     = $dados['id_certificado_modelo'];
        $data["nm_modelo"]      = $dados["nm_modelo"];
        $data["de_titulo"]      = $dados["de_titulo"];
        $data["id_evento"]      = $dados["id_evento"];
        $data["nm_fonte"]       = $dados["nm_fonte"];
        $data["de_instrucoes"]  = $dados["de_instrucoes"];
        $data["nm_fundo"]       = @$dados["nm_fundo"];
        $data["de_texto"]       = $dados["de_texto"];
        $data["de_carga"]       = $dados["de_carga"];
        $data["dt_alteracao"]   = date('Y-m-d');

        // Layout

        $data["de_posicao_titulo"]     = $dados["de_posicao_titulo"];
        $data["de_posicao_texto"]      = $dados["de_posicao_texto"];
        $data["de_posicao_validacao"]  = $dados["de_posicao_validacao"];

        // Texto Verso
        $data["de_titulo_verso"] = $dados['de_titulo_verso'];
        $data["de_texto_verso"]  = $dados['de_texto_verso'];

        $data["de_alinhamento_titulo"]    = $dados["de_alinhamento_titulo"];
        $data["de_alinhamento_texto"]     = $dados["de_alinhamento_texto"];
        $data["de_alinhamento_validacao"] = $dados["de_alinhamento_validacao"];

        $data["de_alin_texto_titulo"]       = $dados["de_alin_texto_titulo"];
        $data["de_alin_texto_texto"]        = $dados["de_alin_texto_texto"];
        $data["de_alin_texto_validacao"]    = $dados["de_alin_texto_validacao"];

        $data["de_cor_texto_titulo"]       = $dados["de_cor_texto_titulo"];
        $data["de_cor_texto_texto"]        = $dados["de_cor_texto_texto"];
        $data["de_cor_texto_validacao"]    = $dados["de_cor_texto_validacao"];

        $data["de_tamanho_titulo"]         = $dados["de_tamanho_titulo"];
        $data["de_tamanho_texto"]          = $dados["de_tamanho_texto"]; 

        $this->db->where('id_certificado_modelo',$id);
        
        if($this->db->update($this->table, $data))
            return true;
        else
            return false;
    }

    /**
     * Remove o registro
     * @param  Integer $idEvento
     * @return Boolean 
     */
    function delete($idCertificadoModelo) {
        $sql  = "SELECT id_modelo_certificado
                 FROM certificados_participante ";
        $sql .= "WHERE
                   id_modelo_certificado = $idCertificadoModelo ";
        $origem = $this->db->query($sql);
        $dados = $origem->result();

        if(!$dados) {   
            $dados["id_certificado_modelo"]  = $idCertificadoModelo;
            $this->db->where('id_certificado_modelo', $idCertificadoModelo);
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
            $this->db->where('id_certificado_modelo',$id);
            $record = $this->db->get($this->table);
        }        
        $dados = $record->result();
        if ($dados) {
            return @$dados[0];
        } else{
            return null;
        }
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
        $this->db->join('eventos', 'eventos.id_evento = certificados_modelo.id_evento');
        if($key) {
            $this->filtrarPesquisa($key, $tipo);
        }
        
        if (($ordem) && !is_numeric($ordem)) {

            if ($ordem == 'codigo')
                $ordem = 'id_certificado_modelo';

            if ($ordem == 'nome')
                $ordem = 'nm_modelo';

            if ($ordem == 'evento')
                $ordem = 'nm_evento';
            
            $this->db->orderby($ordem, $tipoOrdem);
        }
        
        $this->filtrarPermissao();
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
        $this->db->join('eventos', 'eventos.id_evento = certificados_modelo.id_evento');
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
            $this->db->where('id_certificado_modelo',$key);

        if ($tipo == "D")
            $this->db->like('nm_modelo',$key);

        if ($tipo == "E")
            $this->db->like('eventos.nm_evento', $key);        
        
        $this->filtrarPermissao();
    }

    /**
     * Funcao usada para filtrar permissao de visualizacao
     * de certificados
     */
    function filtrarPermissao() {
        // Caso nao seja admin, filtra os dados
        // Caso o usuario seja administrador, a filtragem é desprezada

        if (($this->session->userdata('admin')==0) || ($this->session->userdata('admin')==2)) {
            $eventosPermitidos = $this->session->userdata('eventos_permitidos');
            if ($eventosPermitidos) {
                $this->db->where_in('eventos.id_evento', $eventosPermitidos);
            }
        }
    }

    /**
     * Listagem dos modelos_certificado
     * @return Array
     */
    function listarModelosCertificado() {        
        $query = $this->db->get($this->table);
        return $query->result();
    }


    /**
     * Listagem dos modelos de ceritifcado associados ao evento informado.
     * 
     * @param  Integer $idEvento
     * @return Array 
     */
    function listarModelosEvento($idEvento) {
        $this->db->join('eventos', 'eventos.id_evento = certificados_modelo.id_evento');
        $this->db->where('certificados_modelo.id_evento', $idEvento);
        $this->db->orderby('certificados_modelo.nm_modelo');
        $query = $this->db->get($this->table);
        return $query->result();
    }


    /**
     * Obtem a instrucao de importacao do modelo selecionado
     *
     * @param   Integer $idModelo
     * @return  String
     */
    function getInstrucaoModelo($idModelo) {
        $this->db->select('de_instrucoes');
        $this->db->where('id_certificado_modelo', $idModelo);
        $query = $this->db->get($this->table);
        $dados = $query->result();

        if($dados)
            return $dados[0]->de_instrucoes;
        else
            return null; 
    }



    /**
     * Obtem as colunas (campos que serao substituidos por valores)
     * do modelo de certificado informado
     *
     * @param  Integer $idModelo
     * @return Array
     */
    function getColunasModelo($idModelo) {
        $this->db->select('de_instrucoes, de_texto');
        $this->db->where('id_certificado_modelo', $idModelo);
        $query = $this->db->get($this->table);
        $dados = $query->result();

        if(!$dados)
            return null;

        $textoModelo = $dados[0]->de_instrucoes;

        $pattern     = '/\b[A-Z]+_+(_*[A-Z]*)+\b/';

        if(preg_match_all($pattern, $textoModelo, $matches)) {
            
            $matches[0][]= "NOME_EVENTO";

            return $matches[0];

        } else {
            return null;
        }
    }
}
?>
