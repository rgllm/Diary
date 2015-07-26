<?php
class type {
	private $id;
	private $type;

	function setid($value) {
		$this->id=$value;
	}

	function getid() {
		return $this->id;
	}

	function settype($value) {
		$this->type=$value;
	}

	function gettype() {
		return $this->type;
	}
}

class country {
	private $id;
	private $country;

	function setid($value) {
		$this->id=$value;
	}

	function getid() {
		return $this->id;
	}

	function setcountry($value) {
		$this->country=$value;
	}

	function getcountry() {
		return $this->country;
	}
}

class language {
	private $id;
	private $lang;
	private $language;
	private $file;

	function setid($value) {
		$this->id=$value;
	}

	function getid() {
		return $this->id;
	}

	function setlang($value) {
		$this->lang=$value;
	}

	function getlang() {
		return $this->lang;
	}

	function setlanguage($value) {
		$this->language=$value;
	}

	function getlanguage() {
		return $this->language;
	}

	function setfile($value) {
		$this->file=$value;
	}

	function getfile() {
		return $this->file;
	}
}

class member {
	private $id;
	private $username;
	private $name;
	private $email;
	private $password;
	private $sex;
	private $birthday_day;
	private $birthday_month;
	private $birthday_year;
	private $country;
	private $type;
	private $lang;
	private $state;
	private $code_signup;
	private $signup_date;
	private $last_login;
	private $last_ip;

	function setid($value) {
		$this->id=$value;
	}

	function getid() {
		return $this->id;
	}

	function setusername($value) {
		$this->username=$value;
	}

	function getusername() {
		return $this->username;
	}

	function setname($value) {
		$this->name=$value;
	}

	function getname() {
		return $this->name;
	}

	function setemail($value) {
		$this->email=$value;
	}

	function getemail() {
		return $this->email;
	}

	function setpassword($value) {
		$this->password=$value;
	}

	function getpassword() {
		return $this->password;
	}

	function setsex($value) {
		$this->sex=$value;
	}

	function getsex() {
		return $this->sex;
	}

	function setbirthday_day($value) {
		$this->birthday_day=$value;
	}

	function getbirthday_day() {
		return $this->birthday_day;
	}

	function setbirthday_month($value) {
		$this->birthday_month=$value;
	}

	function getbirthday_month() {
		return $this->birthday_month;
	}

	function setbirthday_year($value) {
		$this->birthday_year=$value;
	}

	function getbirthday_year() {
		return $this->birthday_year;
	}

	function setcountry($value) {
		$this->country=$value;
	}

	function getcountry() {
		return $this->country;
	}

	function settype($value) {
		$this->type=$value;
	}

	function gettype() {
		return $this->type;
	}

	function setlang($value) {
		$this->lang=$value;
	}

	function getlang() {
		return $this->lang;
	}

	function setstate($value) {
		$this->state=$value;
	}

	function getstate() {
		return $this->state;
	}

	function setcode_signup($value) {
		$this->code_signup=$value;
	}

	function getcode_signup() {
		return $this->code_signup;
	}

	function setsignup_date($value) {
		$this->signup_date=$value;
	}

	function getsignup_date() {
		return $this->signup_date;
	}

	function setlast_login($value) {
		$this->last_login=$value;
	}

	function getlast_login() {
		return $this->last_login;
	}

	function setlast_ip($value) {
		$this->last_ip=$value;
	}

	function getlast_ip() {
		return $this->last_ip;
	}
}
?>