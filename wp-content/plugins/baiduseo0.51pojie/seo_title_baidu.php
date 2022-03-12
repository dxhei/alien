<?php  
/*
Plugin Name: 百度站长SEO合集(支持百度/360/Bing/头条推送)
Description: 含百度站长、360站长、Bing站长、今日头条站长、tag标签内链、百度地图sitemap、关键词排名查询监控、360站长JS自动推送、 文章原创率检测、文章伪原创、 网站蜘蛛、robots、图片alt标签、天级推送、category隐藏、死链查询、百度自动推送、批量提交URL到站长、百度收录查询、批量推送未收录、301/404等功能。
Version: 0.5.1
Author: 易搜网
Author URI: https://www.9ysw.com/

*/
ob_start();
if(!defined('ABSPATH'))exit;

// 声明全局变量
global $wpdb;
add_action('wp_footer', 'tagmanage_wztkjseo');
function tagmanage_wztkjseo(){
    echo '';
}
if(function_exists('set_time_limit')){
set_time_limit(0);
}
ini_set('memory_limit', '2048M');
define('BAIDUSEO_URL','http://wp.seohnzz.com');
define('BAIDUSEO_SALT','seohnzz.com');
define('BAIDUSEO_FILE',__FILE__);
define('BAIDUSEO_BASE','api');
define('BAIDUSEO_NAME',plugin_basename(__FILE__));
require plugin_dir_path( BAIDUSEO_FILE ) . 'function.php';//公用函数 
require plugin_dir_path( BAIDUSEO_FILE ) . 'inc/baidu.php';
require plugin_dir_path( BAIDUSEO_FILE ).'inc/link.php';
require plugin_dir_path( BAIDUSEO_FILE ).'inc/get.php';
require plugin_dir_path( BAIDUSEO_FILE ).'inc/post.php';
require plugin_dir_path( BAIDUSEO_FILE ).'inc/json.php';
require plugin_dir_path( BAIDUSEO_FILE ).'inc/cron.php';
function add_yuanchuang_column($columns) {   
    $columns['yuanchuang'] = '原创检测'; 
    echo "<script>
        jQuery(document).ready(function($){
            $('.baiduseo_yuanchuang').click(function() {
                var id = $(this).attr('data-id');
                $.ajax({
                    url: '".admin_url( 'admin.php?page=baiduseo' )."',
                    data: {
                        data: '{\"id\":\"' + id + '\",\"BaiduSEO\":\"35\",\"nonce\":\"".wp_create_nonce('BaiduSEO')."\",\"action\":\"BaiduSEO\"}'
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        if(res.msg){
                            alert('提交成功，请等待处理');
                            location.reload();
                        }else{
                            if(res.data){
                                alert(res.data);
                            }else{
                                alert('提交失败'); 
                            }
                        }
                    }
                })
                return false;
            })
        })
    </script>";
    return $columns;   
}   
add_filter('manage_posts_columns' , 'add_yuanchuang_column');
//为商品有效日期填充数据
function yuanchuang_column_content($column_name, $post_id) {   
    if ($column_name == 'yuanchuang') { 
        $post_extend = get_post_meta( $post_id, 'baiduseo', true );
        if(isset($post_extend['status']) && $post_extend['status']==2){
            $yuanchuang_value ='检测中...';
        }elseif(isset($post_extend['status']) && $post_extend['status']==1){
            
            $yuanchuang_value ='原创率：'.$post_extend['yc'].'%';
        }else{
        $yuanchuang_value ="
        <style>
            .baiduseo_yuanchuang {
                padding:10px 20px;
            }
        </style>
        <button class='baiduseo_yuanchuang' data-id=".$post_id." type='button'>原创检测</button>
        ";
        }
        echo $yuanchuang_value;
    }   
}  

add_action('manage_posts_custom_column', 'yuanchuang_column_content', 10, 2); 
$baiduseo_baidu = new baiduseo_baidu();





	




