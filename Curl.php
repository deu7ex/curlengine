<?php

class Curl extends CurlEngine
{

    /**
     * Init parser engine
     */
    protected function _initEngine()
    {
        $this->_engine = curl_init();

        curl_setopt($this->_engine, CURLOPT_AUTOREFERER, $this->autoreferer);
        curl_setopt($this->_engine, CURLOPT_ENCODING, $this->encoding);
        curl_setopt($this->_engine, CURLOPT_URL, $this->prefix_url . $this->url);
        curl_setopt($this->_engine, CURLOPT_RETURNTRANSFER, $this->returntransfer);
        curl_setopt($this->_engine, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
        curl_setopt($this->_engine, CURLOPT_SSL_VERIFYHOST, $this->ssl_verifyhost);
        curl_setopt($this->_engine, CURLOPT_HEADER, 1);
        curl_setopt($this->_engine, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($this->_engine, CURLOPT_CONNECTTIMEOUT, $this->connect_time);
        curl_setopt($this->_engine, CURLOPT_VERBOSE, $this->verbose);
        curl_setopt($this->_engine, CURLOPT_POST, 0);

        if ($this->userpwd)
            curl_setopt($this->_engine, CURLOPT_USERPWD, $this->userpwd);

        if ($this->agent)
            curl_setopt($this->_engine, CURLOPT_USERAGENT, "");

        if ($this->referer)
            curl_setopt($this->_engine, CURLOPT_REFERER, $this->referer);

        if ($this->interface)
            curl_setopt($this->_engine, CURLOPT_INTERFACE, $this->interface);

        if ($this->httpget)
            curl_setopt($this->_engine, CURLOPT_HTTPGET, $this->httpget);

        if ($this->writeheader != '')
            curl_setopt($this->_engine, CURLOPT_WRITEHEADER, $this->writeheader);

        if ($this->postparams)
        {
            if ($this->put)
                curl_setopt($this->_engine, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($this->post)
                curl_setopt($this->_engine, CURLOPT_POST, 1);

            curl_setopt($this->_engine, CURLOPT_POSTFIELDS, $this->postparams);
        }

        if ($this->del)
        {
            curl_setopt($this->_engine, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        if ($this->proxy)
            curl_setopt($this->_engine, CURLOPT_PROXY, $this->proxy);

        if ($this->proxy_user_data)
            curl_setopt($this->_engine, CURLOPT_PROXYUSERPWD, $this->proxy_user_data);

        if ($this->cookie)
            curl_setopt($this->_engine, CURLOPT_COOKIE, $this->join_cookie());

        if (count($this->httpheader))
            curl_setopt($this->_engine, CURLOPT_HTTPHEADER, $this->httpheader);

        if ($this->sslcert)
            curl_setopt($this->_engine, CURLOPT_SSLCERT, $this->sslcert);

        if ($this->sslkey)
            curl_setopt($this->_engine, CURLOPT_SSLKEY, $this->sslkey);

        if ($this->cainfo)
            curl_setopt($this->_engine, CURLOPT_CAINFO, $this->cainfo);

        if ($this->cookiefile)
        {
            curl_setopt($this->_engine, CURLOPT_COOKIEFILE, $this->cookiefile);
            curl_setopt($this->_engine, CURLOPT_COOKIEJAR, $this->cookiefile);
        }

        return CJSON::decode($this->_getResponse());
    }

    /**
     * Set url to parse
     */
    protected function _setUrl()
    {
        if (!preg_match('/' . $this->_suffix . '/i', $this->url))
        {
            $this->url .= $this->_suffix;
        }

        curl_setopt($this->_engine, CURLOPT_URL, $this->url);
    }

    public function setUSERPWD($pwd)
    {
        $this->userpwd = $pwd;
    }
    
    public function setPrefixURL($url)
    {
        $this->prefix_url = $url;
    }

    public function queryGET($url)
    {
        $this->url = $url;
        return $this->_initEngine();
    }

    public function queryPOST($url, $postparams, $put = false, $enctype = false)
    {
        if (!$put)
            $this->post = 1;
        else
            $this->put = 1;

        return $this->postparam($url, $postparams, $enctype);
    }

    public function queryDelete($url)
    {
        $this->del = 1;
        $this->url = $url;
        return $this->_initEngine();
    }

    public function postparam($url, $postparams, $enctype = false)
    {
        $this->url = $url;

        if (!$enctype)
        {
            $this->postparams = $postparams;
        }
        else
        {
            $this->postparams = array();
            $params = explode("&", $postparams);
            for ($i = 0; $i < count($params); $i++)
            {
                list($name, $value) = explode("=", $params[$i]);
                $this->postparams[$name] = $value;
            }
        }

        return $this->_initEngine();
    }

    public function set_httpget($httpget)
    {
        $this->httpget = $httpget;
    }

    public function set_referer($referer)
    {
        $this->referer = $referer;
    }

    public function set_autoreferer($autoreferer)
    {
        $this->autoreferer = $autoreferer;
    }

    public function set_useragent($agent = null)
    {
        if (is_null($agent) || !isset($this->_userAgentList[$agent]))
        {
            $max = count($this->_userAgentList) - 1;
            $agent = $this->_userAgentList[rand(0, $max)];
        }

        $this->agent = $agent;
    }

    public function set_cookie($head)
    {
        preg_match_all('/Set-Cookie: (.*?)=(.*?);/i', $head, $result, PREG_SET_ORDER);

        foreach ($result as $row)
            $this->cookie[$row[1]] = $row[2];
    }

    public function add_cookie($cookie)
    {
        foreach ($cookie as $name => $value)
            $this->cookie[$name] = $value;
    }

    public function delete_cookie($name)
    {
        if (isset($this->cookie[$name]))
            unset($this->cookie[$name]);
    }

    public function get_cookie()
    {
        return $this->cookie;
    }

    public function clear_cookie()
    {
        $this->cookie = array();
    }

    public function set_httpheader($httpheader)
    {
        $this->httpheader = $httpheader;
    }

    public function clear_httpheader()
    {
        $this->httpheader = array();
    }

    public function set_encoding($encoding)
    {
        $this->encoding = $encoding;
    }

    public function set_interface($interface)
    {
        $this->interface = $interface;
    }

    public function set_writeheader($writeheader)
    {
        $this->writeheader = $writeheader;
    }

    public function set_followlocation($followlocation)
    {
        $this->followlocation = $followlocation;
    }

    public function set_returntransfer($returntransfer)
    {
        $this->returntransfer = $returntransfer;
    }

    public function set_ssl_verifypeer($ssl_verifypeer)
    {
        $this->ssl_verifypeer = $ssl_verifypeer;
    }

    public function set_ssl_verifyhost($ssl_verifyhost)
    {
        $this->ssl_verifyhost = $ssl_verifyhost;
    }

    public function set_sslcert($sslcert)
    {
        $this->sslcert = $sslcert;
    }

    public function set_sslkey($sslkey)
    {
        $this->sslkey = $sslkey;
    }

    public function set_cainfo($cainfo)
    {
        $this->cainfo = $cainfo;
    }

    public function set_timeout($timeout)
    {
        $this->timeout = $timeout;
    }

    public function set_connect_time($connect_time)
    {
        $this->connect_time = $connect_time;
    }

    public function set_cookiefile($cookiefile)
    {
        $this->cookiefile = $cookiefile;
    }

    public function set_proxy($proxy)
    {
        $this->proxy = $proxy;
    }

    public function set_proxy_auth($proxy_user_data)
    {
        $this->proxy_user_data = $proxy_user_data;
    }

    public function set_verbose($verbose)
    {
        $this->verbose = $verbose;
    }

    public function get_error()
    {
        return curl_errno($this->_engine);
    }

    public function get_http_state()
    {
        if (curl_getinfo($this->_engine, CURLINFO_HTTP_CODE) == 200)
            return true;
        else
            return false;
    }

    public function get_speed_download()
    {
        return curl_getinfo($this->_engine, CURLINFO_SPEED_DOWNLOAD);
    }

    public function get_url()
    {
        return curl_getinfo($this->_engine, CURLINFO_EFFECTIVE_URL);
    }

    public function join_cookie()
    {
        $tcookie = array();
        foreach ($this->cookie as $key => $value)
            $tcookie[] = "$key=$value";
        return join('; ', $tcookie);
    }

    /**
     * Get response from url
     */
    private function _getResponse()
    {
        $response = curl_exec($this->_engine);

        $this->_pause();

        // Check for errors
        if (curl_errno($this->_engine))
        {
            // TODO: only log and continue the parsing
            $error = 'Error #' . curl_errno($this->_engine) . ': "' . curl_error($this->_engine) . '"';
            throw new Exception($error);
        }

        $this->set_cookie(substr($response, 0, curl_getinfo($this->_engine, CURLINFO_HEADER_SIZE)));
        $response = substr($response, curl_getinfo($this->_engine, CURLINFO_HEADER_SIZE));

        return $response;
    }

    public function __destruct()
    {
        $this->postparams = array();
    }

}