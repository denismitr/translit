<?php

declare(strict_types=1);

namespace Denismitr\Translit;

class Translit
{
    /**
     * @var Strategy
     */
    private $strategy;


    public function __construct(Strategy $strategy = null)
    {
        if ( ! $strategy) {
            $strategy = StrategyFactory::make('dictionary');
        }

        $this->strategy = $strategy;
    }

    public function transform(?string $text, int $maxLength = null): ?string
    {
        if ($text === null) {
            return null;
        }

        return $this->strategy->translate($text, $maxLength);
    }
}
