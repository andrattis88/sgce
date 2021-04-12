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

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Teste de Email -
 *
 *
 * @author Pedro Junior <pedrojunior@unipampa.edu.br> 
 */

class Valida_email {    
    
    
/**
* PHP Socket resource to remote MTA
* @var resource $sock
*/
   var $sock;
 
/**
  * Current User being validated
  */
    var $user;
    /**
      * Current domain where user is being validated
      */
    var $domain;
    /**
      * List of domains to validate users on
      */
    var $domains;
    /**
      * SMTP Port
      */
    var $port = 25;
    /**
      * Maximum Connection Time to wait for connection establishment per MTA
      */
    var $max_conn_time = 3;
    /**
      * Maximum time to read from socket before giving up
      */
    var $max_read_time = 5;

    /**
      * username of sender
      */
    var $from_user = 'nao.responder';
    /**
      * Host Name of sender
      */
    var $from_domain = 'unipampa.edu.br';

    /**
      * Nameservers to use when make DNS query for MX entries
      * @var Array $nameservers
      */
    var $nameservers = array();

    var $debug = false;

    /**
      * Initializes the Class
      * @return SMTP_validateEmail Instance
      * @param $email Array[optional] List of Emails to Validate
      * @param $sender String[optional] Email of validator
      */
    function SMTP_validateEmail($emails = false, $sender = false) {
        $resultado = null;
        $CI =& get_instance();
        $CI->config->load('email');        
        $CI->config->load_db_items();
        $sender = $CI->config->item('email_from_address');
        $this->nameservers[0] = $CI->config->item('servidor_dns');
        
        if ($emails) {            
            $this->setEmails(array($emails));            
        }
        if ($sender) {
            $this->setSenderEmail($sender);
        }
        $resultado = $this->validate($emails, $sender);        
        return $resultado;
    }

    function _parseEmail($email) {
        $parts = explode('@', $email);
        $domain = array_pop($parts);
        $user= implode('@', $parts);
        return array($user, $domain);
    }

    /**
      * Set the Emails to validate
      * @param $emails Array List of Emails
      */
    function setEmails($email) {                        
        list($user, $domain) = $this->_parseEmail($email[0]);                
        if (!isset($this->domains[$domain])) {
            $this->domains[$domain] = array();
        }
        $this->domains[$domain][] = $user;
            
    }

    /**
      * Set the Email of the sender/validator
      * @param $email String
      */
    function setSenderEmail($email) {
        $parts = $this->_parseEmail($email);
        $this->from_user = $parts[0];
        $this->from_domain = $parts[1];
    }

    /**
     * Validate Email Addresses
     * @param String $emails Emails to validate (recipient emails)
     * @param String $sender Sender's Email
     * @return Array Associative List of Emails and their validation results
     */
    function validate($emails = false, $sender = false) {
        $results = array();

        if ($emails) {
            $this->setEmails($emails);
        }
        if ($sender) {
            $this->setSenderEmail($sender);
        }

    // query the MTAs on each Domain
    foreach($this->domains as $domain=>$users) {

    $mxs = array();

    // current domain being queried
    $this->domain = $domain;

    // retrieve SMTP Server via MX query on domain
    list($hosts, $mxweights) = $this->queryMX($domain);

    // retrieve MX priorities
    for($n=0; $n < count($hosts); $n++){
    $mxs[$hosts[$n]] = $mxweights[$n];
    }
    asort($mxs);

    // last fallback is the original domain
    $mxs[$this->domain] = 0;

    $this->debug(print_r($mxs, 1));

    $timeout = $this->max_conn_time;

    // try each host
    while(list($host) = each($mxs)) {
    // connect to SMTP server
        $this->debug("try $host:$this->port\n");
        if ($this->sock = @fsockopen($host, $this->port, $errno, $errstr, (float) $timeout)) {
            stream_set_timeout($this->sock, $this->max_read_time);
            break;
            }
    }

    // did we get a TCP socket
    if ($this->sock) {
        $reply = fread($this->sock, 2082);
        $this->debug("<<<\n$reply");

        preg_match('/^([0-9]{3}) /ims', $reply, $matches);
        $code = isset($matches[1]) ? $matches[1] : '';

        if($code != '220') {
            // MTA gave an error...
            foreach($users as $user) {
                $results[$user.'@'.$domain] = false;
            }
            continue;
        }

        // say helo
        $this->send("HELO ".$this->from_domain);
        // tell of sender
        $this->send("MAIL FROM: <".$this->from_user.'@'.$this->from_domain.">");

        // ask for each recepient on this domain
        foreach($users as $user) {

            // ask of recepient
            $reply = $this->send("RCPT TO: <".$user.'@'.$domain.">");

            // get code and msg from response
            preg_match('/^([0-9]{3}) /ims', $reply, $matches);
            $code = isset($matches[1]) ? $matches[1] : '';

            if ($code == '250') {
                // you received 250 so the email address was accepted
                $results[$user.'@'.$domain] = true;
            } elseif ($code == '451' || $code == '452') {
                // you received 451 so the email address was greylisted (or some temporary error occured on the MTA) - so assume is ok
                $results[$user.'@'.$domain] = true;
            } elseif ($code == '') { //devido a um problema com enderecos validos do hotmail
                $results[$user.'@'.$domain] = true;                
            } else {
                $results[$user.'@'.$domain] = false;
            }

        }

        // reset before quit
        $this->send("RSET");

        // quit
        $this->send("quit");
        // close socket
        fclose($this->sock);

    }
  }
  return $results;
 }



 function send($msg) {
    fwrite($this->sock, $msg."\r\n");

    $reply = fread($this->sock, 2082);

    $this->debug(">>>\n$msg\n");
    $this->debug("<<<\n$reply");

    return $reply;
 }

/**
  * Query DNS server for MX entries
  * @return
  */
function queryMX($domain) {
    $hosts = array();
    $mxweights = array();
    if (function_exists('getmxrr')) {
        getmxrr($domain, $hosts, $mxweights);
    } 
    return array($hosts, $mxweights);
 }

    /**
      * Simple function to replicate PHP 5 behaviour. http://php.net/microtime
      */
 function microtime_float() {
       list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
 }

 function debug($str) {
     if ($this->debug) {
        echo '<pre>'.htmlentities($str).'</pre>';
     }
 }
 
   
}
?>