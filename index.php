<?php
if (!isset($_SESSION)) { session_start(); }
require_once("includes/functions.php");
new motor(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo motor::sitename(); ?></title>
</head>

<body>
<?php
if (isset($_SESSION["login_reactivated"])) {
	echo member_activate;
	unset($_SESSION["login_reactivated"]);
}
?>
Hello world.
</body>
</html>