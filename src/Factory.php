<?php
/**
 * Created by PhpStorm.
 * User: jedwab
 * Date: 21.07.14
 * Time: 19:16
 */

namespace jedwab\TutorialParser;

use jedwab\TutorialParser\Connector\Connector;
use jedwab\TutorialParser\Parser\AbstractParser;
use jedwab\TutorialParser\Parser\Lynda;
use jedwab\TutorialParser\Parser\TutsPlus;

/**
 * Class ParserFactory
 * @package jedwab\TutorialParser
 */
class Factory {

    /**
     * @var array
     */
    private $parsers = array();

    /**
     * @return Factory
     */
    public static function getFactory()
    {
        $factory = new self;
        $factory->registerParser(Lynda::getFactory());
        $factory->registerParser(TutsPlus::getFactory());
        return $factory;
    }

    /**
     * @param $url
     * @return AbstractParser
     * @throws \Exception
     */
    public function  getParser($url)
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST));

        /** @var Parser\ParserFactory $parser */
        foreach($this->parsers as $parser)
            if(substr_count($host,$parser->getDomain()))
                return $parser->getInstance(new Connector($url));

        throw new \Exception('Unrecognized domain');
    }

    /**
     * @param $domain
     * @param $parser_class
     */
    private function registerParser(\jedwab\TutorialParser\Parser\ParserFactory $parserFactory)
    {
        $this->parsers[] = $parserFactory;
    }
} 