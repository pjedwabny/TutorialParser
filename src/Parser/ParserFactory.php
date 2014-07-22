<?php
/**
 * Created by PhpStorm.
 * User: jedwab
 * Date: 22.07.14
 * Time: 15:27
 */

namespace jedwab\TutorialParser\Parser;
use jedwab\TutorialParser\Connector\Connector;

class ParserFactory {

    private $className;

    public function setClass($className)
    {
        $this->className = $className;
    }

    public function getDomain()
    {
        $className = $this->className;
        return $className::domain;
    }

    public function getInstance(Connector $connector)
    {
        return new $this->className($connector);
    }
} 