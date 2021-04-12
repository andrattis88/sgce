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

function exibeDivRevogacao(idCertificado, status) {
    if(status == 'I') {
        alert("O certificado selecionado já está revogado.");
    } else {
        var div   = "#" + "div_revogacao_" +idCertificado;
        $(div).show('slow')
    }
}

function exibeDivValidacao(idCertificado, status) {
    if(status == 'A') {
        alert("O certificado selecionado já está validado.");
    } else {
        var div   = "#" + "div_validacao_" +idCertificado;
        $(div).show('slow')
    }
}

function revogarCertificado(idCertificado, URL) {
    var div           = "#" + "div_revogacao_"  + idCertificado;
    var justifElement = "#" + "txtJustifRevog_" + idCertificado;
    var justificativa = $(justifElement).val();

    if(justificativa == '') {
        alert('Informe a justificativa.');
        return false;
    }

    var envMailElement = "#" + "txtEnvMailRevog_" + idCertificado;
    var envMail        = $(envMailElement).val();

    if(idCertificado > 0) {
        $.post(URL+'certificados/alterarStatusCertificadoAjax',
            {id_certificado: idCertificado, 
             justificativa : justificativa,
             envia_email: envMail,
             status : 'I'},
            function(data) {
                if(data.length > 0) {
                    alert(data);
                    window.location.reload();
                } else {
                    $(div).hide('slow');
                }
            });
    }
    return true;
}

function validarCertificado(idCertificado, URL) {
    var div   = "#" + "div_validacao_" +idCertificado;
    var justifElement = "#" + "txtJustifValid_" + idCertificado;
    var justificativa = $(justifElement).val();

    if(justificativa == '') {
        alert('Informe a justificativa.');
        return false;
    }

    var envMailElement = "#" + "txtEnvMailValid_" + idCertificado;
    var envMail        = $(envMailElement).val();

    if(idCertificado > 0) {
        $.post(URL+'certificados/alterarStatusCertificadoAjax',
            {id_certificado: idCertificado,
             justificativa : justificativa,
             envia_email: envMail, status : 'A'},
            function(data) {
                if(data.length > 0) {
                    alert(data);
                    window.location.reload();
                } else {
                    $(div).hide('slow');
                }
            });
    }
    return true;
}

function cancelarRevogacao(idCertificado) {
    var div   = "#" + "div_revogacao_" +idCertificado;
    $(div).hide('slow')
}

function cancelarValidacao(idCertificado) {
    var div   = "#" + "div_validacao_" +idCertificado;
    $(div).hide('slow')
}


/**
 * Funcao para marca todos checkboxes com a classe cb
 */
function setAllChecks(elemento) {
    if (elemento.checked) {
        $('.cb').attr('checked','checked');
    } else {
        $('.cb').attr('checked','');
    }
}

function validarAvaliacao() {
    
    if(!document.getElementById('txtJustificativa').value) {
        alert('Campo Justificativa é obrigatório.');
        document.getElementById('txtJustificativa').focus();
        return false;
    }
    
    if(!document.getElementById('txtStatus').value) {
        alert('Campo Status é obrigatório.');
        document.getElementById('txtStatus').focus();
        return false;
    } 
    
    var selecionados = $("input[id='txtAvaliados']").serializeArray();    
    if (selecionados.length==0) {
        alert('Selecione ao menos um certificado para avaliação.');
        document.getElementById('txtAvaliados').focus();
        return false;
    }
       
    return true;        
}

function marcaStatus(url, evento, modelo, campo) {            
    $.post(url + 'certificados/atualizaFiltroAvaliacao/', 
        {evento: evento, 
         modelo:modelo, 
         campo:campo.name, 
         valor:campo.checked},
            function(data){ 
                if (data.length >0) {                                                                                
                    window.location = url+'certificados/novaAvaliacao/'+evento+'/'+modelo;
                }
            });
}