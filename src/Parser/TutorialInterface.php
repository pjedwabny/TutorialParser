<?php
/**
 * Created by PhpStorm.
 * User: jedwab
 * Date: 20.07.14
 * Time: 19:00
 */
namespace jedwab\TutorialParser\Parser;

interface TutorialInterface {

    public function getTitle();
    public function getProducer();
    public function getDescription();
    public function getLevel();
    public function getTime();
    public function getSubjects();
    public function getSoftware();
    public function getAuthors();
    public function getReleaseDate();
    public function getChapters();
}