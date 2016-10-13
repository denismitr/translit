# Yandex-standard russian-english transliteration

##Version
0.1.5

## Author
Denis Mitrofanov
[TheCollection](https://thecollection.ru)

## Installation
```
composer require denismitr/translit
```

##Usage
```php
    $translit = new \Denismitr\Translit\Translit("Строка для транслитерации, по правилам Яндекс!");

    $slug = $translit->getSlug();
    //stroka-dlya-transliteracii-po-pravilam-yandeksa
```
or
```php
    $slug = (new \Denismitr\Translit\Translit)->forString("Привет всем!")->getSlug();
    //privet-vsem
```
To process strings longer than 255 chars use getTranslit() instead of getSlug()
that cuts max length of slug by default to 255

To define your own max length of the slug use
```php
$slug = (new \Denismitr\Translit\Translit)->forString("длинный текст...")->setMaxLength(150)->getSlug();
```
or second parameter of Translit class constructor like this:
```php
$translit = new Translit("some very very long text...", 20); //will cut the slug to 20 chars
```
