# CODED BY Con7ext
# Python 2.7
import urllib2
import re

def aleXa(domain):
  url = 'http://data.alexa.com/data?cli=10&dat=snbamz&url={}'.format(domain)
  req = urllib2.urlopen(url).read().decode('utf-8')
  reslt = re.findall(r'<POPULARITY URL="(.*?)" TEXT="(.*?)"', str(req))
  reslt2 = re.findall(r'<COUNTRY CODE="(.*?)" NAME="(.*?)" RANK="(.*?)"', str(req))
  output = re.search('<POPULARITY', req, flags=re.IGNORECASE)
  if output is not None:
    for result,result2 in zip(reslt,reslt2):
      print ''
      print 'Domain        : ' + domain
      print 'Global Rank   : ' + result[1]
      print 'Country       : ' + result2[1]
      print 'Country Code  : ' + result2[0]
      print 'Local Rank    : ' + result2[2]
      print '=============================='
  else:
    print 'Domain       : ' + domain
    print 'Global Rank  : -'
    print 'Country      : -'
    print 'Country Code : -'
    print 'Local Rank   : -'
    print '=============================='

if __name__ == '__main__':
  print '============= Alexa Rank Checker ============='
  try:
    lists = raw_input("Masukkan List Domain: ")
    ff = open(lists, 'r').read()
    jnck = ff.split('\n')
    for domin in jnck:
      aleXa(domin)
  except:
    print 'File Tidak Di Temukan'
