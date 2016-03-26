<?php namespace Sanatorium\Mailer\Handlers\Mailtransaction;

use Illuminate\Events\Dispatcher;
use Sanatorium\Mailer\Models\Mailtransaction;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;
use Mail;
use Event;
use Cartalyst\Themes\Laravel\Facades\Theme;

class MailtransactionEventHandler extends BaseEventHandler implements MailtransactionEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.mailer.mailtransaction.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.mailer.mailtransaction.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.mailer.mailtransaction.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.mailer.mailtransaction.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.mailer.mailtransaction.deleted', __CLASS__.'@deleted');

		$listening = [];

		foreach( Mailtransaction::all() as $mailtransaction ) 
		{
			if ( !in_array($mailtransaction->event, $listening) )
				$dispatcher->listen($mailtransaction->event, __CLASS__.'@generic');

			$listening[] = $mailtransaction->event;
		}
	}

	public $last_receivers = [];

	public function generic($object = null)
	{
		$event_name = Event::firing();

		$mailtransactions = Mailtransaction::where('event', $event_name)->get();

		if ( $mailtransactions ) {

			// Use queue or send right away
			if ( config('sanatorium-mailer.queue') ) {
				$method = 'queue';
			} else {
				$method = 'send';
			}

			// Set frontend theme for mailing
			Theme::setActive(config("platform-themes.active.frontend"));
			Theme::setFallback(config("platform-themes.fallback.frontend"));

			foreach( $mailtransactions as $mailtransaction ) {

				$content = \DbView::make($mailtransaction)->field('template')->with(['object' => $object])->render();

				$result = Mail::$method('sanatorium/mailer::blank', ['content' => $content], function ($m) use ($mailtransaction, $object, $event_name) {
		            
		            $receivers_raw = \DbView::make($mailtransaction)->field('receivers')->with(['object' => $object])->render();

		            $receivers = explode("\n", $receivers_raw);

		            for ( $i = 0; $i <= count($receivers); $i++ ) {

		            	if ( !isset($receivers[$i]) )
		            		continue;

		            	if ( $i == 0 )
		            		$m->to(trim($receivers[$i]));
		            	else
		            		$m->cc(trim($receivers[$i]));

		            	$this->last_receivers[] = trim($receivers[$i]);

		            }

		            $subject = \DbView::make($mailtransaction)->field('subject')->with(['object' => $object])->render();
		            
		            $m->subject( $subject );

		        });

		        foreach( $this->last_receivers as $receiver ) {

		        	if( count(Mail::failures()) > 0 ) {
		        		$response = 'hard_bounce';
		        	} else {
		        		$response = 'delivered';
		        	}

		        	\Sanatorium\Mailer\Models\Maillog::create([
		        		'event' => $event_name,
		        		'receiver' => $receiver,
		        		'status' => $response
		        		]);

		        }

			}
			
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(Mailtransaction $mailtransaction)
	{
		$this->flushCache($mailtransaction);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Mailtransaction $mailtransaction, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Mailtransaction $mailtransaction)
	{
		$this->flushCache($mailtransaction);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Mailtransaction $mailtransaction)
	{
		$this->flushCache($mailtransaction);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Mailer\Models\Mailtransaction  $mailtransaction
	 * @return void
	 */
	protected function flushCache(Mailtransaction $mailtransaction)
	{
		$this->app['cache']->forget('sanatorium.mailer.mailtransaction.all');

		$this->app['cache']->forget('sanatorium.mailer.mailtransaction.'.$mailtransaction->id);
	}

}
