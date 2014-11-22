<?php
class DatabaseSeeder extends Seeder {
	public function run()
	{
		Eloquent::unguard();
		$this->call('InfoTableSeeder');
		$this->command->info('Info table seeded!');
	}
}

class InfoTableSeeder extends Seeder {
	public function run()
	{
		DB::table('info')->delete();
		Info::create(array(
			'rid'=>'openid',
			'tag'=>'latest',
			'title'=>'PHP-OpenIDサンプル',
			'descript'=>'sslについては、/usr/share/sslから参照できるよう(-vオプション)にすること。')
			);
	}
}
