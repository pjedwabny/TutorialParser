<?php

/**
 * Created by PhpStorm.
 * User: jedwab
 * Date: 20.07.14
 * Time: 15:27
 */
namespace jedwab\TutorialParser\Parser;
use \DateInterval;
use \DateTime;
use \DOMNode;
use \DOMNodeList;


/**
 * Class Lynda
 * @package jedwab\TutorialParser
 */
class Lynda extends AbstractParser
{
    const domain = 'lynda.com';
    /**
     * @param string $url
     * @throws \Exception
     */
    public function __construct($url)
    {
        parent::__construct($url);
        $this->producer = 'Lynda.com';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return trim($this->DOMXpath->query('//div[@class="course-title"]')->item(0)->nodeValue);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        $truncate = $this->DOMXpath->query('//*[@class="truncate-fade"]');
        $truncate->item(0)->parentNode->removeChild($truncate->item(0));

        $spans = $this->DOMXpath->query('//*[@id="tab-details"]/div/div[4]');

        return $this->get_inner_html($spans->item(0));
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        $diff = $this->DOMXpath->query('//div[@class="course-meta"]/span[2]')->item(0)->nodeValue;
        $diff = trim($diff);
        switch ($diff) {
            case 'Appropriate for all':
                return 0;
            case 'Beginner':
                return 1;
            case 'Intermediate':
                return 2;
            case 'Advanced':
                return 3;
        }
    }

    /**
     * @return DateInterval
     */
    public function getTime()
    {
        $time = $this->DOMXpath->query('//div[@class="course-meta"]/span[1]')->item(0)->nodeValue;
        return $this->parseTime($time);
    }

    /**
     * @return array
     */
    public function getSubjects()
    {
        $subjects_spans = $this->DOMXpath->query('//dl[@class="course-categories"]/dd[1]/span');

        $subjects = array();

        foreach ($subjects_spans as $subject_span) {
            $subjects[] = $subject_span->childNodes->item(1)->nodeValue;
        }

        return $subjects;
    }

    /**
     * @return array
     */
    public function getSoftware()
    {
        $software_spans = $this->DOMXpath->query('//dl[@class="course-categories"]/dd[2]/span');
        $software = array();

        foreach ($software_spans as $software_span) {
            $software[] = $software_span->childNodes->item(1)->nodeValue;
        }

        return $software;
    }

    /**
     * @return array
     */
    public function getAuthors()
    {
        $author_spans = $this->DOMXpath->query('//dl[@class="course-categories"]/dd[3]/span');
        $author = array();

        foreach ($author_spans as $author_span) {
            $author[] = $author_span->childNodes->item(1)->nodeValue;
        }

        return $author;
    }

    /**
     * @return array
     */
    public function getChapters()
    {
        /** @var DOMNodeList $chapter_list */
        $chapter_list = $this->DOMXpath->query('//ol[@id="course-toc-outer"]/li');

        /** @var DOMNode $chapter */
        foreach ($chapter_list as $chapter) {
            $chapter_array['title'] = trim(preg_replace('/^\d+\.\s/', '', $this->DOMXpath->query($chapter->getNodePath() . '/div/h3/a')->item(0)->nodeValue));
            $chapter_array['time'] = trim($chapter->childNodes->item(3)->nodeValue);
            $items = $this->DOMXpath->query($chapter->getNodePath() . '/ol/li');
            $chapter_items = array();
            /** @var DOMNode $item */
            foreach ($items as $item) {
                $chapter_item['title'] = trim($this->DOMXpath->query($item->getNodePath() . '/dl/dt/b/a')->item(0)->nodeValue);
                $time = trim($this->DOMXpath->query($item->getNodePath() . '/dl/dd')->item(0)->nodeValue);
                $chapter_item['time'] = $this->parseTime($time);
                $chapter_items[] = $chapter_item;
            }
            $chapter_array['items'] = $chapter_items;
            $chapters[] = $chapter_array;
        }

        return $chapters;
    }

    /**
     * @return DateTime
     */
    public function getReleaseDate()
    {
        $date_format = 'M d, Y';
        $date = $this->DOMXpath->query('//div[@class="course-meta"]/span[4]')->item(0)->nodeValue;
        return DateTime::createFromFormat($date_format, $date);
    }

    /**
     * @param string $time
     * @return DateInterval
     */
    private function parseTime($time)
    {
        $patterns = array('/h/', '/m/', '/s/');
        $replacements = array(' hour', ' min', ' sec');
        $time = preg_replace($patterns, $replacements, $time);
        return DateInterval::createFromDateString($time);
    }

}
