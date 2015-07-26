<?php
if (!isset($_SESSION)) { session_start(); }
require_once("includes/functions.php");
new motor(false);

if (motor::authenticated(false)==false) {
	$error_login=NULL;

	if ($_POST) {
		if (isset($_POST["btnlogin"])) {
			$member=new member();

			$member->setemail($_POST["txtlogin_email"]);
			$member->setpassword($_POST["txtlogin_password"]);

			if (isset($_POST["cbkeep_logged"])) {
				$result=motor::login($member,true);
			} else {
				$result=motor::login($member,false);
			}

			if ($result==NULL) {
				$error_login=incorrect_login;
			} else if ($result->getstate==0 && strlen($result->getcode_signup)>0) {
				$error_login=member_unconfirmed;
			} else if ($result->getstate==0 && !strlen($result->getcode_signup)>0) {
				motor::activate($_POST["txtlogin_email"]);
				$_SESSION["login_reactivated"]=1;
				header("Location: index.php");
			} else if ($result->getstate==1 && strlen($result->getcode_signup)>0) {
				motor::remove_recover_code($_POST["txtlogin_email"]);
				header("Location: index.php");
			} else {
				header("Location: index.php");
			}
		}
	}
?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<table style="width: 100%; background-color: #999999;">
  <tr>
	<td width="60%">&nbsp;</td>
    <td>E-mail</td>
    <td><?php echo password; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  	<td><a href="index.php"><?php echo motor::sitename(); ?></a></td>
    <td><input name="txtlogin_email" type="text" maxlength="100"<?php
if ($_POST) {
	if (isset($_POST["btnlogin"])) {
		echo " value=\"" . $_POST["txtlogin_email"] . "\"";
	}
}
?> /></td>
    <td><input name="txtlogin_password" type="password" maxlength="16" /></td>
    <td><input name="btnlogin" type="submit" value="<?php echo button_login; ?>" /></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
    <td><input type="checkbox" name="cbkeep_logged" /> <?php echo button_keep_me_logged_in; ?></td>
    <td><a href="recover.php"><?php echo recover_password; ?></a></td>
    <td><?php
if ($error_login!=NULL) {
	echo incorrect_login;
} else {
	echo "&nbsp;";
}
?></td>
  </tr>
</table>
</form>
<?php
}
?>