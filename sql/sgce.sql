--
-- PostgreSQL database dump
--

-- Started on 2012-08-29 09:12:14

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 1973 (class 1262 OID 31521)
-- Name: sec; Type: DATABASE; Schema: -; Owner: sgce
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 1974 (class 1262 OID 31521)
-- Dependencies: 1973
-- Name: sec; Type: COMMENT; Schema: -; Owner: sgce
--

COMMENT ON DATABASE sec IS 'Sistema de Emissão Certificados';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 1787 (class 1259 OID 31522)
-- Dependencies: 1909 1910 1911 6
-- Name: certificados_modelo; Type: TABLE; Schema: public; Owner: sgce; Tablespace: 
--

CREATE TABLE certificados_modelo (
    id_certificado_modelo integer DEFAULT nextval(('certificados_modelo_id_certificado_modelo_seq'::text)::regclass) NOT NULL,
    nm_modelo text NOT NULL,
    nm_fundo character varying(255),
    de_titulo character varying(255),
    de_texto text NOT NULL,
    id_evento integer NOT NULL,
    nm_fonte character varying(20) NOT NULL,
    de_carga character varying(50),
    dt_inclusao timestamp without time zone DEFAULT now() NOT NULL,
    dt_alteracao timestamp without time zone DEFAULT now() NOT NULL,
    de_instrucoes text,
    de_posicao_texto integer,
    de_posicao_titulo integer,
    de_posicao_validacao integer,
    de_alinhamento_titulo character varying(10),
    de_alinhamento_texto character varying(10),
    de_alinhamento_validacao character varying(10),
    de_alin_texto_titulo character varying(10),
    de_alin_texto_texto character varying(10),
    de_alin_texto_validacao character varying(10),
    de_cor_texto_titulo character varying(10),
    de_cor_texto_texto character varying(10),
    de_cor_texto_validacao character varying(10),
    de_tamanho_titulo integer,
    de_tamanho_texto integer,
    de_titulo_verso text,
    de_texto_verso text
);


ALTER TABLE public.certificados_modelo OWNER TO sgce;

--
-- TOC entry 1788 (class 1259 OID 31531)
-- Dependencies: 6
-- Name: certificados_modelo_id_certificado_modelo_seq; Type: SEQUENCE; Schema: public; Owner: sgce
--

CREATE SEQUENCE certificados_modelo_id_certificado_modelo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.certificados_modelo_id_certificado_modelo_seq OWNER TO sgce;

--
-- TOC entry 1979 (class 0 OID 0)
-- Dependencies: 1788
-- Name: certificados_modelo_id_certificado_modelo_seq; Type: SEQUENCE SET; Schema: public; Owner: sgce
--

SELECT pg_catalog.setval('certificados_modelo_id_certificado_modelo_seq', 1, true);


--
-- TOC entry 1789 (class 1259 OID 31533)
-- Dependencies: 1912 1913 1914 6
-- Name: certificados_participante; Type: TABLE; Schema: public; Owner: sgce; Tablespace: 
--

CREATE TABLE certificados_participante (
    id_certificado integer DEFAULT nextval(('certificados_participante_id_certificado_seq'::text)::regclass) NOT NULL,
    id_participante integer NOT NULL,
    id_modelo_certificado integer NOT NULL,
    de_complementar text,
    de_hash character varying(255),
    dt_inclusao timestamp without time zone DEFAULT now() NOT NULL,
    dt_alteracao timestamp without time zone DEFAULT now() NOT NULL,
    fl_ativo character(1),
    de_texto_certificado text,
    de_campos_frente text,
    de_campos_verso text
);


ALTER TABLE public.certificados_participante OWNER TO sgce;

--
-- TOC entry 1790 (class 1259 OID 31542)
-- Dependencies: 6
-- Name: certificados_participante_id_certificado_seq; Type: SEQUENCE; Schema: public; Owner: sgce
--

CREATE SEQUENCE certificados_participante_id_certificado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.certificados_participante_id_certificado_seq OWNER TO sgce;

--
-- TOC entry 1982 (class 0 OID 0)
-- Dependencies: 1790
-- Name: certificados_participante_id_certificado_seq; Type: SEQUENCE SET; Schema: public; Owner: sgce
--

SELECT pg_catalog.setval('certificados_participante_id_certificado_seq', 1, true);


--
-- TOC entry 1791 (class 1259 OID 31544)
-- Dependencies: 6
-- Name: config_sistema; Type: TABLE; Schema: public; Owner: sgce; Tablespace: 
--

CREATE TABLE config_sistema (
    id_config_sistema integer NOT NULL,
    nm_parametro text NOT NULL,
    vl_parametro text,
    vl_padrao text
);


ALTER TABLE public.config_sistema OWNER TO sgce;

--
-- TOC entry 1792 (class 1259 OID 31550)
-- Dependencies: 1791 6
-- Name: config_sistema_id_config_sistema_seq; Type: SEQUENCE; Schema: public; Owner: sgce
--

CREATE SEQUENCE config_sistema_id_config_sistema_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.config_sistema_id_config_sistema_seq OWNER TO sgce;

--
-- TOC entry 1985 (class 0 OID 0)
-- Dependencies: 1792
-- Name: config_sistema_id_config_sistema_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sgce
--

ALTER SEQUENCE config_sistema_id_config_sistema_seq OWNED BY config_sistema.id_config_sistema;


--
-- TOC entry 1986 (class 0 OID 0)
-- Dependencies: 1792
-- Name: config_sistema_id_config_sistema_seq; Type: SEQUENCE SET; Schema: public; Owner: sgce
--

SELECT pg_catalog.setval('config_sistema_id_config_sistema_seq', 34, true);


--
-- TOC entry 1793 (class 1259 OID 31552)
-- Dependencies: 1916 1917 1918 6
-- Name: eventos; Type: TABLE; Schema: public; Owner: sgce; Tablespace: 
--

CREATE TABLE eventos (
    id_evento integer DEFAULT nextval(('eventos_id_evento_seq'::text)::regclass) NOT NULL,
    nm_evento character varying(255) NOT NULL,
    sg_evento character varying(20) NOT NULL,
    de_periodo text NOT NULL,
    de_carga text NOT NULL,
    de_local text NOT NULL,
    dt_inclusao timestamp without time zone DEFAULT now() NOT NULL,
    dt_alteracao timestamp without time zone DEFAULT now() NOT NULL,
    de_url text,
    de_email text
);


ALTER TABLE public.eventos OWNER TO sgce;

--
-- TOC entry 1794 (class 1259 OID 31561)
-- Dependencies: 6
-- Name: eventos_id_evento_seq; Type: SEQUENCE; Schema: public; Owner: sgce
--

CREATE SEQUENCE eventos_id_evento_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.eventos_id_evento_seq OWNER TO sgce;

--
-- TOC entry 1989 (class 0 OID 0)
-- Dependencies: 1794
-- Name: eventos_id_evento_seq; Type: SEQUENCE SET; Schema: public; Owner: sgce
--

SELECT pg_catalog.setval('eventos_id_evento_seq', 1, true);


--
-- TOC entry 1795 (class 1259 OID 31563)
-- Dependencies: 1919 6
-- Name: historico_status_certificado; Type: TABLE; Schema: public; Owner: sgce; Tablespace: 
--

CREATE TABLE historico_status_certificado (
    id_certificado integer NOT NULL,
    ip_usuario text NOT NULL,
    nm_usuario text NOT NULL,
    fl_status_certificado text NOT NULL,
    de_justificativa text NOT NULL,
    dt_alteracao timestamp without time zone DEFAULT now(),
    id_historico_status_certificado integer NOT NULL
);


ALTER TABLE public.historico_status_certificado OWNER TO sgce;

--
-- TOC entry 1796 (class 1259 OID 31570)
-- Dependencies: 1795 6
-- Name: historico_status_certificado_teste_seq; Type: SEQUENCE; Schema: public; Owner: sgce
--

CREATE SEQUENCE historico_status_certificado_teste_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.historico_status_certificado_teste_seq OWNER TO sgce;

--
-- TOC entry 1992 (class 0 OID 0)
-- Dependencies: 1796
-- Name: historico_status_certificado_teste_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sgce
--

ALTER SEQUENCE historico_status_certificado_teste_seq OWNED BY historico_status_certificado.id_historico_status_certificado;


--
-- TOC entry 1993 (class 0 OID 0)
-- Dependencies: 1796
-- Name: historico_status_certificado_teste_seq; Type: SEQUENCE SET; Schema: public; Owner: sgce
--

SELECT pg_catalog.setval('historico_status_certificado_teste_seq', 1, true);


--
-- TOC entry 1797 (class 1259 OID 31572)
-- Dependencies: 1921 6
-- Name: log_importacao; Type: TABLE; Schema: public; Owner: sgce; Tablespace: 
--

CREATE TABLE log_importacao (
    id_log integer NOT NULL,
    dt_log timestamp without time zone DEFAULT now() NOT NULL,
    nm_usuario character varying(255) NOT NULL,
    ip_usuario character varying(20) NOT NULL,
    msg_log text,
    id_certificado_modelo integer
);


ALTER TABLE public.log_importacao OWNER TO sgce;

--
-- TOC entry 1798 (class 1259 OID 31579)
-- Dependencies: 6
-- Name: log_importacao_detalhes; Type: TABLE; Schema: public; Owner: sgce; Tablespace: 
--

CREATE TABLE log_importacao_detalhes (
    id_log_detalhe integer NOT NULL,
    id_log integer,
    nr_linha integer,
    de_descricao text
);


ALTER TABLE public.log_importacao_detalhes OWNER TO sgce;

--
-- TOC entry 1799 (class 1259 OID 31585)
-- Dependencies: 1798 6
-- Name: log_importacao_detalhes_id_log_detalhe_seq; Type: SEQUENCE; Schema: public; Owner: sgce
--

CREATE SEQUENCE log_importacao_detalhes_id_log_detalhe_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.log_importacao_detalhes_id_log_detalhe_seq OWNER TO sgce;

--
-- TOC entry 1997 (class 0 OID 0)
-- Dependencies: 1799
-- Name: log_importacao_detalhes_id_log_detalhe_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sgce
--

ALTER SEQUENCE log_importacao_detalhes_id_log_detalhe_seq OWNED BY log_importacao_detalhes.id_log_detalhe;


--
-- TOC entry 1998 (class 0 OID 0)
-- Dependencies: 1799
-- Name: log_importacao_detalhes_id_log_detalhe_seq; Type: SEQUENCE SET; Schema: public; Owner: sgce
--

SELECT pg_catalog.setval('log_importacao_detalhes_id_log_detalhe_seq', 1, true);


--
-- TOC entry 1800 (class 1259 OID 31587)
-- Dependencies: 6 1797
-- Name: log_importacao_id_log_seq; Type: SEQUENCE; Schema: public; Owner: sgce
--

CREATE SEQUENCE log_importacao_id_log_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.log_importacao_id_log_seq OWNER TO sgce;

--
-- TOC entry 2000 (class 0 OID 0)
-- Dependencies: 1800
-- Name: log_importacao_id_log_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sgce
--

ALTER SEQUENCE log_importacao_id_log_seq OWNED BY log_importacao.id_log;


--
-- TOC entry 2001 (class 0 OID 0)
-- Dependencies: 1800
-- Name: log_importacao_id_log_seq; Type: SEQUENCE SET; Schema: public; Owner: sgce
--

SELECT pg_catalog.setval('log_importacao_id_log_seq', 1, true);


--
-- TOC entry 1801 (class 1259 OID 31589)
-- Dependencies: 1924 1925 1926 1927 1928 6
-- Name: organizadores; Type: TABLE; Schema: public; Owner: sgce; Tablespace: 
--

CREATE TABLE organizadores (
    id_organizador integer DEFAULT nextval(('organizadores_id_organizador_seq'::text)::regclass) NOT NULL,
    nm_organizador character varying(50) NOT NULL,
    nr_documento character varying(15) NOT NULL,
    de_email character varying(255),
    nr_telefone character varying(50),
    dt_inclusao timestamp without time zone DEFAULT now() NOT NULL,
    dt_alteracao timestamp without time zone DEFAULT now() NOT NULL,
    de_usuario character varying(255) NOT NULL,
    fl_admin character(1) DEFAULT 'N'::bpchar,
    de_senha character varying(50),
    fl_controlador character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.organizadores OWNER TO sgce;

--
-- TOC entry 1802 (class 1259 OID 31600)
-- Dependencies: 1929 6
-- Name: organizadores_evento; Type: TABLE; Schema: public; Owner: sgce; Tablespace: 
--

CREATE TABLE organizadores_evento (
    id_organizador integer NOT NULL,
    id_evento integer NOT NULL,
    fl_controlador character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.organizadores_evento OWNER TO sgce;

--
-- TOC entry 1803 (class 1259 OID 31604)
-- Dependencies: 6
-- Name: organizadores_evento_id_organizador_evento_seq; Type: SEQUENCE; Schema: public; Owner: sgce
--

CREATE SEQUENCE organizadores_evento_id_organizador_evento_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.organizadores_evento_id_organizador_evento_seq OWNER TO sgce;

--
-- TOC entry 2005 (class 0 OID 0)
-- Dependencies: 1803
-- Name: organizadores_evento_id_organizador_evento_seq; Type: SEQUENCE SET; Schema: public; Owner: sgce
--

SELECT pg_catalog.setval('organizadores_evento_id_organizador_evento_seq', 1, false);


--
-- TOC entry 1804 (class 1259 OID 31606)
-- Dependencies: 6
-- Name: organizadores_id_organizador_seq; Type: SEQUENCE; Schema: public; Owner: sgce
--

CREATE SEQUENCE organizadores_id_organizador_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.organizadores_id_organizador_seq OWNER TO sgce;

--
-- TOC entry 2007 (class 0 OID 0)
-- Dependencies: 1804
-- Name: organizadores_id_organizador_seq; Type: SEQUENCE SET; Schema: public; Owner: sgce
--

SELECT pg_catalog.setval('organizadores_id_organizador_seq', 21, true);


--
-- TOC entry 1805 (class 1259 OID 31608)
-- Dependencies: 1930 1931 1932 6
-- Name: participantes; Type: TABLE; Schema: public; Owner: sgce; Tablespace: 
--

CREATE TABLE participantes (
    id_participante integer DEFAULT nextval(('participantes_id_participante_seq'::text)::regclass) NOT NULL,
    nm_participante text NOT NULL,
    de_email character varying(255) NOT NULL,
    nr_documento character varying(20),
    dt_inclusao timestamp without time zone DEFAULT now() NOT NULL,
    dt_alteracao timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.participantes OWNER TO sgce;

--
-- TOC entry 1806 (class 1259 OID 31617)
-- Dependencies: 6
-- Name: participantes_id_participante_seq; Type: SEQUENCE; Schema: public; Owner: sgce
--

CREATE SEQUENCE participantes_id_participante_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.participantes_id_participante_seq OWNER TO sgce;

--
-- TOC entry 2010 (class 0 OID 0)
-- Dependencies: 1806
-- Name: participantes_id_participante_seq; Type: SEQUENCE SET; Schema: public; Owner: sgce
--

SELECT pg_catalog.setval('participantes_id_participante_seq', 1, true);


--
-- TOC entry 1807 (class 1259 OID 31619)
-- Dependencies: 6
-- Name: rsenha_organizador; Type: TABLE; Schema: public; Owner: sgce; Tablespace: 
--

CREATE TABLE rsenha_organizador (
    id_organizador integer NOT NULL,
    de_senha_md5 character varying(100) NOT NULL,
    id_acesso character varying(100) NOT NULL,
    dt_inclusao date NOT NULL,
    dt_alteracao date NOT NULL
);


CREATE TABLE ci_sessions_sgce
(
  session_id character varying(40) NOT NULL DEFAULT '0'::character varying,
  ip_address character varying(16) NOT NULL DEFAULT '0'::character varying,
  user_agent character varying(120) NOT NULL,
  last_activity integer NOT NULL DEFAULT 0,
  user_data text,
  CONSTRAINT ci_sessions_sgce_pkey PRIMARY KEY (session_id)
);

ALTER TABLE public.ci_sessions_sgce OWNER TO sgce;

ALTER TABLE public.rsenha_organizador OWNER TO sgce;

--
-- TOC entry 1915 (class 2604 OID 31622)
-- Dependencies: 1792 1791
-- Name: id_config_sistema; Type: DEFAULT; Schema: public; Owner: sgce
--

ALTER TABLE config_sistema ALTER COLUMN id_config_sistema SET DEFAULT nextval('config_sistema_id_config_sistema_seq'::regclass);


--
-- TOC entry 1920 (class 2604 OID 31623)
-- Dependencies: 1796 1795
-- Name: id_historico_status_certificado; Type: DEFAULT; Schema: public; Owner: sgce
--

ALTER TABLE historico_status_certificado ALTER COLUMN id_historico_status_certificado SET DEFAULT nextval('historico_status_certificado_teste_seq'::regclass);


--
-- TOC entry 1922 (class 2604 OID 31624)
-- Dependencies: 1800 1797
-- Name: id_log; Type: DEFAULT; Schema: public; Owner: sgce
--

ALTER TABLE log_importacao ALTER COLUMN id_log SET DEFAULT nextval('log_importacao_id_log_seq'::regclass);


--
-- TOC entry 1923 (class 2604 OID 31625)
-- Dependencies: 1799 1798
-- Name: id_log_detalhe; Type: DEFAULT; Schema: public; Owner: sgce
--

ALTER TABLE log_importacao_detalhes ALTER COLUMN id_log_detalhe SET DEFAULT nextval('log_importacao_detalhes_id_log_detalhe_seq'::regclass);


--
-- TOC entry 1960 (class 0 OID 31522)
-- Dependencies: 1787
-- Data for Name: certificados_modelo; Type: TABLE DATA; Schema: public; Owner: sgce
--

INSERT INTO certificados_modelo (id_certificado_modelo, nm_modelo, nm_fundo, de_titulo, de_texto, id_evento, nm_fonte, de_carga, dt_inclusao, dt_alteracao, de_instrucoes, de_posicao_texto, de_posicao_titulo, de_posicao_validacao, de_alinhamento_titulo, de_alinhamento_texto, de_alinhamento_validacao, de_alin_texto_titulo, de_alin_texto_texto, de_alin_texto_validacao, de_cor_texto_titulo, de_cor_texto_texto, de_cor_texto_validacao, de_tamanho_titulo, de_tamanho_texto, de_titulo_verso, de_texto_verso) VALUES (1, 'Evento de Treinamento - Participante', '', 'CERTIFICADO', '<p>Certificamos que <strong>NOME_PARTICIPANTE</strong>&nbsp;participou do<strong>NOME_EVENTO</strong>, realizado de 05 de mar&ccedil;o &agrave; 07 de mar&ccedil;o de 2012, na modalidade MODALIDADE_CURSO.</p>', 1, 'arial', '', '2012-05-21 00:00:00', '2012-05-21 00:00:00', '<p>O arquivo importado deve estar no formato CSV, com a separa&ccedil;&atilde;o dos campos por ponto-e-v&iacute;rgula (;) e ter, obrigatoriamente, os campos NOME_PARTICIPANTE, MODALIDADE_CURSO e EMAIL_PARTICIPANTE. Poder&aacute; tamb&eacute;m conter outros campos, desde que formados por palavras mai&uacute;sculas separadas pelo caractere sublinhado (underline), como no texto de exemplo. Evite usar o ponto-e-v&iacute;rgula junto ao nome de um campo dentro do texto do certificado para evitar problemas na importa&ccedil;&atilde;o de dados. As colunas referenciadas no texto do modelo de certificado devem ser descritos nas instru&ccedil;&otilde;es para importa&ccedil;&atilde;o.</p>', 5, 10, 0, 'center', 'center', 'right', 'center', 'center', 'right', 'black', 'black', 'black', 36, 14, '', '');

--
-- TOC entry 1961 (class 0 OID 31533)
-- Dependencies: 1789
-- Data for Name: certificados_participante; Type: TABLE DATA; Schema: public; Owner: sgce
--

--
-- TOC entry 1962 (class 0 OID 31544)
-- Dependencies: 1791
-- Data for Name: config_sistema; Type: TABLE DATA; Schema: public; Owner: sgce
--

INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (16, 'smtp_user', 'email', 'email');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (17, 'smtp_pass', 'senha', 'senha');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (6, 'server_ldap', 'ldap.empresa.com.br', 'ldap.empresa.com.br');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (26, 'porta_ldap', '389', '389');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (7, 'base_dn', 'ou=usuarios,dc=empresa,dc=com,dc=br', 'ou=usuarios,dc=empresa,dc=com,dc=br');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (10, 'senha_ldap', 'admin', 'admin');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (3, 'max_size', '1048576', '1048576');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (1, 'upload_path', './uploads/', './uploads/');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (2, 'allowed_types', 'jpg|jpeg|csv', 'doc|docx|pdf|zip|rar|jpg|jpeg|gif|png|csv');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (34, 'email_from_address', 'nao.responder@empresa.com.br', 'nao.responder@empresa.com.br');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (33, 'servidor_dns', '10.1.1.1', '10.1.1.1');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (18, 'smtp_port', '25', '25');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (23, 'mail_from_name', 'Nao Responder', 'Nao Responder');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (27, 'protocol', 'smtp', 'smtp');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (28, 'charset', 'utf-8', 'utf-8');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (29, 'wordwrap', 'TRUE', 'TRUE');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (30, 'mailtype', 'html', 'html');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (12, 'smtp_host', 'smtp.empresa.com.br', 'smtp.empresa.com.br');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (22, 'mail_from_address', 'nao.responder@empresa.com.br', 'nao.responder@empresa.com.br');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (32, 'msg_notificacao_qualidade', 'Prezado Avaliador de qualidade, <br/><br/>
Existem certificados aguardando aprovação/liberação para o evento NOME_EVENTO.<br/></br>Pedimos que seja efetuada a liberação através da opção <b>Avaliação de Certificados</b> no menu <b>Certificados</b> o mais brevemente possível. <br /><br /><br />Atenciosamente,<br />Comissão Organizadora do NOME_EVENTO <br/><br/>ATENÇÃO: Esta mensagem foi enviada automaticamente. <br />Por favor, não responda a esta mensagem.', 'Prezado Avaliador de qualidade, <br/><br/>
Existem certificados aguardando aprovação/liberação para o evento NOME_EVENTO.<br/></br>Pedimos que seja efetuada a liberação através da opção <b>Avaliação de Certificados</b> no menu <b>Certificados</b> o mais brevemente possível. <br /><br /><br />Atenciosamente,<br />Comissão Organizadora do NOME_EVENTO <br/><br/>ATENÇÃO: Esta mensagem foi enviada automaticamente. <br />Por favor, não responda a esta mensagem.');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (8, 'master_dn', 'cn=manager,dc=empresa,dc=com,dc=br', 'cn=manager,dc=empresa,dc=com,dc=br');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (31, 'search_dn', 'dc=empresa,dc=com,dc=br', 'dc=empresa,dc=com,dc=br');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (4, 'tipo_de_autenticacao', 'banco', 'banco');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (25, 'msg_alteracao_status', '<p>Prezado NOME_PARTICIPANTE, <br /><br />Comunicamos que o certificado de participa&ccedil;&atilde;o no evento NOME_EVENTO com identifica&ccedil;&atilde;o IDENTIFICACAO_CERTIFICADO foi considerado DESCRICAO_STATUS no Sistema de Emiss&atilde;o de Certificados da Unipampa. <br /><br />Justificativa: DESCRICAO_JUSTIFICATIVA <br /><br />Atenciosamente,<br />Comiss&atilde;o Organizadora do NOME_EVENTO<br /><br />ATEN&Ccedil;&Atilde;O: Esta mensagem foi enviada automaticamente. <br />Por favor, n&atilde;o responda a esta mensagem.</p>', 'Prezado NOME_PARTICIPANTE, <br /><br />Seu certificado de participação no evento NOME_EVENTO com identificação IDENTIFICACAO_CERTIFICADO foi considerado DESCRICAO_STATUS no Sistema de Emissão de Certificados da Unipampa. <br /><br />Justificativa: DESCRICAO_JUSTIFICATIVA <br/><br/>Atenciosamente,<br />Comissão Organizadora do NOME_EVENTO<br/><br/>ATENÇÃO: Esta mensagem foi enviada automaticamente. <br />Por favor, não responda a esta mensagem.
');
INSERT INTO config_sistema (id_config_sistema, nm_parametro, vl_parametro, vl_padrao) VALUES (24, 'msg_notificacao', '<p>Prezado NOME_PARTICIPANTE,<br /><br />Informamos que seu certificado referente &agrave; participa&ccedil;&atilde;o no NOME_EVENTO j&aacute; est&aacute; dispon&iacute;vel para emiss&atilde;o e valida&ccedil;&atilde;o no Sistema de Emiss&atilde;o de Certificados da Unipampa.<br /><br />Para emitir seu certificado, acesse o seguinte endere&ccedil;o:<br /><br /> LINK_CERTIFICADO <br /><br /><br />Atenciosamente,<br />Comiss&atilde;o Organizadora do NOME_EVENTO <br /><br />ATEN&Ccedil;&Atilde;O: Esta mensagem foi enviada automaticamente. <br />Por favor, n&atilde;o responda a esta mensagem.</p>', 'Prezado NOME_PARTICIPANTE,<br /><br />Informamos que seu certificado referente à participação no NOME_EVENTO já está disponível para emissão e validação no Sistema de Emissão de Certificados da Unipampa.<br /><br />Para emitir seu certificado, acesse o seguinte endereço:<br /><br /> LINK_CERTIFICADO <br /><br /><br />Atenciosamente,<br />Comissão Organizadora do NOME_EVENTO <br/><br/>ATENÇÃO: Esta mensagem foi enviada automaticamente. <br />Por favor, não responda a esta mensagem.');


--
-- TOC entry 1963 (class 0 OID 31552)
-- Dependencies: 1793
-- Data for Name: eventos; Type: TABLE DATA; Schema: public; Owner: sgce
--

INSERT INTO eventos (id_evento, nm_evento, sg_evento, de_periodo, de_carga, de_local, dt_inclusao, dt_alteracao, de_url, de_email) VALUES (1, 'Evento de Treinamento', 'ETREINA', '21/09/2011', '1 hora', 'Webconf', '2011-09-19 00:00:00', '2011-12-06 00:00:00', '', 'admin@empresa.com.br');


--
-- TOC entry 1964 (class 0 OID 31563)
-- Dependencies: 1795
-- Data for Name: historico_status_certificado; Type: TABLE DATA; Schema: public; Owner: sgce
--

--
-- TOC entry 1965 (class 0 OID 31572)
-- Dependencies: 1797
-- Data for Name: log_importacao; Type: TABLE DATA; Schema: public; Owner: sgce
--



--
-- TOC entry 1966 (class 0 OID 31579)
-- Dependencies: 1798
-- Data for Name: log_importacao_detalhes; Type: TABLE DATA; Schema: public; Owner: sgce
--


--
-- TOC entry 1967 (class 0 OID 31589)
-- Dependencies: 1801
-- Data for Name: organizadores; Type: TABLE DATA; Schema: public; Owner: sgce
--

INSERT INTO organizadores (id_organizador, nm_organizador, nr_documento, de_email, nr_telefone, dt_inclusao, dt_alteracao, de_usuario, fl_admin, de_senha, fl_controlador) VALUES (1, 'Administrador', '000', 'admin@empresa.com.br', '55', '2011-02-01 08:49:35.34', '2011-11-21 00:00:00', 'admin', 'S', '21232f297a57a5a743894a0e4a801fc3', 'S');


--
-- TOC entry 1968 (class 0 OID 31600)
-- Dependencies: 1802
-- Data for Name: organizadores_evento; Type: TABLE DATA; Schema: public; Owner: sgce
--

INSERT INTO organizadores_evento (id_organizador, id_evento, fl_controlador) VALUES (1, 1, 'S');

--
-- TOC entry 1969 (class 0 OID 31608)
-- Dependencies: 1805
-- Data for Name: participantes; Type: TABLE DATA; Schema: public; Owner: sgce
--

--
-- TOC entry 1970 (class 0 OID 31619)
-- Dependencies: 1807
-- Data for Name: rsenha_organizador; Type: TABLE DATA; Schema: public; Owner: sgce
--

--
-- TOC entry 1934 (class 2606 OID 31627)
-- Dependencies: 1787 1787
-- Name: pk_certificados_modelo; Type: CONSTRAINT; Schema: public; Owner: sgce; Tablespace: 
--

ALTER TABLE ONLY certificados_modelo
    ADD CONSTRAINT pk_certificados_modelo PRIMARY KEY (id_certificado_modelo);


--
-- TOC entry 1936 (class 2606 OID 31629)
-- Dependencies: 1789 1789
-- Name: pk_certificados_participante; Type: CONSTRAINT; Schema: public; Owner: sgce; Tablespace: 
--

ALTER TABLE ONLY certificados_participante
    ADD CONSTRAINT pk_certificados_participante PRIMARY KEY (id_certificado);


--
-- TOC entry 1940 (class 2606 OID 31631)
-- Dependencies: 1791 1791
-- Name: pk_config_sistema; Type: CONSTRAINT; Schema: public; Owner: sgce; Tablespace: 
--

ALTER TABLE ONLY config_sistema
    ADD CONSTRAINT pk_config_sistema PRIMARY KEY (id_config_sistema);


--
-- TOC entry 1944 (class 2606 OID 31633)
-- Dependencies: 1793 1793
-- Name: pk_eventos; Type: CONSTRAINT; Schema: public; Owner: sgce; Tablespace: 
--

ALTER TABLE ONLY eventos
    ADD CONSTRAINT pk_eventos PRIMARY KEY (id_evento);


--
-- TOC entry 1948 (class 2606 OID 31635)
-- Dependencies: 1795 1795
-- Name: pk_historico_status_certificado; Type: CONSTRAINT; Schema: public; Owner: sgce; Tablespace: 
--

ALTER TABLE ONLY historico_status_certificado
    ADD CONSTRAINT pk_historico_status_certificado PRIMARY KEY (id_historico_status_certificado);


--
-- TOC entry 1950 (class 2606 OID 31637)
-- Dependencies: 1797 1797
-- Name: pk_log_importacao; Type: CONSTRAINT; Schema: public; Owner: sgce; Tablespace: 
--

ALTER TABLE ONLY log_importacao
    ADD CONSTRAINT pk_log_importacao PRIMARY KEY (id_log);


--
-- TOC entry 1952 (class 2606 OID 31639)
-- Dependencies: 1801 1801
-- Name: pk_organizadores; Type: CONSTRAINT; Schema: public; Owner: sgce; Tablespace: 
--

ALTER TABLE ONLY organizadores
    ADD CONSTRAINT pk_organizadores PRIMARY KEY (id_organizador);


--
-- TOC entry 1954 (class 2606 OID 31641)
-- Dependencies: 1802 1802 1802
-- Name: pk_organizadores_evento; Type: CONSTRAINT; Schema: public; Owner: sgce; Tablespace: 
--

ALTER TABLE ONLY organizadores_evento
    ADD CONSTRAINT pk_organizadores_evento PRIMARY KEY (id_organizador, id_evento);


--
-- TOC entry 1956 (class 2606 OID 31643)
-- Dependencies: 1805 1805
-- Name: pk_participantes; Type: CONSTRAINT; Schema: public; Owner: sgce; Tablespace: 
--

ALTER TABLE ONLY participantes
    ADD CONSTRAINT pk_participantes PRIMARY KEY (id_participante);


--
-- TOC entry 1958 (class 2606 OID 31645)
-- Dependencies: 1807 1807
-- Name: rsenha_organizador_pkey; Type: CONSTRAINT; Schema: public; Owner: sgce; Tablespace: 
--

ALTER TABLE ONLY rsenha_organizador
    ADD CONSTRAINT rsenha_organizador_pkey PRIMARY KEY (id_organizador);


--
-- TOC entry 1938 (class 2606 OID 31647)
-- Dependencies: 1789 1789
-- Name: uq_certificados_participante_de_hash; Type: CONSTRAINT; Schema: public; Owner: sgce; Tablespace: 
--

ALTER TABLE ONLY certificados_participante
    ADD CONSTRAINT uq_certificados_participante_de_hash UNIQUE (de_hash);


--
-- TOC entry 1946 (class 2606 OID 31649)
-- Dependencies: 1793 1793
-- Name: uq_eventos_id_evento; Type: CONSTRAINT; Schema: public; Owner: sgce; Tablespace: 
--

ALTER TABLE ONLY eventos
    ADD CONSTRAINT uq_eventos_id_evento UNIQUE (id_evento);


--
-- TOC entry 1942 (class 2606 OID 31651)
-- Dependencies: 1791 1791
-- Name: uq_nm_parametro; Type: CONSTRAINT; Schema: public; Owner: sgce; Tablespace: 
--

ALTER TABLE ONLY config_sistema
    ADD CONSTRAINT uq_nm_parametro UNIQUE (nm_parametro);


--
-- TOC entry 1959 (class 2606 OID 31652)
-- Dependencies: 1789 1935 1795
-- Name: fk_certificados_participante; Type: FK CONSTRAINT; Schema: public; Owner: sgce
--

ALTER TABLE ONLY historico_status_certificado
    ADD CONSTRAINT fk_certificados_participante FOREIGN KEY (id_certificado) REFERENCES certificados_participante(id_certificado) ON DELETE CASCADE;


--
-- TOC entry 1976 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: sgce
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM sgce;
GRANT ALL ON SCHEMA public TO sgce;
GRANT USAGE ON SCHEMA public TO sgce;

GRANT ALL ON TABLE ci_sessions_sgce TO sgce;
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE ci_sessions_sgce TO sgce;

--
-- TOC entry 1978 (class 0 OID 0)
-- Dependencies: 1787
-- Name: certificados_modelo; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON TABLE certificados_modelo FROM PUBLIC;
REVOKE ALL ON TABLE certificados_modelo FROM sgce;
GRANT ALL ON TABLE certificados_modelo TO sgce;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE certificados_modelo TO sgce;


--
-- TOC entry 1980 (class 0 OID 0)
-- Dependencies: 1788
-- Name: certificados_modelo_id_certificado_modelo_seq; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON SEQUENCE certificados_modelo_id_certificado_modelo_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE certificados_modelo_id_certificado_modelo_seq FROM sgce;
GRANT ALL ON SEQUENCE certificados_modelo_id_certificado_modelo_seq TO sgce;
GRANT SELECT,UPDATE ON SEQUENCE certificados_modelo_id_certificado_modelo_seq TO sgce;


--
-- TOC entry 1981 (class 0 OID 0)
-- Dependencies: 1789
-- Name: certificados_participante; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON TABLE certificados_participante FROM PUBLIC;
REVOKE ALL ON TABLE certificados_participante FROM sgce;
GRANT ALL ON TABLE certificados_participante TO sgce;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE certificados_participante TO sgce;


--
-- TOC entry 1983 (class 0 OID 0)
-- Dependencies: 1790
-- Name: certificados_participante_id_certificado_seq; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON SEQUENCE certificados_participante_id_certificado_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE certificados_participante_id_certificado_seq FROM sgce;
GRANT ALL ON SEQUENCE certificados_participante_id_certificado_seq TO sgce;
GRANT SELECT,UPDATE ON SEQUENCE certificados_participante_id_certificado_seq TO sgce;


--
-- TOC entry 1984 (class 0 OID 0)
-- Dependencies: 1791
-- Name: config_sistema; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON TABLE config_sistema FROM PUBLIC;
REVOKE ALL ON TABLE config_sistema FROM sgce;
GRANT ALL ON TABLE config_sistema TO sgce;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE config_sistema TO sgce;


--
-- TOC entry 1987 (class 0 OID 0)
-- Dependencies: 1792
-- Name: config_sistema_id_config_sistema_seq; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON SEQUENCE config_sistema_id_config_sistema_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE config_sistema_id_config_sistema_seq FROM sgce;
GRANT ALL ON SEQUENCE config_sistema_id_config_sistema_seq to sgce;
GRANT SELECT,UPDATE ON SEQUENCE config_sistema_id_config_sistema_seq TO sgce;


--
-- TOC entry 1988 (class 0 OID 0)
-- Dependencies: 1793
-- Name: eventos; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON TABLE eventos FROM PUBLIC;
REVOKE ALL ON TABLE eventos FROM sgce;
GRANT ALL ON TABLE eventos to sgce;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE eventos TO sgce;


--
-- TOC entry 1990 (class 0 OID 0)
-- Dependencies: 1794
-- Name: eventos_id_evento_seq; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON SEQUENCE eventos_id_evento_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE eventos_id_evento_seq FROM sgce;
GRANT ALL ON SEQUENCE eventos_id_evento_seq to sgce;
GRANT SELECT,UPDATE ON SEQUENCE eventos_id_evento_seq TO sgce;


--
-- TOC entry 1991 (class 0 OID 0)
-- Dependencies: 1795
-- Name: historico_status_certificado; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON TABLE historico_status_certificado FROM PUBLIC;
REVOKE ALL ON TABLE historico_status_certificado FROM sgce;
GRANT ALL ON TABLE historico_status_certificado to sgce;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE historico_status_certificado TO sgce;


--
-- TOC entry 1994 (class 0 OID 0)
-- Dependencies: 1796
-- Name: historico_status_certificado_teste_seq; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON SEQUENCE historico_status_certificado_teste_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE historico_status_certificado_teste_seq FROM sgce;
GRANT ALL ON SEQUENCE historico_status_certificado_teste_seq TO sgce;
GRANT SELECT,UPDATE ON SEQUENCE historico_status_certificado_teste_seq TO sgce;


--
-- TOC entry 1995 (class 0 OID 0)
-- Dependencies: 1797
-- Name: log_importacao; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON TABLE log_importacao FROM PUBLIC;
REVOKE ALL ON TABLE log_importacao from sgce;
GRANT ALL ON TABLE log_importacao to sgce;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE log_importacao TO sgce;


--
-- TOC entry 1996 (class 0 OID 0)
-- Dependencies: 1798
-- Name: log_importacao_detalhes; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON TABLE log_importacao_detalhes FROM PUBLIC;
REVOKE ALL ON TABLE log_importacao_detalhes from sgce;
GRANT ALL ON TABLE log_importacao_detalhes to sgce;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE log_importacao_detalhes TO sgce;


--
-- TOC entry 1999 (class 0 OID 0)
-- Dependencies: 1799
-- Name: log_importacao_detalhes_id_log_detalhe_seq; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON SEQUENCE log_importacao_detalhes_id_log_detalhe_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE log_importacao_detalhes_id_log_detalhe_seq from sgce;
GRANT ALL ON SEQUENCE log_importacao_detalhes_id_log_detalhe_seq to sgce;
GRANT SELECT,UPDATE ON SEQUENCE log_importacao_detalhes_id_log_detalhe_seq TO sgce;


--
-- TOC entry 2002 (class 0 OID 0)
-- Dependencies: 1800
-- Name: log_importacao_id_log_seq; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON SEQUENCE log_importacao_id_log_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE log_importacao_id_log_seq from sgce;
GRANT ALL ON SEQUENCE log_importacao_id_log_seq to sgce;
GRANT SELECT,UPDATE ON SEQUENCE log_importacao_id_log_seq TO sgce;


--
-- TOC entry 2003 (class 0 OID 0)
-- Dependencies: 1801
-- Name: organizadores; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON TABLE organizadores FROM PUBLIC;
REVOKE ALL ON TABLE organizadores from sgce;
GRANT ALL ON TABLE organizadores to sgce;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE organizadores TO sgce;


--
-- TOC entry 2004 (class 0 OID 0)
-- Dependencies: 1802
-- Name: organizadores_evento; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON TABLE organizadores_evento FROM PUBLIC;
REVOKE ALL ON TABLE organizadores_evento from sgce;
GRANT ALL ON TABLE organizadores_evento to sgce;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE organizadores_evento TO sgce;


--
-- TOC entry 2006 (class 0 OID 0)
-- Dependencies: 1803
-- Name: organizadores_evento_id_organizador_evento_seq; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON SEQUENCE organizadores_evento_id_organizador_evento_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE organizadores_evento_id_organizador_evento_seq from sgce;
GRANT ALL ON SEQUENCE organizadores_evento_id_organizador_evento_seq to sgce;
GRANT SELECT,UPDATE ON SEQUENCE organizadores_evento_id_organizador_evento_seq TO sgce;


--
-- TOC entry 2008 (class 0 OID 0)
-- Dependencies: 1804
-- Name: organizadores_id_organizador_seq; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON SEQUENCE organizadores_id_organizador_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE organizadores_id_organizador_seq from sgce;
GRANT ALL ON SEQUENCE organizadores_id_organizador_seq to sgce;
GRANT SELECT,UPDATE ON SEQUENCE organizadores_id_organizador_seq TO sgce;


--
-- TOC entry 2009 (class 0 OID 0)
-- Dependencies: 1805
-- Name: participantes; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON TABLE participantes FROM PUBLIC;
REVOKE ALL ON TABLE participantes from sgce;
GRANT ALL ON TABLE participantes to sgce;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE participantes TO sgce;


--
-- TOC entry 2011 (class 0 OID 0)
-- Dependencies: 1806
-- Name: participantes_id_participante_seq; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON SEQUENCE participantes_id_participante_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE participantes_id_participante_seq from sgce;
GRANT ALL ON SEQUENCE participantes_id_participante_seq to sgce;
GRANT SELECT,UPDATE ON SEQUENCE participantes_id_participante_seq TO sgce;


--
-- TOC entry 2012 (class 0 OID 0)
-- Dependencies: 1807
-- Name: rsenha_organizador; Type: ACL; Schema: public; Owner: sgce
--

REVOKE ALL ON TABLE rsenha_organizador FROM PUBLIC;
REVOKE ALL ON TABLE rsenha_organizador from sgce;
GRANT ALL ON TABLE rsenha_organizador to sgce;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE rsenha_organizador TO sgce;


-- Completed on 2012-08-29 09:12:14

--
-- PostgreSQL database dump complete
--

