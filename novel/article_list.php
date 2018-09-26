<?php

require_once 'BaseNovel_class.php';
require_once 'ArticleList_class.php';
include_once "../init.php";
include_once WEB_ROOT."smarty_inc.php";
include_once WEB_ROOT."util/util.php";
include_once WEB_ROOT."util/Model_class.php";
include_once WEB_ROOT."util/sql_defines.php";

use novel\ArticleList;

/**
 * Created by PhpStorm.
 * User: lixiaoming
 * Date: 2018/8/9
 * Time: 下午10:50
 */

if(@$_GET["id"]) {
    $article_id = str_check($_GET["id"]);
    $article_info = new ArticleList();
    $article_info->render($smarty,$article_id);
    $smarty->display("wooniu_article_list.html");
}



