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
 * Model para a funcao de log
 * @author Pedro Jr     <pedro.junior@unipampa.edu.br>
 * @author Sergio Jr    <sergiojunior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 * @var $table   - variavel que indica a tabela padrao para operacao.
 * @var $schema  - variavel para configuracao do esquema de banco de dados.
 *
 */

class Log_importacao_model extends Model {
    public $table = 'log_importacao';
    
    /**
     * Construtor da Classe
     *
     * Inicializa o Model de banco de dados
     */

    function Log_importacao_model() {
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
        $ins = $this->db->insert($this->table, $dados);
        if($ins) {
            return $this->db->insert_id();
        } else {
            return null;
        }
    }

    /**
     * Insere registro na tabela de detalhes do log
     * 
     * @param Integer $idLog
     * @param Integer $idLinha
     * @param String $descricao
     * @return Integer 
     */
    function insertDetalhe($idLog, $idLinha, $descricao) {
        $dados['id_log']       = $idLog;
        $dados['nr_linha']     = $idLinha;
        $dados['de_descricao'] = $descricao;
        $ins = $this->db->insert('log_importacao_detalhes', $dados);
        if($ins) {
            return $this->db->insert_id();
        } else {
            return null;
        }
    }


    /**
     * Obtem o log de importacao do modelo
     * 
     * @param Integer $idModelo
     * @return Array 
     */
    function listarLogModelo($idModelo) {
        $this->db->where('id_certificado_modelo', $idModelo);
        $this->db->orderby('dt_log');
        $query = $this->db->get($this->table);
        return $query->result(); 
    }


    /**
     * Listagem do detalhe do log de importacao passado
     *
     * @param Integer $idLog
     * @return Array
     */
    function obtemDetalhesLog($idLog) {
        $this->db->where('id_log', $idLog);
        $query = $this->db->get('log_importacao_detalhes');
        return $query->result();         
    }

    /**
     * Faz a busca de um registro no banco de dados e retorna seus dados.
     * @param Integer $id - ID a buscar.
     * @return Array      - Dados recuperados.
     */
    function getById($id) {
        $record = '';
        if (isset($id)) {
            $this->db->where('id_log', $id);
            $record = $this->db->get($this->table);
        }
        $dados = $record->result();

        if ($dados) {
            return @$dados[0];
        } else {
            return null;
        }
    }
 

}
?>
