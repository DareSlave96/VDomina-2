<?php

ob_start();

// Check to make sure user is over 18
if ( !isset( $_COOKIE['over18'] ) && $_GET['page'] != "over18" && $_GET['action'] != "over18" ){
	header( 'Location: ?page=over18' );
}

// Database connections
function DBCon () {
	return new PDO( 'sqlite: db.sqlite' );
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
		if ( $st === 1 ) {
			// Username correct, get variables from DB query
			foreach ( $st as $row ) {
				$db_pw = $row['Password'];
				$db_dom = $row['DoneDom'];
				$db_sub = $row['DoneSub'];
			}
			// Check to make sure passwords match
			if ( password_verify( $pw, $db_pw ) ) {
				// Passwords match
				setcookie( 'UN', $un, time() + 60*60*12 );
				if ( $db_sub === 1 ){
					// Go to fill in subs details
					header( 'Location: ?page=prefs&cat=sub' );
				}
				else if ( $db_dom === 1 ){
					// Go to fill in doms details
					header( 'Location: ?page=prefs&cat=dom' );
				}
				else {
					// Fill rest of cookies with info
				}
			}
			else {
				// Passwords don't match
				echo "Please try again, that username/password combination was incorrect <br><br>";
				pages::login();
			}
		}
		else {
			echo "Please try again, that username/password combination was incorrect <br><br>";
			pages::login();
		}
		
	}
	public static function signup () {
	
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