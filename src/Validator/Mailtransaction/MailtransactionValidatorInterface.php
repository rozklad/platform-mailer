<?php namespace Sanatorium\Mailer\Validator\Mailtransaction;

interface MailtransactionValidatorInterface {

	/**
	 * Updating a mailtransaction scenario.
	 *
	 * @return void
	 */
	public function onUpdate();

}
