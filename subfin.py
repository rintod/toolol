# Coded By Con7ext
# Thanks ZeroByte
# Python 2.7
import urllib2
import re

def subScn(domain):
  headers = {}
  headers['x-session-hash'] = "16961ee14a95fae7bbfe69587dcca2adf647b7022e88ec6167edec568e4c69d3"
  req = urllib2.Request('https://www.virustotal.com/ui/domains/{}/subdomains'.format(domain), headers = headers)
  response = urllib2.urlopen(req)
  resp = response.read()
  rest = re.findall(r'"id": "(.*?)"', str(resp))
  print '============ '+ domain +' ============'
  for result in rest:
    print '' + result

if __name__ == '__main__':
  print '============ Mass Subdomain Finder ============'
  print 'Website Tanpa http/https'
  print 'Contoh : site.com'
  try:
    website = raw_input("Masukkan List Site : ")
    f = open(website, 'r').read()
    sss = f.split('\n')
    for webs in sss:
      subScn(webs)
  except:
    print 'File Tidak Di Temukan'
    
    
