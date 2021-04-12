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
 * Exibe o progresso do processamento em percentual.
 *
 * @param Integer $i      - Registro atual
 * @param Integer $totReg - Total de registros
 */
function exibeProgresso($i, $totReg) {
    $mod    = 5;
    $totReg = ($totReg > 0) ? $totReg : 1;
    $valor  = $i * 100/$totReg;
    if(((int)$valor) % $mod == 0) {

        $percAtual = ((int)$valor) . '\%';
        $msg       = "<b>Processamento: $percAtual, aguarde...</b>";

        echo "<script>
                  document.getElementById('progresso').innerHTML = '$msg';
              </script>";
        descarregaBufferProgressoExecucao();
    } 
}


/**
 * Finaliza pagina temporaria de exibicao do progresso
 */
function direcionarURL($urlDestino) {
    echo "<script>window.location.href = '$urlDestino'; </script>";
}

/**
 * Descarrega o output buffer do php, forçando a saída dos dados no browser.
 */
function descarregaBufferProgressoExecucao($msg = null) {    
    if($msg)
        echo $msg;
    ob_flush();
    flush();
}

/**
 * Escreve saida do progresso final
 */

function geraSaida($dados) {        
   echo "<script type=\"text/javascript\">";
   echo "   $(\"body\").empty();";      
   echo "</script>";
   echo $dados;
}


?>
