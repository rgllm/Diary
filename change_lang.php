<?php
require_once("includes/functions.php");

if ($_GET) {
	if (isset($_GET["lang"])) {
		motor::change_language($_GET["lang"]);
	}
}

motor::back_page();
?>