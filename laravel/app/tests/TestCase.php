<?php
/**
	テストをすると、既存のデータは破棄されるので注意(手抜きですいません）
	refer: http://qiita.com/ngmy/items/c1487991d48ddba9688d
*/

use Mockery as m;

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	protected $useDatabase=true;

	// original
	public function createApplication()
	{
		$unitTesting = true;
		$testEnvironment = 'testing';
		return require __DIR__.'/../../bootstrap/start.php';
	}
	public function setUp()
	{
		parent::setUp();
		if($this->useDatabase) {
			// データベースの初期処理
			$this->setUpDb();
		}
	}
	public function tearDown() {
		parent::tearDown();

		m::close();
		if($this->useDatabase) {
			$this->tearDownDb();
		}
	}
	protected function setUpDb()
	{
		Artisan::call('migrate');
		Artisan::call('db:seed');
	}
	protected function tearDownDb()
	{
		Artisan::call('migrate:reset');
		DB::disconnect();
	}
}
