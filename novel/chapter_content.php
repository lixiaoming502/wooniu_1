<?php
/**
 * Created by PhpStorm.
 * User: lixiaoming
 * Date: 2018/9/26
 * Time: 下午4:12
 */

require_once 'BaseNovel_class.php';
require_once 'ChapterContent_class.php';
include_once "../init.php";
include_once WEB_ROOT."smarty_inc.php";
include_once WEB_ROOT."util/util.php";
include_once WEB_ROOT."util/Model_class.php";
include_once WEB_ROOT."util/sql_defines.php";

use novel\ChapterContent;

/**
 * Created by PhpStorm.
 * User: lixiaoming
 * Date: 2018/8/9
 * Time: 下午10:50
 */

if(@$_GET["id"]) {
    $chapter_id = str_check($_GET["id"]);
    $chapterContent = new ChapterContent();
    $chapterContent->render($smarty,$chapter_id);
    $smarty->display("wooniu_chapter_content.html");
}
