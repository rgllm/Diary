<?php
if (!isset($_SESSION)) { session_start(); }
require_once("includes/functions.php");
if (motor::authenticated(false)==true) { motor::back_page(); }
new motor(false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo page_signup_thanks . " | " . motor::sitename(); ?></title>
</head>

<body>
<?php
if (isset($_SESSION["signup_thanks"])) {
	echo message_signup_thanks . $_SESSION["signup_thanks"];
	unset($_SESSION["signup_thanks"]);
} else {
	header("Location: welcome.php");
}
?>
</body>
</html>