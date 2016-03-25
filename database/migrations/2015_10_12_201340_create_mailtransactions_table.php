<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailtransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mailtransactions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('event');
			$table->text('subject')->nullable();
			$table->text('template')->nullable();
			$table->text('receivers')->nullable();
			$table->timestamps();
		});

		// Templates are saved in DB therefore
		// check if the cache folder is present
		$cache_path = storage_path('/app/db-blade-compiler/views/');

		if ( !file_exists($cache_path) ) {
			mkdir( $cache_path, 0777, true );
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mailtransactions');
	}

}
