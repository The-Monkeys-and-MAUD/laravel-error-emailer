![The Monkeys](http://www.themonkeys.com.au/img/monkey_logo.png)

Laravel Error Emailer
=====================

Don't have a sysadmin keeping an eye on your application's error logs? Just add this package to your Laravel application
and you'll be sent an email with plenty of diagnostic information whenever an error occurs.


Installation Laravel 4.x
-------------------------
To get the latest version of the package simply require it in your composer.json file.

```bash
composer require themonkeys/error-emailer:dev-master --no-update
composer update themonkeys/error-emailer
```

Once the package is installed you need to register the service provider with the application. Open up
`app/config/app.php` and find the `providers` key.

```php
'providers' => array(
    'Themonkeys\ErrorEmailer\ErrorEmailerServiceProvider',
)
```

Add the following to the `facades` key:

```
'facades' => array(
    'ErrorEmailer' => 'Themonkeys\ErrorEmailer\Facades\ErrorEmailer',
)
```

The package comes disabled by default, since you probably don't want error emailing enabled on your development
environment. Especially if you've set `'debug' => true,`.

To configure the package, you can use the following command to copy the configuration file to
`app/config/packages/themonkeys/error-emailer`.

```sh
php artisan config:publish themonkeys/error-emailer
```

Or you can just create a new file in that folder and only override the settings you need.

The settings themselves are documented inside `config.php`. A minimal config file to enable error emails and set two
recipients can be as simple as:

```php
<?php
return array(
    'enabled' => true,
    'to' => array(
        array('address' => 'you@host.com.au', 'name' => 'Your Name'),
        array('address' => 'me@host.com.au', 'name' => 'My Name'),
    ),
);
```

To make your configuration apply only to a particular environment, put your configuration in an environment folder such
as `app/config/packages/themonkeys/error-emailer/environment-name/config.php`.

Configuring emails
------------------

For the error emails to be sent, your application needs to be properly configured to send email. Open your 
`app/config/mail.php` file to configure default settings, and override those defaults for your application's other
environments by adding `app/config/<environment>/mail.php` files as necessary. In particular, make sure you've set up
a default sender address - without one, the error emailer won't be able to send emails:

```php
    'from' => array('address' => 'someone@somedomain.com', 'name' => 'My Application'),
```

Error handler precedence
------------------------

This package intercepts errors in the same way as your application can, by registering an error handler with the 
application. The default Laravel application includes an empty error handler in `app/start/global.php`:

```php
App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});
```

Because of the way `App::error()` works, this handler is called _before_ this package's handler; so if you return a 
response from the handler in `app/start/global.php` (for example to render a custom error page), you won't receive any 
error emails. To fix this, our recommended approach is to change the priority of the handler in `app/start/global.php`
so that it runs last instead of first:

```php
App::pushError(function(Exception $exception, $code)
{
	return View::make('myerrorpage', array(
	    'exception' => $exception,
	    'code' => $code,
    ));
});
```

(Note the change from `App::error` to `App::pushError`).

Contribute
----------

In lieu of a formal styleguide, take care to maintain the existing coding style.

License
-------

MIT License
(c) [The Monkeys](http://www.themonkeys.com.au/)
