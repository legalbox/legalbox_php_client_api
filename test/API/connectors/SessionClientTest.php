<?php
use API\connectors\SessionClient;

class SessionClientTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @covers API\connectors\SessionClient::__construct
	 */
	public function testConstructor()
	{
		$identifierOrEmail = 'test';
		$password = 'test';
		$SessionClient = new SessionClient($identifierOrEmail, $password);
		
		$this->assertFalse($SessionClient);
	}
}