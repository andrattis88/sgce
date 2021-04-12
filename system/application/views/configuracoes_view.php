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
 * View de Cadastro de eventos
 *
 * Utilizada para Entrada de Dados de eventos
 *
 * @author     Pedro Conrad Jr. <pedro.junior@unipampa.edu.br>
 * @author     Sergio Jr <sergiojunior@unipampa.edu.br
 *
 * @copyright Universidade Federal do Pampa - NTIC Campus Alegrete 2010
 *
 */
?>

<script type="text/javascript" src='<?= base_url()?>system/application/views/includes/js/eventos.js'></script>
<link rel="stylesheet" href='<?= base_url()?>system/application/views/includes/css/tabs.css' type="text/css" />
<script type="text/javascript" src='<?= base_url()?>system/application/views/includes/js/tabs.js'></script>

<?    // Inicializacao de editor de texto ?>
<script type="text/javascript" 
        src="<?= base_url()?>system/application/views/includes/js/tiny_mce/tiny_mce.js">
</script>
<script type="text/javascript"
        src="<?= base_url()?>system/application/views/includes/js/editor_texto.js">
</script>
<? // Fim de editor de texto ?>

<?php echo validation_errors('<div class="error">','</div>'); ?>
<?=form_open(base_url().'configuracoes/salvar');?>
<div class="botoes_left">
    <button type="submit" id="botao_salvar" name="botao_salvar">
        <img src='<?= base_url()?>system/application/views/includes/images/salvar_32.png'
             alt="Salvar"/><br>&nbsp;&nbsp;Salvar&nbsp;&nbsp;
    </button>

    <button onclick="confirmaRestauracaoConfig('<?= base_url()?>configuracoes/restaurar');"
            type="button" id="botao_restaurar" title="Restaurar a configuração padrão">
        <img src='<?= base_url()?>system/application/views/includes/images/restaurar_32.png'
             alt="Cancelar"/><br>Restaur.
    </button>

    <button onclick="parent.location='<?= base_url()?>configuracoes/cancelar'"
            type="button" id="botao_cancelar">
        <img src='<?= base_url()?>system/application/views/includes/images/cancel_32.png'
             alt="Cancelar"/><br>Cancelar
    </button>

</div>


<div class="titulo_right"><h1>Configurações do Sistema</h1></div>
    <div class="center_table">
        <ul class="tabs">
            <li><a href="#tab1">Autenticação</a></li>
            <li><a href="#tab2">Upload</a></li>            
            <li><a href="#tab3">Mensagens</a></li>
        </ul>
    <div class="tab_container">
        <div id="tab1" class="tab_content">
            <br />
            <p>
                <label for='txtModoAuth'>Modo de Autenticação*: </label>
                <select name="txtModoAuth" onchange="selecionaTipoConfig(this.value);">
                    <option value="ldap"
                        <? if (@$tipo_de_autenticacao == 'ldap'):
                            echo " selected ";
                        endif;?>>LDAP</option>

                    <option value="banco" 
                        <? if (@$tipo_de_autenticacao == 'banco'):
                            echo " selected ";
                           endif;?>>Banco de Dados</option>
                    
                    <option value="mista" 
                        <? if (@$tipo_de_autenticacao == 'mista'):
                            echo " selected ";
                           endif;?>>Mista</option>
                </select>                
            <fieldset id="configLDAP">
                <legend>Dados do Servidor LDAP</legend>
                <p align="left">
                <label for='txtServerLDAP'>Servidor LDAP*: </label>
                <input name="txtServerLDAP" type="text" value="<?=@$server_ldap?>"
                       id="txtServerLDAP" size="50" />
                <br /><br />

                <label for='txtPortaLDAP'>Porta LDAP*: </label>
                <input name="txtPortaLDAP" type="text" value="<?=@$porta_ldap?>"
                       id="txtPortaLDAP" size="50" />
                <br /><br />

                <label for='txtDNBase'>DN Base LDAP*: </label>
                <input name="txtDNBase" type="text" 
                       value="<?=@$base_dn?>" id="txtDNBase" size="50" />
                <br /><br />

                <label for='txtDNMaster'>DN Master LDAP*: </label>
                <input name="txtDNMaster" type="text" 
                       value="<?=@$master_dn?>" id="txtDNMaster" size="50" />
                <br /><br />

                <label for='txtDNSearch'>DN Search LDAP*: </label>
                <input name="txtDNSearch" type="text"
                       value="<?=@$search_dn?>" id="txtDNSearch" size="50" />
                <br /><br />

                <label for='txtSenhaLDAP'>Senha LDAP*: </label>
                <input name="txtSenhaLDAP" type="password" 
                       value="<?=@$senha_ldap?>" id="txtSenhaLDAP" size="50" />
                <br /><br />
                </p>
            </fieldset>
            </p>
            <p class="aviso">* Campos Obrigat&oacute;rios</p>
         </div>

        <div id="tab2" class="tab_content">
            <br />
            <p>
                <label for='txtTamanhoUpload'>Tamanho máximo de Upload*: </label>
                <select name="txtTamanhoUpload">
                    <option value="1048576" <?=(@$max_size == '1048576') ? " selected " : "";?> >1Mb</option>
                    <option value="2097152" <?=(@$max_size == '2097152') ? " selected " : "";?> >2Mb</option>
                    <option value="4194304" <?=(@$max_size == '4194304') ? " selected " : "";?> >4Mb</option>
                </select>
                <br /><br />

                <label for='txtCaminhoUpload'>Caminho de Upload*: </label>
                <input name="txtCaminhoUpload" type="text" 
                       value="<?=@$upload_path?>"
                       id="txtCaminhoUpload" size="50" />
                <br /><br />

                <label for='txtExtensoesUpload'>Extensões Permitidas:*: </label>
                <input name="txtExtensoesUpload" type="text"
                       value="<?=@$allowed_types?>"
                       id="txtExtensoesUpload" size="50" />
                <span class="instrucao_campo">separar as extensões com o caracter '|' </span>
                <br /><br />
            </p>
            <p class="aviso">* Campos Obrigat&oacute;rios</p>
        </div>


        <div id="tab3" class="tab_content">            
            <br />
            <p>
                <label for="txtEmailPadrao">E-mail padrão do sistema:</label>
                <input name="txtEmailPadrao" 
                       id="txtEmaiPadrao" type="text"
                       value="<?=@$email_from_address?>" 
                       size="50"/>
                <br/><br/>
                
                <label for="txtServidorDNS">IP Servidor DNS:</label>
                <input name="txtServidorDNS" 
                       id="txtServidorDNS" type="text"
                       value="<?=@$servidor_dns?>" 
                       size="10"/>
                <br/>
                <span class="instrucao_campo">O e-mail padrão e o IP do Servidor DNS são necess&aacute;rios para testes de validade de e-mail e ambos devem existir e estar ativos.</span>
                <br/><br/>
                
                
                <label for='txtMsgNotificacao'>Texto Mensagem de Notificação*: </label>
                <textarea name="txtMsgNotificacao" rows="4" cols="40"><?=@$msg_notificacao?></textarea>
                <br /><br />

                <label for='txtMsgAlteracaoStatus'>Texto Mensagem de Validação/Revogação/Teste*: </label>
                <textarea name="txtMsgAlteracaoStatus"
                          rows="4" cols="40"><?=@$msg_alteracao_status?></textarea>
                <br /><br />
                
                <label for='txtMsgNotifQualidade'>Texto Mensagem de Notificação de Qualidade*: </label>
                <textarea name="txtMsgNotifQualidade"
                          rows="4" cols="40"><?=@$msg_notificacao_qualidade?></textarea>
                <br /><br />
            </p>
            <p class="aviso">* Campos Obrigat&oacute;rios</p>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?=form_close()?>