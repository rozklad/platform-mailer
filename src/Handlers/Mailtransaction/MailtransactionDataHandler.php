<?php namespace Sanatorium\Mailer\Handlers\Mailtransaction;

class MailtransactionDataHandler implements MailtransactionDataHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function prepare(array $data)
	{
		return $data;
	}

}
