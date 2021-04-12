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
 * View de historico de alteracoes no status do certificado - popup
 */
?>
<html xml:lang="pt-br" lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href='<?= base_url()?>system/application/views/includes/css/estilo_admin.css' type="text/css" />
        <script type="text/javascript" src='<?= base_url()?>system/application/views/includes/js/jquery.js'></script>
        <script type="text/javascript" src='<?= base_url()?>system/application/views/includes/js/admin.js'></script>
    </head>
    <body>

    <div class="titulo_popup">
        <h3><?=$titulo_pagina?></h3>
    </div>

    <table width="100%" border='0' class="center_table" id="data_table">
        <tr class="linha_par">
            <td><b>Código</b></td>
            <td><b>Data</b></td>
            <td><b>Hora</b></td>
            <td><b>Usuário</b></td>
            <td><b>IP Usuário</b></td>
            <td><b>Status</b></td>
            <td><b>Justificativa</b></td>
        </tr>

        <?php if(@$mensagem): ?>
        <tr class="linha_par">
            <td width="80" colspan="10" align="center"><b><?=$mensagem?></b></td>
        </tr>
        <?php endif; ?>

        <?php if(!@$mensagem): ?>
            <?php $i = 0; ?>
            <?php foreach($historico as $row):?>
                <?php
                $i++;
                if($i % 2 == 0)
                    $cor = "linha_par";
                else
                    $cor = "linha_impar";
                ?>
            <tr class='<?=$cor?>' id="linha_<?=$i?>">
                <td><?=$row->id_historico_status_certificado?></td>
                <td><?=dataBR($row->dt_alteracao)?></td>
                <td><?=date('H:i:s', strtotime($row->dt_alteracao))?></td>
                <td><?=$row->nm_usuario?></td>
                <td><?=$row->ip_usuario?></td>
                <td>
                        <?php if ($row->fl_status_certificado == 'A') { echo 'Válido'; } ?>
                        <?php if ($row->fl_status_certificado == 'I') { echo 'Revogado'; } ?>
                        <?php if ($row->fl_status_certificado == 'P') { echo 'Pendente'; } ?>
                </td>
                <td><?=$row->de_justificativa?></td>
            </tr>
            <?php endforeach;?>
        <?php endif;?>
     </table>
     <br />
     </body>
</html>
