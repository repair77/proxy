<?php
namespace Home\Controller;
use Think\Controller;
use QL\QueryList;
class IndexController extends Controller {
    public function index()
    {
        var_dump(file_get_contents('http://www.74hy.com/'));
        exit;
        set_time_limit(30);
        // 采集电影列表
        define('HOST', 'http://www.74hy.com/');
        for ($i=1; $i<=2; $i++) { 
            // 采集页面
            $page = HOST.'/type/1/'.$i.'.html';
            // 采集目标规则
            $rules = array(
               'mov_url' => array('.movie-item>a','href'),
            );
            $mov_url[] = QueryList::Query($page,$rules)->data;    
        }
        $mov_info =array();
        foreach ($mov_url as $value) {
            foreach ($value as $v) {
                //采集电影详情
                $page = HOST.$v['mov_url'];
                $rules = array(
                    'mov_title'=>array(".movie-title",'text'), //电影名
                    'mov_img'=>array(".img-thumbnail",'src'), // 海报
                    'mov_year'=>array(".movie-year",'text'), // 年代
                    'mov_director'=>array("tr:eq(0)>td:eq(1)",'text'), //导演
                    'mov_roles'=>array("tr:eq(1)>td:eq(1)",'text'), //主演
                    'mov_type'=>array("tr:eq(2)>td:eq(1)",'text'), //类型
                    'mov_producer'=>array("tr:eq(3)>td:eq(1)",'text'), //国家
                    'mov_lang'=>array("tr:eq(4)>td:eq(1)",'text'),  // 语言
                    'mov_publish'=>array("tr:eq(5)>td:eq(1)",'text'), //发布日期
                    'mov_score'=>array("tr:eq(6)>td:eq(1)",'text'), //评分
                    'mov_summary'=>array(".summary",'text'), //简介
                );
                $temp = QueryList::Query($page,$rules)->data;
                
                $mov_info[] = $temp[0];
            }
        }
        var_dump($mov_info); //电影详情
        echo 'OK';

    }
}