<?php
require_once("./kernel/classes.php");

class motor {
	private $connection=NULL;
	const mysql_host="localhost";
	const mysql_user="your_user";
	const mysql_password="your_password";
	const mysql_db="your_db";

	const sitename="Diary";
	const siteemail="your_email";
	const siteurl="your_url";

// <Constructor>
	public function __construct($verify_authenticated) {
		if ($verify_authenticated==true) {
			self::authenticated(true);
		}

		self::load_language();
	}
// </Constructor>

// <Functions: MySQL>
	static protected function connect() {
		$connection=mysql_connect(self::mysql_host,self::mysql_user,self::mysql_password) or die("Maintenance");
		mysql_select_db(self::mysql_db,$connection) or die("Maintenance");
		mysql_set_charset("utf8",$connection);
	}

	static protected function execute($query) {
		return mysql_query($query);
	}

	static protected function num_rows($obj) {
		$result=0;

		if ($obj<>0) {
			$result=mysql_num_rows($obj);
		}

		return $result;
	}

	static protected function fetch_array($obj) {
		return mysql_fetch_array($obj);
	}

	static protected function disconnect() {
		global $connection;

		if ($connection!=NULL) {
			mysql_close($connection);
		}
	}
// </Functions: MySQL>

// <Functions: Site>
	static function sitename() {
		return self::sitename;
	}

	static function siteemail() {
		return self::siteemail;
	}

	static function siteurl() {
		return self::siteurl;
	}

	static protected function no_injection($text) {
		return mysql_real_escape_string(stripslashes($text));
	}

	static protected function encrypt_password($password) {
		return sha1(md5($password));
	}

	static protected function getip() {
		$result="0.0.0.0";

		if (isset($_SERVER)) {
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && validip($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				$result=$_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_CLIENT_IP"]) && validip($_SERVER["HTTP_CLIENT_IP"])) {
				$result=$_SERVER["HTTP_CLIENT_IP"];
			} else {
				$result=$_SERVER["REMOTE_ADDR"];
			}
		} else {
			if (getenv("HTTP_X_FORWARDED_FOR") && validip(getenv("HTTP_X_FORWARDED_FOR"))) {
				$result=getenv("HTTP_X_FORWARDED_FOR");
			} elseif (getenv("HTTP_CLIENT_IP") && validip(getenv("HTTP_CLIENT_IP"))) {
				$result=getenv("HTTP_CLIENT_IP");
			} else {
				$result=getenv("REMOTE_ADDR");
			}
		}

		return $result;
	}

	static protected function random_string($lenght) {
		$result=NULL;
		$chars="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

		for ($i=0; $i<$lenght; $i++) {
			$result.=$chars[rand(0,strlen($chars)-1)];
		}

		return $result;
	}

	static function back_page() {
		$url=$_SERVER["HTTP_REFERER"];

		if (strlen($url)>0) {
			header("Location: " . $url);
		} else {
			header("Location: index.php");
		}
	}

	static function authenticated($go_to_login) {
		$result=true;

		if (isset($_COOKIE["udata"]) && isset($_COOKIE["udate"])) {
			self::connect();
			$value=self::execute("SELECT * FROM members WHERE id=" . self::decrypt($_COOKIE["udata"]) . " AND password='" . self::decrypt($_COOKIE["udate"]) . "';");

			if (!self::num_rows($value)>0) {
				setcookie("udata");
				setcookie("udate");
				setcookie("lang");

				header("Location: welcome.php");
			}
		} else {
			if ($go_to_login==true) {
				header("Location: welcome.php");
			}

			$result=false;
		}

		return $result;
	}

	static function send_email($from_email,$from_name,$to_email,$to_name,$subject,$body) {
		require_once("phpmailer/class.phpmailer.php");

		$mail=new PHPMailer();

		$body=$body;

		$mail->IsSMTP();
		$mail->SMTPAuth=true;
		$mail->Host="mail.diary.com";
		$mail->Port=25;
		$mail->udata="utilizador@email.com";
		$mail->Password="password";

		$mail->SetFrom($from_email,$from_name);
		$mail->AddReplyTo($from_email,$from_name);

		$mail->Subject=$subject;
		$mail->MsgHTML($body);

		$mail->AddAddress(self::no_injection($to_email),self::no_injection($to_name));

		// $mail->Send(); // TO DO: remover comentário ao fim de meter os dados de SMTP
	}
// </Functions: Site>

// <Functions: Languages>
	static protected function create_language($obj) {
		$language=new language();

		$language->setid($obj["id"]);
		$language->setlang($obj["lang"]);
		$language->setlanguage($obj["language"]);
		$language->setfile($obj["file"]);

		return $language;
	}

	static protected function language($parameter) {
		$result=NULL;

		self::connect();
		$value=self::execute("SELECT * FROM languages WHERE " . $parameter . ";");

		if (self::num_rows($value)>0) {
			$result=self::create_language(self::fetch_array($value));
		}

		self::disconnect();

		return $result;
	}

	static function languages() {
		$result=array();

		self::connect();
		$values=self::execute("SELECT * FROM languages ORDER BY language ASC;");

		if (self::num_rows($values)>0) {
			while ($obj=self::fetch_array($values)) {
				$result[sizeof($result)]=self::create_language($obj);
			}
		}

		self::disconnect();

		return $result;
	}

	static protected function load_language() {
		if (isset($_COOKIE["lang"])) {
			self::connect();

			$value=self::execute("SELECT * FROM languages WHERE lang='" . $_COOKIE["lang"] . "';");

			if (self::num_rows($value)>0) {
				$lang=self::fetch_array($value);
				include_once("./languages/" . $lang["file"]);
			} else {
				include_once("./languages/en.php");
				setcookie("lang","en",(time()+63115200));
			}

			self::disconnect();
		} else {
			include_once("./languages/en.php");
			setcookie("lang","en",(time()+63115200));
		}
	}

	static function change_language($lang) {
		self::connect();
		$value=self::execute("SELECT * FROM languages WHERE lang='" . $lang . "';");

		if (self::num_rows($value)>0) {
			if (self::authenticated(false)==true) {
				$lang=self::fetch_array($value);
				self::execute("UPDATE members SET id_lang=" . ($lang["id"]) . " WHERE id=" . self::decrypt($_COOKIE["udata"]) . ";");
			}

			setcookie("lang",$lang,(time()+63115200));
		}

		self::disconnect();
	}
// </Functions: Languages>

// <Functions: Types>
	static protected function create_type($obj) {
		$type=new type();

		$type->setid($obj["id"]);
		$type->settype($obj["type"]);

		return $type;
	}

	static protected function type($id) {
		$result=NULL;

		self::connect();
		$value=self::execute("SELECT * FROM types WHERE id=" . $id . ";");

		if (self::num_rows($value)>0) {
			$result=self::create_type(self::fetch_array($value));
		}

		self::disconnect();

		return $result;
	}
// </Functions: Types>

// <Functions: Countries>
	static protected function create_country($obj) {
		$country=new country();

		$country->setid($obj["id"]);
		$country->setcountry($obj["country"]);

		return $country;
	}

	static function countries() {
		$result=array();

		self::connect();
		$values=self::execute("SELECT * FROM countries ORDER BY country ASC;");

		if (self::num_rows($values)>0) {
			while ($obj=self::fetch_array($values)) {
				$result[sizeof($result)]=self::create_country($obj);
			}
		}

		self::disconnect();

		return $result;
	}

	static protected function country($id) {
		$result=NULL;

		self::connect();
		$value=self::execute("SELECT * FROM countries WHERE id=" . $id . ";");

		if (self::num_rows($value)>0) {
			$result=self::create_country(self::fetch_array($value));
		}

		self::disconnect();

		return $result;
	}
// </Functions: Countries>

// <Functions: Members>
	static protected function create_member($obj) {
		$member=new member();

		$member->setid($obj["id"]);
		$member->setusername($obj["username"]);
		$member->setname($obj["name"]);
		$member->setemail($obj["email"]);
		$member->setpassword($obj["password"]);
		$member->setsex($obj["sex"]);
		$member->setbirthday_day($obj["birthday_day"]);
		$member->setbirthday_month($obj["birthday_month"]);
		$member->setbirthday_year($obj["birthday_year"]);
		$member->setcountry(self::country($obj["id_country"]));
		$member->settype(self::type($obj["id_type"]));
		$member->setlang(self::language("id=" . $obj["id_lang"]));
		$member->setstate($obj["state"]);
		$member->setcode_signup($obj["code_signup"]);
		$member->setsignup_date($obj["signup_date"]);
		$member->setlast_login($obj["last_login"]);
		$member->setlast_ip($obj["last_ip"]);

		return $member;
	}

	static function exists_username($username) {
		$result=false;

		self::connect();
		$value=self::execute("SELECT * FROM members WHERE username='" . self::no_injection($username) . "';");

		if (self::num_rows($value)>0) {
			$result=true;
		}

		self::disconnect();

		return $result;
	}

	static function exists_email($email) {
		$result=false;

		self::connect();
		$value=self::execute("SELECT * FROM members WHERE email='" . self::no_injection($email) . "';");

		if (self::num_rows($value)>0) {
			$result=true;
		}

		self::disconnect();

		return $result;
	}

	static function confirm_signup($code) {
		$result=false;

		self::connect();
		$value=self::execute("SELECT * FROM members WHERE code_signup='" . self::no_injection($code) . "' AND state=0;");

		if (self::num_rows($value)>0) {
			$member=self::fetch_array($value);
			self::execute("UPDATE members SET state=1,code_signup=NULL WHERE id=" . $member["id"] . ";");

			$result=true;
		}

		self::disconnect();

		return $result;
	}

	static function signup($member) {
		$code_signup=NULL;
		$exists_code=true;

		self::connect();

		while($exists_code==true){
			$code_signup=self::random_string(50);

			$value=self::execute("SELECT * FROM members WHERE code_signup='" . $code_signup . "';");

			if (!self::num_rows($value)>0) {
				$exists_code=false;
			}
		}

		$value=self::execute("INSERT INTO members (username,name,email,password,sex,birthday_day,birthday_month,birthday_year,id_country,id_lang,state,code_signup) VALUES ('" . self::no_injection($member->getusername()) . "','" . self::no_injection($member->getname()) . "','" . self::no_injection($member->getemail()) . "','" . self::encrypt_password($member->getpassword()) . "'," . $member->getsex() . "," . $member->getbirthday_day() . "," . $member->getbirthday_month() . "," . $member->getbirthday_year() . "," . $member->getcountry()->getid() . "," . self::language("lang='" . $_COOKIE["lang"] . "'")->getid() . ",0,'" . $code_signup . "');");

		self::send_email(self::siteemail,self::sitename,$member->getemail(),$member->getname(),email_signup_subject,(email_signup_body . self::siteurl . "confirm.php?c=" . $code_signup));

		self::disconnect();
	}

	static function login($member,$keep_me_logged_in) {
		$result=NULL;

		self::connect();
		$value=self::execute("SELECT * FROM members WHERE email='" . self::no_injection($member->getemail()) . "' AND password='" . self::encrypt_password($member->getpassword()) . "';");

		if (self::num_rows($value)>0) {
			$result=self::create_member(self::fetch_array($value));

			self::execute("UPDATE members SET last_login=CURRENT_TIMESTAMP,last_ip='" . self::getip() . "' WHERE id=" . $result->getid() . ";");

			if ($keep_me_logged_in==true) {
				$time=time()+63115200;
			} else {
				$time=time()+1209600;
			}

			setcookie("udata",self::encrypt($result->getid()),$time);
			setcookie("udate",self::encrypt($result->getpassword()),$time);
			setcookie("lang",($result->getlang()->getlang()),$time);
		}

		self::disconnect();

		return $result;
	}

	static function recover($email) {
		self::connect();

		$value=self::execute("SELECT * FROM members WHERE email='" . self::no_injection($email) . "';");

		if (self::num_rows($value)>0) {
			$result=self::create_member(self::fetch_array($value));

			$code=NULL;
			$exists_code=true;

			while($exists_code==true){
				$code=self::random_string(50);

				$value=self::execute("SELECT * FROM members WHERE code_signup='" . $code . "';");

				if (!self::num_rows($value)>0) {
					$exists_code=false;
				}
			}

			self::execute("UPDATE members SET code_signup='" . $code . "' WHERE id=" . $result->getid() . ";");

			self::send_email(self::siteemail,self::sitename,$email,$result->getname(),email_recover_subject,(email_recover_body . self::siteurl . "redefine.php?c=" . $code));
		}

		self::disconnect();
	}

	static function validates_recover_code($code) {
		$result=false;

		self::connect();
		$value=self::execute("SELECT * FROM members WHERE code_signup='" . self::no_injection($code) . "';");

		if (self::num_rows($value)>0) {
			$result=true;
		}

		self::disconnect();

		return $result;
	}

	static function change_password_recover($password,$code) {
		self::connect();

		self::execute("UPDATE members SET password='" . self::encrypt_password($password) . "' WHERE signup_code='" . self::no_injection($code) . "';");

		self::disconnect();
	}
	
	static function remove_recover_code($email) {
		self::connect();

		$value=self::execute("UPDATE members SET code_signup=NULL WHERE email='" . self::no_injection($email) . "';");

		self::disconnect();
	}

	static function activate($email) {
		self::connect();

		$value=self::execute("UPDATE members SET state=1 WHERE email='" . self::no_injection($email) . "';");

		self::disconnect();
	}
// <Functions: Members>

// <Secret>
	static protected function encrypt($text) {
		$key="D1@rY_s€cR3t";
		$iv_size=mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB);
		$iv=mcrypt_create_iv($iv_size,MCRYPT_RAND);
		$h_key=hash("sha256",$key,TRUE);
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256,$h_key,$text,MCRYPT_MODE_ECB,$iv));
	}

	static protected function decrypt($text) {
		$key="D1@rY_s€cR3t";
		$iv_size=mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB);
		$iv=mcrypt_create_iv($iv_size,MCRYPT_RAND);
		$h_key=hash("sha256",$key,TRUE);
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$h_key,base64_decode($text),MCRYPT_MODE_ECB,$iv));
	}
// </Secret>
}
?>