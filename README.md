# PHP templates - An extremely simple, yet wonderful, PHP template system

[![Latest Version][ico-version]][link-packagist]
[![Latest Unstable Version][ico-unstable-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]
[![Coverage Status][ico-code-coverage]][link-code-coverage]
[![Quality Score][ico-code-quality]][link-code-quality]

In the PHP world we have wonderful template engines/systems like [Twig](https://twig.symfony.com/)
and [Plates](http://platesphp.com/). If you need features like inheritance, extensions, built-in helpers etc. you
should go with one of those.

On the other hand, if you just want to be able to create a PHP template like this:

```html
<h1>Hello <?=$name?></h1>
<p>Today's date is <?=$date->format('d.m.Y')?></p>
```

Then this library is for you.

## Installation
```bash
$ composer require setono/php-templates
```

## Usage
In this example I assume your templates are here: `templates/php` and you have a template like this:

```html
<!-- templates/php/hello.php -->
<h1>Hello <?=$name?></h1>
```

```php
<?php
// render.php

use Setono\PhpTemplates\Engine\Engine;

$engine = new Engine();
$engine->addPath('YourNamespace', 'templates/php');

echo $engine->render('@YourNamespace/hello', [
    'name' => 'John Doe',
]);
```

This should output:

```html
<h1>Hello John Doe</h1>
```

[ico-version]: https://poser.pugx.org/setono/php-templates/v/stable
[ico-unstable-version]: https://poser.pugx.org/setono/php-templates/v/unstable
[ico-license]: https://poser.pugx.org/setono/php-templates/license
[ico-github-actions]: https://github.com/Setono/php-templates/workflows/build/badge.svg
[ico-code-coverage]: https://img.shields.io/scrutinizer/coverage/g/Setono/php-templates.svg
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Setono/php-templates.svg

[link-packagist]: https://packagist.org/packages/setono/php-templates
[link-github-actions]: https://github.com/Setono/php-templates/actions
[link-code-coverage]: https://scrutinizer-ci.com/g/Setono/php-templates/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Setono/php-templates
