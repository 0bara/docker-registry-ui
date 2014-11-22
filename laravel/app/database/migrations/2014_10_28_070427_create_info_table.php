<?php
// vim: ts=4:sw=4:tw=80:ai:syntax=off

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('info',function($table)
		{
			$table->increments('id');
			// rid: docker image id
			$table->string('rid');
			// tag: docker image tag
			$table->string('tag');
			// これが今回追加したいもの!
			$table->string('title',80);
			$table->text('descript');
			$table->timestamps();
			$table->unique('rid','tag');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('info');
	}

}
