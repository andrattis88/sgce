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
 * View de Entrada de dados de validacao
 * Utilizado como tela para validacao e emissao de certificados
 *
 * @author     Pedro Conrad Jr. <pedro.junior@unipampa.edu.br> 
 *
 * @copyright Universidade Federal do Pampa - NTIC Campus Alegrete 2010
 *
 */
?>

<?php

/**
 * O codigo dessa funcao foi retirado de:
 * http://www.php.net/manual/en/function.get-browser.php
 * @param <type> $agent
 * @return <type>
 */
function browser_info($agent=null) {
    // Declare known browsers to look for
    $known = array('msie', 'firefox', 'safari', 'webkit', 'opera', 'netscape',
            'konqueror', 'gecko');

    // Clean up agent and build regex that matches phrases for known browsers
    // (e.g. "Firefox/2.0" or "MSIE 6.0" (This only matches the major and minor
    // version numbers.  E.g. "2.0.0.6" is parsed as simply "2.0"
    $agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);
    $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';

    // Find all phrases (or return empty array if none found)
    if (!preg_match_all($pattern, $agent, $matches)) return array();

    // Since some UAs have more than one phrase (e.g Firefox has a Gecko phrase,
    // Opera 7,8 have a MSIE phrase), use the last one found (the right-most one
    // in the UA).  That's usually the most correct.
    $i = count($matches['browser'])-1;
    return array($matches['browser'][$i] => $matches['version'][$i]);
}
?>


<br />
<div id="login_place">
    <?php
    $browser = browser_info();
    ?>
    <?php if(isset ($browser['msie']) && $browser['msie']  < 8):?>
    <div class="">
        Esse sistema <b>n&atilde;o suporta</b> o <br />
        <span class="ie6message"> <b>Internet Explorer Vers&atilde;o 7 ou inferior</b> </span>
    </div>
    <h2>Atualize seu navegador</h2>
    <table>
        <tr>
            <td>
                <center>
                    <a href="http://br.mozdev.org/" target="_black">
                        <img src="<?php echo base_url(); ?>system/application/views/includes/images/mozilla-firefox-logo.jpg"
                             alt="FireFox" /><br/>
                        FireFox
                    </a>
                </center>
            </td>
            <td>
                <center>
                    <a href="http://www.google.com/chrome" target="_balck">
                        <img src="<?php echo base_url(); ?>system/application/views/includes/images/google-chrome-logo.gif"
                             alt="Google Chrome" /><br />
                        Google Chrome
                    </a>
                </center>
            </td>
        </tr>
        <tr>
            <td>
                <center>
                    <a href="http://www.apple.com/safari/" target="_black">
                        <img src="<?php echo base_url(); ?>system/application/views/includes/images/Apple_Safari-logo.jpg"
                             alt="Safari" /><br/>
                        Safari
                    </a>
                </center>
            </td>
            <td>
                <center>
                    <a href="http://www.microsoft.com/brasil/windows/internet-explorer/" target="_black">
                        <img src="<?php echo base_url(); ?>system/application/views/includes/images/ie_logo.jpg"
                             alt="Internet Explorer 8 ou superior" /><br/>
                        Internet Explorer 8 <br/>
                        (ou superior)
                    </a>
                </center>

            </td>
        </tr>
    </table>

    <?php else: ?>
        <br />
        <br />
        <?=form_open('certificados/recebeCodigo');?>
        Informe o código de autenticação do certificado no campo abaixo. <br /><br />
            <b>Autenticação:</b>&nbsp;&nbsp;&nbsp;&nbsp;
            <input type='text' name='txtHash' id='txtHash'
                   class="medium_input" value=""/>
            <br />
            <b>Operação:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <select  class="medium_input" name="txtOperacao">
                <option value="emitir">Emitir Certificado</option>
                <option value="validar">Verificar Validade</option>
            </select>
            <div id="message">
                <?php if (@$mensagem):
                          echo @$mensagem;
                      endif;?>
            </div>
            <br />

            <button type="submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <img src='<?= base_url()?>system/application/views/includes/images/ok_32.png'
                     alt="Entrar" height="15" width="15" border="0">&nbsp;Enviar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </button>        
        <?=form_close(); ?>
            <br/>
            <a href="<?=base_url()?>listaPublica">Não recebi um código para autenticar/emitir meu certificado</a>
            <br/>
            <a href="<?=base_url()?>">Clique aqui para voltar à tela de entrada</a>
    <?php endif; ?>
    <?=@$retorno;?>
</div>
<div class="clear"></div>
