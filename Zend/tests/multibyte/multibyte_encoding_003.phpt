--TEST--
Zend Multibyte and UTF-16 BOM
--SKIPIF--
<?php
if (!extension_loaded("mbstring")) {
  die("skip Requires mbstring extension");
}
?>
--INI--
zend.multibyte=1
internal_encoding=iso-8859-1
--FILE--
��< ? p h p 
 p r i n t   " H e l l o   W o r l d \ n " ; 
 ? > 
 = = = D O N E = = = 
 
--EXPECT--
Hello World
===DONE=== 
