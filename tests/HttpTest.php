<?php


use Badtomcat\Routing\RequestContext;
use Badtomcat\Routing\Exception\MethodNotAllowedException;

class HttpTest extends PHPUnit_Framework_TestCase
{

    public function testMethod()
    {
    	$test = new \Badtomcat\Httpclient\Curl();
    	$ret = $test->get("http://tiananwei.com/tool/method");
        $this->assertEquals("GET",trim($ret));

        $test = new \Badtomcat\Httpclient\Curl();
        $ret = $test->post("http://tiananwei.com/tool/method");
        $this->assertEquals("POST",trim($ret));


        $test = new \Badtomcat\Httpclient\Curl();
        $ret = $test->delete("http://tiananwei.com/tool/method");
        $this->assertEquals("DELETE",trim($ret));
    }

    public function testCookie()
    {
        $test = new \Badtomcat\Httpclient\Curl();
        $test->cookie = __DIR__."/cookie";
        $test->get("http://tiananwei.com/tool/sessionset");

        $test = new \Badtomcat\Httpclient\Curl();
        $test->cookie = __DIR__."/cookie";
        $ret = $test->get("http://tiananwei.com/tool/sessionecho");
        $this->assertEquals("garri",trim($ret));
    }

    public function testUa()
    {
        $test = new \Badtomcat\Httpclient\Curl();
        $ret = $test->get("http://tiananwei.com/tool/ua");
        $this->assertEquals(\Badtomcat\Httpclient\Curl::DEF_UA,trim($ret));
    }


    public function testHeader()
    {
        $test = new \Badtomcat\Httpclient\Curl();
        $test->addHeader("xxx","17739");
        $ret = $test->get("http://tiananwei.com/tool/headers");
        var_dump($ret);
    }

}

