# Yandex-standard russian-english transliteration

##Version
3.0
requires PHP >= 7.2

## Author
Denis Mitrofanov <denis.mitr@gmail.com>
[TheCollection](https://thecollection.ru)

## Installation
```
composer require denismitr/translit
```

##Usage
```php
    $translit = new \Denismitr\Translit\Translit();

    $slug = $translit->transform("Строка для транслитерации, по правилам Яндекс!");
    //stroka-dlya-transliteracii-po-pravilam-yandeksa
```

To define max length of the output do:
```php
$slug = (new \Denismitr\Translit\Translit)->transform("очень длинный текст...", 10);
// ochen-dlin
```

You can provide your own translit implementation as long as it implements the 
`\Denismitr\Translit\TranslitStrategy` interface
and inject it into the `Translit` class constructor, this way it will override the default behavior like so:
```php
new \Denismitr\Translit\Translit(new YourTranslitStrategyImpl());
```

#### Run tests
```bash
composer test
```