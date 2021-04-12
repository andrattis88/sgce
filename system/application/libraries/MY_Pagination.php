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
 * Extende a classe Pagination do CodeIgniter.
 *
 * Metodos incluidos:
 *  configPagination
 *
 * @package	CodeIgniter
 * @subpackage	Libraries
 * @category	Pagination
 * @author	Marcio Vinissius Fernandes Furtado <marciofurtado@unipampa.edu.br>
  */
class MY_Pagination extends CI_Pagination {
    public function configPagination($url, $total, $itensPorPag) {
        $CI =& get_instance();
       
        $inicio = (!$CI->uri->segment("3")) ? 0 : $CI->uri->segment("3");
        $config['base_url']   = base_url() . $url;
        $config['total_rows'] = $total;
        $config['per_page']   = $itensPorPag;
        $config['first_link'] = 'Primeiro';
        $config['last_link']  = 'Último';
        $config['next_link']  = 'Próximo';
        $config['prev_link']  = 'Anterior';
        $CI->pagination->initialize($config);
        $links = $CI->pagination->create_links();

        //resultado
        $result["links"]  = $links;
        $result["maximo"] = $itensPorPag;
        $result["inicio"] = $inicio;
        return $result;
    }
}
?>
