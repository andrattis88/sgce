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
 * View de Cadastro de eventos
 *
 * Utilizada para Entrada de Dados de eventos
 *
 * @author     Pedro Conrad Jr. <pedro.junior@unipampa.edu.br>
 * @author     Sergio Jr <sergiojunior@unipampa.edu.br
 *
 * @copyright Universidade Federal do Pampa - NTIC Campus Alegrete 2010
 *
 */
?>
<script type="text/javascript" src='<?= base_url()?>system/application/views/includes/js/eventos.js'></script>

<?php echo validation_errors('<div class="error">','</div>'); ?>
<?=form_open_multipart(base_url().'eventos/salvar');?>
<div class="botoes_left">
    <button type="submit" id="botao_salvar" name="botao_salvar">
        <img src='<?= base_url()?>system/application/views/includes/images/salvar_32.png'
             alt="Salvar"/><br>&nbsp;&nbsp;Salvar&nbsp;&nbsp;
    </button>

    <button onclick="parent.location='<?= base_url()?>eventos/cancelar'" type="button" id="botao_cancelar">
        <img src='<?= base_url()?>system/application/views/includes/images/cancel_32.png'
             alt="Novo"/><br>Cancelar
    </button>
    
</div>


<div class="titulo_right"><h1><?=$titulo_pagina?></h1></div>
<div class="center_table">
    <div class="form_left">
        <p align="left">
            <label for='txtId'>C&oacute;digo:</label>
            <input name="txtId" class="disabled_input" value="<?=@$evento->id_evento?>"
                   type="text" id="txtId" size="10" readonly="readonly"  />
            <br /><br />       

            <label for='txtNome'>Nome*: </label>
            <input name="txtNome" type="text" value="<?=@$evento->nm_evento?>" id="txtNome" size="50" />
            <br /><br />

            <label for='txtSigla'>Sigla*: </label>
            <input name="txtSigla" type="text" value="<?=@$evento->sg_evento?>" id="txtSigla" size="50" />
            <br /><br />

            <label for='txtPeriodo'>Período de Realização*: </label>
            <input name="txtPeriodo" type="text" value="<?=@$evento->de_periodo?>" id="txtPeriodo" size="50" />
            <br /><br />

            <label for='txtCarga'>Carga Horária*: </label>
            <input name="txtCarga" type="text" value="<?=@$evento->de_carga?>" id="txtCarga" size="50" />
            <br /><br />

            <label for='txtLocal'>Local de Realização*: </label>
            <input name="txtLocal" type="text" value="<?=@$evento->de_local?>" id="txtLocal" size="50" />
            <br /><br />

            <label for='txtURL'>Site do Evento: </label>
            <input name="txtURL" type="text" value="<?=@$evento->de_url?>" id="txtURL" size="50" />
            <br /><br />

            <label for='txtEmail'>E-mail do Evento*: </label>
            <input name="txtEmail" type="text" value="<?=@$evento->de_email?>" id="txtEmail" size="50" />
            <br /><br />

            <label for='txtDtInclusao'>Data de Inclusão: </label>
            <input name="txtDtInclusao" class="disabled_input" type="text" value="<?=dataBR(@$evento->dt_inclusao)?>" id="txtDtInclusao" size="10" readonly="readonly"/>
            <br /><br />

            <label for='txtDtAlteracao'>Data de Alteração: </label>
            <input name="txtDtAlteracao" class="disabled_input" type="text" value="<?=dataBR(@$evento->dt_alteracao)?>" id="txtDtAlteracao" size="10" readonly="readonly"/>
            <br /><br />

             <?php if(@$evento->id_evento > 0) : ?>
                    <a href="javascript:exibeFormOrganizadores('<?= base_url()?>');"
                       id="link_add_Organizadores">
                    <img src="<?= base_url()?>system/application/views/includes/images/more_16.png"
                         alt="Adicionar Organizadores ao Evento" height="15" width="15" /><b>Adicionar Organizadores</b>
                    </a><br />

                    <div id="newRegister">
                        <p>
                            <label for='txtOrganizadores'>Organizadores*:
                            <img id="loading_organizadores"
                                 src='<?= base_url()?>system/application/views/includes/images/ajax-loader-branco.gif'/>
                            </label>
                            <br />
                            <select name='txtOrganizadores[]' id='txtOrganizadores'
                                    multiple='multiple' size="6" class="combo">
                                    <option value="">Selecione os organizadores...</option>
                            </select>
                            <br /><br />

                            <button type="button" onClick="javascript:addOrganizadoresTable(document.getElementById('txtId').value,
                                                                                            document.getElementById('txtOrganizadores'),
                                                                                            '<?=base_url()?>');">
                                <img src='<?= base_url()?>system/application/views/includes/images/disponivel_16.png'
                                 alt="Adicionar" height="15" width="15" />Adicionar
                            </button>

                            <button type="button" onClick="javascript:cancelFormOrganizadores();">
                                <img src='<?= base_url()?>system/application/views/includes/images/cancel_16.png'
                                     alt="Cancelar" height="15" width="15" />Cancelar
                            </button>
                        </p>
                    </div>

                    <!-- Organizadores - Tabela -->
                    <table border='0' class="center_table_interna" id="data_table_interna"
                           name="data_table_interna">
                    <tr class="linha_par">
                        <td width="50px"><b>C&oacute;digo</b></td>
                        <td><b>Organizador</b></td>
                        <td width="20px"><center><b>Controlador</b></center></td>
                        <td width="20px"><center><b>Excluir</b></center></td>
                    </tr>
                    <?php if(@$organizadores): ?>
                        <?php echo geraTabelaOrganizadores($organizadores, @$evento->id_evento); ?>
                    <?php endif; ?>
                    </table>
                <br />
            <?php endif;?>
    
        <p class="aviso">* Campos Obrigat&oacute;rios</p>
    </div>
    <div class="clear"></div>    
</div>
<?=form_close();?>
