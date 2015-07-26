<?php
require_once("includes/functions.php");
if (motor::authenticated(false)==true) { motor::back_page(); }
new motor(false);

$code=NULL;
$error_password=NULL;

if ($_POST) {
	if (isset($_POST["btnnew_password"])) {
		$code=$_POST["txtcode"];

		if (strlen($code)>0) {
			if (motor::validates_recover_code($code)==true) {
				if (strlen($_POST["txtnew_password"])<6 || strlen($_POST["txtnew_password"])>18) {
					$error_password=incorrect_signup_password;
				} else {
					motor::validates_recover_code($code,$_POST["txtnew_password"]);
				}
			} else {
				header("Location: welcome.php");
			}
		} else {
			header("Location: welcome.php");
		}
	}
} else if ($_GET) {
	if (isset($_GET["c"])) {
		if (motor::validates_recover_code($_GET["c"])==true) {
			$code=$_GET["c"];
		} else {
			echo message_recover_incorrect_code;
		}
	} else {
		header("Location: welcome.php");
	}
} else {
	header("Location: welcome.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo page_recover_new_password . " | " . motor::sitename(); ?></title>
</head>

<body>
<?php include_once("header.php"); ?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<table border="1">
  <tr>
    <td align="right"><?php echo new_password; ?>:</td>
    <td><input type="hidden" name="txtcode" value="<?php echo $code; ?>" /><input name="txtnew_password" type="password" maxlength="16" /></td>
  </tr>
  <tr>
    <td colspan="2"><input name="btnnew_password" type="submit" value="<?php echo button_new_password; ?>" /><?php
if ($error_password!=NULL) {
	echo "<br />" . $error_password;
}
?></td>
  </tr>
</table>
</form>
<?php include_once("footer.php"); ?>
</body>
</html>