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

function validaFornecedor() {

    if(!document.getElementById('txtNome').value > 0) {
        alert('Campo nome é obrigatório.');
        document.getElementById('txtNome').focus();
        return false;
    }
/*
    if(validaCpf(document.getElementById('txtCPF').value) == false) {
        alert("CPF Invalido");
        document.getElementById('txtCPF').focus();
        return false;
    }
    */

    if(validaEmail(document.getElementById('txtEmail')) == false) {
        alert("Email Invalido");
        document.getElementById('txtEmail').focus();
        return false;
    }

    if(!document.getElementById('txtTelefone').value > 0) {
        alert('O campo telefone é obrigatório.');
        document.getElementById('txtTelefone').focus();
        return false;
    }


    if(!document.getElementById('txtUsuario').value > 0) {
        alert('O campo usuario é obrigatório.');
        document.getElementById('txtUsuario').focus();
        return false;
    }

    return true;
}

