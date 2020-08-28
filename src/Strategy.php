<?php

declare(strict_types=1);

namespace Denismitr\Translit;


interface Strategy
{
    public function translate(?string $text, ?int $maxLength): ?string;
}