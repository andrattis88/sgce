<?php
/**
 * View de Lista de certificados
 *
 * Utilizada para Listar certificados cadastrados
 *
 * @author     Pedro Conrad Jr. <pedro.junior@unipampa.edu.br>
 * @author     Sergio Jr <sergiojunior@unipampa.edu.br
 *
 * @copyright Universidade Federal do Pampa - NTIC Campus Alegrete 2010
 *
 */
?>
<script type="text/javascript" src='<?= base_url() ?>application/views/includes/js/certificados.js'></script>

<div class="titulo_right">
    <h1>Certificados de Participante <?=(!empty($nome_pessoa)) ? " - $nome_pessoa" : "" ?></h1>
</div>
<div class="clear"></div>


<div class="botoes_left">
    <button onclick="parent.location='<?= base_url()?>certificados/listaPublica'" 
            type="button" id="botao_cancelar">
        <img src='<?= base_url()?>system/application/views/includes/images/seta_voltar_32.png'
             alt="Voltar"/><br>Voltar
    </button>
</div>

<table width="100%" border='0' class="center_table" id="data_table">
    <tr class="linha_par">        
        <td><b>Evento</b></td>
        <td><b>Tipo de Certificado</b></td>
        <td width="50px"><b>Emitir</b></td>
    </tr>
    <?php if(@$mensagem): ?>
    <tr class="linha_par">
        <td width="80" colspan="11" align="center"><b><?= $mensagem ?></b></td>
    </tr>
    <?php endif;?>

    <?php $i = 0; ?>
    <?php if (!@$mensagem): ?>
        <?php foreach ($certificados as $row): ?>
            <?php
            $i++;
            if ($i % 2 == 0)
                $cor = "linha_par";
            else
                $cor = "linha_impar";
            ?>
            <tr class='<?= $cor ?>' id="linha_<?= $i ?>" onmouseover="overHighLight('<?= $i ?>')"
                onmouseout="outHighLight('#linha_<?= $i ?>')">
                <td><?= $row->nm_evento?></td>   
                <td><?= $row->nm_modelo ?></td>                     
                <td><center>
                <a href="<?= base_url() ?>emitir/<?= $row->de_hash ?>">
                    <img src='<?= base_url() ?>system/application/views/includes/images/comprovante_16.png'
                         border="0" alt="Emitir certificado"
                         title="Emitir certificado">
                </a>
            </center>
        </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
</table>
<div class="paginacao"><?= @$paginacao; ?></div>