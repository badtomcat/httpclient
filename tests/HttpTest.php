<?php

class HttpTest extends PHPUnit_Framework_TestCase
{

    public function testMethod()
    {
    	$test = new \Badtomcat\Httpclient\Curl();
    	$ret = $test->get("http://tiananwei.com/tool/method")->send();
        $this->assertEquals("GET",trim($ret));

        $test = new \Badtomcat\Httpclient\Curl();
        $ret = $test->post("http://tiananwei.com/tool/method")->send();
        $this->assertEquals("POST",trim($ret));


        $test = new \Badtomcat\Httpclient\Curl();
        $ret = $test->delete("http://tiananwei.com/tool/method")->send();
        $this->assertEquals("DELETE",trim($ret));
    }

    public function testCookie()
    {
        $test = new \Badtomcat\Httpclient\Curl();
        $test->cookie = __DIR__."/cookie";
        $test->get("http://tiananwei.com/tool/sessionset")->send();

        $test = new \Badtomcat\Httpclient\Curl();
        $test->cookie = __DIR__."/cookie";
        $ret = $test->get("http://tiananwei.com/tool/sessionecho")->send();
        $this->assertEquals("garri",trim($ret));
    }

    public function testUa()
    {
        $test = new \Badtomcat\Httpclient\Curl();
        $ret = $test->get("http://tiananwei.com/tool/ua")->send();
        $this->assertEquals(\Badtomcat\Httpclient\Curl::DEF_UA,trim($ret));
    }


    public function testHeader()
    {
        $test = new \Badtomcat\Httpclient\Curl();
        $test->addHeader("xxx","17739");
        $ret = $test->get("http://tiananwei.com/tool/headers")->send();
        var_dump($ret);
    }


    public function testJson()
    {
        $test = new \Badtomcat\Httpclient\Curl();
        $test->addHeader("xxx","17739");
        $test->requestDataType = \Badtomcat\Httpclient\Curl::REQUEST_DATA_TYPE_JSON;
        $ret = $test->post("http://tiananwei.com/tool/echojson",[
            "foo" => "bar"
        ])->send();
        var_dump($ret);
        $this->assertTrue(strpos($ret,"bar") !== false);
    }
}

