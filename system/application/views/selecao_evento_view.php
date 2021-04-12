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
 * View de selecao de evento para a listagem de certificados
 *
 *
 * @author     Sergio Jr <sergiojunior@unipampa.edu.br
 *
 * @copyright Universidade Federal do Pampa - NTIC Campus Alegrete 2010
 *
 */
?>
<br /><br /><br />
<?=form_open(base_url().'certificados/listar');?>
<div class="titulo_right"><h1><?=$titulo_pagina?></h1></div>
<div class="center_table">
    <div class="form_left">
        <p>
            Ao selecionar o evento,
            você será direcionado para uma nova página, com a listagem de certificados
            deste evento.
        </p>
        <p>
            <br />
            <label for='txtEvento'>Evento*: </label>
            <select name="txtEvento" class="combo"
                    onChange="javascript:document.forms[0].submit();">
                <option value="">Selecione...</option>
                <?php if(@$eventos): ?>
                    <?php foreach($eventos as $evento): ?>
                        <option value="<?=$evento->id_evento;?>" title="<?=$evento->nm_evento?>" ><?= character_limiter($evento->nm_evento, 50) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <br /><br />

        </p>
    </div>
    <div class="clear"></div>
</div>
<?=form_close();?>