# werx\messages

[![Source](https://img.shields.io/badge/source-werx/messages-blue.svg?style=flat-square)](https://github.com/werx/messages) [![Build Status](https://img.shields.io/travis/werx/messages.svg?style=flat-square)](https://travis-ci.org/werx/messages) [![Total Downloads](https://img.shields.io/packagist/dt/werx/messages.svg?style=flat-square)](https://packagist.org/packages/werx/messages) [![Latest Stable Version](https://img.shields.io/packagist/v/werx/messages.svg?style=flat-square)](https://packagist.org/packages/werx/messages)

Simple package for displaying various types of messages in a web app.

## Usage

```php
<?php
use werx\Messages\Messages;

// Import the composer autoloader, if you aren't already using it.
require '../vendor/autoload.php';

// Get an instance of the Messages class.
Messages::getInstance();
```

Now you can add some messages. Valid messages types are error, info, and success.

```php
Messages::error('Oops, something bad happened.');
Messages::info('Nothing big, this is just a informational message.');
Messages::success('Hooray! This is a success message.');
Messages::success('Here is another success message.');
```

You can also use `printf` compatible format codes in your message strings along with an array as the 2nd parameter containing string replacements to make it easier to embed dynamic data in your messages without doing a lot of string concatenation.

```php
Messages::error('The quick %s %s jumped over the %s.', ['brown', 'fox', 'log']);

// The quick brown fox jumped over the log.
```

If you are only using one replacement, you can pass a string as the 2nd param instead of an array.


```php
Messages::error('The quick brown fox jumped over the %s.', 'lazy dog');

// The quick brown fox jumped over the lazy dog.
```

Once you've added the messages to the stack, you have a couple options.

1) Fetch all the messages back as an array.

```php
$items = Messages::all();
print_r($items);

/*
Array
(
    [error] => Array
        (
            [0] => Oops, something bad happened
        )

    [info] => Array
        (
            [0] => Nothing big, this is just a informational message.
        )

    [success] => Array
        (
            [0] => Hooray! This is a success message
            [1] => Here is another success message.
        )

)
*/
```

2) Display the messages using a decorator

```php
$display = Messages::display();
print $display;
```

The above renders something like this:

```html
<ul class="error">
    <li>Oops, something bad happened</li>
</ul>

<ul class="info">
    <li>Nothing big, this is just a informational message.</li>
</ul>

<ul class="success">
    <li>Hooray! This is a success message</li>
    <li>Here is another success message.</li>
</ul>
```

## Decorators
By default, a simple decorator will be used that wraps the messages in a series of unordered lists as shown above. The `<ul>` for each type of message (`error`, `info`, `success`) will be classed with the name of the message type.

If you are using [Bootstrap](http://getbootstrap.com/) for your design, you can specify that messages should be decorated using the [Bootstrap Alert HTML Markup](http://getbootstrap.com/components/#alerts) instead.

If you want to create your own decorator, just create a class that implements `werx\Messages\Decorators\DecoratorInterface` and pass an instance to `Messages::setDecorator()`.

```php
Messages::setDecorator(new Decorators\Bootstrap);
$display = Messages::display();
print $display;
```

Renders:

```html
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <ul>
        <li>Oops, something bad happened</li>
    </ul>
</div>

<div class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <ul>
        <li>Nothing big, this is just a informational message.</li>
    </ul>
</div>

<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <ul>
        <li>Hooray! This is a success message</li>
        <li>Here is another success message.</li>
    </ul>
</div>
```
## Sessions

By storing messages in session, they can persist across multiple page loads until you either display or delete them.

By default, this library will create a new instance of the [Symfony Native Session Storage](http://api.symfony.com/2.6/Symfony/Component/HttpFoundation/Session/Storage/NativeSessionStorage.html) object for storage of messages. If you already have an instance of a Symfony Session Interface, you can pass that to `Messages::getInstance()`.

```php
// Create a new session object.
$session = new \Symfony\Component\HttpFoundation\Session\Session(
				new \Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage(['cookie_lifetime' => 604800])
);

Messages::getInstance($session);
```

## Installation
This package is installable and autoloadable via Composer as [werx/messages](https://packagist.org/packages/werx/messages). If you aren't familiar with the Composer Dependency Manager for PHP, [you should read this first](https://getcomposer.org/doc/00-intro.md).

## Contributing

### Unit Testing

``` bash
$ vendor/bin/phpunit
```

### Coding Standards
This library uses [PHP_CodeSniffer](http://www.squizlabs.com/php-codesniffer) to ensure coding standards are followed.

I have adopted the [PHP FIG PSR-2 Coding Standard](http://www.php-fig.org/psr/psr-2/) EXCEPT for the tabs vs spaces for indentation rule. PSR-2 says 4 spaces. I use tabs. No discussion.

To support indenting with tabs, I've defined a custom PSR-2 ruleset that extends the standard [PSR-2 ruleset used by PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/blob/master/CodeSniffer/Standards/PSR2/ruleset.xml). You can find this ruleset in the root of this project at PSR2Tabs.xml

Executing the codesniffer command from the root of this project to run the sniffer using these custom rules.


	$ ./codesniffer
