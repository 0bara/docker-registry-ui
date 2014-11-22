<?php

class GetTest extends TestCase {


	public function setUp()
	{
		parent::setUp();
	}

	/**
	 */
	public function testRidNothing()
	{
		//$this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
		$res = $this->call('GET', '/meta//');
		$this->assertResponseStatus(404);
		/*
		echo "\ncause: ".$res->getContent();
		echo "\n";
		*/
		$edat=json_decode($res->getContent());
		$this->assertEquals(404,$edat->error);
		$this->assertEquals('illegal path.',$edat->message);
	}
	/**
	 */
	public function testInvalidAPIEndpoint()
	{
		//$this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
		$res = $this->call('GET', '/meta');
		$this->assertResponseStatus(404);
		$edat=json_decode($res->getContent());
		$this->assertEquals(404,$edat->error);
		$this->assertEquals('illegal path.',$edat->message);
	}
	public function testShowDefault()
	{
		$res = $this->call('GET', '/meta/centos/');
		$this->assertResponseOk();
/*
		echo "\nbefore content: ".$res->getContent();
		echo "\n";
		$edat=json_decode($res->getContent());
		echo "\nbefore content: ";
		print_r($edat[0]);
		echo "\n";
*/
		$edat=json_decode($res->getContent());
		//$this->assertEquals(1,$edat[0]->id);
		$this->assertEquals('openid',$edat[0]->rid);
		$this->assertEquals('latest',$edat[0]->tag);
	}
	public function testInvalidTag()
	{
		$res = $this->call('GET', '/meta/centos/centos5');
		$this->assertResponseOk();
		// 存在しないtagを指定しても、元々空でも区別しない

		// 以下3つの方法で同じtestができる
		$this->expectOutputString('[]');
		echo $res->getContent();
		//$this->assertEquals('[]',$res->getContent());
		//$this->assertEquals(0,count(json_decode($res->getContent())));
	}
}
