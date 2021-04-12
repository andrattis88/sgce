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
 * Substitui os caracteres especiais da string passada por caracteres ASCII
 * @param String $var - String com caracteres especiais
 * @return String $nova - String Ascii
 */
  function replaceAscii($var) {
      $de =   array('À','Á','Ã','Â','É','Ê','Í','Ì','Ó','Õ','Ô','Ú','Ü','Ç',
           'à','á','ã','â','é','ê','í','ì','ó','õ','ô','ú','ü','ç','º','°','ª',"'",' ',
           '!', '@', '#', '$', '%', '¨', '&', '*', '(', ')', '?', 'ñ', 'Ñ' );

      $para = array('A','A','A','A','E','E','I','I','O','O','O','U','U;','C',
           'a','a','a','a','e','e','i','i','o','o','o','u','u','c','.','.','.',' ','_',
           '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', 'n', 'N');

      $nova = str_replace($de, $para, $var);

      return $nova;
   }
?>
