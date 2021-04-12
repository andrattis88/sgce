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

<div id="menu">
    <ul class="menu">
        <li><a href="#" class="parent"><span>Cadastros</span></a>
            <div>
                <ul>
                    <li><a href="<?=base_url().'eventos'?>"><span>Eventos</span></a>
                    </li>
                    <li><a href="<?=base_url().'organizadores'?>"><span>Usuários</span></a>
                    </li>
                    <li><a href="<?=base_url().'participantes'?>"><span>Participantes</span></a>
                    </li>
               </ul>
            </div>
        </li>
        
        <li><a href="#" class="parent"><span>Certificados</span></a>
            <div>
                <ul>
                    <li><a href="<?=base_url().'certificados'?>"><span>Listagem de Certificados</span></a>
                    </li>
                    <li><a href="<?=base_url().'modelos_certificados'?>"><span>Modelos de Certificados</span></a>
                    </li>                    
                    <li><a href="<?=base_url().'participantes/formImporta'?>"><span>Importar Dados</span></a>
                    </li>
                    <li><a href="<?=base_url().'certificados/selecionarModeloParaAvaliacao'?>"><span>Avaliação de Certificados</span></a>
                    </li>
                    <li><a href="<?=base_url().'certificados/notificar'?>"><span>Notificação de Participantes</span></a>
                    </li>
               </ul>
            </div>
        </li>
        
        <li><a href="#" class="parent"><span>Sistema</span></a>
            <div>
                <ul>
                    <li><a href="<?=base_url().'configuracoes'?>"><span>Configurações</span></a>
                    </li>
               </ul>
            </div>
        </li>

    </ul>
</div>




