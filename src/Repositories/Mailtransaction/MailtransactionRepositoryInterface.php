<?php namespace Sanatorium\Mailer\Repositories\Mailtransaction;

interface MailtransactionRepositoryInterface {

	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \Sanatorium\Mailer\Models\Mailtransaction
	 */
	public function grid();

	/**
	 * Returns all the mailer entries.
	 *
	 * @return \Sanatorium\Mailer\Models\Mailtransaction
	 */
	public function findAll();

	/**
	 * Returns a mailer entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \Sanatorium\Mailer\Models\Mailtransaction
	 */
	public function find($id);

	/**
	 * Determines if the given mailer is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given mailer is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates or updates the given mailer.
	 *
	 * @param  int  $id
	 * @param  array  $input
	 * @return bool|array
	 */
	public function store($id, array $input);

	/**
	 * Creates a mailer entry with the given data.
	 *
	 * @param  array  $data
	 * @return \Sanatorium\Mailer\Models\Mailtransaction
	 */
	public function create(array $data);

	/**
	 * Updates the mailer entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Sanatorium\Mailer\Models\Mailtransaction
	 */
	public function update($id, array $data);

	/**
	 * Deletes the mailer entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}
