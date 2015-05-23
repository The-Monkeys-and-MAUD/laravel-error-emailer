<?php

namespace Themonkeys\ErrorEmailer;

use Illuminate\Support\Facades\Config;
use Symfony\Component\Debug\Exception\FlattenException;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Debug\ExceptionHandler;
use Illuminate\Support\Facades\View;

class ErrorEmailer
{
    public function sendException($exception)
    {
        if (!$this->isErrorFromBot()) {
            $recipients = Config::get("error-emailer::to");
            if (isset($recipients['address'])) {
                // this is a single recipient
                if ($recipients['address']) {
                    $recipients = array($recipients);
                } else {
                    $recipients = array();
                }
            }

            if (sizeof($recipients) > 0) {
                if ($exception instanceof FlattenException) {
                    $flattened = $exception;
                } else {
                    $flattened = FlattenException::create($exception);
                }
                $handler = new ExceptionHandler();
                $content = $handler->getContent($flattened);

                $model = array(
                    'trace' => $content,
                    'exception' => $exception,
                    'flattened' => $flattened
                );
                Mail::send(Config::get("error-emailer::error_template"), $model, function ($message) use ($model, $recipients) {
                    $subject = View::make(Config::get("error-emailer::subject_template"), $model)->render();

                    $message->subject($subject);
                    foreach ($recipients as $to) {
                        $message->to($to['address'], $to['name']);
                    }
                });
            }
        }
    }

    protected function isErrorFromBot()
    {
        $ignoredBots = Config::get("error-emailer::ignoredBots");
        $serverUserAgent = array_key_exists('HTTP_USER_AGENT', $_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : null;
        $serverFrom = array_key_exists('HTTP_FROM', $_SERVER) ? $_SERVER['HTTP_FROM'] : null;
        if (is_array($ignoredBots)) {
            foreach ($ignoredBots as $bot) {
                if (($serverUserAgent && strpos(strtolower($serverUserAgent), $bot) !== false) ||
                    ($serverFrom && strpos(strtolower($serverFrom), $bot) !== false)
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}
