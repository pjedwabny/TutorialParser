<?php
/**
 * Created by PhpStorm.
 * User: jedwab
 * Date: 20.07.14
 * Time: 21:21
 */

namespace jedwab\TutorialParser\Parser;


class TutsPlus extends AbstractParser
{

    const domain = 'tutsplus.com';
    public function __construct($url)
    {
        parent::__construct($url);
        $this->producer = 'TutsPlus';
    }

    public function getTitle()
    {
        return $this->DOMXpath->query('//h1[@class="content-header__title"]')->item(0)->nodeValue;
    }

    public function getDescription()
    {
        $spans = $this->DOMXpath->query('//div[@class="course__description"]');
        return $this->get_inner_html($spans->item(0));
    }

    public function getLevel()
    {
        return null;
    }

    public function getTime()
    {
        $match_array = array();
        $time = $this->DOMXpath->query('//div[@class="course-meta__value"]')->item(1)->nodeValue;
        $time = preg_match('/(\d)\.(\d)/', $time, $match_array);
        $hours = $match_array[1];
        $minutes = $match_array[2] * 6;
        $time = new \DateInterval('PT' . $hours . 'H' . $minutes . 'M');
        return $time;
    }

    /**
     * @return array
     */
    public function getSubjects()
    {
        $category_links = $this->DOMXpath->query('//a[@class="course-meta__category-link"]');
        $subjects = array();
        /** @var \DOMNode $category_link */
        foreach ($category_links as $category_link) {
            $subjects[] = $category_link->nodeValue;
        }
        return $subjects;
    }

    public function getSoftware()
    {
        return null;
    }

    public function getAuthors()
    {
        $author_links = $this->DOMXpath->query('//a[@class="content-header__author-link"]');
        $authors = array();
        foreach ($author_links as $author_link)
            $authors[] = $author_link->nodeValue;

        return $authors;
    }

    public function getReleaseDate()
    {
        $time_element = $this->DOMXpath->query('//time[@class="content-header__publication-date"]')->item(0);
        $time = $time_element->attributes->getNamedItem('datetime')->nodeValue;
        return \DateTime::createFromFormat(\DateTime::ISO8601, $time);
    }

    public function getChapters()
    {
        $chapter_nodes = $this->DOMXpath->query('//div[@class="lesson-index"]/h2');
        $chapters = array();
        /** @var \DOMNode $chapter_node */
        for ($i = 0; $i < $chapter_nodes->length; $i++) {
            $chapter_node = $chapter_nodes->item($i);
            if ($i < $chapter_nodes->length - 1)
                $next_chapter = $chapter_nodes->item($i + 1);
            else
                $next_chapter = false;

            $chapter['name'] = $this->DOMXpath->query($chapter_node->getNodePath() . '/div/span[2]')->item(0)->nodeValue;
            $chapter['items'] = array();

            if ($next_chapter)
                $item_nodes = $this->DOMXpath->query(
                    $chapter_node->getNodePath().'/following-sibling::node()
                    [count(.|'.$next_chapter->getNodePath().'/preceding-sibling::node())=count('.$next_chapter->getNodePath().'/preceding-sibling::node())]');
            else
                $item_nodes = $this->DOMXpath->query($chapter_node->getNodePath() . '/following-sibling::h3');
            /** @var \DOMNode $item_node */
            foreach ($item_nodes as $item_node) {
                $item['name'] = $this->DOMXpath->query($item_node->getNodePath() . '/descendant::div[@class="lesson-index__lesson-title"]')->item(0)->nodeValue;
                $item['duration'] = $this->DOMXpath->query($item_node->getNodePath() . '/descendant::div[@class="lesson-index__lesson-duration"]')->item(0)->nodeValue;
                $chapter['items'][] = $item;
            }
            $chapters[] = $chapter;
        }
        return $chapters;
    }

    function checkUrl($url)
    {
        return substr_count($url, 'tutsplus.com/courses/');
    }
}