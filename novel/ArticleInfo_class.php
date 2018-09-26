<?php
/**
 * Created by PhpStorm.
 * User: lixiaoming
 * Date: 2018/9/26
 * Time: 下午1:52
 */

namespace novel;


class ArticleInfo extends BaseNovel
{

    function render($smarty,$article_id){

        global $article_info_sql,$sql_last5_chapter,$sql_first5_chapter;
        $article_lst = $this->query($article_info_sql,array($article_id));
        $article = $article_lst[0];
        $last5_chapters = $this->query($sql_last5_chapter,array($article_id));
        $first5_chapters = $this->query($sql_first5_chapter,array($article_id));

        for($i=0;$i<count($first5_chapters);$i++){
            $e = $first5_chapters[$i];
            $e['chapter_url']=$this->get_chapter_url($e["id"]);
            $first5_chapters[$i]=$e;
        }

        for($i=0;$i<count($last5_chapters);$i++){
            $e = $last5_chapters[$i];
            $e['chapter_url']=$this->get_chapter_url($e["id"]);
            $last5_chapters[$i]=$e;
        }

        $smarty->assign("web_title",$GLOBALS ['web'] ['title']);
        $smarty->assign("article_title",$article['title']);
        $smarty->assign("description",$article['comment']);
        $smarty->assign("image",$article['img']);
        $smarty->assign("category",$article['category_name']);
        $smarty->assign("author",$article['author']);
        $smarty->assign("read_url",$this->get_readURL($article_id));
        $smarty->assign("status",$article['st1']);
        $smarty->assign("update_time",$article['modify_date']);
        $smarty->assign("article_id",$article_id);


        $lastChapter = $last5_chapters[4];
        $firstChapter = $first5_chapters[0];


        $smarty->assign("last5_chapters",$last5_chapters);
        $smarty->assign("first5_chapters",$first5_chapters);
        $smarty->assign("lastChapter",$lastChapter);
        $smarty->assign("firstChapter",$firstChapter);

        $smarty->assign("latest_chapter_url",$this->get_chapter_url($lastChapter['id']));
        $smarty->assign("latest_chapter_name",$lastChapter['title']);
        $smarty->assign("all_chapter_url",$this->get_all_chapter_url($article_id));


    }

    private function get_readURL($article_id){
        //http://lixm.com/info_4.html
        return $GLOBALS ['http'] ['url']."/info_".$article_id.".html";
    }

}