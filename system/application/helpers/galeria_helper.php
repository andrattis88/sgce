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
 * Helper para exibicao de galeria de imagens
 * @param String $diretorio
 */
function exibeGaleria($diretorio=null) {
    $i=0;
    $open = opendir($diretorio);
    $imgOk = base_url() . 'system/application/views/includes/images/ok_16.png';
    echo '<table width="500px" border="0" cellspacing="0" cellpadding="0"><tr>';
    while($ler=readdir($open)){

        if(($ler!='.') && ($ler!='..') && (substr($ler,0,1)!='.') ){
            echo '<td width="120px" height="100px" class="preview">';
            echo '<a href="'.base_url().$diretorio.$ler.'" target="_blank">
                  <img src="'.base_url().$diretorio.$ler.'" width="120px" height="80px"
                      title="'.$ler.'"/></a>';
            echo '<br>'.'<a style="color:#FFF" href="#"
                            title="Clique aqui para escolher esta imagem como fundo do certificado"
                            onclick="javascript:atribuiModelo(\''.$ler.'\')"> <img src="'.$imgOk.'"/>' . substr($ler,0,strlen($ler)-4).'</a></td>';
            //acrescenta +1 a contagem
            $i=$i+1;
        }
            //verifica se ja mostrou n imagens, pode ser alterado
        if($i==4){
            //se sim muda de row e muda o contador para zero
            echo '</tr>';
            $i=0;
        }
    }

    echo "</table>";
}

?>
