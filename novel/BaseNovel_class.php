<?php

/**
 * Created by PhpStorm.
 * User: lixiaoming
 * Date: 2018/9/26
 * Time: 下午1:49
 */

namespace novel;
use Model;

class BaseNovel
{
    public function query($sql_select,$condition)
    {
        $model = new Model();
        $article = $model->query_with_array($sql_select,$condition);
        return $article;
    }

    public function get_chapter_url($chapter_id){
        //http://lixm.com/ctx_4.html
        return "/content_".$chapter_id.".html";
    }


    public function get_all_chapter_url($article_id)
    {
        return "/list_".$article_id.".html";
    }
}