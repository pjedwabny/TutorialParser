<?php
/**
 * Created by PhpStorm.
 * User: jedwab
 * Date: 22.07.14
 * Time: 14:33
 */

namespace jedwab\TutorialParser\Connector;


/**
 * Class AbstractConnector
 * @package jedwab\TutorialParser\Connector
 */
abstract class AbstractConnector {
    /**
     * @var \DOMXPath
     */
    protected $DOMXpath;
    /**
     * @var \DOMDocument
     */
    protected $DOM;

    /**
     * @return \DOMXPath
     */
    public function getDOMXpath()
    {
        return $this->DOMXpath;
    }

    /**
     * @return \DOMXPath
     */
    public function getDOM()
    {
        return $this->DOM;
    }
} 