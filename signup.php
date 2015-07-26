<?php
if (!isset($_SESSION)) { session_start(); }
require_once("includes/functions.php");
if (motor::authenticated(false)==true) { motor::back_page(); }
new motor(false);

$error_signup_username=NULL;
$error_signup_name=NULL;
$error_signup_email=NULL;
$error_signup_password=NULL;
$error_signup_iam=NULL;
$error_signup_birthday_day=NULL;
$error_signup_birthday_month=NULL;
$error_signup_birthday_year=NULL;
$error_signup_country=NULL;

if ($_POST) {
	if (isset($_POST["btnsignup"])) {
		if(!preg_match("/^[a-zA-Z0-9]{5,}$/",$_POST["txtsignup_username"])) {
			$error_signup_username=incorrect_signup_username;
		}

		if (!preg_match("/^[a-zA-Z��������������������������������]+$/",$_POST["txtsignup_name"])) {
			$error_signup_name=incorrect_signup_name;
		}

		if (!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',$_POST["txtsignup_email"])) {
			$error_signup_email=incorrect_signup_email;
		}

		if (strlen($_POST["txtsignup_password"])<6 || strlen($_POST["txtsignup_password"])>18) {
			$error_signup_password=incorrect_signup_password;
		}

		if ($_POST["cmbiam"]==0) {
			$error_signup_iam=incorrect_signup_iam;
		}

		if ($_POST["cmbbirthday_day"]==0) {
			$error_signup_birthday_day=incorrect_signup_birthday_day;
		}

		if ($_POST["cmbbirthday_month"]==0) {
			$error_signup_birthday_month=incorrect_signup_birthday_month;
		}

		if ($_POST["cmbbirthday_year"]==0) {
			$error_signup_birthday_year=incorrect_signup_birthday_year;
		}

		if ($_POST["cmbcountry"]==0) {
			$error_signup_country=incorrect_signup_coutry;
		}

		if ($error_signup_username==NULL && $error_signup_name==NULL && $error_signup_email==NULL && $error_signup_password==NULL && $error_signup_iam==NULL && $error_signup_birthday_day==NULL && $error_signup_birthday_month==NULL && $error_signup_birthday_year==NULL && $error_signup_country==NULL) {
			$exists_username=motor::exists_username($_POST["txtsignup_username"]);

			if ($exists_username==true) {
				$error_signup_username=exists_username;
			} else {
				$exists_email=motor::exists_email($_POST["txtsignup_email"]);

				if ($exists_email==true) {
					$error_signup_email=exists_email;
				} else {
					$member=new member();

					$member->setusername($_POST["txtsignup_username"]);
					$member->setname($_POST["txtsignup_name"]);
					$member->setemail($_POST["txtsignup_email"]);
					$member->setpassword($_POST["txtsignup_password"]);
					$member->setsex($_POST["cmbiam"]);
					$member->setbirthday_day($_POST["cmbbirthday_day"]);
					$member->setbirthday_month($_POST["cmbbirthday_month"]);
					$member->setbirthday_year($_POST["cmbbirthday_year"]);

					$country=new country();

					$country->setid($_POST["cmbcountry"]);

					$member->setcountry($country);

					motor::signup($member);

					$_SESSION["signup_thanks"]=$_POST["txtsignup_email"];

					header("Location: signup_ok.php");
				}
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo page_signup_title . " | " . motor::sitename(); ?></title>
</head>

<body>
<?php include_once("header.php"); ?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<?php echo username; ?>: <input name="txtsignup_username" type="text" maxlength="50"<?php
if ($_POST) {
	if (isset($_POST["btnsignup"])) {
		echo " value=\"" . $_POST["txtsignup_username"] . "\"";
	}
} else if (isset($_SESSION["signup_username"])) {
	echo " value=\"" . $_SESSION["signup_username"] . "\"";
	unset($_SESSION["signup_username"]);
}
?> />
<br />
<?php echo name; ?>: <input name="txtsignup_name" type="text" maxlength="100"<?php
if ($_POST) {
	if (isset($_POST["btnsignup"])) {
		echo " value=\"" . $_POST["txtsignup_name"] . "\"";
	}
} else if (isset($_SESSION["signup_name"])) {
	echo " value=\"" . $_SESSION["signup_name"] . "\"";
	unset($_SESSION["signup_name"]);
}
?> />
<br />
E-mail: <input name="txtsignup_email" type="text" maxlength="100"<?php
if ($_POST) {
	if (isset($_POST["btnsignup"])) {
		echo " value=\"" . $_SESSION["txtsignup_email"] . "\"";
	}
} else if (isset($_SESSION["signup_email"])) {
	echo " value=\"" . $_SESSION["signup_email"] . "\"";
	unset($_SESSION["signup_email"]);
}
?> />
<br />
<?php echo password; ?>: <input name="txtsignup_password" type="password" maxlength="16" />
<br />
<?php echo iam; ?>: <select name="cmbiam"><option value="0"><?php echo sex; ?>:</option><option value="1"<?php
if ($_POST) {
	if (isset($_POST["btnsignup"])) {
		if ($_POST["cmbiam"]==1) {
			echo " selected=\"selected\"";
		}
	}
}
?>><?php echo sex_female; ?></option><option value="2"<?php
if ($_POST) {
	if (isset($_POST["btnsignup"])) {
		if ($_POST["cmbiam"]==2) {
			echo " selected=\"selected\"";
		}
	}
}
?>><?php echo sex_male; ?></option></select>
<br />
<?php echo birthday; ?>: <select name="cmbbirthday_day"><option value="0"><?php echo day; ?>:</option><?php
for ($i=1; $i<32; $i++) {
	echo "<option value=\"" . $i . "\"";

	if ($_POST) {
		if (isset($_POST["btnsignup"])) {
			if ($_POST["cmbbirthday_day"]==$i) {
				echo " selected=\"selected\"";
			}
		}
	}

	echo ">". $i . "</option>"; 
}
?></select>
<select name="cmbbirthday_month"><option value="0"><?php echo month; ?>:</option><?php
$months=array(january,february,march,april,may,june,july,august,september,october,november,december);

for ($i=1; $i<13; $i++) {
	echo "<option value=\"" . $i . "\"";

	if ($_POST) {
		if (isset($_POST["btnsignup"])) {
			if ($_POST["cmbbirthday_month"]==$i) {
				echo " selected=\"selected\"";
			}
		}
	}

	echo ">" . $months[$i-1] . "</option>";
}
?></select>
<select name="cmbbirthday_year"><option value="0"><?php echo year; ?>:</option><?php
for ($i=0; $i<111; $i++) {
	$year=date("Y",strtotime("-" . $i . " year"));
	echo "<option value=\"" . $year . "\"";

	if ($_POST) {
		if (isset($_POST["btnsignup"])) {
			if ($_POST["cmbbirthday_year"]==$year) {
				echo " selected=\"selected\"";
			}
		}
	}

	echo ">". $year . "</option>";
} 
?></select>
<br />
<?php echo country; ?>: <select name="cmbcountry"><option value="0"><?php echo select_country; ?>:</option><?php
foreach (motor::countries() as $country) {
	echo "<option value=\"" . $country->getid() . "\"";

	if ($_POST) {
		if (isset($_POST["btnsignup"])) {
			if ($_POST["cmbcountry"]==$country->getid()) {
				echo " selected=\"selected\"";
			}
		}
	}

	echo ">". $country->getcountry() . "</option>";
}
?></select>
<br />
<input name="btnsignup" type="submit" value="<?php echo button_signup; ?>" />
<?php
if ($error_signup_username!=NULL) { 
	echo "<br />" . $error_signup_username;
}

if ($error_signup_name!=NULL) { 
	echo "<br />" . $error_signup_name;
}

if ($error_signup_email!=NULL) {
	echo "<br />" . $error_signup_email;
}

if ($error_signup_password!=NULL) {
	echo "<br />" . $error_signup_password;
}

if ($error_signup_iam!=NULL) {
	echo "<br />" . $error_signup_iam;
}

if ($error_signup_birthday_day!=NULL) {
	echo "<br />" . $error_signup_birthday_day;
}

if ($error_signup_birthday_month!=NULL) {
	echo "<br />" . $error_signup_birthday_month;
}

if ($error_signup_birthday_year!=NULL) {
	echo "<br />" . $error_signup_birthday_year;
}

if ($error_signup_country!=NULL) {
	echo "<br />" . $error_signup_country;
}
?>
</form>
<?php include_once("footer.php"); ?>
</body>
</html>