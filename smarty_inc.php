<?php

include_once(WEB_ROOT."smarty/libs/Smarty.class.php"); 

$smarty = new Smarty(); 

$smarty->config_dir=WEB_ROOT."smarty/Config_File.class.php";  

$smarty->caching=false; 

$smarty->template_dir = WEB_ROOT."templates"; 

$smarty->compile_dir = WEB_ROOT."templates_c"; 

$smarty->cache_dir = WEB_ROOT."smarty_cache"; 


$smarty->left_delimiter = "{#";

$smarty->right_delimiter = "#}";
?>