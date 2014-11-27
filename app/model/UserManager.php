<?php

namespace App\Model;

use langosh\database\PDOWrapper;
use Nette\Security\IAuthenticator;
use Nette\Security\AuthenticationException;
use Nette\Security\Identity;
use Nette\Security\Passwords;

class UserManager implements IAuthenticator {

	/** @var PDOWrapper */
	private $database;


	/**
	 * @param PDOWrapper $database
	 */
	public function __construct(PDOWrapper $database) {
		$this->database = $database;
	}


	/**
	 * @return Identity
	 * @throws AuthenticationException
	 */
	public function authenticate(array $credentials) {
		list($login, $password) = $credentials; // Nette uses this *** format
		$user = $this->database->sqlFetchOne('*', 'users', 'login = ?', array($login));
		if (!$user) {
			throw new AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!Passwords::verify($password, $user->pass)) {
			throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif (Passwords::needsRehash($user->pass)) {
			$this->database->sqlUpdate('users', 'id = '.$user->id, array('pass'=>Passwords::hash($password)));
		}

		$this->database->sqlUpdate('users', 'id = '.$user->id, array('lastLoggedIn'=>date('Y-m-d H:i:s')));
		$user->pass = NULL;
		return new Identity($user->id, 'admin', $user);
	}
	
	
	public function add($login, $password, $name = NULL) {
		if( !$name ) {
			$name = $login;
		}
		
		$this->database->sqlInsert('users', array(
			'login' => $login,
			'pass' => Passwords::hash($password),
			'name' => $name
		));
	}

}
