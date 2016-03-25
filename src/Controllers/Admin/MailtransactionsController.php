<?php namespace Sanatorium\Mailer\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Mailer\Repositories\Mailtransaction\MailtransactionRepositoryInterface;

use Sanatorium\Mailer\Models\Maillog;
use Carbon\Carbon;

class MailtransactionsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Mailer repository.
	 *
	 * @var \Sanatorium\Mailer\Repositories\Mailtransaction\MailtransactionRepositoryInterface
	 */
	protected $mailtransactions;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Mailer\Repositories\Mailtransaction\MailtransactionRepositoryInterface  $mailtransactions
	 * @return void
	 */
	public function __construct(MailtransactionRepositoryInterface $mailtransactions)
	{
		parent::__construct();

		$this->mailtransactions = $mailtransactions;
	}

	/**
	 * Display a listing of mailtransaction.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/mailer::mailtransactions.index');
	}

	/**
	 * Datasource for the mailtransaction Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->mailtransactions->grid();

		$columns = [
			'id',
			'event',
			'subject',
			//'template',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.mailer.mailtransactions.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new mailtransaction.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new mailtransaction.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating mailtransaction.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating mailtransaction.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified mailtransaction.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->mailtransactions->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/mailer::mailtransactions/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.mailer.mailtransactions.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->mailtransactions->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a mailtransaction identifier?
		if (isset($id))
		{
			if ( ! $mailtransaction = $this->mailtransactions->find($id))
			{
				$this->alerts->error(trans('sanatorium/mailer::mailtransactions/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.mailer.mailtransactions.all');
			}
		}
		else
		{
			$mailtransaction = $this->mailtransactions->createModel();
		}

		// Show the page
		return view('sanatorium/mailer::mailtransactions.form', compact('mode', 'mailtransaction'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the mailtransaction
		list($messages) = $this->mailtransactions->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/mailer::mailtransactions/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.mailer.mailtransactions.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

	public function history($days = 7)
	{
		$histories = [];

		$day = 24*60*60;
		$now = Carbon::now()->timestamp;
		$start = $now - $days*$day;

		$last_valid_rate = 1;

		foreach( Maillog::groupBy('status')->get() as $mailtype ) {
			
			$values = [];

			for ( $i = 0; $i < $days; $i++ ) {

				$day_before = $start + (($i) * $day);
				$day_after = $start + (($i+1) * ($day));
				$history = Maillog::where('status', $mailtype->status)
							->where('created_at', '>', \Carbon\Carbon::createFromTimeStamp($day_before)->format('Y-m-d H:i:s') )
							->where('created_at', '<',  \Carbon\Carbon::createFromTimeStamp($day_after)->format('Y-m-d H:i:s') )
				           ->count();

				if ( $history ) {
					$values[] = [$day_before, $history];

					$last_valid_rate = $history;
				} else {
					$values[] = [$day_before, 0];
				}
			}

			$histories[] = [
				'key' => trans('sanatorium/mailer::status.'.$mailtype->status),
				'values' => $values
			];
		}

		return $histories;
	}

	public function template()
	{
		if ( !request()->has('id') )
			return response('Failed');

		$id = request()->get('id');

		if ( ! $mailtransaction = $this->mailtransactions->find($id))
		{
			return response('Failed');
		}

		return $mailtransaction;
	}

}
