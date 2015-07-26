<?php
require_once("includes/functions.php");
?>
<table style="width: 100%; background-color: #999999;">
  <tr>
    <td><?php
echo motor::sitename() . " &copy; 2012 - ";

foreach (motor::languages() as $language) {
	echo "<a href=\"change_lang.php?lang=" . $language->getlang() . "\">". $language->getlanguage() . "</a> ";
}
?></td>
  </tr>
</table>