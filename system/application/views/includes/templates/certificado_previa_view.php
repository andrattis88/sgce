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
        <link rel="stylesheet" href="<?= base_url()?>system/application/views/includes/css/certificado/<?=$certificado->nm_fonte?>.css" />
        <link rel="stylesheet" href="<?= base_url()?>system/application/views/includes/css/certificado/certificado.css" />        
    </head>
    <body>
        <div class="pagina"
             style="background-image: url('<?= base_url()?>uploads/modelos/<?=$certificado->nm_fundo?>');
                    background-repeat: no-repeat;
                    /*background-attachment: fixed;*/
                    background-position:center center;">

            <br /><br />
            <div class="titulo" 
                 style="padding-top: <?=@$certificado->de_posicao_titulo?>%;
                        float: <?=@$certificado->de_alinhamento_titulo?>;
                        text-align:<?=@$certificado->de_alin_texto_titulo?>;
                        color: <?=@$certificado->de_cor_texto_titulo?>;
                        font-size: <?=@$certificado->de_tamanho_titulo?>pt;">

                <p><b><?=$certificado->de_titulo?></b></p>
            </div>
            <div class="corpo"
                 style="padding-top: <?=@$certificado->de_posicao_texto?>%;
                        float: <?=@$certificado->de_alinhamento_texto?>;
                        text-align:<?=@$certificado->de_alin_texto_texto?>;
                        color: <?=@$certificado->de_cor_texto_texto?>;
                        font-size: <?=@$certificado->de_tamanho_texto?>pt;">
                
                <p><?=$certificado->de_texto?></p>
            </div>
            <br /><br />
            <div class="linha_validacao"
                 style="padding-top: <?=@$certificado->de_posicao_validacao?>%;
                        float: <?=$certificado->de_alinhamento_validacao?>;
                        text-align:<?=@$certificado->de_alin_texto_validacao?>;
                        color: <?=@$certificado->de_cor_texto_validacao?>;">
                <br/><br/><br/>
                <p>
                    a autenticidade deste documento pode ser verificada através da URL:<br/>
                    <?=URL_CERTIFICADO.ENDERECO_VALIDACAO.'#MODELO#'?>
                </p>
            </div>
        </div>
    <? if (!@$certificado->de_texto_verso) :?>
    </body>
</html>
<?endif; ?>