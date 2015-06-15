<?php
namespace RestProxy;

use Symfony\Component\HttpFoundation\Request;

class RestProxy
{
    const GET = "GET";
    const POST = "POST";
    const DELETE = "DELETE";
    const PUT = "PUT";
    const OPTIONS = "OPTIONS";
    
    private $request;
    private $curl;
    private $map;
    private $content;
    private $headers;

    private $actions = [
        self::GET     => 'doGet',
        self::POST    => 'doPost',
        self::DELETE  => 'doDelete',
        self::PUT     => 'doPut',
        self::OPTIONS => 'doOptions',
    ];

    public function __construct(Request $request, CurlWrapper $curl)
    {
        $this->request = $request;
        $this->curl    = $curl;
    }

    public function register($name, $url)
    {
        $this->map[$name] = $url;
    }

    public function run()
    {
        $url = $this->request->getPathInfo();
        foreach ($this->map as $name => $mapUrl) {
            if (strpos($url, $name) == 1 || $name == "/") {
//                 return $this->dispatch($mapUrl . str_replace("/{$name}", NULL, $url));
                return $this->dispatch($mapUrl);
            }
        }
        throw new \Exception("Not match");
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getContent()
    {
        return $this->content;
    }

    private function dispatch($url)
    {
        $queryString   = $this->request->getQueryString();
        $action        = $this->getActionName($this->request->getMethod());
        $this->content = $this->curl->$action($url, $queryString);
        $this->headers = $this->curl->getHeaders();
    }

    private function getActionName($requestMethod)
    {
        if (!array_key_exists($requestMethod, $this->actions)) throw \Exception("Method not allowed");

        return $this->actions[$requestMethod];
    }
}