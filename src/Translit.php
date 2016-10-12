<?php

namespace Denismitr\Translit;

class Translit
{
    protected $text;

    protected $dictionary;

    protected $translit = '';

    public function __construct(String $text = '')
    {
        $this->text = $text;

        $this->dictionary = require(
            dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'dictionary.php'
        );
    }

    public function getSlug()
    {
        $this->useDictionary();
        $this->clearWhiteSpaces();
        $this->clearSpecialCharacters();

        return $this->translit;
    }


    public function forString($text)
    {
        $this->text = $text;

        return $this;
    }

    public function getDictionary()
    {
        return $this->dictionary;
    }

    protected function useDictionary()
    {
        $this->translit = '';

        $text = mb_strtolower($this->text);

        for($ii = 0, $len = mb_strlen($text); $ii < $len; $ii++) {
            $current = mb_substr($text, $ii, 1);
            $previous = ($ii > 1) ? mb_substr($text, $ii - 1, 1) : null;
            $next = ($ii < ($len - 1)) ? mb_substr($text, $ii + 1, 1) : null;

            $this->translit .= $this->translateLetter($current, $previous, $next);
        }
    }

    protected function translateLetter($current, $previous = null, $next = null)
    {
        if ( array_key_exists($current, $this->dictionary) ) {
            if (is_array($this->dictionary[$current])) {
                return $this->specialCase($current, $this->dictionary[$current], $previous, $current, $next);
            }

            return $this->dictionary[$current];
        }

        return $current;
    }


    protected function specialCase($letter, array $subArray, $previous, $current, $next)
    {
        $combination = '';

        if ($previous) {
            $combination = $previous . $current;
            if ( array_key_exists($combination, $subArray) ) {
                return $subArray[$combination];
            }
        }

        if ($next) {
            $combination = $current . $next;
            if ( array_key_exists($combination, $subArray) ) {
                return $subArray[$combination];
            }
        }

        $combination = $previous . $current . $next;

        if ( array_key_exists($combination, $subArray) ) {
            return $subArray[$combination];
        }

        if ( array_key_exists('*', $subArray) ) {
            return $subArray['*'];
        }

        return $letter;
    }


    protected function clearSpecialCharacters()
    {
        $this->translit = str_replace('«', '', $this->translit);
        $this->translit = str_replace('»', '', $this->translit);
        $this->translit = str_replace(',', '', $this->translit);
        $this->translit = str_replace('.', '', $this->translit);
    }

    protected function clearWhiteSpaces()
    {
        $this->translit = str_replace(' ', '-', $this->translit);
    }
}
