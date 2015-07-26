<?php
if (!isset($_SESSION)) { session_start(); }
require_once("includes/functions.php");
if (motor::authenticated(false)==true) { motor::back_page(); }
new motor(false);

if ($_POST) {
	if (isset($_POST["btnsignup"])) {
		$_SESSION["signup_username"]=$_POST["txtsignup_username"];
		$_SESSION["signup_name"]=$_POST["txtsignup_name"];
		$_SESSION["signup_email"]=$_POST["txtsignup_email"];

		header("Location: signup.php");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo page_welcome_title; ?></title>
</head>

<body>
<?php include_once("header.php"); ?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<table border="1">
  <tr>
    <td align="right"><?php echo username; ?>:</td>
    <td><input name="txtsignup_username" type="text" maxlength="50" /></td>
  </tr>
  <tr>
    <td align="right"><?php echo name; ?>:</td>
    <td><input name="txtsignup_name" type="text" maxlength="100" /></td>
  </tr>
  <tr>
    <td align="right">E-mail:</td>
    <td><input name="txtsignup_email" type="text" maxlength="100" /></td>
  </tr>
  <tr>
    <td colspan="2"><input name="btnsignup" type="submit" value="<?php echo button_signup; ?>" /></td>
  </tr>
</table>
</form>
<?php include_once("footer.php"); ?>
</body>
</html>