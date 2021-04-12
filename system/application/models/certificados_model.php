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
 * Model para a funcao de certificados
 * @author Pedro Jr     <pedro.junior@unipampa.edu.br>
 * @author Sergio Jr    <sergiojunior@unipampa.edu.br>
 * @copyright NTIC Unipampa 2010
 *
 * @var $table   - variavel que indica a tabela padrao para operacao.
 * @var $schema  - variavel para configuracao do esquema de banco de dados.
 *
 */

class Certificados_model extends Model {
    public $table = 'certificados_participante';
    
    /**
     * Construtor da Classe
     *
     * Inicializa o Model de banco de dados
     */

    function certificados_model() {
        parent::Model();        
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
        $id                           = $dados['id_certificado'];
        $data["de_texto_certificado"] = $dados['de_texto_certificado'];
        
        $this->db->where('id_certificado',$id);
        
        if($this->db->update($this->table, $data))
            return true;
        else
            return false;
    }

    
    /**
     * Faz a busca de um registro no banco de dados e retorna seus dados.
     * @param Integer $id - ID a buscar.
     * @return Array      - Dados recuperados.
     */    
    function getById($id) {
        $record ='';
        if (isset($id)) {
            $this->db->where('id_certificado',$id);
            $record = $this->db->get($this->table);
        }        
        $dados = $record->result();
        return $dados[0];   
    }

    /**
     * Recupera um ou mais registros do banco de dados conforme os criterios
     * especificados.
     *
     * @param  String  $key
     * @param  String  $tipo
     * @param  Integer $idEvento
     * @param  Integer $maximo
     * @param  Integer $inicio
     * @param  String  $ordem
     * @param  String  $tipoOrdem
     * @return Array 
     */
    function search($key, $tipo, $idEvento, $maximo, $inicio=0,
                    $ordem=null, $tipoOrdem=null) {
        $return = null;
        $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
        $this->db->join('eventos', 'certificados_modelo.id_evento = eventos.id_evento');
        $this->db->join('participantes', 'participantes.id_participante = certificados_participante.id_participante');
        $this->db->where('eventos.id_evento', $idEvento);
        $this->filtrarPermissao();

        if($key)
            $this->filtrarPesquisa($key, $tipo);

        if (($ordem) && !is_numeric($ordem)) {

            if ($ordem == 'codigo')
                $ordem = 'id_certificado';

            if ($ordem == 'nome')
                $ordem = 'nm_modelo';

            if ($ordem == 'evento')
                $ordem = 'nm_evento';

            if ($ordem == 'nome_participante')
                $ordem = 'nm_participante';

            if ($ordem == 'validacao')
                $ordem = 'de_hash';

            if ($ordem == 'situacao')
                $ordem = 'fl_ativo';

            $this->db->orderby($ordem, $tipoOrdem);
        }       
        
        $query = $this->db->get('certificados_participante', $maximo, $inicio);
        return $query->result();

    }


    /**
     * Obtem o total de registros da tabela
     * @param String $key  - valor a ser pesquisado
     * @param String $tipo - tipo do valor
     * @param Integer $idEvento - id do evento
     * @return Integer - total de registros
     */
    function getTotal($key, $tipo, $idEvento) {
        $return = null;
        $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
        $this->db->join('eventos', 'certificados_modelo.id_evento = eventos.id_evento');
        $this->db->join('participantes', 'participantes.id_participante = certificados_participante.id_participante');
        $this->db->where('eventos.id_evento', $idEvento);
        $this->filtrarPermissao();

        if($key)
            $this->filtrarPesquisa($key, $tipo);
        
        $this->filtrarPermissao();
        return $this->db->count_all_results('certificados_participante');
    }
    
    /**
     * Obtem o total de registros publicos da tabela
     * @param Integer $idEvento - id do evento
     * @return Integer - total de registros
     */
    function getTotalPublicos($idEvento, $key, $tipo) {
        $return = null;
        if (isset($key))
            $this->filtrarPesquisa ($key, $tipo);
        
        $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
        $this->db->join('eventos','certificados_modelo.id_evento = eventos.id_evento');        
        $this->db->join('participantes', 'participantes.id_participante = certificados_participante.id_participante');
        $this->db->where('certificados_participante.fl_ativo', 'A');
        $this->db->where('eventos.id_evento', $idEvento);
        
        return $this->db->count_all_results('certificados_participante');
    }

    /**
     * Filtra os registros pesquisados
     * @param String $key  - valor a ser pesquisado
     * @param String $tipo - tipo do valor
     */
    function filtrarPesquisa($key, $tipo) {        
        if (($tipo == "C") && (is_numeric($key)))
            $this->db->where('id_certificado',$key);

        if ($tipo == "D")
            $this->db->like('nm_participante',$key);

        if ($tipo == "M")
            $this->db->like('nm_modelo',$key);

        if ($tipo == "E")
            $this->db->like('nm_evento',$key);
        
        if ($tipo == "V")
            $this->db->like('de_hash',$key);


    }

    /**
     * Recupera os dados do certificado especificado para impressao
     * @param Integer $idCertificado
     * @return Array
     */
    function recuperaCertificado($idCertificado) {
        if ($idCertificado) {
            $this->db->where('id_certificado', $idCertificado);            
            $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
            $this->db->join('eventos', 'certificados_modelo.id_evento = eventos.id_evento');
            $this->db->join('participantes', 'participantes.id_participante = certificados_participante.id_participante');
            $record = $this->db->get($this->table);
            $dados = $record->result();

            return $dados[0];
        }
        
    }

    /**
     * Carrega os destinatarios de email de certificados para selecao
     * @param Integer $idModelo
     * @return Array
     */
    function carregaDestinatariosModelo($idModelo) {
        if ($idModelo) {            
            $this->db->select('distinct(participantes.id_participante)');
            $this->db->select('participantes.nm_participante');
            $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
            $this->db->join('eventos', 'certificados_modelo.id_evento = eventos.id_evento');
            $this->db->join('participantes', 'participantes.id_participante = certificados_participante.id_participante');
            $this->db->where('id_modelo_certificado', $idModelo);
            $this->db->where('certificados_participante.fl_ativo', 'A');
            $this->db->orderby('participantes.nm_participante');
            $record = $this->db->get($this->table);
            $dados =  $record->result();
            return $dados;
        }
    }

    /**
     * Carrega os dados os participantes para envio de email
     *
     * @param  Integer $idModelo
     * @param  Array   $participantes
     * @return Array
     */
    function carregaDadosEmail($idModelo, $participantes) {
        if (($idModelo) && (count($participantes) > 0)) {
            $this->db->select('participantes.id_participante');
            $this->db->select('participantes.nm_participante');
            $this->db->select('participantes.de_email');
            $this->db->select('eventos.nm_evento');
            $this->db->select('eventos.de_url as site_evento');
            $this->db->select('eventos.de_email as email_evento');
            $this->db->select('certificados_participante.de_hash');
            $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
            $this->db->join('eventos', 'certificados_modelo.id_evento = eventos.id_evento');
            $this->db->join('participantes', 'participantes.id_participante = certificados_participante.id_participante');
            $this->db->where('certificados_participante.fl_ativo', 'A');
            $this->db->where('certificados_participante.id_modelo_certificado', $idModelo);
            $this->db->where_in('participantes.id_participante', $participantes);
            $this->db->orderby('certificados_participante.de_hash');
            $record = $this->db->get($this->table);
            $dados =  $record->result();

            if($dados)
                $retorno = $dados;
            else
                $retorno = null;

        } else {
            $retorno = null;
        }

        return $retorno;
    }

    /**
     * Verifica se o hash do certificado existe
     * e retorna a ID do mesmo.
     *
     * @param String $hashCertificado
     * @return Integer
     */
    function existeHash($hashCertificado=null) {
        if ($hashCertificado){
            $this->db->where('de_hash', $hashCertificado);
            $record = $this->db->get($this->table);
            $dados = $record->result();
            return $dados[0]->id_certificado;
        }
    }



    /**
     * Funcao usada para filtrar permissao de visualizacao
     * de certificados
     */
    function filtrarPermissao() {
        // Caso nao seja admin, filtra os dados
        // Caso o usuario seja administrador, a filtragem é desprezada

        if ($this->session->userdata('admin')==0) {
            $eventosPermitidos = $this->session->userdata('eventos_permitidos');
            if ($eventosPermitidos) {
                $this->db->where_in('eventos.id_evento', $eventosPermitidos);
            }
        }
    }


    /**
     * Altera o status do certificado: Revogado(I) ou Valido(A)
     *
     * @param Integer $idCertificado
     * @param String  $justificativa
     * @param String  $envMail
     * @param String  $status
     * @return Boolean
     */
    function alterarStatus($idCertificado, $justificativa, $envMail, $status) {
        $data["fl_ativo"] = $status;
        $this->db->where('id_certificado', $idCertificado);

        if($this->db->update($this->table, $data)) {
            $this->registraHistoricoAlteracaoStatus($idCertificado,
                                                    $justificativa, $status);
            if($envMail != 'N')
                $this->notificarDestinatarios($idCertificado,
                                              $justificativa, $status, $envMail);

            return true;
        } else
            return false;
    }


    /**
     * Registra o log de alteracao do status do certificado
     *
     * @param Integer $idCertificado
     * @param String  $justificativa
     * @param String  $status
     */
    function registraHistoricoAlteracaoStatus($idCertificado, $justificativa,
                                              $status) {
        $this->load->library('session');
        
        $data['id_certificado']        = $idCertificado;
        $data['de_justificativa']      = $justificativa;
        $data['fl_status_certificado'] = $status;
        $data['nm_usuario']            = $this->session->userdata('uid');
        $data['ip_usuario']            = $this->input->ip_address();

        $this->db->insert('historico_status_certificado', $data);
    }


    /**
     * Retorna o historico de alteracoes no status do certificado
     *
     * @param  Integer $idCertificado
     * @return string
     */
    function listarHistoricoStatus($idCertificado) {
        $this->db->where('id_certificado', $idCertificado);
        $this->db->orderby('dt_alteracao');
        $record = $this->db->get('historico_status_certificado');
        $dados =  $record->result();

        if($dados)
            $retorno = $dados;
        else
            $retorno = null;

        return $retorno;
    }
    


    /**
     * Notificacao por email sobre a alteracao do status do certificado
     *
     * @param Integer $idCertificado
     * @param String  $justificativa
     * @param String  $status
     */
    function notificarDestinatarios($idCertificado, $justificativa, $status, $envMail) {
        $certificado         = $this->recuperaCertificado($idCertificado);
        $emailProprietario   = $this->getEmailProprietarioCertificado($idCertificado);
        $emailsOrganizadores = $this->getEmailsOrganizadores($idCertificado);
        $this->load->library('email');
        $this->load->library('Gerenciador_de_email');

        if($status == 'A') {
            $assunto  = "Notificação de Validação de Certificado Digital";
            $operacao = "validado";
        }

        if($status == 'I') {
            $assunto  = "Notificação de Revogação de Certificado Digital";
            $operacao = "revogado";
        }
        
        if($status == 'P') {
            $assunto  = "Notificação de Emissão de Certificado Digital de Teste";
            $operacao = "pendente ou certificado de teste";
        }
        
        if($status == 'D') {
            $assunto  = "Notificação de Exclusão de Certificado Digital de Teste";
            $operacao = "excluído/descartado";
        }
        
        if($envMail == 'P' || $envMail == 'T')
            if($emailProprietario) {
                
                
                $texto   = $this->montaTextoAlteracaoStatus($certificado->nm_participante,
                                                    $certificado->nm_evento,
                                                    $certificado->de_hash,
                                                    $operacao,
                                                    $justificativa);
                
                $this->gerenciador_de_email->enviaEmailPessoa($emailProprietario,
                                                              $assunto, $texto);
            }
        if($envMail == 'O' || $envMail == 'T')
            if($emailsOrganizadores)
                foreach($emailsOrganizadores as $organizador) {
                    $texto   = $this->montaTextoStatusOrganizador($organizador->nm_organizador,
                                                    $certificado->nm_evento,
                                                    $certificado->de_hash,
                                                    $operacao,
                                                    $justificativa);
                    
                    $this->gerenciador_de_email->enviaEmailPessoa($organizador->email,
                                                                  $assunto, $texto);
                }

    }

    /**
     * Retorna a mensagem que sera enviada por e-mail informando a alteracao
     * no status do certificado: validado ou revogado
     *
     * @param  string $participante
     * @param  string $evento
     * @param  string $hash
     * @param  string $operacao
     * @param  string $justificativa
     * @return $string
     */
    function montaTextoAlteracaoStatus($participante, $evento, $hash, $operacao,
                                       $justificativa) {
        $this->config->load_db_items();
        $msg = $this->config->item('msg_alteracao_status');
        $msg = str_replace('NOME_PARTICIPANTE', $participante, $msg);
        $msg = str_replace('NOME_EVENTO', $evento, $msg);
        $msg = str_replace('IDENTIFICACAO_CERTIFICADO', $hash, $msg);
        $msg = str_replace('DESCRICAO_STATUS', $operacao, $msg);
        $msg = str_replace('DESCRICAO_JUSTIFICATIVA', $justificativa, $msg);
        return $msg;
    }
    
    function montaTextoStatusOrganizador($organizador, $evento, $hash, $operacao, 
                                         $justificativa) {
        
        $this->config->load_db_items();
        $msg = $this->config->item('msg_alteracao_status');
        $msg = str_replace('NOME_PARTICIPANTE', $organizador, $msg);
        $msg = str_replace('NOME_EVENTO', $evento, $msg);
        $msg = str_replace('IDENTIFICACAO_CERTIFICADO', $hash, $msg);
        $msg = str_replace('DESCRICAO_STATUS', $operacao, $msg);
        $msg = str_replace('DESCRICAO_JUSTIFICATIVA', $justificativa, $msg);
        return $msg;
    }


    /**
     * Obtem o email do proprietario do certificato
     *
     * @param  Integer $idCertificado
     * @return String
     */
    function getEmailProprietarioCertificado($idCertificado) {
        $this->db->select('participantes.de_email as email');
        $this->db->join('participantes', 'participantes.id_participante = certificados_participante.id_participante');
        $this->db->where('certificados_participante.id_certificado', $idCertificado);
        $record = $this->db->get($this->table);
        $dados  = $record->result();

        $email = null;
        if($dados)
            if($dados[0]->email) {
                $email = $dados[0]->email;
            }

        return $email;
    }


    /**
     * Obtem os emails dos organizadores do evento vinculado ao certificado
     *
     * @param  Integer $idCertificado
     * @return Array
     */
    function getEmailsOrganizadores($idCertificado) {
        $this->db->select('organizadores.de_email as email');
        $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
        $this->db->join('organizadores_evento', 'organizadores_evento.id_evento = certificados_modelo.id_evento');
        $this->db->join('organizadores', 'organizadores.id_organizador = organizadores_evento.id_organizador');
        $this->db->where('certificados_participante.id_certificado', $idCertificado);
        $record = $this->db->get($this->table);
        $organizadores  = $record->result();

        if($organizadores)
            $retorno = $organizadores;
        else
            $retorno = null;

        return $retorno; 
    }

    function listarCertificadosDoModelo($idModelo) {
        $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
        $this->db->join('participantes', 'participantes.id_participante = certificados_participante.id_participante');
        $this->db->where('certificados_participante.id_modelo_certificado', $idModelo);
        $this->db->orderby('certificados_participante.id_certificado');

        $query = $this->db->get('certificados_participante');
        return $query->result();
    }
    
    function listarCertificadosAvaliacao($idModelo, $key, $tipo, $maximo, $inicio=0) {        
        $conjunto = array();
        $checkValido    = $this->session->userdata('chkValido');
        if ($checkValido=='true') {
            $conjunto[0] = "A";
        } else {
            $conjunto[0] = null;
        }        
        $checkRevogado  = $this->session->userdata('chkRevogado');
        if ($checkRevogado=='true') {
            $conjunto[1] = "I";        
        } else {
            $conjunto[1] = null;
        }        
        
        $checkPendente  = $this->session->userdata('chkPendente');
        if ($checkPendente=='true') {
            $conjunto[2] = "P";
        } else {
            $conjunto[2] = null;
        }        
        
        $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
        $this->db->join('participantes', 'participantes.id_participante = certificados_participante.id_participante');        
        $this->db->where('certificados_participante.id_modelo_certificado', $idModelo);        
        
        if ($key) {
            $this->filtrarAvaliacao($key, $tipo);
        }
        
        $this->db->where_in('certificados_participante.fl_ativo',$conjunto);
                
        $this->db->orderby('certificados_participante.id_certificado');

        $query = $this->db->get('certificados_participante');        
        return $query->result();
    }
    
    function totalCertificadosAvaliacao($idModelo, $key, $tipo) {
        $conjunto = array();
        $checkValido    = $this->session->userdata('chkValido');
        if ($checkValido=='true') {
            $conjunto[0] = "A";
        } else {
            $conjunto[0] = null;
        }        
        $checkRevogado  = $this->session->userdata('chkRevogado');
        if ($checkRevogado=='true') {
            $conjunto[1] = "I";        
        } else {
            $conjunto[1] = null;
        }        
        
        $checkPendente  = $this->session->userdata('chkPendente');
        if ($checkPendente=='true') {
            $conjunto[2] = "P";
        } else {
            $conjunto[2] = null;
        }        
        
        $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
        $this->db->join('participantes', 'participantes.id_participante = certificados_participante.id_participante');        
        $this->db->where('certificados_participante.id_modelo_certificado', $idModelo);        
        
        if ($key) {
            $this->filtrarAvaliacao($key, $tipo);
        }
        
        $this->db->where_in('certificados_participante.fl_ativo',$conjunto);                
        $this->db->orderby('certificados_participante.id_certificado');
        $query = $this->db->get('certificados_participante');
        return $query->num_rows();        
    }
    
    function filtrarAvaliacao($key, $tipo) {
        if (($tipo == "C") && (is_numeric($key)))
            $this->db->where('certificados_participante.id_certificado',$key);

        if ($tipo == "D")
            $this->db->like('participantes.nm_participante',$key);
    }
    
    
    function listarCertificadosAguardando($idModelo) {        
        
        $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
        $this->db->join('participantes', 'participantes.id_participante = certificados_participante.id_participante');        
        $this->db->join('eventos', 'eventos.id_evento = certificados_modelo.id_evento');
        $this->db->where('certificados_participante.id_modelo_certificado', $idModelo);        
        
        $this->db->where('certificados_participante.fl_ativo','P');
                
        $this->db->orderby('certificados_participante.id_certificado');

        $query = $this->db->get('certificados_participante');        
        return $query->result();
    }
    
    function delete($idCertificado) {
        if ($idCertificado) {
            // Faz a busca pelo certificado antes de apagar
            $this->db->where('fl_ativo', 'P');
            $this->db->where('id_certificado', $idCertificado);
            $query = $this->db->get($this->table);
            $dados = $query->result();
            
            if ($dados[0]->id_certificado == $idCertificado) {            
                // Apaga historico do certificado            
                $this->db->where('id_certificado', $idCertificado);
                $this->db->delete('historico_status_certificado');

                // Apaga certificado do arquivo
                $this->db->where('fl_ativo', 'P');
                $this->db->where('id_certificado', $idCertificado);
                $this->db->delete($this->table);                
                return true;
            } else {
                return null;
            }
        }
    }
    
    function migraCertificado($dados) {
        $id                       = $dados['id_certificado'];
        $data['de_campos_frente'] = $dados['de_campos_frente'];
        $data['de_campos_verso']  = $dados['de_campos_verso'];
        
        $this->db->where('id_certificado',$id);
        
        if($this->db->update($this->table, $data))
            return true;
        else
            return false;
        
    }
    
    function atualizaCertificado($dados) {
        $id                           = $dados['id_certificado'];
        $data["de_texto_certificado"] = $dados['de_texto_certificado'];
        $data["de_complementar"]      = $dados['de_complementar'];        
        
        $this->db->where('id_certificado',$id);
        
        if($this->db->update($this->table, $data))
            return true;
        else
            return false;
    }
    
    /**
     * Listagem de certificados de acordo com o evento que estejam liberados
     * (lista pública) 
     */
    
    function listaCertificadosPublicos($idEvento, $key, $tipo, $maximo, $inicio=0) {
        $this->db->select('nm_modelo');
        $this->db->select('nm_participante');
        $this->db->select('de_hash');
        
        $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
        $this->db->join('eventos','certificados_modelo.id_evento = eventos.id_evento');        
        $this->db->join('participantes', 'participantes.id_participante = certificados_participante.id_participante');
        $this->db->where('certificados_participante.fl_ativo', 'A');
        $this->db->where('eventos.id_evento', $idEvento);        
        $this->db->orderby('nm_participante');
        
        if (isset($key))
            $this->filtrarPesquisa ($key, $tipo);
        
        $query = $this->db->get('certificados_participante', $maximo, $inicio);
        return $query->result();
    }
    
    /**
     * Obtem o total de registros publicos da tabela
     * @param Integer $idEvento - id do evento
     * @return Integer - total de registros
     */
    function getTotalPessoa($idPessoa, $key, $tipo) {
        $return = null;
        $this->db->join('certificados_modelo', 
                        'certificados_modelo.id_certificado_modelo = 
                         certificados_participante.id_modelo_certificado');
        
        $this->db->join('eventos',
                        'certificados_modelo.id_evento = eventos.id_evento');       
        
        $this->db->join('participantes', 
                        'participantes.id_participante = certificados_participante.id_participante');
        
        $this->db->where('certificados_participante.fl_ativo', 'A');
        
        $this->db->where('participantes.id_participante', $idPessoa);
        
        if($key)
            $this->filtrarPesquisa($key, $tipo);
        
        return $this->db->count_all_results('certificados_participante');
    }

    /**
     * Listagem de certificados de acordo com o evento que estejam liberados
     * (lista pública) 
     */
    
    function listaCertificadosPessoa($idPessoa, $key, $tipo, $maximo, $inicio=0) {
        $this->db->select('nm_modelo');
        $this->db->select('nm_evento');
        $this->db->select('de_hash');
        
        $this->db->join('certificados_modelo', 'certificados_modelo.id_certificado_modelo = certificados_participante.id_modelo_certificado');
        $this->db->join('eventos','certificados_modelo.id_evento = eventos.id_evento');        
        $this->db->join('participantes', 'participantes.id_participante = certificados_participante.id_participante');
        $this->db->where('certificados_participante.fl_ativo', 'A');
        $this->db->where('participantes.id_participante', $idPessoa);        
        
        if($key)
            $this->filtrarPesquisa($key, $tipo);
        
        $this->db->order_by('nm_participante');
        $query = $this->db->get('certificados_participante', $maximo, $inicio);
        return $query->result();
    }

}
?>
