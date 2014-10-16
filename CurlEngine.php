<?php

abstract class CurlEngine
{

    /**
     * Curl engine
     * @var resource
     */
    protected $httpget = '';
    protected $postparams = array();
    protected $cookie = array();
    protected $proxy = '';
    protected $proxy_user_data = '';
    protected $verbose = 0;
    protected $userpwd = 0;
    protected $referer = '';
    protected $autoreferer = 0;
    protected $writeheader = '';
    protected $url = '';
    protected $agent = 0;
    protected $followlocation = 1;
    protected $returntransfer = 1;
    protected $ssl_verifypeer = 0;
    protected $put = 0;
    protected $post = 0;
    protected $ssl_verifyhost = 2;
    protected $sslcert = '';
    protected $sslkey = '';
    protected $cainfo = '';
    protected $del = 0;
    protected $cookiefile = '';
    protected $timeout = 0;
    protected $connect_time = 0;
    protected $encoding = 'deflate';
    protected $interface = '';
    protected $_api_key = array();
    protected $_user = array();
    protected $_base = array();
    protected $_suffix = '.json';
    protected $_engine;
    protected $_usePause = false;
    protected $_minPause = 3;
    protected $_maxPause = 7;
    protected $_userAgentList = array(
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)",
        "Mozilla/4.0 (compatible;)",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.1) Gecko/2008070208",
        "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)",
        "Googlebot/2.1 (+http://www.google.com/bot.html)",
        "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.1)",
        "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7) Gecko/20040801 Firefox/0.9.0",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.1) Gecko/20040803 Firefox/0.9.1",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7) Gecko/20040821 Firefox/0.9.5",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7) Gecko/20040821 Firefox/0.9.5",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.23) Gecko/20110920 Firefox/3.6.23",
        "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.52",
        "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.51",
        "Opera/9.80 (Windows NT 5.09; U; ru) Presto/2.9.161 Version/11.50",
        "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.173 Version/11.52",
        "Mozilla/4.8 [en] (Windows NT 5.0; U)",
        "Opera/9.80 (S60; SymbOS; Opera Mobi/499; U; ru) Presto/2.4.18 Version/10.00",
        "Opera/9.60 (J2ME/MIDP; Opera Mini/4.2.14912/812; U; ru) Presto/2.4.15",
        "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)",
        "Android-x86-1.6-r2 - Mozilla/5.0 (Linux; U; Android 1.6; en-us; eeepc Build/Donut) AppleWebKit/528.5+ (KHTML, like Gecko) Version/3.1.2 Mobile Safari/525.20.1",
        "Samsung Galaxy S - Mozilla/5.0 (Linux; U; Android 2.1-update1; ru-ru; GT-I9000 Build/ECLAIR) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17",
    );
    public $httpheader = array(
        'Content-type: application/json; charset=utf8',
        'X-Requested-With: XMLHttpRequest',
        'X-CSRFToken: iFd0zkS777uOYSFNjDadvCCY6tVD9xRv',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language: ru-ru,ru;q=0.8,en-us;q=0.5,en;q=0.3',
        'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7'
    );
    public $prefix_url = '';

    /**
     * Init parser engine
     */
    abstract protected function _initEngine();

    abstract protected function _setUrl();

    abstract public function setUSERPWD($pwd);

    abstract public function queryGET($url);

    abstract public function queryPOST($url, $postparams, $put = false, $enctype = false);

    abstract public function queryDelete($url);

    abstract protected function postparam($url, $postparams, $enctype = false);

    abstract public function set_httpget($httpget);

    abstract public function set_referer($referer);

    abstract public function set_autoreferer($autoreferer);

    abstract public function set_useragent($agent);

    abstract public function set_cookie($head);

    abstract public function add_cookie($cookie);

    abstract public function delete_cookie($name);

    abstract public function get_cookie();

    abstract public function clear_cookie();

    abstract public function set_httpheader($httpheader);

    abstract public function clear_httpheader();

    abstract public function set_encoding($encoding);

    abstract public function set_interface($interface);

    abstract public function set_writeheader($writeheader);

    abstract public function set_followlocation($followlocation);

    abstract public function set_returntransfer($returntransfer);

    abstract public function set_ssl_verifypeer($ssl_verifypeer);

    abstract public function set_ssl_verifyhost($ssl_verifyhost);

    abstract public function set_sslcert($sslcert);

    abstract public function set_sslkey($sslkey);

    abstract public function set_cainfo($cainfo);

    abstract public function set_timeout($timeout);

    abstract public function set_connect_time($connect_time);

    abstract public function set_cookiefile($cookiefile);

    abstract public function set_proxy($proxy);

    abstract public function set_proxy_auth($proxy_user_data);

    abstract public function set_verbose($verbose);

    abstract public function get_error();

    abstract public function get_http_state();

    abstract public function get_speed_download();

    abstract public function get_url();

    abstract public function join_cookie();

    /**
     * Pause
     */
    protected function _pause()
    {
        if ($this->_usePause)
        {
            sleep(mt_rand($this->_minPause, $this->_maxPause));
        }
    }

}