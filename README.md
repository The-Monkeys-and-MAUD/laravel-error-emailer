![The Monkeys](http://www.themonkeys.com.au/img/monkey_logo.png)

Laravel Error Emailer
=====================

Don't have a sysadmin keeping an eye on your application's error logs? Just add this package to your Laravel application
and you'll be sent an email with plenty of diagnostic information whenever an error occurs.


Installation
------------
To get the latest version of cachebuster simply require it in your composer.json file.

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


Contribute
----------

In lieu of a formal styleguide, take care to maintain the existing coding style.

License
-------

MIT License
(c) [The Monkeys](http://www.themonkeys.com.au/)
