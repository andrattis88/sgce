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

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* CodeIgniter Config Extended Library
*
* This class extends the config to a database. Based on class written by Tim Wood (aka codearachnid).
*
* @package       CodeIgniter
* @subpackage    Extended Libraries
* @author        Arnas Lukosevicius (aka steelaz)
* @link          http://www.arnas.net/blog/
*
*/

class MY_Config extends CI_Config
{
    /**
     * CodeIgniter instance
     *
     * @var object
     */
    private $CI = NULL;

    /**
     * Database table name
     *
     * @var string
     */
    private $table = 'config_sistema';


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Load config items from database
     *
     * @return void
     */
    public function load_db_items()
    {
        if (is_null($this->CI)) $this->CI = get_instance();

        $query = $this->CI->db->get($this->table);

        foreach ($query->result() as $row)
        {
            $this->set_item($row->nm_parametro, $row->vl_parametro);
        }

    }

    /**
     * Save config item to database
     *
     * @return bool
     * @param string $key
     * @param string $value
     */
    public function save_db_item($key, $value)
    {
        if (is_null($this->CI)) $this->CI = get_instance();

        $where = array('nm_parametro' => $key);
        $found = $this->CI->db->get_where($this->table, $where, 1);

        if ($found->num_rows > 0)
        {
            return $this->CI->db->update($this->table, array('vl_parametro' => $value), $where);
        }
        else
        {
            return $this->CI->db->insert($this->table, array('nm_parametro' => $key, 'vl_parametro' => $value, 'vl_padrao' => $value));
        }
    }

    /**
     * Remove config item from database
     *
     * @return bool
     * @param string $key
     */
    public function remove_db_item($key)
    {
        if (is_null($this->CI)) $this->CI = get_instance();

        return $this->CI->db->delete($this->table, array('key' => $key));
    }

}

/* End of file MY_Config.php */
/* Location: ./application/libraries/MY_Config.php */