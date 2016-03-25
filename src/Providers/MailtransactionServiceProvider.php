<?php namespace Sanatorium\Mailer\Providers;

use Cartalyst\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class MailtransactionServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Mailer\Models\Mailtransaction']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.mailer.mailtransaction.handler.event');

		// flynsarmy/db-blade-compiler
		$this->registerDbBladeCompilerPackage();
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.mailer.mailtransaction', 'Sanatorium\Mailer\Repositories\Mailtransaction\MailtransactionRepository');

		// Register the data handler
		$this->bindIf('sanatorium.mailer.mailtransaction.handler.data', 'Sanatorium\Mailer\Handlers\Mailtransaction\MailtransactionDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.mailer.mailtransaction.handler.event', 'Sanatorium\Mailer\Handlers\Mailtransaction\MailtransactionEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.mailer.mailtransaction.validator', 'Sanatorium\Mailer\Validator\Mailtransaction\MailtransactionValidator');
	}

	public function registerDbBladeCompilerPackage()
	{
		$serviceProvider = 'Flynsarmy\DbBladeCompiler\DbBladeCompilerServiceProvider';

		if (!$this->app->getProvider($serviceProvider)) {
			$this->app->register($serviceProvider);

			AliasLoader::getInstance()->alias('DbView', 'Flynsarmy\DbBladeCompiler\Facades\DbView');
		}
/*
		$this->app['dbview']->directive('hook', function ($value) {
            return "<?php echo Widget::make('sanatorium/hooks::hook.show', array$value); ?>";
        });*/
	}
}
