## Coded By Con7ext
## INFORMASI GEMPA Indonesia Dan Luar Indonesia
## Find Me On FB: https://www.facebook.com/JeMbUd.go.id
import urllib2
import re
import time
def gempa():
  req = urllib2.urlopen('http://inatews.bmkg.go.id/light/?act=realtimeev').read()
  reg = re.findall(r'<tr><td>(.*?)</td><td>(.*?)</td><td>(.*?)</td><td>(.*?)</td><td>(.*?)</td><td>(.*?)</td><td>(.*?)</td><td><a href="(.*?)" onclick="(.*?)">(.*?)</a></td></tr>', str(req))
  print '========== Info Gempa Terkini ==========\n'
  for data in reg:
    print 'Tanggal         : ' + data[0]
    print 'Jam             : ' + data[1]
    print 'Lintang         : ' + data[2]
    print 'Bujur           : ' + data[3]
    print 'Kedalaman       : ' + data[4] + ' Km'
    print 'Magnitudo       : ' + data[5]
    print 'Potensi Tsunami : ' + data[6]
    print 'Wilayah         : ' + data[9]
    print 'Maps            : https://maps.google.com/maps?q=' + data[2] + ',' + data[3] + '\n'
    print '=========================================='
    time.sleep(3)
    
if __name__ == '__main__':
  gempa()
