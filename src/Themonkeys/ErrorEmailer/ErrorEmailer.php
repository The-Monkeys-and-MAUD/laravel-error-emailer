<?php

namespace Themonkeys\ErrorEmailer;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Debug\Exception\FlattenException;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Debug\ExceptionHandler;
use Illuminate\Support\Facades\View;

class ErrorEmailer {
    public function sendException($exception)
    {
		// From
		$from = Config::get("error-emailer::from");
		if (!isset($from['address'])) {
			App::fatal(function ($exception) {
               return 'Cannot send message without a sender address';
			});
		}

		// To
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
            Mail::send('error-emailer::error', $model, function($message) use ($model, $recipients) {
                $subject = View::make('error-emailer::subject', $model)->render();

				$message->from($from['address'], $from['name']);

                $message->subject($subject);
                foreach ($recipients as $to) {
                    $message->to($to['address'], $to['name']);
                }
            });
        }
    }
}