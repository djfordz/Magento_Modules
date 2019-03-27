<?php
/**
 * Created by PhpStorm.
 * User: dford
 * Date: 1/16/19
 * Time: 1:54 PM
 */

class Filofax_Caadiscount_Model_Client
{
    /**
     * @var
     */
    public $soapClient;

    /**
     * @var array
     */
    protected $options = array('soap_version' => SOAP_1_2, 'trace' => 1, 'exceptions' => 1, 'encoding' => 'UTF-8');

    /**
     * Filofax_Caadiscount_Model_Client constructor.
     */
    public function __construct()
    {
        // we unregister the current HTTP wrapper
        stream_wrapper_unregister('http');

        // we register the new HTTP wrapper
        stream_wrapper_register('http', 'MyServiceProviderNTLMStream');
    }
    /**
     * @param $wsdl
     * @param $options
     * @return MyServiceNTLMSoapClient
     */
    public function soapClient($wsdl)
    {
        $this->soapClient = new MyServiceNTLMSoapClient($wsdl, $this->options);
        return $this->soapClient;
    }

    /**
     *
     */
    public function __destruct()
    {
        // restore the original http protocol
        stream_wrapper_restore('http');
    }
}

/**
 * Class MyServiceProviderNTLMStream
 */
class MyServiceProviderNTLMStream extends NTLMStream
{

    /**
     * @var mixed
     */
    public $username;
    /**
     * @var mixed
     */
    public $password;

    /**
     * MyServiceProviderNTLMStream constructor.
     */
    public function __construct()
    {
        $this->username = Mage::helper('filocaa')->getUserId(Filofax_Caadiscount_Model_Membership::MEMBERSHIP);
        $this->password = Mage::helper('filocaa')->getPassword(Filofax_Caadiscount_Model_Membership::MEMBERSHIP);
    }
}

/**
 * Class MyServiceNTLMSoapClient
 */
class MyServiceNTLMSoapClient extends NTLMSoapClient
{

    /**
     * MyServiceNTLMSoapClient constructor.
     * @param $wsdl
     * @param $options
     */
    public function __construct($wsdl, $options)
    {
        parent::__construct($wsdl, $options);
    }
}

/**
 * Class NTLMStream
 */
class NTLMStream
{
    /**
     * @var
     */
    private $path;
    /**
     * @var
     */
    private $mode;
    /**
     * @var
     */
    private $options;
    /**
     * @var
     */
    private $opened_path;
    /**
     * @var
     */
    private $buffer;
    /**
     * @var
     */
    private $pos;
    /**
     * @var
     */
    private $username;
    /**
     * @var
     */
    private $password;

    /**
     * Open the stream
     *
     * @param unknown_type $path
     * @param unknown_type $mode
     * @param unknown_type $options
     * @param unknown_type $opened_path
     * @return unknown
     */
    public function stream_open($path, $mode, $options, $opened_path) {
        $this->path = $path;
        $this->mode = $mode;
        $this->options = $options;
        $this->opened_path = $opened_path;
        $this->username = Mage::helper('filocaa')->getUserId(Filofax_Caadiscount_Model_Membership::MEMBERSHIP);
        $this->password = Mage::helper('filocaa')->getPassword(Filofax_Caadiscount_Model_Membership::MEMBERSHIP);

        $this->createBuffer($path);

        return true;
    }

    /**
     * Close the stream
     *
     */
    public function stream_close() {
        echo "[NTLMStream::stream_close] \n";
        curl_close($this->ch);
    }

    /**
     * Read the stream
     *
     * @param int $count number of bytes to read
     * @return content from pos to count
     */
    public function stream_read($count) {
        echo "[NTLMStream::stream_read] $count \n";
        if(strlen($this->buffer) == 0) {
            return false;
        }

        $read = substr($this->buffer,$this->pos, $count);

        $this->pos += $count;

        return $read;
    }

    /**
     * write the stream
     *
     * @param int $count number of bytes to read
     * @return content from pos to count
     */
    public function stream_write($data) {
        echo "[NTLMStream::stream_write] \n";
        if(strlen($this->buffer) == 0) {
            return false;
        }
        return true;
    }

    /**
     *
     * @return true if eof else false
     */
    public function stream_eof() {
        echo "[NTLMStream::stream_eof] ";

        if($this->pos > strlen($this->buffer)) {
            echo "true \n";
            return true;
        }

        echo "false \n";
        return false;
    }

    /**
     * @return int the position of the current read pointer
     */
    public function stream_tell() {
        echo "[NTLMStream::stream_tell] \n";
        return $this->pos;
    }

    /**
     * Flush stream data
     */
    public function stream_flush() {
        echo "[NTLMStream::stream_flush] \n";
        $this->buffer = null;
        $this->pos = null;
    }

    /**
     * Stat the file, return only the size of the buffer
     *
     * @return array stat information
     */
    public function stream_stat() {
        echo "[NTLMStream::stream_stat] \n";

        $this->createBuffer($this->path);
        $stat = array(
            'size' => strlen($this->buffer),
        );

        return $stat;
    }

    /**
     * Stat the url, return only the size of the buffer
     *
     * @return array stat information
     */
    public function url_stat($path, $flags) {
        echo "[NTLMStream::url_stat] \n";
        $this->createBuffer($path);
        $stat = array(
            'size' => strlen($this->buffer),
        );

        return $stat;
    }

    /**
     * Create the buffer by requesting the url through cURL
     *
     * @param unknown_type $path
     */
    private function createBuffer($path) {
        if($this->buffer) {
            return;
        }

        $this->ch = curl_init($path);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($this->ch, CURLOPT_USERPWD, $this->username.':'.$this->password);
        $this->buffer = curl_exec($this->ch);

        $this->pos = 0;
    }
}

/**
 * Class NTLMSoapClient
 */
class NTLMSoapClient extends SoapClient
{

    /**
     * @var
     */
    protected $__last_request_headers;

    /**
     * @param string $request
     * @param string $location
     * @param string $action
     * @param int $version
     * @param int $one_way
     * @return bool|string
     */
    function __doRequest($request, $location, $action, $version, $one_way = 0) {

        $headers = array(
            'Method: POST',
            'Connection: Keep-Alive',
            'User-Agent: PHP-SOAP/7.3.1',
            'Content-Type: application/soap+xml; charset=utf-8',
            'SOAPAction: "'.$action.'"',
        );

        $this->__last_request_headers = $headers;
        $ch = curl_init($location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        $response = curl_exec($ch);

        return $response;
    }

    /**
     * @return string
     */
    function __getLastRequestHeaders() {
        return $this->__last_request_headers;
    }
}
