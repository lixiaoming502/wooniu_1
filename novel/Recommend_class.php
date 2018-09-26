<?php
/**
 * Created by PhpStorm.
 * User: lixiaoming
 * Date: 2018/9/26
 * Time: 下午2:46
 */

namespace novel;


class Recommend extends BaseNovel
{
    function get_recommend($recommend_id){
        global $sql_recommend;
        return $this->query($sql_recommend,array($recommend_id));
    }

    function render($smarty){
        $smarty->assign("web_title",$GLOBALS ['web'] ['title']);
        $qiantui = $this->get_recommend(QIANG_TUI);
        $xuanhuan = $this->get_recommend(XUAN_HUAN);
        $wuxia = $this->get_recommend(WU_XIA);
        $dushi = $this->get_recommend(DU_SHI);
        $lishi = $this->get_recommend(LI_SHI);
        $smarty->assign("qiantui",$qiantui);
        $smarty->assign("xuanhuan",$xuanhuan);
        $smarty->assign("wuxia",$wuxia);
        $smarty->assign("dushi",$dushi);
        $smarty->assign("lishi",$lishi);
    }
}