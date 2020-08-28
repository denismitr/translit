<?php

declare(strict_types=1);

namespace Denismitr\Translit;


final class StrategyFactory
{
    private const DICTIONARY_STRATEGY = 'dictionary';

    public static function make(string $translitStrategy): Strategy
    {
        switch ($translitStrategy) {
            case self::DICTIONARY_STRATEGY:
                $dictionaryPath = self::resolveDictionaryPath();
                return new DictionaryStrategy($dictionaryPath);
            default:
                throw new \InvalidArgumentException("Unsupported translit strategy {$translitStrategy}");
        }
    }

    public static function availableStrategies(): array
    {
        return [self::DICTIONARY_STRATEGY];
    }

    /**
     * @return string
     */
    protected static function resolveDictionaryPath(): string
    {
        return dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'dictionary.php';
    }
}