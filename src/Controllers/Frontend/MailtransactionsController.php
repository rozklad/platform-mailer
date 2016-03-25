<?php namespace Sanatorium\Mailer\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;

class MailtransactionsController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/mailer::index');
	}

}
