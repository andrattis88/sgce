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
 * Model para a funcao de configuracoes
 *
 * @author Sergio Jr    <sergiojunior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 * @var $table   - variavel que indica a tabela padrao para operacao.
 *
 */

class Configuracoes_model extends Model {
    public $table = 'config_sistema';

    
    /**
     * Construtor da Classe
     *
     * Inicializa o Model de banco de dados
     */
    function Configuracoes_model() {
        parent::Model();
    }

    /**
     * Metodo Update
     *
     * Recebe os dados que serao atualizados no banco de dados e faz a gravacao de
     * registro na tabela apropriada
     *
     * @param array $dados
     */
    function update($data) {
        foreach($data as $key => $value) {
            $this->db->where('nm_parametro', $key);
            $campo['vl_parametro'] = $value;
            $this->db->update($this->table, $campo);
        }
    }


    /**
     * Obtem a listagem de todos os parametros de configuracao em forma de
     * array associativo: nome do campo = indice do array
     *
     * @return Array
     */
    function obtemTodosParametrosArray() {
        $record = $this->db->get($this->table);
        $dados  = $record->result();

        $arr = null;
        foreach ($dados as $dado) {
            $arr[$dado->nm_parametro] = $dado->vl_parametro;
        }

        return $arr;
    }

    /**
     * Restaurar as configuracoes padroes do sistema
     * @return Boolean
     */
    function restaurarPadrao() {
        $sql = "UPDATE config_sistema SET vl_parametro = vl_padrao";
        if($this->db->query($sql))
            return true;
        else
            return false;
    }
}

?>
