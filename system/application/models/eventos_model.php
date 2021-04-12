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
 * Model para a funcao de eventos
 * @author Pedro Jr     <pedro.junior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 * @var $table   - variavel que indica a tabela padrao para operacao.
 * @var $schema  - variavel para configuracao do esquema de banco de dados.
 *
 */

class Eventos_model extends Model {
    public $table = 'eventos';
    
    /**
     * Construtor da Classe
     *
     * Inicializa o Model de banco de dados
     */

    function eventos_model() {
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
        $data["nm_evento"]      = $dados['nm_evento'];
        $data["sg_evento"]      = $dados['sg_evento'];
        $data["de_carga"]       = $dados["de_carga"];
        $data["de_local"]       = $dados['de_local'];
        $data["de_periodo"]     = $dados['de_periodo'];
        $data["de_url"]         = $dados['de_url'];
        $data["de_email"]       = $dados['de_email'];
        $data["dt_inclusao"]    = date('Y-m-d');
        $data["dt_alteracao"]   = date('Y-m-d');

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
        $id                     = $dados['id_evento'];
        $data["nm_evento"]      = $dados['nm_evento'];
        $data["sg_evento"]      = $dados['sg_evento'];
        $data["de_carga"]       = $dados["de_carga"];
        $data["de_local"]       = $dados['de_local'];
        $data["de_periodo"]     = $dados['de_periodo'];
        $data["de_url"]         = $dados['de_url'];
        $data["de_email"]       = $dados['de_email'];
        $data["dt_alteracao"]   = date('Y-m-d');

        $this->db->where('id_evento',$id);
        $update = $this->db->update($this->table, $data);

        if($update)
            $this->insereOrganizadores($id, $dados["idsOrganizadores"], $dados["idsControladores"]);
        
        if($update)
            return true;
        else
            return false;
    }

    /**
     * Remove o registro
     * @param  Integer $idEvento
     * @return Boolean 
     */
    function delete($idEvento) {
        $sql  = "SELECT id_evento
                 FROM   certificados_modelo
                 WHERE  id_evento = $idEvento ";

        $origem = $this->db->query($sql);
        $dados  = $origem->result();

        if(!$dados) {   
            $dados["id_evento"]  = $idEvento;
            
            // Primeiro apaga os organizadores do evento
            $this->db->where('id_evento', $idEvento);
            $this->db->delete('organizadores_evento');
            
            // Depois apaga o registro do evento
            $this->db->where('id_evento', $idEvento);
            if ($this->db->delete('eventos'))
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
            $this->db->where('id_evento',$id);
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
        if($key)
            $this->filtrarPesquisa($key, $tipo);

        if (($ordem) && !is_numeric($ordem)) {

            if ($ordem == 'codigo')
                $ordem = 'id_evento';

            if ($ordem == 'nome')
                $ordem = 'nm_evento';
            
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
            $this->db->where('id_evento',$key);

        if ($tipo == "D")
            $this->db->like('nm_evento',$key);
    }

    /**
     * Listagem dos eventos
     * @return Array
     */
    function listarEventos() {
        if ($this->filtrarPermissao()) {
            $this->db->orderby('nm_evento');        
            $query = $this->db->get($this->table);        
            return $query->result();
        } else {
            return null;
        }
    }
    
    function listarEventosPublicos() {
        $this->db->orderby('nm_evento');        
        $query = $this->db->get($this->table);        
        return $query->result();
    }
    
    /**
     * Listagem dos eventos do controlador
     * @return Array
     */
    function listarEventosControlador($usuario) {
        $permite=null;
        if ($this->session->userdata('admin')=='1') {
            $permite=true;
        }
        if ($this->session->userdata('admin')=='2') {
            $permite=false;
        }
        if ($this->session->userdata('controlador')=='1') {
            $permite=true;
        }
        
        if ($permite!=true)
            $permite = $this->filtrarPermissaoControlador($usuario);                
        
        if ($permite) {
            $this->db->orderby('nm_evento');        
            $query = $this->db->get($this->table);        
            return $query->result();
        } else {
            return false;
        }
    }   
    

    /**
     * Lista as linhas de fornecimento associadas ao fornecedor
     * @param integer $idFornecedor - ID do Fornecedor
     * @return Array
     */
    function listarOrganizadoresEvento($idEvento) {
        if ($idEvento) {
            $this->db->select('organizadores.id_organizador');
            $this->db->select('organizadores.nm_organizador');
            $this->db->select('organizadores_evento.fl_controlador');
            
            $this->db->join('organizadores', 'organizadores.id_organizador = organizadores_evento.id_organizador');
            $this->db->join('eventos', 'eventos.id_evento = organizadores_evento.id_evento');

            $this->db->where('eventos.id_evento', $idEvento);
            $query = $this->db->get('organizadores_evento');
            return $query->result();
        }
    }

    /**
     * Insere os organizadores do evento
     *
     * @param Integer $idEvento
     * @param Array   $organizadores
     */
    function insereOrganizadores($idEvento, $organizadores, $controladores) {
        $this->removeOrganizadores($idEvento);
        foreach ($organizadores as $organizador) {
            if($organizador > 0) {
                $data["id_evento"]      = $idEvento;
                $data["id_organizador"] = $organizador;                
                $data["fl_controlador"] = $controladores[$organizador];
                $this->db->insert('organizadores_evento', $data);
            }
        }
    }

    /**
     * Remove os organizadores do evento
     *
     * @param Integer $idEvento
     */
    function removeOrganizadores($idEvento) {
        $this->db->where('id_evento', $idEvento);
        $this->db->delete('organizadores_evento');
    }

    /**
     * Funcao usada para filtrar permissao de visualizacao
     * de certificados
     */
    function filtrarPermissao() {
        // Caso nao seja admin, filtra os dados
        // Caso o usuario seja administrador, a filtragem é desprezada
        // Caso seja administrador limitado, eh dada a mesma regra de listagem de usuario comum

        if ($this->session->userdata('admin')!='1') {
            $eventosPermitidos = $this->session->userdata('eventos_permitidos');            
            if ($eventosPermitidos) {
                $this->db->where_in('id_evento', $eventosPermitidos);
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
    
    /**
     * Funcao usada para filtrar permissao de visualizacao
     * de certificados pelo controlador
     */
    function filtrarPermissaoControlador($usuario) {
        // Caso nao seja admin, filtra os dados
        // Caso o usuario seja administrador, a filtragem é desprezada

        if (($this->session->userdata('admin')!=1) 
         && ($this->session->userdata('controlador')==0)) {
            $eventosControlador = $this->session->userdata('eventos_controlador');            
            
            if ($eventosControlador!=null) {
                $this->db->where_in('id_evento', $eventosControlador);                
                return true;
            }
            else {
                return false;
            }
        } else {
            return true;
        }
    }


}
?>
