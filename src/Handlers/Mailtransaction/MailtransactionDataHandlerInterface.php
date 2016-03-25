<?php namespace Sanatorium\Mailer\Handlers\Mailtransaction;

interface MailtransactionDataHandlerInterface {

	/**
	 * Prepares the given data for being stored.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function prepare(array $data);

}
