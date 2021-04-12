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
 * Model para a funcao de vinculacao de Organizadores e Eventos
 * @author Pedro Jr     <pedro.junior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 * @var $table   - variavel que indica a tabela padrao para operacao.
 * @var $schema  - variavel para configuracao do esquema de banco de dados.
 *
 */

class Organizadores_evento_model extends Model {
    public $table = 'organizadores_evento';
    
    /**
     * Construtor da Classe
     *
     * Inicializa o Model de banco de dados
     */

    function Organizadores_evento_model() {
        parent::Model();        
    }
    

    /**
     * Lista as linhas de fornecimento associadas ao fornecedor
     * @param integer $idFornecedor - ID do Fornecedor
     * @return Array
     */
    function listarEventosOrganizador($usuario) {
        if ($usuario) {
            $this->db->select('organizadores_evento.id_evento');
            $this->db->join('organizadores', 'organizadores.id_organizador = organizadores_evento.id_organizador');            
            $this->db->where('organizadores.id_organizador', $usuario);
            $query = $this->db->get('organizadores_evento');
            $dados = $query->result();
            $retorno = array();
            $i=0;
            foreach ($dados as $evento) {
                $retorno[$i] = $evento->id_evento;
                $i++;
            }
            return $retorno;
        }
    }
    
    function listarEventosControlador($usuario){
        if ($usuario) {
            $this->db->select('organizadores_evento.id_evento');
            $this->db->join('organizadores', 'organizadores.id_organizador = organizadores_evento.id_organizador');            
            $this->db->where('organizadores_evento.fl_controlador','S');
            $this->db->where('organizadores.id_organizador', $usuario);
            
            $query = $this->db->get('organizadores_evento');
            $dados = $query->result();
            $retorno = array();
            $i=0;
            foreach ($dados as $evento) {
                $retorno[$i] = $evento->id_evento;
                $i++;
            }
            return $retorno;
        }
    }

    /**
     * Insere os organizadores do evento
     *
     * @param Integer $idEvento
     * @param Array   $organizadores
     */
    function insereOrganizadores($idEvento, $organizadores) {
        $this->removeOrganizadores($idEvento);
        foreach ($organizadores as $organizador) {
            if($organizador > 0) {
                $data["id_evento"]      = $idEvento;
                $data["id_organizador"] = $organizador;
                $data["fl_controlador"] = "N";
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
    
    function removeOrganizador($idEvento, $idOrganizador) {
        $this->db->where('id_evento', $idEvento);
        $this->db->where('id_organizador', $idOrganizador);
        $this->db->delete('organizadores_evento');
    }
    
    function atribuiControlador($idEvento, $idOrganizador) {
        // Consulta primeiro
        $this->db->where('id_evento', $idEvento);
        $this->db->where('id_organizador', $idOrganizador);
        
        $query = $this->db->get($this->table);
        $resultado = $query->result();
        if ($resultado[0]->fl_controlador=='N') {
            $data['fl_controlador']='S';
        } else {
            $data['fl_controlador']='N';
        }
        
        //Agora consulta novamente para atualizar        
        $this->db->where('id_evento', $idEvento);
        $this->db->where('id_organizador', $idOrganizador);
        
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
        
    }
    
    function gravaOrganizadorEvento($idEvento, $idOrganizador) {
        // Consulta primeiro
        $this->db->where('id_evento', $idEvento);
        $this->db->where('id_organizador', $idOrganizador);
        
        $query = $this->db->get($this->table);
        $resultado = $query->result();
        if ((@$resultado[0]->id_organizador==$idOrganizador)
             &&(@$resultado[0]->id_evento==$idEvento) ) {
            return true;
        } else {
            $data['id_evento']      = $idEvento;
            $data['id_organizador'] = $idOrganizador;            
            $data['fl_controlador'] = 'N';
            
            if ($this->db->insert($this->table, $data)) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    function buscaControladorEspecifico($idEvento) {
        $retorno = null;
        if ($idEvento) {
            
            $this->db->select('organizadores.id_organizador, organizadores.nm_organizador, organizadores.de_email');            
            $this->db->from('organizadores');
            $this->db->join('organizadores_evento', 'organizadores.id_organizador = organizadores_evento.id_organizador');
            $this->db->where('organizadores_evento.id_evento', $idEvento);
            $this->db->where('organizadores_evento.fl_controlador', 'S');
            $dados = $this->db->get();
            $retorno = $dados->result();            
        }
        return $retorno;
    }
    
    
}
?>
