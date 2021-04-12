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
 * View de Cadastro de participantes
 *
 * Utilizada para Entrada de Dados de participantes
 *
 * @author     Pedro Conrad Jr. <pedro.junior@unipampa.edu.br>
 * @author     Sergio Jr <sergiojunior@unipampa.edu.br
 *
 * @copyright Universidade Federal do Pampa - NTIC Campus Alegrete 2010
 *
 */
?>

<?php echo validation_errors('<div class="error">','</div>'); ?>
<?=form_open_multipart(base_url().'participantes/salvar');?>
<div class="botoes_left">
    <button type="submit" id="botao_salvar" name="botao_salvar">
        <img src='<?= base_url()?>system/application/views/includes/images/salvar_32.png'
             alt="Salvar"/><br>&nbsp;&nbsp;Salvar&nbsp;&nbsp;
    </button>

    <button onclick="parent.location='<?= base_url()?>participantes/cancelar'" type="button" id="botao_cancelar">
        <img src='<?= base_url()?>system/application/views/includes/images/cancel_32.png'
             alt="Novo"/><br>Cancelar
    </button>
</div>


<div class="titulo_right"><h1><?=$titulo_pagina?></h1></div>
<div class="center_table">
    <div class="form_left">
        <p>
            <label for='txtId'>C&oacute;digo:</label>
            <input name="txtId" class="disabled_input" value="<?=@$participante->id_participante?>"
                   type="text" id="txtId" size="10" readonly="readonly"  />
            <br /><br />       

            <label for='txtNome'>Nome*: </label>
            <input name="txtNome" type="text" value="<?=@$participante->nm_participante?>" id="txtNome" size="50" />
            <br /><br />

            <label for='txtNrDocumento'>Documento: </label>
            <input name="txtNrDocumento" type="text" value="<?=@$participante->nr_documento?>" id="txtNrDocumento" size="50" maxlength="20"/>
            <br /><br />

            <label for='txtDeEmail'>E-mail*: </label>
            <input name="txtDeEmail" type="text" value="<?=@$participante->de_email?>" id="txtDeEmail" size="50" />
            <br /><br />
            
            <label for='txtDtInclusao'>Data de Inclusão: </label>
            <input name="txtDtInclusao" class="disabled_input" type="text" value="<?=dataBR(@$participante->dt_inclusao)?>" id="txtDtInclusao" size="10" readonly="readonly"/>
            <br /><br />

            <label for='txtDtAlteracao'>Data de Alteração: </label>
            <input name="txtDtAlteracao" class="disabled_input" type="text" value="<?=dataBR(@$participante->dt_alteracao)?>" id="txtDtAlteracao" size="10" readonly="readonly"/>
            <br /><br />
        </p>          
        <p class="aviso">* Campos Obrigat&oacute;rios</p>
    </div>
    <div class="clear"></div>    
</div>
<?=form_close()?>