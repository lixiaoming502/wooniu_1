<?php

/**
 * Created by PhpStorm.
 * User: lixiaoming
 * Date: 2018/9/26
 * Time: 下午3:55
 */
namespace novel;

class ArticleList extends BaseNovel
{
    function render($smarty,$article_id){

        global $sql_chapter_all,$article_info_sql;
        $chapter_lst = $this->query($sql_chapter_all,array($article_id));
        $article_lst = $this->query($article_info_sql,array($article_id));
        $article = $article_lst[0];

        for($i=0;$i<count($chapter_lst);$i++){
            $e = $chapter_lst[$i];
            $e['chapter_url']=$this->get_chapter_url($e["id"]);
            $chapter_lst[$i]=$e;
        }

        $smarty->assign("web_title",$GLOBALS ['web'] ['title']);
        $smarty->assign("article_title",$article['title']);
        $smarty->assign("chapter_lst",$chapter_lst);
        $smarty->assign("article_id",$article_id);

    }
}