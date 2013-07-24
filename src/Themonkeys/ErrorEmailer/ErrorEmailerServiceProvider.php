<?php namespace Themonkeys\ErrorEmailer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class ErrorEmailerServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('themonkeys/error-emailer');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->error(function(\Exception $exception, $code) {
            if (!App::runningInConsole() &&
                Config::get('error-emailer::enabled') &&
                Config::get('error-emailer::to.address') &&
                !Config::get('error-emailer::disabledStatusCodes.'.$code)) {

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
                Mail::send('error-emailer::error', $model, function($message) use ($model) {
                    $subject = View::make('error-emailer::subject', $model)->render();

                    $message->to(
                        Config::get("error-emailer::to.address"),
                        Config::get("error-emailer::to.name")
                    )->subject($subject);
                });
            }
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}