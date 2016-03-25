<?php namespace Sanatorium\Mailer\Repositories\Mailtransaction;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class MailtransactionRepository implements MailtransactionRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Mailer\Handlers\Mailtransaction\MailtransactionDataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent mailer model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['sanatorium.mailer.mailtransaction.handler.data'];

		$this->setValidator($app['sanatorium.mailer.mailtransaction.validator']);

		$this->setModel(get_class($app['Sanatorium\Mailer\Models\Mailtransaction']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('sanatorium.mailer.mailtransaction.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.mailer.mailtransaction.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new mailtransaction
		$mailtransaction = $this->createModel();

		// Fire the 'sanatorium.mailer.mailtransaction.creating' event
		if ($this->fireEvent('sanatorium.mailer.mailtransaction.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the mailtransaction
			$mailtransaction->fill($data)->save();

			// Fire the 'sanatorium.mailer.mailtransaction.created' event
			$this->fireEvent('sanatorium.mailer.mailtransaction.created', [ $mailtransaction ]);
		}

		return [ $messages, $mailtransaction ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the mailtransaction object
		$mailtransaction = $this->find($id);

		// Fire the 'sanatorium.mailer.mailtransaction.updating' event
		if ($this->fireEvent('sanatorium.mailer.mailtransaction.updating', [ $mailtransaction, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($mailtransaction, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the mailtransaction
			$mailtransaction->fill($data)->save();

			// Fire the 'sanatorium.mailer.mailtransaction.updated' event
			$this->fireEvent('sanatorium.mailer.mailtransaction.updated', [ $mailtransaction ]);
		}

		return [ $messages, $mailtransaction ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the mailtransaction exists
		if ($mailtransaction = $this->find($id))
		{
			// Fire the 'sanatorium.mailer.mailtransaction.deleted' event
			$this->fireEvent('sanatorium.mailer.mailtransaction.deleted', [ $mailtransaction ]);

			// Delete the mailtransaction entry
			$mailtransaction->delete();

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

}
