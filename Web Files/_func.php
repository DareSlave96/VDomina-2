<?php

ob_start();

// Check to make sure user is over 18
if ( !isset( $_COOKIE['over18'] ) && $_GET['page'] != "over18" && $_GET['action'] != "over18" ){
	header( 'Location: ?page=over18' );
}

// Database connections
function DBCon () {
	return new PDO( 'sqlite:db.sqlite' );
}

class pages {
	public static function home () { ?>
		Home page <br>
		<br>
		The news will appear here soon
	<?php }
	public static function login () { ?>
		<h3> Existing user </h3>
		<form action="?action=login" method="post">
			Username<br>
			<input name="UN"><br>
			Password <br>
			<input name="PW" type="password"><br>
			<input type="submit">
		</form>
		<h3> New user </h3>
		<form action="?action=signup" method="post">
			Username<br>
			<input name="UN"><br>
			Password <br>
			<input name="PW1" type="password"><br>
			<input name="PW2" type="password"><br>
			<input type="submit">
		</form>
	<?php }
	public static function over18 () { ?>
		Before entering this site you must confirm that you are over the age of 18 (or 21, in some countries). <br>
		<br>
		If you are over 18 (or 21), <a href="?action=over18">click here</a><br>
		<br>
		Else, you can <a href="http://google.com/">go to Google</a>
	<?php }
	public static function prefs () {
	
	}
	public static function prefs_dom () {
	
	}
	public static function prefs_sub () {
	
	}
	public static function prefs_revPunishments () {
	
	}
	public static function prefs_revRewards () {
	
	}
	public static function prefs_revTasks () {
	
	}
	public static function prefs_manageNews () {
	
	}
	public static function prefs_users () {
	
	}
	public static function prefs_manageStaffMes () {
	
	}
	public static function prefs_changePassword () {
	
	}
	public static function session () {
	
	}
	public static function tasks () {
	
	}
	public static function rewards () {
	
	}
	public static function punishments () {
	
	}
}

class action {
	
	public static function over18 () {
		setcookie( 'over18', 'true', time() + 60*60*24*14 );
		header( 'Location: /' );
	}
	public static function login () {
		// Cache vars
		$un = $_POST['UN'];
		$pw = $_POST['PW'];
		// Open database connection
		$db = DBCon();
		// Check for username
		$st = $db -> prepare("SELECT * FROM `Users` WHERE `Username` = :Username");
		$st -> bindparam( ':Username', $un );
		$st -> execute();
		// Check and make sure username available
		if ( $st -> execute() == 1 ) {
			// Username correct, get variables from DB query
			foreach ( $st as $row ) {
				$db_pw = $row['Password'];
				$db_dom = $row['DoneDom'];
				$db_sub = $row['DoneSub'];
				$db_pnts = $row['Points'];
			}
			// Check to make sure passwords match
			if ( password_verify( $pw, $db_pw ) ) {
				// Passwords match so fill in info
				$time = time() + 60*60*12;
				setcookie( 'points', $db_pnts, $time );
				setcookie( 'UN', $un, $time );
				if ( $db_sub === 1 ){
					// Go to fill in subs details
					header( 'Location: ?page=prefs&cat=sub' );
				}
				else if ( $db_dom === 1 ){
					// Go to fill in doms details
					header( 'Location: ?page=prefs&cat=dom' );
				}
			}
			else {
				// Passwords don't match
				echo "Please try again, that password combination was incorrect <br><br>";
				pages::login();
			}
		}
		else {
			echo "Please try again, that username combination was incorrect <br><br>";
			pages::login();
		}
		
	}
	public static function signup () {
		// Cache vars
		$pw1 = $_POST['PW1'];
		$pw2 = $_POST['PW2'];
		$un = $_POST['UN'];
		// Check username
		$db = DBCon();
		$st = $db -> prepare( 'SELECT * FROM `Users` WHERE `Username` = :UN' );
		$st -> bindparam( ':UN', $un );
		$st -> execute();
		if ( $st -> rowCount() == 0 ) {
			// Username available
			if ( $pw1 === $pw2 ){
				// Passwords match
				$pw = password_hash( $pw1, PASSWORD_DEFAULT );
				$st = $db -> prepare( "INSERT INTO `Users` (`Username`, `Password`, `DoneDom`, `DoneSub`) VALUES (:UN, :PW, 1, 1)" );
				$st -> bindparam( ':UN', $un );
				$st -> bindparam( ':PW', $pw );
				$st -> execute();
				$st = $db -> prepare( "INSERT INTO `Details01` VALUES ( :UN, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1 )" );
				$st -> bindparam( ':UN', $un );
				$st -> execute();
				$st = $db -> prepare( "INSERT INTO `Details02` ( `Username` ) VALUES ( :UN )" );
				$st -> bindparam( ':UN', $un );
				$st -> execute();
				$st = $db -> prepare( "INSERT INTO `Details03` VALUES ( :UN, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 0 )" );
				$st -> bindparam( ':UN', $un );
				$st -> execute();
				$st = $db -> prepare( "INSERT INTO `Details04` VALUES ( :UN, '', '', '', 0 )" );
				$st -> bindparam( ':UN', $un );
				$st -> execute();
				header( 'Location: ?page=prefs&cat=sub' );
			}
			else {
				echo "The passwords do not match, please try again.<br>";
				pages::login();
			}
		}
		else {
			echo "We're sorry, that username isn't available, please try another<br>";
			pages::login();
		}
	}
	public static function add_Punishment () {
		// Cache vars
		$punishment = strip_tags($_POST['punishment']);
		$category = $_POST['category'];
		// Insert into database
		$db = DBCon();
		$st = $db -> prepare( "INSERT INTO `Punishments` (`Author`, `Punishment`, `Category`) VALUES ( :auth, :pun, :cat )" );
		$st -> bindparam( ':auth', $_COOKIE['UN'] );
		$st -> bindparam( ':pun', $punishment );
		$st -> bindparam( ':auth', $category );
		$st -> execute;
		header( 'Location: ?page=punishments' );
	}
	public static function add_Task () {
		// Cache vars
		$task = strip_tags($_POST['task']);
		$category = $_POST['category'];
		// Insert into database
		$db = DBCon();
		$st = $db -> prepare( "INSERT INTO `Tasks` (`Author`, `Task`, `Category`) VALUES ( :auth, :task, :cat )" );
		$st -> bindparam( ':auth', $_COOKIE['UN'] );
		$st -> bindparam( ':task', $task );
		$st -> bindparam( ':auth', $category );
		$st -> execute;
		header( 'Location: ?page=tasks' );
	}
	public static function add_Reward () {
		// Cache vars
		$reward = strip_tags($_POST['reward']);
		$category = $_POST['category'];
		// Insert into database
		$db = DBCon();
		$st = $db -> prepare( "INSERT INTO `Rewards` (`Author`, `Reward`, `Category`) VALUES ( :auth, :rew, :cat )" );
		$st -> bindparam( ':auth', $_COOKIE['UN'] );
		$st -> bindparam( ':rew', $reward );
		$st -> bindparam( ':auth', $category );
		$st -> execute;
		header( 'Location: ?page=rewards' );
	}
	public static function updateUserLevel () {
	
	}
	public static function fetch_Task () {
	
	}
	public static function fetch_Punishment () {
	
	}
	public static function fetch_Reward () {
	
	}
	public static function update_domPrefs () {
	
	}
	public static function update_subPrefs () {
	
	}
	public static function update_password () {
	
	}
	public static function delete_task ( $ID ) {
	
	}
	public static function delete_reward ( $ID ) {
	
	}
	public static function delete_punishment ( $ID ) {
	
	}
	public static function add_news () {
	
	}
	public static function delete_news ( $ID ) {
	
	}
	public static function add_staffMes () {
	
	}
	public static function Delete_staffMes ( $ID ) {
	
	}
	public static function updatePoints ( $type, $ID ) {
	
	}
}

class session {
	public static function check_limit ( $limit ) {
		// return 0 if limit, 1 if dislike, 2 if meh, 3 if like.
	}
	public static function check_item ( $item ) {
		// return 0 if doesn't have, 1 if does
	}
	public static function check_gender () {
		// returns "m" if male, "f" if female
	}
}