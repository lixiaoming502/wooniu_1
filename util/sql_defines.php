<?php
/**
 * Created by PhpStorm.
 * User: lixiaoming
 * Date: 2018/9/26
 * Time: 上午11:37
 */


define("QIANG_TUI",1);
define("XUAN_HUAN",2);
define("WU_XIA",3);
define("DU_SHI",4);
define("LI_SHI",5);

//查询文章基本信息
$article_info_sql = "select
    case
        when a.status = 1 then '已完结'
    else '连载中'
    end as st1,a.*,
    b.category_name from t_article a,t_category b where a.category_id = b.id and a.id=?";

//推荐
$sql_recommend = "select a.* from t_article a,t_recommend b where a.id = b.article_id and b.module=?";

//文章的最新5节
$sql_last5_chapter = "select * from t_chapter where artile_id = ? order by seq_id desc limit 5";

//文章的最小5节
$sql_first5_chapter = "select * from t_chapter where artile_id = ? order by seq_id asc limit 5";

//文章的所有章节
$sql_chapter_all = "select * from t_chapter where artile_id = ? order by seq_id ";

//章节内容
$sql_chapter_content = "select a.id as article_id,b.id as chapter_id,a.title as article_title,b.title as chapter_title,b.local_url,b.seq_id  from t_article a,t_chapter b where b.id=? and b.artile_id = a.id";

//上一章
$sql_last_chapter = "select id from t_chapter where artile_id=? and  seq_id < ?  order by seq_id desc limit 1";

//下一章
$sql_next_chapter = "select id from t_chapter where artile_id=?  and seq_id > ? order by seq_id  limit 1";
