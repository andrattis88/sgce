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
 * View de Cadastro de Modelos de Certificados
 *
 * Utilizada para Entrada de Dados de modelos_certificados
 *
 * @author     Pedro Conrad Jr. <pedro.junior@unipampa.edu.br>
 * @author     Sergio Jr <sergiojunior@unipampa.edu.br
 *
 * @copyright Universidade Federal do Pampa - NTIC Campus Alegrete 2010
 *
 */
?>

<link rel="stylesheet" href='<?= base_url()?>system/application/views/includes/css/tabs.css' type="text/css" />
<script type="text/javascript" src='<?= base_url()?>system/application/views/includes/js/tabs.js'></script>

<?php    // Inicializacao de editor de texto ?>
<script type="text/javascript" 
        src="<?= base_url()?>system/application/views/includes/js/tiny_mce/tiny_mce.js">
</script>

<script type="text/javascript"
        src="<?= base_url()?>system/application/views/includes/js/editor_texto.js">
</script>
<?php // Fim de editor de texto ?>

<?php echo validation_errors('<div class="error">','</div>'); ?>
<?=form_open_multipart(base_url().'modelos_certificados/salvar');?>
<div class="botoes_left">
    <button type="submit" id="botao_salvar" name="botao_salvar">
        <img src='<?= base_url()?>system/application/views/includes/images/salvar_32.png'
             alt="Salvar"/><br>&nbsp;&nbsp;Salvar&nbsp;&nbsp;
    </button>

    <?php if ($operacao!='novo'): ?>
        <button onclick="parent.location='<?= base_url()?>modelos_certificados/verModelo/<?=@$modelo_certificado->id_certificado_modelo?>'" type="button" id="botao_ver">
            <img src='<?= base_url()?>system/application/views/includes/images/search_32.png'
                 alt="Visualizar"/><br>Visualizar
        </button>
    <?php endif; ?>
    
    <button onclick="parent.location='<?= base_url()?>modelos_certificados/cancelar'" type="button" id="botao_cancelar">
        <img src='<?= base_url()?>system/application/views/includes/images/cancel_32.png'
             alt="Cancelar"/><br>Cancelar
    </button>

</div>


<div class="titulo_right"><h1><?=$titulo_pagina?></h1></div>
<div class="center_table">
    <ul class="tabs">
            <li><a href="#tabDescricao">Descrição</a></li>
            <li><a href="#tabTextoFrente">Texto Frente</a></li>
            <li><a href="#tabTextoVerso">Texto Verso</a></li>
            <li><a href="#tabImagens">Imagem de Fundo</a></li>
            <li><a href="#tabLayoutFrente">Layout</a></li>
        </ul>
    <div class="tab_container">
        <div id="tabDescricao" class="tab_content">
            <p align="left">
                <label for='txtId'>C&oacute;digo:</label>
                <input name="txtId" class="disabled_input" value="<?=@$modelo_certificado->id_certificado_modelo?>"
                       type="text" id="txtId" size="10" readonly="readonly"  />
                <br /><br />

                <label for='txtNome'>Nome*: </label>
                <input name="txtNome" type="text" value="<?=@$modelo_certificado->nm_modelo?>" id="txtNome" size="50" />
                <br /><br />

                <label for='txtEvento'>Evento*: </label>
                <select name="txtEvento">
                    <option value="">Selecione um evento</option>
                        <?php foreach ($eventos as $evento):
                            if (@$modelo_certificado->id_evento == @$evento->id_evento) {
                                $sel = " SELECTED ";
                            }
                            else {$sel = " ";}
                        ?>
                        <option <?=$sel?> value="<?=@$evento->id_evento;?>"><?=@$evento->nm_evento?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <br /><br />
        </div>
        <div id="tabTextoFrente" class="tab_content">
            <p>

                <label for='txtTitulo'>Título: </label>
                <input name="txtTitulo" type="text" value="<?=@$modelo_certificado->de_titulo?>" id="txtTitulo" size="50" />
                <br /><br />

                <label for='txtCarga'>Carga Horária: </label>
                <input name="txtCarga" type="text" value="<?=@$modelo_certificado->de_carga?>" id="txtCarga" size="50" />
                <br /><br />

                <label for='txtTexto'>Texto*: </label>
                <textarea class="ckeditor" name="txtTexto" id="txtTexto" rows="4" cols="39"><?php if ($operacao=='novo') { echo "Exemplo: Certificamos que NOME_PARTICIPANTE participou do evento NOME_EVENTO, realizado em DATA_EVENTO. "; }?><?=@$modelo_certificado->de_texto?></textarea>
                <br /><br />

                <label for='txtInstrucoes'>Instruções de Importação:* </label>
                <textarea class="ckeditor" name="txtInstrucoes" id="txtInstrucoes" rows="4" cols="39"><?php if ($operacao=='novo') { echo "O arquivo importado deve estar no formato CSV, com a separação dos campos por ponto-e-vírgula (;) e ter, obrigatoriamente, os campos NOME_PARTICIPANTE e EMAIL_PARTICIPANTE. Poderá também conter outros campos, desde que formados por palavras maiúsculas separadas pelo caractere sublinhado (underline), como no texto de exemplo. Evite usar o ponto-e-vírgula junto ao nome de um campo dentro do texto do certificado para evitar problemas na importação de dados. As colunas referenciadas no texto do modelo de certificado devem ser descritos nas instruções para importação."; }?><?=@$modelo_certificado->de_instrucoes?></textarea>
                <br /><br />

                <label for='txtId'>Data Inclusão:</label>
                <input name="txtDtInclusao" class="disabled_input" value="<?=@dataBR($modelo_certificado->dt_inclusao)?>"
                       type="text" id="txtId" size="10" readonly="readonly"  />
                <br /><br />

                <label for='txtId'>Data Alteração:</label>
                <input name="txtDtAlteracao" class="disabled_input" value="<?=@dataBR($modelo_certificado->dt_alteracao)?>"
                       type="text" id="txtId" size="10" readonly="readonly"  />
                <br /><br />

            </p>
        </div>
        <div id="tabTextoVerso" class="tab_content">
            <p align="left">
               <label for='txtTituloVerso'>Título: </label>
                <input name="txtTituloVerso" type="text" value="<?=@$modelo_certificado->de_titulo_verso?>" id="txtTituloVerso" size="50" />
                <br /><br />

                <label for='txtTexto'>Texto Verso: </label>
                <textarea class="ckeditor" name="txtTextoVerso" id="txtTextoVerso" rows="4" cols="39"><?=@$modelo_certificado->de_texto_verso?></textarea>
                <br /><br />
            </p>
        </div>
        <div id="tabImagens" class="tab_content">
          <p align="left">
            <label for="modoArquivo">Imagem de Fundo:</label>
            <select name="modoArquivo">
                <?php if(@$modelo_certificado->nm_fundo):?>                
                    <option value="atual">Usar imagem atual</option>
                <?php endif; ?>
                <option value="">Nenhuma imagem</option>
                <option value="upload">Usar um arquivo neste computador</option>                
            </select>
            &nbsp;
            <?php if(@$modelo_certificado->nm_fundo):?>
                <?php $fileName = base_url().$this->config->item('upload_path').'modelos/'.$modelo_certificado->nm_fundo;?>
                    <?php if(@file_get_contents($fileName, true)): ?>
                            <a href="<?=$fileName?>">
                            <img src='<?= base_url()?>system/application/views/includes/images/salvar_16.png'
                                 border="0"
                                 alt="Este modelo já possui uma imagem associada. Clique aqui para baixar o arquivo"
                                 title="Este modelo já possui uma imagem associada. Clique aqui para baixar o arquivo" />
                            </a>
                    <?php endif;?>
                    <input type="hidden" name="nomeModelo" value="<?=@$modelo_certificado->nm_fundo?>"/>
            <?php endif;?>


                <div id="upload">
                    <p align="left">
                        <label for='txtArquivo'>Arquivo: </label>

                        <input name="txtArquivo" type="file" id="txtArquivo" size="50"
                               title='<?= "A imagem de fundo deverá ter 1106 pixels de altura e 756 pixels de largura, correspondendo a uma folha A4 na orientação de paisagem. O arquivo enviado deverá estar, preferencialmente em formato JPG." ?>' />
                    </p>
                    <p>
                        <b>Atenção:</b>
                            A imagem de fundo deverá ter 1106 pixels de altura e 756 pixels de largura, correspondendo a uma folha A4 na orientação de paisagem. O arquivo enviado deverá estar, preferencialmente em formato JPG.
                    </p>
                    </p>
                </div>
        </div>
        <div id="tabLayoutFrente" class="tab_content">
            <br />
            <fieldset>
            <legend><b>Estilo</b></legend>
                <p>
                    <label for='txtEstilo'>Estilo*: </label>
                    <select name="txtEstilo" class="combo">                        
                        <option <?php if (trim(@$modelo_certificado->nm_fonte) == 'arial')
                                echo " SELECTED "; ?> value="arial">Arial</option>
                        <option <?php if (trim(@$modelo_certificado->nm_fonte) == 'times')
                                echo " SELECTED "; ?> value="times">Times New Roman</option>
                    </select>
                </p>
            </fieldset>
            
            <fieldset>
            <legend><b>Título</b></legend>
                <p>
                    <label for="txtDistanciaTopoTitulo">Distância do Topo ao Título:</label>
                    <input type="text" name="txtDistanciaTopoTitulo" size="10"
                           id="txtDistanciaTopoTitulo" value="<?=@$modelo_certificado->de_posicao_titulo ?>">
                           <span class="instrucao_campo">(%) sugestão: 15</span>
                    <br /><br />

                    <label for="txtSelAlinSecaoTitulo">Alinhamento da Seção:</label>
                    <select name="txtSelAlinSecaoTitulo" class="combo">
                        <option value="left" <?=@$modelo_certificado->de_alinhamento_titulo   == 'left' ? ' SELECTED ': ' '?>>Alinhar seção à esquerda</option>
                        <option value="center" <?=@$modelo_certificado->de_alinhamento_titulo == 'center' ? ' SELECTED ': ' '?>>Seção centralizada</option>
                        <option value="right" <?=@$modelo_certificado->de_alinhamento_titulo  == 'right' ? ' SELECTED ': ' '?>>Alinhar seção à direita</option>
                    </select>
                    <br /><br />

                    <label for="txtSelAlinTextoTitulo">Alinhamento do Título:</label>
                    <select name="txtSelAlinTextoTitulo" class="combo">
                        <option value="left" <?=@$modelo_certificado->de_alin_texto_titulo    == 'left' ? ' SELECTED ': ' '?>>Alinhar texto à esquerda</option>
                        <option value="center" <?=@$modelo_certificado->de_alin_texto_titulo  == 'center' ? ' SELECTED ': ' '?>>Texto centralizado</option>
                        <option value="right" <?=@$modelo_certificado->de_alin_texto_titulo   == 'right' ? ' SELECTED ': ' '?>>Alinhar texto à Direita</option>
                        <option value="justify" <?=@$modelo_certificado->de_alin_texto_titulo == 'justify' ? ' SELECTED ': ' '?>>Texto justificado</option>
                    </select>
                    <br /><br />

                    <label for="txtSelCorTitulo">Cor do Título:</label>
                    <select name="txtSelCorTitulo" class="combo">
                        <option value="black" <?=@$modelo_certificado->de_cor_texto_titulo  == 'black' ? ' SELECTED ': ' '?>>Cor: Preto</option>
                        <option value="white" <?=@$modelo_certificado->de_cor_texto_titulo  == 'white' ? ' SELECTED ': ' '?>>Cor: Branco</option>
                        <option value="#CCC" <?=@$modelo_certificado->de_cor_texto_titulo   == '#CCC' ? ' SELECTED ': ' '?>>Cor: Cinza claro</option>
                        <option value="#999" <?=@$modelo_certificado->de_cor_texto_titulo   == '#999' ? ' SELECTED ': ' '?>>Cor: Cinza escuro</option>
                    </select>
                    <br/> <br/>
                    <label for="txtTamanhoTitulo">Tamanho da Fonte:</label>
                    <select name="txtTamanhoTitulo" class="combo">
                        <option value="30" <?=@$modelo_certificado->de_tamanho_titulo  == '30' ? ' SELECTED ': ' '?>>30pt</option>
                        <option value="32" <?=@$modelo_certificado->de_tamanho_titulo  == '32' ? ' SELECTED ': ' '?>>32pt</option>
                        <option value="36" <?=@$modelo_certificado->de_tamanho_titulo   == '36' ? ' SELECTED ': ' '?>>36pt</option>
                        <option value="40" <?=@$modelo_certificado->de_tamanho_titulo   == '40' ? ' SELECTED ': ' '?>>40pt</option>
                    </select>
                </p>
                <br />
            </fieldset>

            <fieldset>
            <legend><b>Texto</b></legend>
                <p>
                    <label for="txtDistanciaTopoTexto">Distância do Título ao Texto:</label>
                    <input type="text" size="10"
                           name="txtDistanciaTopoTexto" id="txtDistanciaTopoTexto" value="<?=@$modelo_certificado->de_posicao_texto ?>">
                           <span class="instrucao_campo">(%) sugestão: -10</span>
                    <br /><br />

                    <label for="txtSelAlinSecaoTexto">Alinhamento da Seção:</label>
                    <select name="txtSelAlinSecaoTexto" class="combo">
                             <option value="left" <?=@$modelo_certificado->de_alinhamento_texto == 'left' ? ' SELECTED ': ' '?>>Alinhar seção à esquerda</option>
                        <option value="center" <?=@$modelo_certificado->de_alinhamento_texto    == 'center' ? ' SELECTED ': ' '?>>Seção centralizada</option>
                        <option value="right" <?=@$modelo_certificado->de_alinhamento_texto     == 'right' ? ' SELECTED ': ' '?>>Alinhar seção à direita</option>
                    </select>
                    <br /><br />

                    <label for="txtSelAlinTextoTexto">Alinhamento do Texto:</label>
                    <select name="txtSelAlinTextoTexto" class="combo">
                        <option value="left" <?=@$modelo_certificado->de_alin_texto_texto    == 'left' ? ' SELECTED ': ' '?>>Alinhar texto à esquerda</option>
                        <option value="center" <?=@$modelo_certificado->de_alin_texto_texto  == 'center' ? ' SELECTED ': ' '?>>Texto centralizado</option>
                        <option value="right" <?=@$modelo_certificado->de_alin_texto_texto   == 'right' ? ' SELECTED ': ' '?>>Alinhar texto à Direita</option>
                        <option value="justify" <?=@$modelo_certificado->de_alin_texto_texto == 'justify' ? ' SELECTED ': ' '?>>Texto justificado</option>
                    </select>
                    <br /><br />

                    <label for="txtSelCorTexto">Cor do Texto:</label>
                    <select name="txtSelCorTexto" class="combo">
                        <option value="black" <?=@$modelo_certificado->de_cor_texto_texto  == 'black' ? ' SELECTED ': ' '?>>Cor: Preto</option>
                        <option value="white" <?=@$modelo_certificado->de_cor_texto_texto  == 'white' ? ' SELECTED ': ' '?>>Cor: Branco</option>
                        <option value="#CCC" <?=@$modelo_certificado->de_cor_texto_texto   == '#CCC' ? ' SELECTED ': ' '?>>Cor: Cinza claro</option>
                        <option value="#999" <?=@$modelo_certificado->de_cor_texto_texto   == '#999' ? ' SELECTED ': ' '?>>Cor: Cinza escuro</option>
                    </select>
                    <br/><br/>

                    <label for="txtTamanhoTexto">Tamanho da Fonte:</label>
                    <select name="txtTamanhoTexto" class="combo">
                        <option value="12" <?=@$modelo_certificado->de_tamanho_texto   == '12' ? ' SELECTED ': ' '?>>12pt</option>
                        <option value="14" <?=@$modelo_certificado->de_tamanho_texto   == '14' ? ' SELECTED ': ' '?>>14pt</option>
                        <option value="16" <?=@$modelo_certificado->de_tamanho_texto   == '16' ? ' SELECTED ': ' '?>>16pt</option>
                        <option value="18" <?=@$modelo_certificado->de_tamanho_texto   == '18' ? ' SELECTED ': ' '?>>18pt</option>
                    </select>
                    <br />
                </p>
            </fieldset>
            
            <fieldset>
            <legend><b>Rodapé - Validação</b></legend>
                <p>
                    <label for="txtDistanciaTopoValidacao">Distância do Texto ao Rodapé:</label>
                    <input type="text" size="10"
                           name="txtDistanciaTopoValidacao" id="txtDistanciaTopoValidacao" value="<?=@$modelo_certificado->de_posicao_validacao ?>">
                    <span class="instrucao_campo">(%) sugestão: 0</span>
                    <br /><br />
                   
                    <label for="txtSelAlinSecaoValidacao">Alinhamento da Seção:</label>
                    <select name="txtSelAlinSecaoValidacao" class="combo">
                        <option value="left" <?=@$modelo_certificado->de_alinhamento_validacao   =='left' ? ' SELECTED ': ' '?>>Alinhar seção à esquerda</option>
                        <option value="center" <?=@$modelo_certificado->de_alinhamento_validacao =='center' ? ' SELECTED ': ' '?>>Seção centralizada</option>
                        <option value="right" <?=@$modelo_certificado->de_alinhamento_validacao  =='right' ? ' SELECTED ': ' '?>>Alinhar seção à direita</option>
                    </select>
                    <br /><br />

                    <label for="txtSelAlinTextoValidacao">Alinhamento do Texto:</label>
                    <select name="txtSelAlinTextoValidacao" class="combo">
                        <option value="left" <?=@$modelo_certificado->de_alin_texto_validacao    == 'left' ? ' SELECTED ': ' '?>>Alinhar texto à esquerda</option>
                        <option value="center" <?=@$modelo_certificado->de_alin_texto_validacao  == 'center' ? ' SELECTED ': ' '?>>Texto centralizado</option>
                        <option value="right" <?=@$modelo_certificado->de_alin_texto_validacao   == 'right' ? ' SELECTED ': ' '?>>Alinhar texto à Direita</option>
                        <option value="justify" <?=@$modelo_certificado->de_alin_texto_validacao == 'justify' ? ' SELECTED ': ' '?>>Texto justificado</option>
                    </select>
                    <br /><br />

                    <label for="txtSelCorValidacao">Cor do Texto:</label>
                    <select name="txtSelCorValidacao" class="combo">
                        <option value="black" <?=@$modelo_certificado->de_cor_texto_validacao  == 'black' ? ' SELECTED ': ' '?>>Cor: Preto</option>
                        <option value="white" <?=@$modelo_certificado->de_cor_texto_validacao  == 'white' ? ' SELECTED ': ' '?>>Cor: Branco</option>
                        <option value="#CCC" <?=@$modelo_certificado->de_cor_texto_validacao   == '#CCC' ? ' SELECTED ': ' '?>>Cor: Cinza claro</option>
                        <option value="#999" <?=@$modelo_certificado->de_cor_texto_validacao   == '#999' ? ' SELECTED ': ' '?>>Cor: Cinza escuro</option>
                    </select>
                    <br />
                </p>
            </fieldset>

         </div>
    </div>
            
        <p class="aviso">* Campos Obrigat&oacute;rios</p>        
    <div class="clear"></div>    
</div>
<?=form_close()?>