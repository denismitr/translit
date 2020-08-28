<?php

declare(strict_types=1);

namespace Denismitr\Translit;


class DictionaryStrategy implements Strategy
{
    /**
     * @var array
     */
    private $dictionary;

    private static $cached = [];

    /**
     * DictionaryStrategy constructor.
     * @param string $dictionaryPath
     */
    public function __construct(string $dictionaryPath)
    {
        if (isset(static::$cached[$dictionaryPath])) {
            $this->dictionary = static::$cached[$dictionaryPath];
        } else {
            if ( ! file_exists($dictionaryPath) || ! is_readable($dictionaryPath)) {
                throw new \InvalidArgumentException("Dictionary {$dictionaryPath} not found or cannot be loaded");
            }

            $this->dictionary = require $dictionaryPath;

            static::$cached[$dictionaryPath] = $this->dictionary;
        }
    }

    public function translate(string $text, ?int $maxLength): ?string
    {
        $result = $this->clearSpecialCharacters(
            $this->parse($text)
        );

        if ($maxLength) {
            return substr($result, 0, $maxLength);
        }

        return $result;
    }

    public function parse(string $text): string
    {
        $lowered = mb_strtolower($text, 'UTF-8');

        $result = '';
        for($ii = 0, $len = mb_strlen($lowered, 'UTF-8'); $ii < $len; $ii++) {
            $current = mb_substr($lowered, $ii, 1);
            $previous = ($ii > 0) ? mb_substr($lowered, $ii - 1, 1) : null;
            $next = ($ii < ($len - 1)) ? mb_substr($lowered, $ii + 1, 1) : null;

            $result .= $this->translateLetter($current, $previous, $next);
        }

        return $result;
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
    private function specialCase($letter, array $subArray, $previous, $current, $next)
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
     * @param string $text
     * @return string
     */
    private function clearSpecialCharacters(string $text): string
    {
        $noWhiteSpaces = str_replace(' ', '-', $text);

        // Remove all characters but words, numbers and dashes
        $alphaNum = preg_replace('/[^\w\-]+/', '', $noWhiteSpaces);

        //Remove double dashes
        $noDoubleDashed = preg_replace('/--+/', '-', $alphaNum);

        //Remove trailing or ending colon
       return trim($noDoubleDashed, '-');
    }
}