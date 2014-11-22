<?php

class ModTest extends TestCase {

	protected $rid;
	protected $title;
	protected $desc;

	public function setUp()
	{
		parent::setUp();

		$this->rid="centos";
		$this->title="Official CentOS base image";
		$this->desc="tagでCentOSのバージョンを示す";
	}
	protected function addTestData()
	{
		$res=$this->call('POST',"/meta/$this->rid", ['title'=>$this->title, 'descript'=>$this->desc ]);
		return $res;
	}

	/**
	 */
	public function testAddText()
	{
		//echo __METHOD__.":".__LINE__."\n";
		$res=$this->addTestData();

		$this->assertResponseOk();
		$this->assertEquals('[OK]', $res->getContent());

		$res = $this->call('GET', "/meta/$this->rid");
		$this->assertResponseOk();
		$edat=json_decode($res->getContent());
		//$this->assertEquals(2,$edat[0]->id);
		$this->assertEquals($this->rid,$edat[0]->rid);
		$this->assertEquals('latest',$edat[0]->tag);

		//echo __METHOD__.":".__LINE__."\n";
		// ここでthisを返却する事で、上記のデータ更新が保持され、次のテストの際も有効になる
		return $this;
	}

	/**
	 * @depends testAddText
	 */
	public function testModTitle()
	{
		$res=$this->addTestData();
		$this->assertResponseOk();
		/*
		echo __METHOD__.":".__LINE__."\n";
		$res = $this->call('GET', "/meta/$this->rid");
		$this->assertResponseOk();
		echo "\n".__METHOD__."[";
		echo print_r($res->getContent());
		//echo print_r($res);
		echo "]\n";
		return;
		*/

		// titleのみ指定した場合、titleのみ修正される
		$newTitle="Official CentOS7 base image";
		$res=$this->call('POST',"/meta/$this->rid", ['title'=>$newTitle ]);
		$this->assertResponseOk();
		$this->assertEquals('[OK]', $res->getContent());

		$res = $this->call('GET', "/meta/$this->rid");
		$this->assertResponseOk();
		$edat=json_decode($res->getContent());
		/*
		echo "\n[";
		echo $edat[0]->descript;
		echo "]\n";
		*/
		//$this->assertEquals(2,$edat[0]->id);
		$this->assertEquals($this->rid,$edat[0]->rid);
		$this->assertEquals('latest',$edat[0]->tag);
		$this->assertEquals($newTitle,$edat[0]->title);
		$this->assertEquals($this->desc,$edat[0]->descript);
		//echo __METHOD__.":".__LINE__."\n";
	}

	public function testModDesc()
	{
		$res=$this->addTestData();
		$this->assertResponseOk();

		// descriptのみ指定した場合、descriptのみ修正される
		$newDesc="DBは、DB_PORT_3306_TCP_ADDR,DB_PORT_3306_TCP_PORTを参照する. sslについては、/usr/share/sslから参照できるよう(-vオプション)にすること。server.cert/server_nopass.keyが存在する事";
		$res=$this->call('POST',"/meta/$this->rid", ['descript'=>$newDesc ]);
		$this->assertResponseOk();
		$this->assertEquals('[OK]', $res->getContent());

		$res = $this->call('GET', "/meta/$this->rid");
		$this->assertResponseOk();
		$edat=json_decode($res->getContent());
		/*
		echo "\n[";
		echo $edat[0]->descript;
		echo "]\n";
		*/
		//$this->assertEquals(2,$edat[0]->id);
		$this->assertEquals($this->rid,$edat[0]->rid);
		$this->assertEquals('latest',$edat[0]->tag);
		$this->assertEquals($this->title,$edat[0]->title);
		$this->assertEquals($newDesc,$edat[0]->descript);
		//echo __METHOD__.":".__LINE__."\n";
	}

	/**
	 */
	public function testDelTitle()
	{
		$res=$this->addTestData();
		$this->assertResponseOk();

		// titleのみ指定した場合、titleのみ修正される
		$res=$this->call('POST',"/meta/$this->rid", ['title'=>""]);
		$this->assertResponseOk();
		$this->assertEquals('[OK]', $res->getContent());

		$res = $this->call('GET', "/meta/$this->rid");
		$this->assertResponseOk();
		$edat=json_decode($res->getContent());
		/*
		echo "\n[";
		echo $edat[0]->descript;
		echo "]\n";
		*/
		//$this->assertEquals(2,$edat[0]->id);
		$this->assertEquals($this->rid,$edat[0]->rid);
		$this->assertEquals('latest',$edat[0]->tag);
		$this->assertEquals("",$edat[0]->title);
		$this->assertEquals($this->desc,$edat[0]->descript);
		//echo __METHOD__.":".__LINE__."\n";
	}

	public function testDelDesc()
	{
		$res=$this->addTestData();
		$this->assertResponseOk();

		// descriptのみ指定した場合、descriptのみ修正される
		$res=$this->call('POST',"/meta/$this->rid", ['descript'=>""]);
		$this->assertResponseOk();
		$this->assertEquals('[OK]', $res->getContent());

		$res = $this->call('GET', "/meta/$this->rid");
		$this->assertResponseOk();
		$edat=json_decode($res->getContent());
		/*
		echo "\n[";
		echo $edat[0]->descript;
		echo "]\n";
		*/
		//$this->assertEquals(2,$edat[0]->id);
		$this->assertEquals($this->rid,$edat[0]->rid);
		$this->assertEquals('latest',$edat[0]->tag);
		$this->assertEquals($this->title,$edat[0]->title);
		$this->assertEquals("",$edat[0]->descript);
		//echo __METHOD__.":".__LINE__."\n";
	}

}
