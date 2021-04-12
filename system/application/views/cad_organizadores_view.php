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
 * View de Cadastro de organizadores
 *
 * Utilizada para Entrada de Dados de organizadores
 *
 * @author     Sergio Jr <sergiojunior@unipampa.edu.br
 *
 * @copyright Universidade Federal do Pampa - NTIC Campus Alegrete 2010
 *
 */
?>

<script type="text/javascript" src='<?= base_url()?>system/application/views/includes/js/organizadores.js'></script>

<?php echo validation_errors('<div class="error">','</div>'); ?>
<?php $atributos = array('onSubmit' => 'return validaFornecedor()')?>
<?=form_open_multipart(base_url().'organizadores/salvar', $atributos);?>
<div class="botoes_left">
    <button type="submit" id="botao_salvar" name="botao_salvar">
        <img src='<?= base_url()?>system/application/views/includes/images/salvar_32.png'
             alt="Salvar"/><br>&nbsp;&nbsp;Salvar&nbsp;&nbsp;
    </button>

    <button onclick="parent.location='<?= base_url()?>organizadores/cancelar'" type="button" id="botao_cancelar">
        <img src='<?= base_url()?>system/application/views/includes/images/cancel_32.png'
             alt="Novo"/><br>Cancelar
    </button>
</div>


<div class="titulo_right"><h1><?=$titulo_pagina?></h1></div>
<div class="center_table">
    <div class="form_left">
            <br />
            <fieldset>
            <legend><b>Dados Pessoais</b></legend>
            <p>
                <label for='txtId'>C&oacute;digo:</label>
                <input name="txtId" class="disabled_input" value="<?=@$organizador->id_organizador?>"
                       type="text" id="txtId" size="10" readonly="readonly"  />
                <br /><br />

                <label for='txtNome'>Nome*: </label>
                <input name="txtNome" type="text" value="<?=@$organizador->nm_organizador?>"
                       id="txtNome" size="50" />
                <br /><br />

                <label for='txtDocumento'>Documento*: </label>
                <input name="txtDocumento" type="text" value="<?=@$organizador->nr_documento?>"
                       id="txtDocumento" size="20" onKeyUp="somenteNumero(this)"
                       maxlength="20" />
                <span class="instrucao_campo">digite somente números</span>
                <br /><br />

                <label for='txtEmail'>E-mail*: </label>
                <input name="txtEmail" type="text"
                       value="<?=@$organizador->de_email?>"
                       id="txtEmail" size="50" />
                <br /><br />

                <label for='txtTelefone'>Telefone*: </label>
                <input name="txtTelefone" type="text"
                       value="<?=@$organizador->nr_telefone?>"
                       id="txtTelefone" size="20" 
                       maxlength="20"/>
                <br /><br />

                <label for='txtDtInclusao'>Data de Inclusão: </label>
                <input name="txtDtInclusao" class="disabled_input"
                       type="text" value="<?=dataBR(@$organizador->dt_inclusao)?>"
                       id="txtDtInclusao" size="10" readonly="readonly"/>
                <br /><br />

                <label for='txtDtAlteracao'>Data de Alteração: </label>
                <input name="txtDtAlteracao" class="disabled_input"
                       type="text" value="<?=dataBR(@$organizador->dt_alteracao)?>"
                       id="txtDtAlteracao" size="10" readonly="readonly"/>
                <br /><br />

            </p>
            </fieldset>

            <br />
            <fieldset>
            <legend><b>Dados de Acesso</b></legend>
            <p>
                <label for='txtUsuario'>Usuario*: </label>
                <input name="txtUsuario" type="text"
                       value="<?=@$organizador->de_usuario?>"
                       id="txtUsuario" size="20" />
                <br /><br />
                                
                <? if (($this->config->item('tipo_de_autenticacao')!='ldap')
                        && (($operacao == 'novo') 
                            || ($this->session->userdata('admin') == '1') 
                            || ($this->session->userdata('uid') == @$organizador->de_usuario))): // apenas administradores e o proprio usuario podem alterar senha ?>
                
                    <label for='txtSenha'>Senha: </label>
                    <input name="txtSenha" type="password"
                           id="txtSenha" size="20" 
                           maxlength="20" />                
                    <span class="instrucao_campo"><b>NOTA:</b>Use até 20 caracteres. Deixe a senha em branco caso não queira alterá-la no banco de dados.</span>
                    <br /><br />                
                <? endif; ?>
                    
                <? if ($this->session->userdata('admin') == '1'): ?>
                    <label for='txtFlAdmin'>Adm. Sistema*: </label>
                    <select name='txtFlAdmin' id='txtFlAdmin'>                        
                        <option value='S' <?= (@$organizador->fl_admin == 'S') ? 'selected' : ''; ?>  >Sim</option>
                        <option value='N' <?= (@$organizador->fl_admin == 'N') ? 'selected' : ''; ?>  >Não</option>
                        <option value='L' <?= (@$organizador->fl_admin == 'L') ? 'selected' : ''; ?>  >Limitado</option>
                    </select>                
                <br /><br />
                
                <label for='txtFlControlador'>Controlador de Qualidade Global*: </label>
                <select name='txtFlControlador' id='txtFlControlador'>
                    <option value='S' <?= (@$organizador->fl_controlador == 'S') ? 'selected' : ''; ?>  >Sim</option>
                    <option value='N' <?= (@$organizador->fl_controlador == 'N') ? 'selected' : ''; ?>  >Não</option>
                </select>
                <br /><br />
                <? else: //Administradores limitados nao podem atribuir administradores/controladores globais ?> 
                    <input type="hidden" name="txtFlAdmin" value="N"/>
                    <input type="hidden" name="txtFlControlador" value="N"/>
                <? endif; ?>
            </p>
            </fieldset>

        <p class="aviso">* Campos Obrigat&oacute;rios</p>
    </div>
    <div class="clear"></div>
</div>
<?=form_close()?>