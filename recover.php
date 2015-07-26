<?php
if (!isset($_SESSION)) { session_start(); }
require_once("includes/functions.php");
if (motor::authenticated(false)==true) { motor::back_page(); }
new motor(false);

$error_recover_email=NULL;

if ($_POST) {
	if (isset($_POST["btnrecover"])) {
		if (!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',$_POST["txtrecover_email"])) {
			$error_recover_email=incorrect_recover_email;
		} else {
			motor::recover($_POST["txtrecover_email"]);

			$_SESSION["recover_email"]=$_POST["txtrecover_email"];

			header("Location: recover_ok.php");
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo page_recover_title . " | " . motor::sitename(); ?></title>
</head>

<body>
<?php include_once("header.php"); ?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<table border="1">
  <tr>
    <td align="right">E-mail:</td>
    <td><input name="txtrecover_email" type="text" maxlength="100" /></td>
  </tr>
  <tr>
    <td colspan="2"><input name="btnrecover" type="submit" value="<?php echo button_recover; ?>" /><?php
if ($error_recover_email!=NULL) { 
	echo "<br />" . $error_recover_email;
}
?></td>
  </tr>
</table>
</form>
<?php include_once("footer.php"); ?>
</body>
</html>