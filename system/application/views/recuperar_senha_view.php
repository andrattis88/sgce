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
 * Login do candidato
 *
 * @author     Sergio Jr <sergiojunior@unipampa.edu.br
 *
 * @copyright Universidade Federal do Pampa - NTIC Campus Alegrete 2010
 *
 */
?>

<br />
<div class="center_table">
    <h3>Recuperar Senha</h3>
       Digite seu documento no campo abaixo (documento utilizado no momento de seu cadastro no sistema).<br/>
       Uma senha aleatória será enviada para o seu e-mail com um link para efetivá-la.<br />

        <div id="login_place">
            <br /><br />

            <?=form_open('sistema/gerarSenha');?>
                <b>Documento:</b>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type='text' name='txtDocumento' id='txtDocumento'
                       class="medium_input" value="" />
                <br /><br />

                <button type="submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src='<?= base_url()?>system/application/views/includes/images/ok_32.png'
                         alt="Enviar" height="15" width="15" border="0">&nbsp;Enviar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </button>
            <?=form_close(); ?>
            <br/>
            <a href="<?=base_url()?>">Clique aqui para voltar à tela de entrada</a>
        </div>
        <div class="clear"></div>
<div class="clear"></div>
</div>
