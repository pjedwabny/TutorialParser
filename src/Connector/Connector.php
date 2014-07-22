<?php
/**
 * Created by PhpStorm.
 * User: jedwab
 * Date: 22.07.14
 * Time: 14:36
 */

namespace jedwab\TutorialParser\Connector;
use DOMDocument;
use DOMXPath;
use Httpful\Request;

/**
 * Class Connector
 * @package jedwab\TutorialParser\Connector
 */
class Connector extends AbstractConnector{

    /**
     * @param string $url
     * @throws \Exception
     * @throws \Httpful\Exception\ConnectionErrorException
     */
    public function __construct($url)
    {
        $response = Request::get($url)->send();
        if($response->code != 200)
            throw new \Exception('Page not found');

        $this->url = $url;
        $this->html = $response->body;
        $this->dom = new DOMDocument();
        $this->dom->loadHTML($this->html);
        $this->DOMXpath = new DOMXPath($this->dom);
    }
} 