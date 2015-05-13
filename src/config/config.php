<?php
return array(
    /*
    |--------------------------------------------------------------------------
    | Enable emailing errors
    |--------------------------------------------------------------------------
    |
    | Should we email error traces?
    |
    */
    'enabled' => false,

    /*
    |--------------------------------------------------------------------------
    | Skip emailing errors for some HTTP status codes
    |--------------------------------------------------------------------------
    |
    | For which HTTP status codes should we NOT send error emails?
    |
    */
    'disabledStatusCodes' => array(
        '404' => true,
    ),
	
	'error_template' => 'error-emailer::error',
	
	'subject_template' => 'error-emailer::subject',

    /*
    |--------------------------------------------------------------------------
    | Error email recipients
    |--------------------------------------------------------------------------
    |
    | Email stack traces to these addresses.
    |
    | For a single recipient, the format can just be
    |   'to' => array('address' => 'you@domain.com', 'name' => 'Your Name'),
    |
    | For multiple recipients, just specify an array of those:
    |   'to' => array(
    |       array('address' => 'you@domain.com', 'name' => 'Your Name'),
    |       array('address' => 'me@domain.com', 'name' => 'My Name'),
    |   ),
    |
    */

    'to' => array('address' => null, 'name' => null),

    /*
    |--------------------------------------------------------------------------
    | Ignore Crawler Bots
    |--------------------------------------------------------------------------
    |
    | For which bots should we NOT send error emails?
    |
    */
    'ignoredBots' => array(
        'googlebot',        //Googlebot
        'bingbot',          //Microsoft Bingbot
        'slurp',            //Yahoo! Slurp
        'AhrefsBot',        //AhrefsBot
        'ia_archiver',      //crawler@alexa.com
    ),
);