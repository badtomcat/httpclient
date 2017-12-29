<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/12
 * Time: 8:54
 *
 */

namespace Badtomcat\Httpclient;


class Curl
{
    const DEF_UA = 'badtomcat/1.0';
    const REQUEST_DATA_TYPE_FORMDATA = 0x1;
    const REQUEST_DATA_TYPE_JSON = 0x2;
    protected $curl;
    public $msg;
    public $errorno;
    public $ua = self::DEF_UA;
    public $cookie; // cookie保存路径
    public $timeout = 10;
    public $headers = [];
    public $requestDataType = self::REQUEST_DATA_TYPE_FORMDATA;

    public function __construct()
    {
        $this->curl = curl_init();
    }

    /**
     * @return $this
     */
    public function setUaAsMobile()
    {
        $this->ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1';
        return $this;
    }

    /**
     * @return $this
     */
    public function setUaAsPc()
    {
        $this->ua = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
        return $this;
    }

    /**
     * @return $this
     */
    public function resetUa()
    {
        $this->ua = "badtomcat/1.0";
        return $this;
    }

    /**
     * @param $option
     * @param $value
     * @return $this
     */
    public function setOption($option, $value)
    {
        curl_setopt($this->curl, $option, $value);
        return $this;
    }

    /**
     * 格式为key value形式
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        if (!empty($this->headers)) {
            $this->setOption(CURLOPT_HTTPHEADER, $this->buildHeaders());
            $this->setOption(CURLOPT_HEADER, 0);//是否返回HEAD,1返回完整的HTTP Response,包括头部
        }
        return $this;
    }

    /**
     * @param $key
     * @param $val
     * @return $this
     */
    public function addHeader($key, $val)
    {
        $this->headers[$key] = $val;
        $this->setOption(CURLOPT_HTTPHEADER, $this->buildHeaders());
        $this->setOption(CURLOPT_HEADER, 0);
        return $this;
    }

    protected function buildHeaders()
    {
        $ret = [];
        foreach ($this->headers as $key => $header) {
            $ret[] = "$key: $header";
        }
        return $ret;
    }

    /**
     * 返回请求内容,FALSE为有错误
     * @return bool|mixed
     */
    public function send()
    {
        $content = curl_exec($this->curl);
        $this->errorno = curl_errno($this->curl);
        $this->msg = curl_error($this->curl);
        curl_close($this->curl);
        if ($this->errorno > 0) {
            return false;
        }
        return $content;
    }

    /**
     * @param $url
     * @param null $headers
     * @return Curl
     */
    public function get($url, $headers = null)
    {
        return $this->request("get", $url, null, $headers);
    }

    /**+
     * @param $url
     * @param array $data
     * @param null $headers
     * @return Curl
     */
    public function post($url, $data = [], $headers = null)
    {
        return $this->request("post", $url, $data, $headers);
    }

    /**
     * @param $url
     * @param array $data
     * @param null $headers
     * @return Curl
     */
    public function put($url, $data = [], $headers = null)
    {
        return $this->request("put", $url, $data, $headers);
    }

    /**
     * @param $url
     * @param array $data
     * @param null $headers
     * @return Curl
     */
    public function delete($url, $data = [], $headers = null)
    {
        return $this->request("delete", $url, $data, $headers);
    }

    /**
     * @param $method
     * @param $url
     * @param null $data
     * @param null $headers
     * @return $this
     */
    protected function request($method, $url, $data = null, $headers = null)
    {
        if (!$url)
            return $this;
        if (is_array($headers)) {
            $this->setHeaders($headers);
        }
        $this->setOption(CURLOPT_URL, $url);
        $this->setOption(CURLOPT_COOKIEJAR, $this->cookie);
        $this->setOption(CURLOPT_COOKIEFILE, $this->cookie);
        $this->setOption(CURLOPT_USERAGENT, $this->ua);
        if (!is_null($data)) {
            $data = $this->buildData($data);
            $this->setOption(CURLOPT_POST, true);
            $this->setOption(CURLOPT_POSTFIELDS, $data);
            $this->addHeader('Content-Length', strlen($data));
        }
        $method = strtoupper($method);
        switch ($method) {
            case "PUT":
            case "DELETE":
                $this->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }
        $this->setOption(CURLOPT_TIMEOUT, $this->timeout);
        $this->setOption(CURLOPT_RETURNTRANSFER, 1);
        return $this;
    }

    protected function buildData($data)
    {
        if (is_string($data))
            return $data;
        elseif (is_array($data)) {
            if ($this->requestDataType == self::REQUEST_DATA_TYPE_FORMDATA) {
                return http_build_query($data);
            } elseif ($this->requestDataType == self::REQUEST_DATA_TYPE_JSON) {
                return json_encode($data);
            }
        }
        return '';
    }
}