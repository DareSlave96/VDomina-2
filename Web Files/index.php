<!DOCTYPE html>

<?php 
	// Stop headers being sent
	ob_start(); 
	// Get _func.php
	require_once( '_func.php' );
?>

<head>

</head>

<style>
	body {
		width: 1000px;
		margin: 0px auto;
		text-align: center;
	}
	#header {
		position: fixed;
		top: 0px;
		background-color: black;
		color: white;
		width: 1000px;
		height: 125px;
		margin-top: 0px;
	}
	#header_title {
		margin-left: 250px;
		text-align: left;
	}
	#header_details {
		position: fixed;
		margin-left: 700px;
		margin-top: 25px;
		width: 250px;
		height: 65px;
		border-radius: 5px;
		color: black;
		background-color: white;
		padding: 5px;
		top: 0px;
	}
	#menu {
		margin-top: 135px;
		width: 990px;
		border-radius: 5px;
		padding: 5px;
		background-color: black;
		color: white;
		margin-left: auto;
		margin-right: auto;
	}
	#footer {
		width: 990px;
		padding: 5px;
		background-color: black;
		color: white;
	}
</style>

<body>

<!-- Header -->
<div id = "header">
	<div id = "header_title">
		<h1> VDomina </h1>
	</div>
<?php
	// Check to see whether user's logged in
	if ( isset( $_COOKIE['UN'] ) ){ ?>
		<!-- Content for logged in person -->
		<div id = "header_details">
			Welcome <?php echo $_COOKIE['subName'] ?> <br>
			You have <?php echo $_COOKIE['points'] ?> points
		</div>
	<?php } else { ?>
		<!-- Content for visitor -->
		<div id = "header_details">
			Welcome visitor <br>
			Please <a href="?page=login">login or sign up</a> to use the sight
		</div>
	<?php }
?>
</div>

<!-- Menu -->
<div id = "menu">
	<?php
		if ( isset( $_COOKIE['UN'] ) ){ ?>
			<a href="/"> Home </a> | etc...
		<?php }
		else { ?>
			<a href="/"> Home </a> | 
			<a href="?page=login"> Sign in / up </a> 
		<?php } ?>
</div>
<br>
<!-- Page content -->
<div id="mainContent">
<?php
	// Cache page variable
	$page;
	$page = $_GET['page'];
	$action = $_GET['action'];
	// Load page contents or do action
	if ( $action == "over18" ) {
		action::over18();
	}
	else if ( $action == "login" ) {
		action::login();
	}
	else if ( $action == "signup" ) {
		action::signup();
	}
	else if( $page == "login" ) {
		pages::login();
	}
	else if ( $page == "prefs" ) {
		// Cache category
		$cat = $_GET['cat'];
		// Display category content
		if ( $cat == "sub" ) {
		
		}
		else if ( $cat == "dom" ) {
		
		}
		else if ( $cat == "reviewPunish" ) {
		
		}
		else if ( $cat == "reviewTasks" ) {
		
		}
		else if ( $cat == "reviewRewards" ) {
		
		}
		else if ( $cat == "userLevels" ) {
		
		}
		else if ( $cat == "news" ) {
		
		}
		else if ( $cat == "manageStaffMessages" ) {
		
		}
		else if ( $cat == "changePass" ) {
		
		}
		else {
			// List of categories available to the user & staff messages if user is member of staff
		}
	}
	else if ( $page == "session" ) {
	}
	else if ( $page == "tasks" ) {
	}
	else if ( $page == "punishments" ) {
	}
	else if ( $page == "rewards" ) {
	}
	else if ( $page == "over18" ) {
		// Make sure user is over 18
		pages::over18();
	}
	else {
		pages::home();
	}
?>
</div>

<!-- Footer -->
<br><div id = "footer">
	Copyright DareSlave96 | Contact Us | About us
</div>

</body>

</html>