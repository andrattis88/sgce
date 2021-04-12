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

// Organizadores
function exibeOrganizadores(evento, URL) {
    var combo      = "#txtOrganizadores";
    var loading    = "#loading_organizadores";
    $(loading).fadeIn('slow');
    $.post(URL+'eventos/carregaOrganizadoresAjax', {evento: evento},
    function(data){
            if(data.length >0) {
                $(loading).fadeOut('slow');
                $(combo).html(data);
            } else {
                $(loading).fadeOut('slow');
                $(combo).html("<option value='0'>Nenhum organizador encontrada</option>");
            }
    });

}


function addOrganizadoresTable(evento, organizadores, URL) {
    var vetor = organizadores;
    var fSelecionados = new Array();
    var j = 0;            
    for(i=0; i< vetor.length; i++) {
        if(vetor[i].selected) {
            fSelecionados[j] = vetor[i].value;
            $.post(URL + 'eventos/gravaOrganizadorAjax', 
                    {evento:evento, organizador:vetor[i].value},
                function(data){
                    if (data.length <= 0) {
                        alert('Impossível gravar organizador');
                    } //else {
                      //  location.reload();
                    //}
                });
            j++;
        }        
    }
    
    var tbl = '#data_table_interna';
    $.post(URL+'eventos/carregaOrganizadoresEventoAjax',
    {evento: evento, organizadores: fSelecionados},
    function(data) {
            if(data.length >0) {
                $(tbl+' tr:last').after(data);
            }
    });

    //$('#newRegister').hide('slow');
}

function removeOrganizadorTable(URL, idOrganizador, idEvento) {    
    if (confirm('Deseja realmente excluir o organizador?')) {
        $.post(URL + 'eventos/removeOrganizadorAjax', 
               {evento:idEvento, organizador:idOrganizador},
                function(data){                   
                   location.reload();                    
                });
        document.getElementById('idsOrganizadores['+idOrganizador+']').value = 0;
        $("#linha_"+idOrganizador).remove();        
    }
}

function cancelFormOrganizadores() {
    $('#newRegister').hide('slow');
}

function exibeFormOrganizadores(URL) {
    var combo      = "#txtOrganizadores";
    var loading    = "#loading_organizadores";
    $(loading).fadeIn('slow');

    $.post(URL+'eventos/carregaOrganizadoresAjax',
    function(data){
            if(data.length >0) {
                $(loading).fadeOut('slow');
                $(combo).html(data);
            } else {
                $(loading).fadeOut('slow');
                $(combo).html("<option value='0'>Nenhum organizador encontrado</option>");
            }
    });
    $('#newRegister').show('slow');

}

function alteraControladorTable(URL, idOrganizador) {    
    var idEvento      = document.getElementById('txtId').value;    
    $.post(URL+'eventos/atribuiControladorAjax/', {evento: idEvento, organizador: idOrganizador},    
    function(data){       
       if (data.length >0) {           
           window.location.reload(true);
       } else {
           alert("Houve um erro na atribuição dos controladores. Salve o registro do evento e tente novamente.");
       }
            
    });    
}
