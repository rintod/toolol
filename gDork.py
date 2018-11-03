# AUTHOR : CON7EXT
# THANKS : Teguh Aprianto
import urllib2
import re
import sys

def ngeDorks(page, dork):
  dork = dork.split()
  reqq = urllib2.Request("https://cse.google.com/cse.js?cx=partner-pub-2698861478625135:3033704849")
  rss = urllib2.urlopen(reqq).read()
  resst = re.findall(r'"cse_token": "(.*?)"', str(rss))
  for kntl in resst:
    cse_token = kntl
  api_key = "partner-pub-2698861478625135:3033704849"
  gUrl = "https://cse.google.com/cse/element/v1?num=10&hl=en&cx="+ api_key +"&safe=off&cse_tok="+ cse_token +"&start={}&q={}&callback=x".format(page, dork)
  req = urllib2.Request(gUrl)
  rs = urllib2.urlopen(req).read()
  result = re.findall(r'"unescapedUrl": "(.*?)"', str(rs))
  output = re.search('500', rs, flags=re.IGNORECASE)
  if output:
    print 'Error 500'
    sys.exit()
  else:
    for a in result:
      print a

    
print '--- Google Dorker ---\nCreated By Rinto'
print '--- List --'
print '[1] Grabbing Random Page'
print '[2] Grabbing Custom Page'
print '[99] Exit'
pilih = int(raw_input('Chose: '))
if(pilih == 1):
  dorks = raw_input("Input Dorks: ")
  pages = 1000
  for i in range(1,pages):
    print '-- Grabbing Page {} --'.format(i)
    ngeDorks(i, dorks)
elif(pilih == 2):
  dorks = raw_input("Input Dorks: ")
  pages = raw_input("Page: ")
  ngeDorks(pages, dorks)
elif(pilih == 99):
  sys.exit()
else:
  print 'Chose 1 - 2 or 99 for exit'
  
