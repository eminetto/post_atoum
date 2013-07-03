<?php
namespace Service;

class Auth
{

	const INVALID_USER = 1; 
	const INVALID_PASSWORD = 2; 
	const VALID_AUTH = 3; 

	private $pdo;

	public function __construct(\PDO $pdo) 
	{
		$this->pdo = $pdo;
	}

	public function authenticate($login, $password)
	{
		//todo: filter parameters!
    	$sth = $this->pdo->prepare('select * from user where login = ?');
		$sth->bindParam(1, $login, \PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetch(\PDO::FETCH_ASSOC);
		if (! $result) {
			return $this::INVALID_USER;
		}
		if ($password != $result['password']) {
			return $this::INVALID_PASSWORD;
		}
	
		return $this::VALID_AUTH;
	}
}