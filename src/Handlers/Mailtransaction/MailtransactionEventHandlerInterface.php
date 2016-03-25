<?php namespace Sanatorium\Mailer\Handlers\Mailtransaction;

use Sanatorium\Mailer\Models\Mailtransaction;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface MailtransactionEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a mailtransaction is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a mailtransaction is created.
	 *
	 * @param  \Sanatorium\Mailer\Models\Mailtransaction  $mailtransaction
	 * @return mixed
	 */
	public function created(Mailtransaction $mailtransaction);

	/**
	 * When a mailtransaction is being updated.
	 *
	 * @param  \Sanatorium\Mailer\Models\Mailtransaction  $mailtransaction
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Mailtransaction $mailtransaction, array $data);

	/**
	 * When a mailtransaction is updated.
	 *
	 * @param  \Sanatorium\Mailer\Models\Mailtransaction  $mailtransaction
	 * @return mixed
	 */
	public function updated(Mailtransaction $mailtransaction);

	/**
	 * When a mailtransaction is deleted.
	 *
	 * @param  \Sanatorium\Mailer\Models\Mailtransaction  $mailtransaction
	 * @return mixed
	 */
	public function deleted(Mailtransaction $mailtransaction);

}
