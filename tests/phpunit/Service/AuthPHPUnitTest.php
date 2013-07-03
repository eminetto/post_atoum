<?php

use Service\Auth;

class AuthPHPUnitTest extends \PHPUnit_Framework_TestCase
{

	private $validUser = 'kratos';
	private $validPassowrd = '1ca308df6cdb0a8bf40d59be2a17eac1';
    private $pdo;

	/**
     * Faz o setup dos testes
     * @return void
     */
    public function setup()
    {
        parent::setup();
        $this->pdo = new \PDO('sqlite:memory');
        $this->pdo->query('create table user (id INTEGER PRIMARY KEY AUTOINCREMENT, login text, password text)');
        $sth = $this->pdo->prepare('insert into user values(null,?,?)');
        $sth->bindParam(1, $this->validUser, \PDO::PARAM_STR);
        $sth->bindParam(2, $this->validPassowrd, \PDO::PARAM_STR);
        $sth->execute();
    }

    public function testInvalidUser() 
    {
    	$auth = new Auth($this->pdo);
    	$result = $auth->authenticate('invalidUser', $this->validPassowrd);

    	$this->assertEquals($result, Auth::INVALID_USER);
    }

    public function testInvalidPassword() 
    {
    	$auth = new Auth($this->pdo);
    	$result = $auth->authenticate($this->validUser, 'invalidPassword');

    	$this->assertEquals($result, Auth::INVALID_PASSWORD);
    }

    public function testValidAuth() 
    {
    	$auth = new Auth($this->pdo);
    	$result = $auth->authenticate($this->validUser, $this->validPassowrd);

    	$this->assertEquals($result, Auth::VALID_AUTH);
    }
}