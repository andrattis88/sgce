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
 * View para o resultado de importacao
 *
 *
 * @author     Sergio Jr <sergiojunior@unipampa.edu.br
 *
 * @copyright Universidade Federal do Pampa - NTIC Campus Alegrete 2010
 *
 */
?>
<p align='center' id="td_log_<?=$id_log?>">
    <center>
        <?php echo $mensagem;?>        
        <br />        
        <a href="javascript:visualizaDetalhesLog('<?=$id_log?>', '<?=base_url()?>')"
               title="Visualizar Detalhes">
                <img alt="Detalhes" title="Detalhes"
                     src="<?=base_url() . 'system/application/views/includes/images/comprovante_16.png'?>" />
                Ver detalhes da Importação
        </a>
        <br /><br />
            <a href="<?=$link_voltar?>" >
            <img src='<?= base_url()?>system/application/views/includes/images/seta_voltar.png'
            alt="Voltar" title="Voltar"/>
        </a>
    </center>
    <div id="boxes">                  
        <div id="detalhes_log_<?=$id_log?>" class="detalhes_log">                
            <a href="javascript:escondeDetalhesLog('<?=$id_log?>')" class="close">
                <img alt="Fechar" title="Fechar"
                     src="<?=base_url() . 'system/application/views/includes/images/fechar_modal.png'?>" />
            </a>
            <p class="titulo_tabela">Detalhes da Importação</p>
            <table width="100%" border='0' class="center_table"
                   id="tbl_detalhes_log_<?=$id_log?>">
               <!--Monta tabela de historico em helper -->
            </table>
        </div>        
     </div>
</p>