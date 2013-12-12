<?php

/**
 * Auth class (project specific)
 * 
 * @author Timo Witte <timo.witte@googlemail.com>
 * @copyright 2009 Timo Witte
 * @licence GPLv3
 */
class Auth {
	private $app;
	private $db;

	public static function get(Application $app) {
		$auth = new Auth();
		$auth->setApp($app);
		$auth->setDB($app->DB);

		return $auth;
	}

	public function __construct() {

	}

	public function setApp(Application $app) {
		$this->app = $app;
	}

	public function setDB(DB $db) {
		$this->db = $db;
	}

	public function forceAuth() {
		$this->app->Log->debug(_("Forcing Auth"));
		if(!$this->isAuthed()) {
			// redirect
			$p = isset($_GET['p']) ? $_GET['p'] : "overview";
			$pp = isset($_GET['pp']) ? $_GET['pp'] : "";
			$newloc = "?p=login&pp[redirect_page]=".$_GET['p']."&pp[redirect_params]=".urlencode(serialize($pp));
			header("Location: ".$newloc, 307);
			echo _("you are not authed and should have been redirected!");
			exit;
		}
	}

	public function forceAdmin() {
		if(!$this->isAdmin()) {
			throw new Exception(_("Only admins have permission to access this area!"));
		}
	}

	public function isAuthed() {
		if(isset($this->app->Session->data['auth']['userid']))
			return true;
	}

	public function isAdmin() {
		if($this->app->Session->data['auth']['isAdmin'])
			return true;
		else
			return false;
	}

	public function auth($username, $password) {
		$sql = "SELECT id, username, lastLogin, lastIp, isAdmin, canCreateDomain ";
		$sql.= "FROM user WHERE ";
		$sql.= "username = '".addslashes($username)."' AND ";
		$sql.= "password = '".md5($password)."'";

		$row = $this->db->getRow($sql);

		if($row) {
			$this->app->Session->data['auth'] = array(
				"userid"	=> $row['id'],
				"username"	=> $row['username'],
				"isAdmin"	=> $row['isAdmin'],
				"canCreateDomain" => $row['canCreateDomain'],
				"lastLogin"	=> $row['lastLogin'],
				"lastIp"	=> $row['lastIp'],
			);

			$sql = "UPDATE user SET lastLogin = UTC_TIMESTAMP(), lastIp = '".$_SERVER['REMOTE_ADDR']."' WHERE id = ".$row['id'];
			$this->db->query($sql);

			$this->app->ActionLog->log("auth", sprintf(_("%s has successfully loged in from %s"), $username, $_SERVER['REMOTE_ADDR']));

			return true;
		}
		else {
			$this->app->ActionLog->log("auth", sprintf(_("Authentification from %s for %s has failed"), $_SERVER['REMOTE_ADDR'], $username));
			return false;
		}
	}

	public function logout() {
		$this->app->Session->destruct();
	}
}
