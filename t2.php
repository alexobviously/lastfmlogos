<?php
$f = fopen("log.txt","w+");
fwrite($f,"helloworld");
fclose($f);
?>