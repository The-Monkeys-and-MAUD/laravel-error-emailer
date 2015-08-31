<?php namespace Themonkeys\ErrorEmailer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

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
        $app = $this->app;
        $app['ErrorEmailer'] = $this->app->share(function($app) {
            return new ErrorEmailer();
        });
        $app->error(function(\Exception $exception, $code) use ($app) {
            if (
                ( Config::get('error-emailer::run_in_console') || !$app->runningInConsole() ) &&
                Config::get('error-emailer::enabled') &&
                !Config::get('error-emailer::disabledStatusCodes.'.$code)) {

                $app['ErrorEmailer']->sendException($exception);
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