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
 * View de Notificacoes
 *
 * Utilizada para Entrada de Dados de cursos
 *
 * @author     Pedro Conrad Jr. <pedro.junior@unipampa.edu.br>
 * @author     Sergio Jr <sergiojunior@unipampa.edu.br
 *
 * @copyright Universidade Federal do Pampa - NTIC Campus Alegrete 2010
 *
 */

?>
<script type="text/javascript" src='<?= base_url()?>system/application/views/includes/js/notificacao.js'></script>

<?php echo validation_errors('<div class="error">','</div>'); ?>
<?=form_open_multipart(base_url().'certificados/enviarNotificacao');?>
<div class="botoes_left">
    <button type="submit">
        <img src='<?= base_url()?>system/application/views/includes/images/mail_32.png'
             alt="Enviar"/><br>&nbsp;&nbsp;Enviar&nbsp;&nbsp;
    </button>

    <button onclick="parent.location='<?=base_url()?>sistema/principal'" type="button" id="botao_cancelar">
        <img src='<?= base_url()?>system/application/views/includes/images/cancel_32.png'
             alt="Novo"/><br>Cancelar
    </button>
</div>


<div class="titulo_right"><h1>Notificação de Participantes</h1></div>
<div class="center_table">
    <div class="form_left">
        <p>
            <label for='txtEvento'>Evento*: </label>
            <select name="txtEvento" class="combo"
                    onChange="carregaModelosEvento(this.value,
                                                   'loading_modelos',
                                                   '<?=base_url()?>',
                                                   'txtModelo',
                                                   'instrucoes_importacao');
                                                    limpaCombo('txtDestinatarios');">
                <option value="">Selecione...</option>
                <?php if(@$eventos): ?>
                    <?php foreach($eventos as $evento): ?>
                        <option value="<?=$evento->id_evento;?>" ><?=$evento->nm_evento?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <br /><br />

            <label for='txtModelo'>Modelo de Certificado*: </label>
            <select name="txtModelo" id="txtModelo" class="combo"
                                     onChange="carregaDestinatariosModelo(this.value,
                                               'loading_destinatarios',
                                               '<?=base_url()?>',
                                               'txtDestinatarios');">
                <option value="">Selecione...</option>
            </select>
            <img id="loading_modelos" class="loading"
                 src='<?= base_url()?>system/application/views/includes/images/ajax-loader-preto.gif'/>
            <br /><br />

            <label for='txtDestinatarios'>Destinatários*:
            <img id="loading_destinatarios" class="loading"
                 src='<?= base_url()?>system/application/views/includes/images/ajax-loader-preto.gif'/>
            </label>
            <select name="txtDestinatarios[]" id="txtDestinatarios"
                    class="combo" size="6" multiple="multiple">
                <option value="">Selecione...</option>
            </select>
            <br /><br />

        </p>
        <p class="aviso">* Campos Obrigat&oacute;rios</p>
    </div>
    <div class="clear"></div>    
</div>
<?=form_close()?>