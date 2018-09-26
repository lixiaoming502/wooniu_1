<?php
/**
 * Created by PhpStorm.
 * User: lixiaoming
 * Date: 2018/9/26
 * Time: 下午4:13
 */

namespace novel;


class ChapterContent extends BaseNovel
{
    function render($smarty,$chapter_id){

        global $sql_chapter_content,$sql_last_chapter,$sql_next_chapter;
        $chapter_lst = $this->query($sql_chapter_content,array($chapter_id));
        $chapter = $chapter_lst[0];

        $seq_id = $chapter['seq_id'];
        $article_id = $chapter['article_id'];
        $local_url = WEB_ROOT.$chapter['local_url'];
        //print_r($local_url);

        $content = "chapter content not exist!!!";
        if(file_exists($local_url)){
            $myfile = fopen($local_url, "r") or die("Unable to open file!");
            $content =  fread($myfile,filesize($local_url));
        }
        $menu_url = $this->get_all_chapter_url($article_id);

        $cond = array($article_id,$seq_id);
        $last = $this->query($sql_last_chapter,$cond);
        $next = $this->query($sql_next_chapter,$cond);

        $last_url = $this->get_chapter_url($chapter_id);
        $next_url = $this->get_chapter_url($chapter_id);

        if(!empty($last)&&count($last)>0){
            $last_url = $this->get_chapter_url($last[0]["id"]);
        }
        if(!empty($next)&&count($next)>0){
            $next_url = $this->get_chapter_url($next[0]["id"]);
        }

        $smarty->assign("web_title",$GLOBALS ['web'] ['title']);
        $smarty->assign("chapter",$chapter);

        //下一页
        $smarty->assign("last_url",$last_url);
        //上一页
        $smarty->assign("next_url",$next_url);
        $smarty->assign("menu_url",$menu_url);
        $smarty->assign("content",$content);




    }
}