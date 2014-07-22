<?php

/**
 * Created by PhpStorm.
 * User: jedwab
 * Date: 20.07.14
 * Time: 19:07
 */
namespace jedwab\TutorialParser\Parser;

trait ParserHelpers
{

    public function get_inner_html($node)
    {
        $innerHTML = '';
        $children = $node->childNodes;
        foreach ($children as $child) {
            $innerHTML .= $child->ownerDocument->saveXML($child);
        }

        return $innerHTML;
    }
} 