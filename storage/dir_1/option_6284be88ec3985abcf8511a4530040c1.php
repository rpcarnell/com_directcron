<?php
$expired = (time() > 1472159940) ? true : false;
if ($expired) { return; }

$data =  unserialize('a:2:{i:0;a:0:{}i:1;a:17:{s:10:"disponible";s:1:"3";s:8:"duration";s:1:"3";s:10:"production";s:1:"3";s:6:"nation";s:1:"3";s:5:"notes";s:1:"3";s:7:"title_2";s:1:"3";s:4:"year";s:1:"1";s:12:"distribution";s:1:"3";s:4:"MPAA";s:1:"3";s:9:"jmovie_id";s:1:"1";s:8:"director";s:1:"3";s:6:"actor2";s:1:"3";s:6:"actor3";s:1:"3";s:11:"extra_image";s:1:"6";s:19:"another_extra_image";s:1:"6";s:5:"actor";s:1:"3";s:8:"amazonid";s:1:"3";}}');

?>