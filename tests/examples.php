<?php
use werx\Messages\Messages;

$vendor_dir = dirname(dirname(__FILE__)) . '/vendor';

require $vendor_dir . '/autoload.php';

// Get an instance of the Messages class.
Messages::getInstance();

Messages::error('Oops, something bad happened');
Messages::info('Nothing big, this is just a informational message.');
Messages::success('Hooray! This is a success message');
Messages::success('Here is another success message.');
print_r(Messages::all());


Messages::error('Oops, something bad happened');
Messages::info('Nothing big, this is just a informational message.');
Messages::success('Hooray! This is a success message');
Messages::success('Here is another success message.');

print Messages::display();


Messages::setDecorator(new \werx\Messages\Decorators\Bootstrap);
Messages::error('Oops, something bad happened');
Messages::info('Nothing big, this is just a informational message.');
Messages::success('Hooray! This is a success message');
Messages::success('Here is another success message.');
print Messages::display();
