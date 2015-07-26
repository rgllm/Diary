<?php
require_once("includes/functions.php");
if (motor::authenticated(false)==true) { motor::back_page(); }
new motor(false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo page_confirm_title . " | " . motor::sitename(); ?></title>
</head>

<body>
<?php include_once("header.php"); ?>
<?php
if ($_GET) {
	if (isset($_GET["c"])) {
		if (strlen($_GET["c"])>0) {
			if (motor::confirm_signup($_GET["c"])==true) {
				echo message_confirm_confirmation;
			} else {
				echo message_confirm_incorrect_code;
			}
		} else {
			echo message_confirm_incorrect_code;
		}
	} else {
		echo message_confirm_incorrect_code;
	}
} else {
	echo message_confirm_incorrect_code;
}
?>
<?php include_once("footer.php"); ?>
</body>
</html>