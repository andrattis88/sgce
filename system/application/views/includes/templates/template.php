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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="pt-br" lang="pt-br" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />        
        <META HTTP-EQUIV="pragma" CONTENT="no-cache" />
        <meta http-equiv="Cache-control" content="no-cache" />        
        <link rel="stylesheet" href='<?= base_url()?>system/application/views/includes/css/menu.css' type="text/css" />
        <link rel="stylesheet" href='<?= base_url()?>system/application/views/includes/css/estilo_admin.css' type="text/css" />
        <script type="text/javascript" src='<?= base_url()?>system/application/views/includes/js/jquery.js'></script>
        <script type="text/javascript" src='<?= base_url()?>system/application/views/includes/js/menu.js'></script>
        <script type="text/javascript" src='<?= base_url()?>system/application/views/includes/js/admin.js'></script>
        
        <title><?=SIGLA_SISTEMA . ' - '. NOME_SISTEMA ?></title>
    </head>
    <body>
        <?php $this->load->view('includes/templates/header_view'); ?>
        <div class="content">
            <div id="center_place">
                <center>
                    <?php $this->load->view($corpo_pagina); ?>
                </center>
            </div>
        </div>
        <?php $this->load->view('includes/templates/footer_view'); ?>
    </body>
</html>