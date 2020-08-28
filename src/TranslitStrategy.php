<?php

declare(strict_types=1);

namespace Denismitr\Translit;


interface TranslitStrategy
{
    public function translate(string $text, ?int $maxLength): string;
}