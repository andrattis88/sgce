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
 * Funcao para geracao de senha aleatoria.
 * Para usar a funcao faca assim:
 * $senhaAuth = gerarSenha(5, false, true, true, false);
 *
 * @param Integer $tamanho     - Tamanho da senha
 * @param Boolean $maiuscula   - Gerar com maiusculas
 * @param Boolean $minuscula   - Gerar com minusculas
 * @param Boolean $numeros     - Gerar com numeros
 * @param Boolean $codigos     - Gerar com caracteres especiais
 * @return String $senha       - Senha gerada aleatoriamente.
 */
function gerarSenha($tamanho, $maiuscula, $minuscula, $numeros, $codigos) {
    $maius = "ABCDEFGHIJKLMNOPQRSTUWXYZ";
    $minus = "abcdefghijklmnopqrstuwxyz";
    $numer = "0123456789";
    $codig = '!@#$%&*()-+.,;?{[}]^><:|';
    $base = '';
    $base .= ($maiuscula) ? $maius : '';
    $base .= ($minuscula) ? $minus : '';
    $base .= ($numeros) ? $numer : '';
    $base .= ($codigos) ? $codig : '';
    srand((float) microtime() * 10000000);

    $senha = '';
    for ($i = 0; $i < $tamanho; $i++) {
        $senha .= substr($base, rand(0, strlen($base)-1), 1);
    }

    return $senha;
}

?>
