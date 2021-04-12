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

function carregaDestinatariosModelo(idModelo, nmLoading,  URL, nmComboDest) {
    var combo      = "#" + nmComboDest;
    var loading    = "#" + nmLoading;

    $(loading).fadeIn('slow');
    $.post(URL+'certificados/carregaDestinatariosModeloAjax', {id_modelo: idModelo},
    function(data){
            if(data.length >0) {
                $(loading).fadeOut('slow');
                $(combo).html(data);
            } else {
                $(loading).fadeOut('slow');
                $(combo).html("<option value='0'>Nenhum certificado liberado para notificação!</option>");
                alert('Nenhum certificado está liberado para notificação do participante.\n\nO Controlador de Qualidade precisa avaliar e liberar os certificados que ainda estiverem pendentes para este evento.')
            }
    });

}

function limpaCombo(nmCombo) {
    var combo = "#" + nmCombo;
    $(combo).html("<option value='0'>Selecione...</option>");
}
