<?php
// Created By Rinto AR
class WHATANIMEAPI{
  var $image, $ch, $mime, $ext, $req, $getData;
  function cUrlGetData($url, $post_fields = null, $headers = null) {
    $this->ch = curl_init();
    curl_setopt($this->ch, CURLOPT_URL, $url);
    if ($post_fields && !empty($post_fields)) {
      curl_setopt($this->ch, CURLOPT_POST, 1);
      curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_fields);
    }
    if ($headers && !empty($headers)) {
      curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
    $data = curl_exec($this->ch);
    if (curl_errno($this->ch)) {
      echo 'Error:' . curl_error($this->ch);
    }
    curl_close($this->ch);
    return $data;
  }
  function ConvertIT($url){
    $this->image = base64_encode($this->cUrlGetData($url));
    $this->mime = array(
    'pdf' => 'application/pdf',
    'doc' => 'application/msword',
    'odt' => 'application/vnd.oasis.opendocument.text ',
    'docx'	=> 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'gif' => 'image/gif',
    'jpg' => 'image/jpg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'bmp' => 'image/bmp'
    );
    $this->ext = pathinfo($url, PATHINFO_EXTENSION);
    if (array_key_exists($this->ext, $this->mime)) {
      $a = $this->mime[$this->ext];
    }
    return 'data: '.$a.';base64,'.$this->image;
  }
  function getRequest(){
    $this->req = $_GET['url'];
    $this->image = $this->ConvertIT($this->req);
    $this->getData = $this->cUrlGetData("https://trace.moe/api/search", "{\"image\":\"{$this->image}\"}");
    $json = json_decode($this->getData, true);
    if($this->req){
      for($i = 0; $i <= $json["limit"]; $i++){
        $imagesss = "https://trace.moe/thumbnail.php?anilist_id=".$json["docs"][$i]["anilist_id"]."&file=".$json["docs"][$i]["filename"]."&t=".$json["docs"][$i]["at"]."&token=".$json["docs"][$i]["tokenthumb"];
        echo "<b>Image         </b>: ".$imagesss."<br>";
        echo "<b>Title English </b>: ".$json["docs"][$i]["title_english"]."<br>";
        echo "<b>Title Japanese</b>: ".$json["docs"][$i]["title_romaji"]."<br>";
        echo "<b>From Episode  </b>: ".$json["docs"][$i]["episode"]."<br>";
        echo "<b>Similarity    </b>: ".round((float)$json["docs"][$i]["similarity"] * 100)."%<br>";
        echo "<b>Mal ID        </b>: ".$json["docs"][$i]["mal_id"]."<br><br>";
      }
    }
    else{
      echo "<br>Please Usage: file.php?url=IMAGE_URL";
    }
  }
}
$lib = new WHATANIMEAPI();
$lib->getRequest();
