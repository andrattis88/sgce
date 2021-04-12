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
 * View de Cadastro de certificados
 *
 * Utilizada para Entrada de Dados de certificados
 *
 * @author     Pedro Conrad Jr. <pedro.junior@unipampa.edu.br>
 * @author     Sergio Jr <sergiojunior@unipampa.edu.br
 *
 * @copyright Universidade Federal do Pampa - NTIC Campus Alegrete 2010
 *
 */
?>

<script type="text/javascript"
        src="<?= base_url()?>system/application/views/includes/js/tiny_mce/tiny_mce.js">
</script>

<script type="text/javascript"
        src="<?= base_url()?>system/application/views/includes/js/editor_texto.js">
</script>

<?php echo validation_errors('<div class="error">','</div>'); ?>
<?=form_open_multipart(base_url().'certificados/salvar');?>
<div class="botoes_left">
    <button type="submit" id="botao_salvar" name="botao_salvar">
        <img src='<?= base_url()?>system/application/views/includes/images/salvar_32.png'
             alt="Salvar"/><br>&nbsp;&nbsp;Salvar&nbsp;&nbsp;
    </button>

    <button onclick="parent.location='<?= base_url()?>certificados/cancelar'" type="button" id="botao_cancelar">
        <img src='<?= base_url()?>system/application/views/includes/images/cancel_32.png'
             alt="Novo"/><br>Cancelar
    </button>
</div>


<div class="titulo_right"><h1><?=$titulo_pagina?></h1></div>
<div class="center_table">
    <div class="form_left">
        <p>
            <label for='txtId'>C&oacute;digo:</label>
            <input name="txtId" class="disabled_input" value="<?=@$certificado->id_certificado?>"
                   type="text" id="txtId" size="10" readonly="readonly"  />
            <br /><br />       

            <label for='txtNome'>Evento*: </label>
            <input name="txtNome" type="text" class="disabled_input" value="<?=@$certificado->nm_evento?>" id="txtNome" size="50" />
            <br /><br />

            <label for='txtNome'>Emitido para*: </label>
            <input name="txtNome" type="text" class="disabled_input" value="<?=@$certificado->nm_participante?>" id="txtNome" size="50" />
            <br /><br />

            <label for='txtNome'>Texto*: </label>
            <textarea class="ckeditor" name="txtTextoCertificado" id="txtTextoCertificado" rows="4" cols="40"><?=@$certificado->de_texto_certificado?></textarea>
            <br /><br />
            
            <label for='txtHash'>Cód. Autenticação: </label>
            <input name="txtHash" class="disabled_input" type="text" value="<?=@$certificado->de_hash?>" id="txtHash" size="10" readonly="readonly"/>
            <br /><br />
            
            <label for='txtDtInclusao'>Data de Inclusão: </label>
            <input name="txtDtInclusao" class="disabled_input" type="text" value="<?=dataBR(@$certificado->dt_inclusao)?>" id="txtDtInclusao" size="10" readonly="readonly"/>
            <br /><br />

            <label for='txtDtAlteracao'>Data de Alteração: </label>
            <input name="txtDtAlteracao" class="disabled_input" type="text" value="<?=dataBR(@$certificado->dt_alteracao)?>" id="txtDtAlteracao" size="10" readonly="readonly"/>
            <br /><br />
        </p>          
        <p class="aviso">* Campos Obrigat&oacute;rios</p>
    </div>
    <div class="clear"></div>    
</div>
<?=form_close()?>