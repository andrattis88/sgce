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
 * Controller para a funcao de Migracao
 * @author Pedro Jr     <pedro.junior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 */

class Migrar extends Controller {

    /**
     * Construtor da Classe.
     *
     * Inicializa helpers e bibliotecas do CodeIgniter e verifica se o usuario
     * tem permissao para abrir o controller.
     *
     */
    function migrar() {
        parent::Controller();        
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('data');
        $this->load->helper('replace_ascii');        
        $this->load->library('session');
        $this->load->library('pagination');
        $this->lang->load('msg');
        $this->config->load_db_items();
        
        $this->load->library('Gerenciador_de_acesso');
        //$this->gerenciador_de_acesso->usuarioAuth();        
    }
    
    function index() {
        echo "Iniciando migracao de dados...";
        $this->load->model('modelos_certificado_model');
        $modelos = $this->modelos_certificado_model->listarModelosCertificado();
        foreach ($modelos as $modelo) {
            $idModelo = $modelo->id_certificado_modelo;
            echo "<br>Migrando modelo $idModelo";
            $this->migrarModelo($idModelo);
            echo "<br>Modelo $idModelo Migrado!<br>";
        }
        echo "<br>Tarefa completada";
    }

    
    
    function migrarModelo($idModelo) {   
        $this->load->helper('diff_helper');
        $this->load->model('modelos_certificado_model');
        $this->load->model('certificados_model');
        if (!$idModelo)
            return false;
        
        $dadosModelo      = $this->modelos_certificado_model->getById($idModelo);
        $certsModelo      = $this->certificados_model->listarCertificadosDoModelo($idModelo);  
        
        // Pega textos Originais
        $textoModeloFrente      = $dadosModelo->de_texto;
        $textoModeloVerso       = $dadosModelo->de_texto_verso;
        $regs=0;
        foreach($certsModelo as $certificado) {                        
            $dados = array();
            $diferencasFrente = arrayDiff($textoModeloFrente, $certificado->de_texto_certificado);
            $diferencasVerso  = arrayDiff($textoModeloVerso, $certificado->de_complementar);
            
            $saidaFrente = serialize($diferencasFrente);
            $saidaVerso  = serialize($diferencasVerso);
            
            echo "<br>&gt;&gt;Migrando registro ".$certificado->id_certificado."...";
            // inserir migração aqui...
            $dados['id_certificado']   = $certificado->id_certificado;
            $dados['de_campos_frente'] = $saidaFrente;
            $dados['de_campos_verso']  = $saidaVerso;
            $this->certificados_model->migraCertificado($dados);
            // fim da migração
            $regs++;
        }
        echo "<br>$regs registros migrados.";
    }
    
    /**
     * Obtem colunas do texto no padrao.
     * @param type $texto
     * @return type 
     */
    
    function getColunasTexto($texto) {
        $pattern     = '/\b[A-Z]+_+(_*[A-Z]*)+\b/';

        if(preg_match_all($pattern, $texto, $matches))
            return $matches[0];
        else
            return null;
    
    }
}
?>
