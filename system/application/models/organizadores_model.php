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
 * Model organizadores de eventos
 *
 * @author Sergio Junior     <sergiojunior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 * @var $table   - variavel que indica a tabela padrao para operacao.
 * @var $schema  - variavel para configuracao do esquema de banco de dados.
 *
 */

class Organizadores_model extends Model {
    public $table = 'organizadores';

    /**
     * Construtor da Classe
     *
     * Inicializa o Model de banco de dados
     */

    function Organizadores_model() {
        parent::Model();
    }

    /**
     * Metodo Insert
     *
     * Recebe os dados que serao inseridos no banco de dados e faz a gravacao de
     * registro na tabela apropriada
     *
     * @param array $dados
     */
    function insert($dados) {
        $data["nm_organizador"]      = $dados['nm_organizador'];
        $data["nr_documento"]        = $dados['nr_documento'];
        $data["de_email"]            = $dados['de_email'];
        $data["nr_telefone"]         = $dados['nr_telefone'];
        $data["de_usuario"]          = $dados['de_usuario'];
        if(@$dados['de_senha'])
            $data["de_senha"]        = md5($dados['de_senha']);
        
        $data["fl_admin"]            = $dados['fl_admin'];
        $data["fl_controlador"]      = $dados['fl_controlador'];
        
        $data["dt_inclusao"]         = date('Y-m-d');
        $data["dt_alteracao"]        = date('Y-m-d');
        return $this->db->insert($this->table, $data);
    }

    /**
     * Metodo Update
     *
     * Recebe os dados que serao atualizados no banco de dados e faz a gravacao de
     * registro na tabela apropriada
     *
     * @param array $dados
     */
    function update($dados) {
        $id                          = $dados['id_organizador'];
        $data["nm_organizador"]      = $dados['nm_organizador'];
        $data["nr_documento"]        = $dados['nr_documento'];
        $data["de_email"]            = $dados['de_email'];
        $data["nr_telefone"]         = $dados['nr_telefone'];
        $data["de_usuario"]          = $dados['de_usuario'];
        if(@$dados['de_senha'])
            $data["de_senha"]        = md5($dados['de_senha']);
        
        $data["fl_admin"]            = $dados['fl_admin'];
        $data["fl_controlador"]      = $dados['fl_controlador'];
        
        $data["dt_alteracao"]        = date('Y-m-d');

        $this->db->where('id_organizador',$id);

        if($this->db->update($this->table, $data))
            return true;
        else
            return false;
    }

    /**
     * Remove o registro
     * @param  Integer $idOrganizador
     * @return Boolean
     */
    function delete($idOrganizador) {
        $sql  = "SELECT id_organizador
                 FROM   organizadores_evento
                 WHERE  id_organizador = $idOrganizador ";

        $origem = $this->db->query($sql);
        $dados  = $origem->result();

        if(!$dados) {
            $this->db->where('id_organizador', $idOrganizador);
            if($this->db->delete($this->table))
                return true;
             else
                return false;
        } else {
            return false;
        }
    }


    /**
     * Faz a busca de um registro no banco de dados e retorna seus dados.
     * @param Integer $id - ID a buscar.
     * @return Array      - Dados recuperados.
     */
    function getById($id) {
        $record ='';
        if (isset($id)) {
            $this->db->where('id_organizador',$id);
            $record = $this->db->get($this->table);
        }
        $dados = $record->result();
        return $dados[0];
    }

    function getByUser($usuario) {
        $record=null;
        $retorno = null;
        if (isset($usuario)) {
            $this->db->where('de_usuario',$usuario);
            $record = $this->db->get($this->table);            
            $dados = $record->result();
            $retorno=$dados[0];
        }        
        return $retorno;
    }

    /**
     * Recupera um ou mais registros do banco de dados conforme os criterios
     * especificados.
     * @param Array $where  - Especifica os criterios de busca
     * @param String $order - Especifica criterios de ordenacao
     * @param Integer $min  - Especifica quantidade minima de registros.
     * @param Integer $max  - Especifica quantidade maxima de registros.
     * @return Array        - Dados recuperados.
     */
    function search($key, $tipo, $maximo, $inicio=0,  $ordem=null, $tipoOrdem=null) {
        $return = null;
        if($key)
            $this->filtrarPesquisa($key, $tipo);


        if (($ordem) && !is_numeric($ordem)) {

            if ($ordem == 'codigo')
                $ordem = 'id_organizador';

            if ($ordem == 'nome')
                $ordem = 'nm_organizador';

            $this->db->orderby($ordem, $tipoOrdem);
        }

        $query = $this->db->get($this->table, $maximo, $inicio);
        return $query->result();

    }


    /**
     * Obtem o total de registros da tabela
     * @param String $key  - valor a ser pesquisado
     * @param String $tipo - tipo do valor
     * @return Integer - total de registros
     */
    function getTotal($key, $tipo) {
        $return = null;
        if($key)
            $this->filtrarPesquisa($key, $tipo);
        return $this->db->count_all_results($this->table);
    }

    /**
     * Filtra os registros pesquisados
     * @param String $key  - valor a ser pesquisado
     * @param String $tipo - tipo do valor
     */
    function filtrarPesquisa($key, $tipo) {
        if (($tipo == "C") && (is_numeric($key)))
            $this->db->where('id_organizador',$key);

        if ($tipo == "D")
            $this->db->like('nm_organizador',$key);
    }


    /**
     * Listagem completa em ordem alfabetica dos organizadores
     * @return Array
     */
    function listarOrganizadores() {
        $this->db->select('id_organizador, nm_organizador');
        $this->db->orderby('nm_organizador');
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * Obtem o registro pelo login usuario e pela senha. Metodo utilizado
     * na operacao de login.
     *
     * @param String $usuario - login do usuario
     * @param String $senha   - senha criptografada
     * @return Object         - retorna um objeto com dados do usuario; caso
     * nao encontrar o usuario, retorna nulo;
     */
    function getByLoginSenha($usuario, $senha) {   
        $this->db->where('de_usuario', $usuario);
        $this->db->where('de_senha', $senha);
        $query  = $this->db->get('organizadores');
        $result = $query->result();

        if($result)
            return $result[0];
        else
            return null;
    }

   /**
     * Insere uma solicitacao de recuperacao de senha na table rsenha_organizador, remo-
     * vendo a solicitacao anteriormente armazenada.
     *
     * @param String  $documento    - Documento
     * @param String  $senha        - Senha Aberta - gerada aleatoriamente
     * @param Integer $idOrganizador
     * @param String  $email        - Email do organizador
     * @return String               - Devolve o id de acesso - chave utilizada
     *                                no link enviado por email para efitivacao da senha
     */
    function insereSolicitacaoRecSenha($documento, $senha, $idOrganizador, $email) {
        $idAcesso               = $documento . date('Y-m-d-h-i-s');
        $data['id_organizador'] = $idOrganizador;
        $data['de_senha_md5']   = md5($senha);
        $data['id_acesso']      = md5($idAcesso);
        $data['dt_inclusao']    = date('Y-m-d');
        $data['dt_alteracao']   = date('Y-m-d');

        //remove a solicitacao de recuperacao de senha anterior
        $removeAnterior = true;
        $this->db->where('id_organizador', $idOrganizador);
        $removeAnterior = $this->db->delete('rsenha_organizador');

        if($this->db->insert('rsenha_organizador', $data) && $removeAnterior) {
            return $data['id_acesso'];
        } else {
            return null;
        }
        return $retorno;
    }

    /**
     * Apartir do idAcesso, enviado no link de alteracao de senha recebido por email,
     * faz a alteracao da senha do organizador
     *
     * @param Integer $idAcesso
     * @return Boolean
     */
    function alterarSenha($idAcesso) {
        //procura a solicitacao de senha
        $this->db->where('id_acesso', $idAcesso);
        $query = $this->db->get('rsenha_organizador');
        $result = $query->result();

        if(!$result)
            return false;

        $idOrganizador = $result[0]->id_organizador;
        $novaSenha   = $result[0]->de_senha_md5;

        $data["de_senha"] = $novaSenha;
        $this->db->where('id_organizador', $idOrganizador);

        if($this->db->update($this->table, $data)) {
            $this->db->where('id_organizador', $idOrganizador);
            $removeAtutal = $this->db->delete('rsenha_organizador');
            return true;
        } else {
            return false;
        }
    }


    /**
     * Busca o organizador pelo documento que o identifica
     *
     * @param  String $documento
     * @return Object
     */
    function getByDocumento($documento) {
        $this->db->where('nr_documento', $documento);
        $query = $this->db->get($this->table);
        $result = $query->result();
        if($result)
            return $result[0];
        else
            return null;
    }



    
    /**
     * Obtem o total de organizadores cadastrados com o doc passado
     * 
     * @param String  $documento - nr do doc
     * @param Integer $id        - id do organizador
     * @return Integer           - total de organizadores cadastrados
     */
    function getTotalOrganPorDocumento($documento, $id) {
        $return = null;
        if($documento)
            $this->db->where('nr_documento', $documento);
        if($id)
            $this->db->where('id_organizador <> ', $id);
        return $this->db->count_all_results($this->table);
    }

    /**
     * Obtem o total de organizadores cadastrados com o login do usuario passado
     *
     * @param String  $usuario   - login usuario
     * @param Integer $id        - id do organizador
     * @return Integer           - total de organizadores cadastrados
     */
    function getTotalOrganPorLogin($login, $id) {
        $return = null;
        if($login)
            $this->db->where('de_usuario', $login);
        if($id)
            $this->db->where('id_organizador <> ', $id);
        return $this->db->count_all_results($this->table);
    }
    
    function listarControladoresGlobais() {
        $this->db->where('fl_controlador', 'S');
        $query = $this->db->get($this->table);
        $result = $query->result();
        return $result;
    }



    
}
?>
