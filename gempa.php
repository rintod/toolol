<!-- RINTOD -->
<html>
  <head>
    <title>Gempa</title>
 
   <style type='text/css'>
   @import url('https://fonts.googleapis.com/css?family=Space+Mono');
     html {
       background: black;
       color: grey;
       font-family: 'Space Mono';
         font-size: 12px;
         width: 100%;
     }
     </style>
  </head>
  <body>
    <?php
    function curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $resp = curl_exec($ch);
    curl_close($ch);
    return $resp;
    }
    $getGempa = curl("http://inatews.bmkg.go.id/light/?act=realtimeev");
    preg_match("/<tbody>(.*?)<\/tbody>/", $getGempa, $gemps);
    preg_match_all("/<tr>(.*?)<\/tr>/", $gemps[1], $rest);
    foreach($rest[1] as $hasil){
      $a = str_replace("<td>", "", $hasil);
      $b = explode("</td>", $a);
      echo "========================<br>Tanggal : ".$b[0]."<br>Jam : ".$b[1]."<br>Lintang : ".$b[2]."<br>Bujur : ".$b[3]."<br>Kedalaman : ".$b[4]." Km<br>Magnitudo : ".$b[5]."<br>Wilayah : ".$b[7]."<br>Maps : <a href='https://maps.google.com/maps?q=".$b[2].",".$b[3]."' target='_blank'> View On Map </a><br>========================<br>";
    }
    ?>
  </body>
</html>
