<?php

namespace Commercial\Framework\Http;

use Commercial\Framework\Lang\Strings;

// TODO: Implement Query String, FILES and COOKIES
class Request
{
    protected $protocol = "HTTP/1.1";

    protected $method = "GET";
    protected $uri = "/";

    protected $headers = [];

    protected $body;
    protected $query;
    protected $parsedBody;
    protected $queryParams;

    public function __construct($method, $uri, $headers, $body, $query, $parsedBody = [], $queryParams = [])
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->body = $body;
        $this->query = $query;

        if (empty($queryParams)) {
            parse_str($query, $this->queryParams);
        } else {
            $this->queryParams = $queryParams;
        }

        if (empty($parsedBody)) {
            $this->parsedBody = json_decode($body, true);
        } else {
            $this->parsedBody = $parsedBody;
        }
    }

    public static function createFromGlobals()
    {
        $headers = getallheaders();

        $body = file_get_contents('php://input');

        $url = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
        $url = Strings::removePrefix($url, "index.php");
        $url = "/" . trim($url, "/");

        return new static(
            $_SERVER["REQUEST_METHOD"],
            $url,
            $headers,
            $body,
            parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY),
            $_POST,
            $_GET
        );
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }

    public function getParsedBody()
    {
        return $this->parsedBody;
    }
}
