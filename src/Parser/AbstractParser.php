<?php
/**
 * Created by PhpStorm.
 * User: jedwab
 * Date: 20.07.14
 * Time: 20:36
 */
namespace jedwab\TutorialParser\Parser;
use jedwab\TutorialParser\Connector\Connector;
use jedwab\TutorialParser\Parser\ParserFactory;

/**
 * Class AbstractParser
 * @package jedwab\TutorialParser
 */
abstract class AbstractParser  implements TutorialInterface
{
    use ParserHelpers;
    /**
     * @var string
     */

    protected $DOMXpath = null;
    protected $producer = null;

    /**
     * @param string $url
     * @throws \Exception
     * @throws \Httpful\Exception\ConnectionErrorException
     */
    protected function __construct(Connector $connector)
    {
        $this->DOMXpath = $connector->getDOMXpath();

        $this->checkData();
    }

    public static function getFactory()
    {
        $factory = new ParserFactory();
        $factory->setClass(get_called_class());
        return $factory;
    }

    /**
     * @return string
     */
    public function getProducer()
    {
        return $this->producer;
    }


    private function checkData()
    {
        if(!$this->getTitle())
            throw new \Exception('Title not found');
        if(!$this->getAuthors())
            throw new \Exception('Author not found');
        if(!$this->getDescription())
            throw new \Exception('Description not found');
    }

} 