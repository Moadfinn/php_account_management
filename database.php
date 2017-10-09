<!DOCTYPE htmll>
<?php
class Database {

	private static $user = 'root';
	private static $pword = 'root';
	private static $db = 'whwebapp';
	private static $host = 'localhost';

	private static $dbc = null;

	public function _construct() {
		die('Init function is not allowed');
	}

	public static function connect() {

		$user = 'root';
		$pword = 'root';
		$db = 'whwebapp';
		$host = 'localhost';


		//one connection only
		if (null == self::$dbc) {
			try {
				self::$dbc = new PDO("mysql:host=$host;dbname=$db", $user, $pword);
//echo '<p>Im in the try block</p>';
			}
			catch (PDOException $e) {
				die($e->getMessage());
//echo '<p>Im in the catch block</p>';
			}
		}
		else {
			echo '<p>Im in the else block</p>';
		}
		return self::$dbc;
	}

	public static function disconnect() {
		self::$dbc = null;
	}

}
?>
