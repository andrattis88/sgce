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

function atribuiModelo(nomeModelo) {    
    $("#nomeModelo").val(nomeModelo);    
}

function escolheModo(tipoModo) {
    if (tipoModo=='upload') {
        $('#upload').show();
    }
    if ((tipoModo==null) || (tipoModo==''))  {
        $('#upload').hide();
    }
}
/* Cores alternadas - tabelas*/
function overHighLight(entrada){

    $("#linha_"+entrada).addClass('high_lights');
}

function outHighLight(entrada){
    $(entrada).removeClass('high_lights');
}

/* Colore a linha selecionada*/
function colorRowSelected(i){
    //caso exista alguma linha selecionada ele desmarca
    $(actualSelectedLine).removeClass("editing");
    //para deixar a linha selecionada para indicar que esta sendo editada
    actualSelectedLine = "#linha_"+i;
    $(actualSelectedLine).addClass("editing");
}

/**
* Funcao generica para abrir as pop-ups do sistema
**/
function abrirPopup(URL, width, height) {
    if (!width)
        var width = 700;
    if (!height)    
        var height = 300;
        
    var left = 99;
    var top = 99;
    window.open(URL,'janela', 'width='+width+', height='+height+', top='+top+', left='+left+', scrollbars=yes, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=yes, fullscreen=no');
}

function confirmaExclusao( url,nome ) {
    if ( window.confirm( "Confirmar a exclusão "+nome+" ?" ) ) 
        window.location.href=url;
}

function confirmaClonagem( url,nome ) {
    if ( window.confirm( "Confirma a clonagem "+nome+" ?" ) ) 
        window.location.href=url;
}

/**
* Habilita o input para aceitar apenas numeros. passar o campo.
**/
function somenteNumero(campo) {
    var digits = "0123456789";
    var campoTemp;
    for (var i=0;i< campo.value.length;i++) {
        campoTemp = campo.value.substring(i,i+1);
        if (digits.indexOf(campoTemp)==-1) {
            campo.value = campo.value.substring(0,i);
        }
    }
}

function retornaNum( obj, e ) {
    var tecla = ( window.event ) ? e.keyCode : e.which;
    if ( tecla == 8 || tecla == 0 )
        return true;
    if (tecla < 48 || tecla > 57 )
        return false;
}

function JumpField(fields) {
    if (fields.value.length == fields.maxLength) {
        for (var i = 0; i < fields.form.length; i++) {
            if (fields.form[i] == fields && fields.form[(i + 1)] && fields.form[(i + 1)].type != "hidden") {
                fields.form[(i + 1)].focus();
                break;
            }
        }
    }
}

/**
 * Verifica se a data passada e valida. Chamar no onBlur do elemento. passar o valor.
 */
function verificaData(data) {
    dia = (data.value.substring(0,2));
    mes = (data.value.substring(3,5));
    ano = (data.value.substring(6,10));

    situacao = "";
    // verifica o dia valido para cada mes
    if ((dia < "01")|| (dia < "01" || dia > "30") 
        && (  mes == "04" || mes == "06" || mes == "09" || mes == "11" ) || dia > "31") {
        situacao = "falsa";
    }

    // verifica se o mes e valido
    if (mes < "01" || mes > "12" ) {
        situacao = "falsa";
    }

    // verifica se e ano bissexto
    if (mes == 2 && ( dia < 01 || dia > 29 || ( dia > 28 && (parseInt(ano / 4) != ano / 4)))) {
        situacao = "falsa";
    }

    if (data.value == "") {
        situacao = "falsa";
    }

    if (situacao == "falsa") {
        alert("Data inválida!");
        data.value = '';
    }
}


/**
* Verifica se o email passado e valido - passar o campo.
**/
function validaEmail(campo_email) {
    //Checando se o endereço e-mail não esta vazio
    if(campo_email=="") {
        return false;
    }
    //Checando se o endereço de e-mail é válido
    if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(campo_email.value))) {
        return false;
    }
    return true;

}


/**
/ Verifica se o cpf passado e valido. passar o valor.
**/
function validaCpf(s) {
    var i;
    var c = s.substr(0,9);
    var dv = s.substr(9,2);
    var d1 = 0;
    for (i = 0; i < 9; i++)
    {
        d1 += c.charAt(i)*(10-i);
    }
    if (d1 == 0){
        return false;
    }
    d1 = 11 - (d1 % 11);
    if (d1 > 9) d1 = 0;
    if (dv.charAt(0) != d1)
    {
        return false;
    }
    d1 *= 2;
    for (i = 0; i < 9; i++)
    {
        d1 += c.charAt(i)*(11-i);
    }
    d1 = 11 - (d1 % 11);
    if (d1 > 9) d1 = 0;
    if (dv.charAt(1) != d1)
    {
        return false;
    }
    return true;
}

function carregaModelosEvento(idEvento, nmLoading,  URL, nmComboModelo, nmDivInstrucoes) {
    var combo      = "#" + nmComboModelo;
    var div        = "#" + nmDivInstrucoes;
    limpaComboModelos(combo, div);
    if(idEvento > 0) {
        var loading    = "#" + nmLoading;
        $(loading).fadeIn('slow');

        $.post(URL+'eventos/carregaModelosEventoAjax',
            {id_evento: idEvento},
            function(data) {
                if(data.length > 0) {
                    $(loading).fadeOut('slow');
                    $(combo).html(data);
                } else {
                    $(loading).fadeOut('slow');
                    $(combo).html("<option value=''>Nenhum registro encontrado</option>");
                }
            });
    } 
}

function limpaComboModelos(combo, div) {
    $(combo).html("<option value=''>Selecione...</option>");
    $(div).hide('slow');

}

function carregaInstrucoesImportacao(idModelo, nmLoading, URL, nmDivInstrucoes) {
    var div      = "#" + nmDivInstrucoes;
    if(idModelo > 0) {
        var loading    = "#" + nmLoading;
        $(loading).fadeIn('slow');
        $(div).hide('slow');

        $.post(URL+'modelos_certificados/carregaInstrucoesImportacaoAjax',
            {id_modelo: idModelo},
            function(data) {                
                if(data.length > 0) {
                    $(loading).fadeOut('slow');
                    $(div).show('slow');
                    $(div).html("<label for='instrucoes_importacao'>Instruções para Importação:</label><br/>"+data);
                } else {
                    $(loading).fadeOut('slow');
                    $(div).hide('slow');
                }
            });
    } else {
        $(div).hide('slow')
    }

}

function confirmaRestauracaoConfig(url) {
    if (window.confirm( "Confirmar a restauração da configuração padrão?" ))
        window.location.href = url;
}

/**
 * Visualiza numa div o detalhe do log
 */
function visualizaDetalhesLog(idLog, URL) {
    if(idLog) {
        var div         = "#detalhes_log_"+idLog;
        var tdLog       = "#td_log_"+idLog;
        var posicao     = $(tdLog).offset();        
               
        $(div).css('margin-top', posicao.top - 50);
        $(div).css('margin-left', posicao.left);
        $(div).show('slow');

        var tbl = "#tbl_detalhes_log_"+idLog;
        $(tbl).html('Aguarde...');

        $.post(URL+'participantes/carregaDetalhesLogTableAjax',
        {id_log:  idLog},
            function(data) {
                if(data.length > 0) {
                    $(tbl).html(data);
                } else {
                    $(tbl).html('Não há informações complementares a exibir.');
                }
            });

    }

}

/**
 *Esconde a div de itens do historico
 */
function escondeDetalhesLog(idLog) {
        $('#detalhes_log_'+idLog).hide('slow');
}

/**
 * Selecao de tipo de configuracao 
 */

function selecionaTipoConfig(config) {
    if (config =='banco') {
        $('#configLDAP').hide('fast');
    } else {
        $('#configLDAP').show('fast');
    }
}

function confirmaReimportar(opcao, url) {
    if (opcao=='S') {
        if ( window.confirm( "Tem certeza de que deseja repetir a última importação de certificados feita para o modelo? \n\ Caso já exista um certificado, ele será duplicado!" ) ) window.location.href=url;
    }
}