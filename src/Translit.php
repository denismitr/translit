<?php

namespace Denismitr\Translit;

class Translit
{
    protected $text;

    protected $dictionary;

    protected $translit = '';

    protected $maxLength;


    public function __construct(String $text = '', $maxLength = 255)
    {
        $this->text = $text;

        $this->maxLength = is_int($maxLength) ? $maxLength : 255;

        //Load the dictionary
        $this->dictionary = require(
            dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'dictionary.php'
        );

        //Translate
        $this->useDictionary();

        if ( ! empty($this->text) ) {
            $this->sanitize();
        }
    }

    /**
     * Set the max length of the slug
     *
     * @param $value
     * @return $this
     */
    public function setMaxLength($value)
    {
        if (is_int($value)) {
            $this->maxLength = $value;
        }

        return $this;
    }

    /**
     * Get the slug no longer than maxLength
     *
     * @return mixed
     */
    public function getSlug()
    {
        return substr($this->translit, 0, $this->maxLength);
    }

    /**
     * Get uncut translited string
     *
     * @return string
     */
    public function getTranslit()
    {
        return $this->translit;
    }

    /**
     * Sets text to translate
     *
     * @param $text
     * @return $this
     */
    public function forString($text)
    {
        $this->text = $text;

        //Translate
        $this->useDictionary();

        if ( ! empty($this->text) ) {
            $this->sanitize();
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDictionary()
    {
        return $this->dictionary;
    }

    /**
     * Works with dictionary to translate text
     */
    protected function useDictionary()
    {
        $this->translit = '';

        $text = mb_strtolower($this->text, 'UTF-8');

        for($ii = 0, $len = mb_strlen($text, 'UTF-8'); $ii < $len; $ii++) {
            $current = mb_substr($text, $ii, 1);
            $previous = ($ii > 0) ? mb_substr($text, $ii - 1, 1) : null;
            $next = ($ii < ($len - 1)) ? mb_substr($text, $ii + 1, 1) : null;

            $this->translit .= $this->translateLetter($current, $previous, $next);
        }
    }

    /**
     * Calls methods to sanitize the output
     */
    protected function sanitize()
    {
        $this->clearWhiteSpaces();
        $this->clearSpecialCharacters();
    }

    /**
     * Works with every single letter comparing it with dictionary
     *
     * @param $current
     * @param null $previous
     * @param null $next
     * @return mixed
     */
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

    /**
     * Special cases where dictionary array contains sub arrays of additional rules
     *
     * @param $letter
     * @param array $subArray
     * @param $previous
     * @param $current
     * @param $next
     * @return mixed
     */
    protected function specialCase($letter, array $subArray, $previous, $current, $next)
    {
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

    /**
     * Cuts all special chars or double dashes
     */
    protected function clearSpecialCharacters()
    {
        // Remove all characters but words, numbers and dashes
        $this->translit = preg_replace('/[^\w\-]+/', '', $this->translit);

        //Remove double dashes
        $this->translit = preg_replace('/--+/', '-', $this->translit);
    }

    /**
     * Clears whitespaces
     */
    protected function clearWhiteSpaces()
    {
        $this->translit = str_replace(' ', '-', $this->translit);
    }
}
