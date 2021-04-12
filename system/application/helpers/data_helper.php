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
 * Recebe a data em formato SQL e devolve em formato brasileiro.
 * @param String $dataSQL - data em formato SQL: yyyy-mm-dd
 * @param String $hora
 * @return string - data em formato brasileiro: dd/mm/yyyy
 */
function dataBR($dataSQL, $hora='d') {
    if ($dataSQL <> '') {
        $data = explode("-","$dataSQL");
        $d = substr($data[2],0,2);
        $m = $data[1];
        $y = $data[0];
        $h = substr($data[2],2,6);
        $res = checkdate($m,$d,$y);

        if ($res == 1) {
            $novaData = "$d/$m/$y";
        } else {
            $novaData = "";
        }
    } else {
        $novaData = "";
    }
    if ($hora=='d') {
        return $novaData;
    } elseif ($hora=='dh') {
        return $novaData . $h;
    } if
    ($hora=='h') {
        return $h;
    }
}


/**
 * Recebe a data em formato brasileiro, e devolve em formato americano
 * @param  String $data - data em formato br:  dd/mm/yyyy
 * @return String       - data em formato americano: mm/dd/yyyy
 */
function dataEUA($data) {
    $dataForm = null;
    if($data) {
        $dia = substr($data, 0, 2);
        $mes = substr($data, 3, 2);
        $ano = substr($data, 6, 4);
        $dataForm = $mes."/".$dia."/".$ano;
    }
    return $dataForm;
}

/**
 * Calcula a diferenca de dias entre as duas datas passadas
 * $date_ini e $date_end devem ser no formato mm/dd/yyyy
 * retorna o nmero de dias entre as datas
 *
 * @param  String $date_ini - data formato mm/dd/yyyy
 * @param  String $date_fin - data formato mm/dd/yyyy
 * @return Integer - Diferenca em dias
 *
 */
function daysDiff($date_ini, $date_end, $round = 3) {
    $date_ini = strtotime($date_ini);
    $date_end = strtotime($date_end);
    $date_diff = ($date_end - $date_ini) / 86400;
    return round($date_diff,$round);
}

/**
 * Checks date if matches given format and validity of the date.
 * Examples:
 * <code>
 * is_date('22.22.2222', 'mm.dd.yyyy'); // returns false
 * is_date('11/30/2008', 'mm/dd/yyyy'); // returns true
 * is_date('30-01-2008', 'dd-mm-yyyy'); // returns true
 * is_date('2008 01 30', 'yyyy mm dd'); // returns true
 * </code>
 * @param string $value the variable being evaluated.
 * @param string $format Format of the date. Any combination of <i>mm<i>, <i>dd<i>, <i>yyyy<i>
 * with single character separator between.
 */
function isValidDate($value, $format = 'dd.mm.yyyy') {

    if(strlen($value) >= 6 && strlen($format) == 10) {
        // find separator. Remove all other characters from $format
        $separator_only = str_replace(array('m','d','y'),'', $format);
        $separator = $separator_only[0]; // separator is first character

        if($separator && strlen($separator_only) == 2) {
            // make regex
            $regexp = str_replace('mm', '(0?[1-9]|1[0-2])', $format);
            $regexp = str_replace('dd', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp);
            $regexp = str_replace('yyyy', '(19|20)?[0-9][0-9]', $regexp);
            $regexp = str_replace($separator, "\\" . $separator, $regexp);

            if($regexp != $value && preg_match('/'.$regexp.'\z/', $value)) {
                // check date
                $arr=explode($separator,$value);
                $day=$arr[0];
                $month=$arr[1];
                $year=$arr[2];
                if(@checkdate($month, $day, $year))
                    return true;
            }
        }
    }
    return false;
}


/**
 * Converte data brasileira pra padrao mySql
 */
function dataSQL($databr) {
    if ($databr <>'') {
        $data = explode("/","$databr");
        $d = $data[0];
        $m = $data[1];
        $y = $data[2];

        // verifica se a data é válida!
        // 1 = true (válida)
        // 0 = false (inválida)
        $res = checkdate($m,$d,$y);
        if ($res == 1) {
            $novadata = "$y-$m-$d";
        } else {
            $novadata = "";
        }

    } else {
        $novadata = "";
    }
    return $novadata;
}





?>