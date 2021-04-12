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
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2009, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 * @deprecated  Substituto pelo arquivo de linguagem form_validation_lang.php no
 *              system/application/language/pt-br
 */

// ------------------------------------------------------------------------

/**
 * Validation Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Validation
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/validation.html
 */
class Errors {
    function configureFormErrorMessage($form_validation) {
        $form_validation->set_message('required', 
                'O campo <span class="message_field">%s</span> &eacute; obrigat&oacute;rio.');

        $form_validation->set_message('valid_email',
                'O campo <span class="message_field">%s</span> n&aring;o &eacute; um e-mail v&aacute;lido.');

        $form_validation->set_message('max_length',
                'O campo <span class="message_field">%s</span> apresenta um tamanho m&aacute;ximo de 12 caracteres.');

        $form_validation->set_message('min_length',
                'O campo <span class="message_field">%s</span> apresenta um tamanho m&iacute;nimo de 5 caracteres.');

        $form_validation->set_message('numeric',
                'O campo <span class="message_field">%s</span> deve possuir somente valor num&eacute;rico.');

        $form_validation->set_message('exact_length',
               'O campo <span class="message_field">%s</span> deve possuir 11 d&iacute;gitos.');
               
        $form_validation->set_message('matches',
               'As <span class="message_field">%s</span> devem ser iguais.');
               
        $form_validation->set_message('valid_email',
               'O <span class="message_field">%s</span> informado n&atilde;o &eacute; v&aacute;lido.');

 }
}
?>
