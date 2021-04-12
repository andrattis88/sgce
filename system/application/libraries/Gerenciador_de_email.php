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

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Gerenciador de email -
 *
 *
 * @author Sergio Junior <sergiojunior@unipampa.edu.br>
 * @uses helpers/template_mail.php
 */

class Gerenciador_de_email extends CI_Email {
    
    public $enviar_email = true;

    /**
     *
     * @param String $destinatarioEmail
     * @param String $assuntoEmail
     * @param String $textoEmail 
     */
    function enviaEmailPessoa($destinatarioEmail, $assuntoEmail, $textoEmail, 
            $nomeRemetente=null, $emailRemetente=null) {        
        
        if (($destinatarioEmail) && ($assuntoEmail) && ($textoEmail)) {
            $CI =& get_instance();            
            $CI->load->library('email');            
            $CI->config->load('email');
            $CI->load->helper('template_mail');            
            
            
            if ($this->enviar_email) {

                // Caso tenha sido passado dado de remetente utiliza...
                if (($nomeRemetente) && ($emailRemetente)) {
                    $CI->email->from($emailRemetente,
                                     $nomeRemetente);
                } else {
                    $CI->email->from($CI->config->item('mail_from_address'),
                                     $CI->config->item('mail_from_name'));
                }
                
                $CI->email->to($destinatarioEmail);                                            
                
                $CI->email->subject($assuntoEmail);
                $msg = geraHtmlMail($textoEmail);
                $CI->email->message($msg);

                if(@$CI->email->send()) {
                    return true;
                }
                else {                
                    return false;
                }                 
            }
        }
            
      
    }
    
    function testarEmail($destinatarioEmail, $emailRemetente) {     
        $CI =& get_instance();
        $CI->load->library('valida_email');        
        
        if ($destinatarioEmail && $emailRemetente)  {
           $resultado = $CI->valida_email->
                        SMTP_validateEmail($destinatarioEmail, $emailRemetente);
           if ($resultado[$destinatarioEmail] == '1') {
               return true;
           } else {
               return false;
           }
        } else {
            return false;
        }
        
    }
}

?>
