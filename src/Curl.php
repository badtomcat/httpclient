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
    protected $curl;
    public $msg;
    public $errorno;
    public $ua = self::DEF_UA;
    public $cookie; // cookie保存路径
    public $timeout = 10;
    public $headers = [];

    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function setUaAsMobile()
    {
        $this->ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1';
        return $this;
    }

    public function setUaAsPc()
    {
        $this->ua = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
        return $this;
    }

    public function resetUa()
    {
        $this->ua = "badtomcat/1.0";
        return $this;
    }

    public function setOption($option, $value)
    {
        curl_setopt($this->curl, $option, $value);
        return $this;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        if (!empty($this->headers)) {
            $this->setOption(CURLOPT_HTTPHEADER, $this->buildHeaders());
            $this->setOption(CURLOPT_HEADER, 0);//是否返回HEAD,1返回完整的HTTP Response,包括头部
        }
        return $this;
    }

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

    protected function perform()
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
     * CURL-get方式获取数据
     *
     * @param string $url URL
     * @param null $headers
     * @return bool|mixed
     */
    public function get($url, $headers = null)
    {
        if (!$url)
            return false;

        $this->setOption(CURLOPT_URL, $url);
        $this->setOption(CURLOPT_COOKIEJAR, $this->cookie);
        $this->setOption(CURLOPT_COOKIEFILE, $this->cookie);
        $this->setOption(CURLOPT_USERAGENT, $this->ua);
        $this->setOption(CURLOPT_RETURNTRANSFER, 1);
        if (is_array($headers)) {
            $this->setHeaders($headers);
        }
        $this->setOption(CURLOPT_TIMEOUT, $this->timeout);
        return $this->perform();
    }

    /**
     * CURL-post方式获取数据
     *
     * @param string $url
     *            URL
     * @param array $data
     *            POST数据
     * @param null $headers
     * @return bool|mixed
     */
    public function post($url, $data = [], $headers = null)
    {
        if (!$url)
            return false;
        $data = (is_array($data)) ? http_build_query($data) : $data;

        $this->setOption(CURLOPT_URL, $url);
        $this->setOption(CURLOPT_COOKIEJAR, $this->cookie);
        $this->setOption(CURLOPT_COOKIEFILE, $this->cookie);
        $this->setOption(CURLOPT_USERAGENT, $this->ua);
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, $data);
        $this->setOption(CURLOPT_RETURNTRANSFER, 1);
        if (is_array($headers)) {
            $this->setHeaders($headers);
        }
        $this->setOption(CURLOPT_TIMEOUT, $this->timeout);
        return $this->perform();
    }

    /**
     * CURL-put方式获取数据
     *
     * @param string $url
     *            URL
     * @param array $data
     *            POST数据
     * @param null $headers
     * @return bool|mixed
     */
    public function put($url, $data = [], $headers = null)
    {
        if (!$url)
            return false;
        $data = (is_array($data)) ? http_build_query($data) : $data;

        $this->setOption(CURLOPT_URL, $url);
        $this->setOption(CURLOPT_COOKIEJAR, $this->cookie);
        $this->setOption(CURLOPT_COOKIEFILE, $this->cookie);
        $this->setOption(CURLOPT_USERAGENT, $this->ua);
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, $data);
        if (is_array($headers)) {
            $this->setHeaders($headers);
        }
        $this->setOption(CURLOPT_TIMEOUT, $this->timeout);
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->addHeader('Content-Length', strlen($data));
        return $this->perform();
    }

    /**
     * CURL-DEL方式获取数据
     *
     * @param string $url
     *            URL
     * @param array $data
     *            POST数据
     * @param null $headers
     * @return bool|mixed
     */
    public function delete($url, $data = [], $headers = null)
    {
        if (!$url)
            return false;
        $data = (is_array($data)) ? http_build_query($data) : $data;

        $this->setOption(CURLOPT_URL, $url);
        $this->setOption(CURLOPT_COOKIEJAR, $this->cookie);
        $this->setOption(CURLOPT_COOKIEFILE, $this->cookie);
        $this->setOption(CURLOPT_USERAGENT, $this->ua);
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, $data);
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');
        $this->addHeader('Content-Length', strlen($data));
        if (is_array($headers)) {
            $this->setHeaders($headers);
        }
        $this->setOption(CURLOPT_TIMEOUT, $this->timeout);

        $this->setOption(CURLOPT_RETURNTRANSFER, 1);
        return $this->perform();
    }
}