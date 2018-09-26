<?php
	require_once 'novel/BaseNovel_class.php';
	require_once 'novel/Recommend_class.php';
	include_once "init.php";
	include_once WEB_ROOT."smarty_inc.php";
	include_once WEB_ROOT."util/util.php";
	include_once WEB_ROOT."util/Model_class.php";
	include_once WEB_ROOT."util/sql_defines.php";

	use novel\Recommend;


	$recommend = new Recommend();
	$recommend->render($smarty);
	$smarty->display("wooniu_index.html");
?>