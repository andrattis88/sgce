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
     * Gera linha (tr) na tabela html de organizadores -
     * cadastro de atas
     * @param Array $res
     * @return string 
     */
    function geraTabelaOrganizadores ($res, $idEvento) {    
        $linhas   = '';

        foreach ($res as $registro) {
            $linkExcluir     = geraLinkExcluir($registro->id_organizador, $idEvento);
            $linkControlador = geraLinkControlador($registro->id_organizador, $registro->fl_controlador);

            $linhas .= "<tr id=linha_".$registro->id_organizador.">
                            <td>$registro->id_organizador</td>
                            <td>$registro->nm_organizador</td>
                            <td>$linkControlador
                                <input type='hidden'
                                       name='idsOrganizadores[".$registro->id_organizador."]'
                                       id='idsOrganizadores[".$registro->id_organizador."]'
                                       value=$registro->id_organizador />
                                       
                                <input type='hidden'
                                       name='idsControladores[".$registro->id_organizador."]'
                                       id='idsControladores[".$registro->id_organizador."]'
                                       value='$registro->fl_controlador' />
                    
                    
                            </td>
                            <td>$linkExcluir
                                <input type='hidden'
                                       name='idsOrganizadores[".$registro->id_organizador."]'
                                       id='idsOrganizadores[".$registro->id_organizador."]'
                                       value=$registro->id_organizador />
                            </td>
                        </tr>";

        }
        return $linhas;
    }

    /**
     * Gera o link de exclusao com a imagem, para cada organizador do evento
     * cadastro de atas
     * @param Integer $idFormando
     * @return string 
     */
    function geraLinkExcluir($idOrganizador, $idEvento) {
        $html = "
                <center>
                <a href=\"javascript:removeOrganizadorTable('".base_url()."', ".$idOrganizador.",$idEvento);\"'
                    class='delete' title='Excluir Organizador' >
                    <img src='".base_url()."system/application/views/includes/images/cancel_16.png'
                         border='0' alt='Excluir Organizador'
                         title='Excluir Organizadores' />
                </a>
                </center>
            ";
        return $html;
    }
    
    function geraLinkControlador($idOrganizador, $statusAtual) {
        if (!$statusAtual) {
            $statusAtual="N";
        }
        
        $html = "
                <center>
                <a href=\"javascript:alteraControladorTable('".base_url()."', ".$idOrganizador.");\"'
                    class='delete' title='Status de Controlador' >
                    <img src='".base_url()."system/application/views/includes/images/controlador_".strtolower($statusAtual)."_16.png'
                         border='0' alt='Alterar Status de Controlador'
                         title='Alterar Status de Controlador' />
                </a>
                </center>
            ";
        return $html;
    }

?>
