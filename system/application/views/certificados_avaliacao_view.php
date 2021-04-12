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
 * View de listagem de certificados para revogacao
 *
 * @author     Sergio Jr <sergiojunior@unipampa.edu.br
 *
 * @copyright Universidade Federal do Pampa - NTIC Campus Alegrete 2010
 *
 */
?>

<script type="text/javascript" src='<?= base_url()?>system/application/views/includes/js/certificados.js'></script>

<div id="searchRegister" class="center_table">    
    <div  id="pesq_message" class="form_left">
        <?=form_open('certificados/novaAvaliacao/'.$this->uri->segment(3).'/'.$this->uri->segment(4));?>                
            <p>Pesquisar por:</p>
                <input  type='hidden' name='hdnPesquisa' id='hdnPesquisa' value='pesquisa'/>
                <select name="cmbPesquisa" id="cmbPesquisa">                    
                    <option value="D">Nome do Participante</option>                    
                </select>
                <input type='text' name='txtPesquisa' id='txtPesquisa' size='40' class="big_input"/>
                <button type="submit">
                    <img src='<?= base_url()?>system/application/views/includes/images/search.png'
                     alt="Executar pesquisa" height="15" width="15"/> Pesquisar
                </button>
                <i>(em branco para listar todos)</i>
            <p> <b>Mostrar apenas os certificados com a situação:</b>                    
                <input type="checkbox" name="chkValido"   onclick="javascript:marcaStatus('<?=base_url()?>', '<?=$this->uri->segment(3)?>', '<?=$this->uri->segment(4)?>', this)" <?=$this->session->userdata('chkValido') ? ' checked ' : ''; ?> >Válido</input>
                <input type="checkbox" name="chkRevogado" onclick="javascript:marcaStatus('<?=base_url()?>', '<?=$this->uri->segment(3)?>', '<?=$this->uri->segment(4)?>', this)" <?=$this->session->userdata('chkRevogado') ? ' checked ' : ''; ?> >Revogado</input>
                <input type="checkbox" name="chkPendente" onclick="javascript:marcaStatus('<?=base_url()?>', '<?=$this->uri->segment(3)?>', '<?=$this->uri->segment(4)?>', this)" <?=$this->session->userdata('chkPendente') ? ' checked ' : ''; ?> >Pendente</input>                
            </p>
        <?=form_close()?>
    </div>
    <div class="clear"></div>
</div>

<?php echo validation_errors('<div class="error">','</div>'); ?>
<?php $atributos = array('onSubmit' => 'return validarAvaliacao()')?>
<?=form_open_multipart(base_url().'certificados/salvarAvaliacao', $atributos);?>

<div class="botoes_left">
    <button type="submit" id="botao_salvar" name="botao_salvar">
        <img src='<?= base_url()?>system/application/views/includes/images/salvar_32.png'
             alt="Salvar"/><br>&nbsp;&nbsp;Salvar&nbsp;&nbsp;
    </button>
    
    <button onclick="parent.location='<?= base_url()?>certificados/cancelarAvaliacao'" 
            type="button" id="botao_cancelar">
        <img src='<?= base_url()?>system/application/views/includes/images/seta_voltar_32.png'
             alt="Voltar"/><br>Voltar
    </button>
</div>

<div class="titulo_right"><h1>Avaliação de Certificados</h1></div>
    <div class="center_table">
        <div class="form_left">            
            <p>
                <label for="txtJustificativa">Justificativa:*</label>
                <textarea rows="4" cols="45"
                          name="txtJustificativa"
                          id="txtJustificativa"></textarea>
                <br /><br />
                
                <label for="txtStatus">Novo status: *</label>
                <select id="txtStatus" name="txtStatus">
                    <option value="A">Validado</option>                    
                    <option value="I">Revogado</option>
                    <option value="P">Pendente</option>
                    <option value="D">Apagar (apenas pendentes/de teste)</option>
                </select>                    
                <br /><br />
                
                <label for="txtEnvMail">Notificar por E-mail:</label>
                <select name="txtEnvMail" id="txtEnvMail">
                        <option value="N">Não notificar</option>
                        <option value="T">Proprietário do Certificado e Organizadores do Evento</option>
                        <option value="P">Apenas Proprietário do Certificado</option>
                        <option value="O">Apenas Organizadores do Evento</option>
                </select>
                <br /><br />
                               
                <input type="hidden" name="txtEvento" id="txtEvento" value="<?=$idEvento?>" />
                <input type="hidden" name="txtModelo" id="txtModelo" value="<?=$idModelo?>" />                
            </p>
        </div>
        <div class="clear"></div>      
    </div>

    <br />
    <p>
        <b>Selecione na listagem abaixo os certificados para avaliação</b>
    </p>
    
    <table width="100%" border='0' class="center_table" id="data_table">
        <tr class="linha_par">
            <td width="80px"><b>Código</b></td>
            <td><b>Certificado</b></td>
            <td><b>Participante</b></td>
            <td><b>Cód. Validação</b>
            <td width="50px"><b>Situação</b></td>
            <td width="50px"><b>Visualizar</b></td>
            <td width="50px"><b>Histórico</b></td>
            <td width="50px">
                <center>
                    <input type="checkbox" name="txtCheckAll"
                           id="txtCheckAll" onclick="setAllChecks(this)" />
                </center>
            </td>
        </tr>
        <?php if(@$mensagem): ?>
            <tr class="linha_par">
                <td width="80" colspan="11" align="center"><b><?=$mensagem?></b></td>
            </tr>
        <?php endif;?>

        <? $i = 0;?>
        <?php if (!@$mensagem): ?>
            <?php foreach($certificados as $row):?>
                <?php
                $i++;
                if($i % 2 == 0):
                    $cor = "linha_par";
                else:
                    $cor = "linha_impar";
                endif;
                ?>
            <tr class='<?=$cor?>' id="linha_<?=$i?>" onmouseover="overHighLight('<?=$i?>')"
                onmouseout="outHighLight('#linha_<?=$i?>')">
                <td><?=$row->id_certificado?></td>
                <td><?=$row->nm_modelo?></td>
                <td><?=$row->nm_participante?></td>
                <td><?=$row->de_hash?></td>
                <td>
                    <?php if($row->fl_ativo=='A'): ?>
                        <?="Válido"?>
                    <?php endif; ?>

                    <?php if($row->fl_ativo=='I'): ?>
                        <?="Revogado"?>
                    <?php endif; ?>
                    
                    <?php if($row->fl_ativo=='P'): ?>
                        <?="Pendente"?>
                    <?php endif; ?>
                </td>
                <td width="30">
                    <center>
                        <a href="javascript:abrirPopup('<?= base_url()?>emitir/<?=$row->de_hash?>', 900, 300);"
                           class="edit_command" title="Visualizar certificado finalizado">
                            <img src='<?= base_url()?>system/application/views/includes/images/pdf_16.png'
                                 border="0" alt="Visualizar"
                                 title="Visualizar certificado finalizado" />
                        </a>
                    </center>
                </td>
                <td width="30">
                    <center>
                        <a href="javascript:abrirPopup('<?= base_url()?>certificados/historicoStatus/<?=$row->id_certificado?>', 900, 300);"
                           class="edit_command" title="Visualizar histórico de revogações e validações">
                            <img src='<?= base_url()?>system/application/views/includes/images/historico_16.png'
                                 border="0" alt="Histórico"
                                 title="Visualizar histórico de revogações e validações"" />
                        </a>
                    </center>
                </td>
                <td>
                    <center>
                        <input type="checkbox" name="txtAvaliados[]" 
                               id="txtAvaliados" class="cb" value="<?=$row->id_certificado?>"/>
                    </center>
                </td>
            </tr>
            <?php endforeach;?>
    <?php endif;?>
    </table>
<?=form_close()?>
<div class="paginacao"><?=@$paginacao;?></div>