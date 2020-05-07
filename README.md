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
<!-- templates/php/App/hello.php -->
<h1>Hello <?=$name?></h1>
```

The path of this template is divided into three parts: `templates/php` is the path added to the engine.
`App` is the namespace. `hello` is the template name.

Keep this in mind when looking at the rendering of this template:

```php
<?php
// render.php

use Setono\PhpTemplates\Engine\Engine;

$engine = new Engine();
$engine->addPath('templates/php');

echo $engine->render('@App/hello', [
    'name' => 'John Doe',
]);
```

This will output:

```html
<h1>Hello John Doe</h1>
```

### Override templates
If you want to override templates, it is very straight forward. Let's set up the engine first:

```php
<?php
// override.php

use Setono\PhpTemplates\Engine\Engine;

$engine = new Engine();
$engine->addPath('vendor/namespace/src/templates/php'); // The path is added with a default priority of 0
$engine->addPath('templates/php', 10); // Here we set the priority higher than the vendor added path
```

Here is the template we want to override:

```html
<!-- vendor/namespace/src/templates/php/ThirdPartyNamespace/hello.php -->
<h1>Hi <?=$name?>! This template is not rendered, since it is overridden</h1>
```

And here is the template that will override the previous one:
```html
<!-- templates/php/ThirdPartyNamespace/hello.php -->
<h1>Hi <?=$name?>! This template is rendered, yeah!</h1>
```

**Notice** that we override templates by adding a directory with the same name as the original directory. In this case: `ThirdPartyNamespace`.

```php
<?php
echo $engine->render('@ThirdPartyNamespace/hello', [
    'name' => 'John Doe',
]);
```

This will output:

```html
<h1>Hi John Doe! This template is rendered, yeah!</h1>
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
