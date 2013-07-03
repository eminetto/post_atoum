<?php
namespace tests\units\Service;

include __DIR__ . '/../bootstrap.php';

use mageekguy\atoum\test;
use Service\Auth as AuthService;
use mageekguy\atoum\reports;

class Auth extends test
{
	private $validUser = 'kratos';
	private $validPassowrd = '1ca308df6cdb0a8bf40d59be2a17eac1';
    private $pdo;

    public function beforeTestMethod($testMethod) {
        $this->pdo = new \PDO('sqlite:memory');
        $this->pdo->query('create table user (id INTEGER PRIMARY KEY AUTOINCREMENT, login text, password text)');
        $sth = $this->pdo->prepare('insert into user values(null,?,?)');
        $sth->bindParam(1, $this->validUser, \PDO::PARAM_STR);
        $sth->bindParam(2, $this->validPassowrd, \PDO::PARAM_STR);
        $sth->execute();
    }

    public function testInvalidUser()
    {
 		$auth = new AuthService($this->pdo);
    	$result = $auth->authenticate('invalidUser', $this->validPassowrd);
    	$this->assert->integer($result)
    				 ->isEqualTo(AuthService::INVALID_USER);
    }

    public function testInvalidPassword() 
    {
    	$auth = new AuthService($this->pdo);
    	$result = $auth->authenticate($this->validUser, 'invalidPassword');

    	$this->assert->integer($result)
    				 ->isEqualTo(AuthService::INVALID_PASSWORD);
    }

    public function testValidAuth() 
    {
    	$auth = new AuthService($this->pdo);
    	$result = $auth->authenticate($this->validUser, $this->validPassowrd);

    	$this->assert->integer($result)
    				 ->isEqualTo(AuthService::VALID_AUTH);
    }
}