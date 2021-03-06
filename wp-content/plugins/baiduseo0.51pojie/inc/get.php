<?php
class baiduseo_get{
    public $limit;
    public $page;
    public $data;
    function __construct($data) {
        $this->limit=(int)$data['limit'];
        $this->page = (int)$data['pages'];
        $this->data = $data;
    }
    public function baiduseo_day_sl(){
        global $wpdb;
        $page = (int)$this->page;   	 		       	 	    
        $limit = (int)$this->limit; 
        $start = ((int)$page-1)*(int)$limit;
        $count2 = wp_count_posts()->publish;
        $count1 = $wpdb->query('select ID from '.$wpdb->prefix . 'posts where seo_baidu_sl=0 and post_status="publish" and post_type="post"',ARRAY_A);
		$count = $count2-$count1;
    	$article = $wpdb->get_results('select ID,post_title,post_date from '.$wpdb->prefix . 'posts where seo_baidu_sl=1 and post_status="publish" and post_type="post" order by ID desc limit '.$start.','.$limit,ARRAY_A);		  		       				 	 
		$art = [];      					      	  	 	 
		foreach($article as $key=>$val){    		 	   	    	 		  		
			$art[$key]['num']=$val['ID'];   	    	    	   	 	 
			$art[$key]['title']=$val['post_title'];    	  		           	   
			$art[$key]['link']=get_permalink($val['ID']);    	 			 		     					 	
			$art[$key]['time']=$val['post_date'];      	 			         	  	
		}    	    		      	      
		echo json_encode(['code'=>0,'msg'=>'','count'=>$count,'data'=>$art]);exit; 
    }
    public function baiduseo_day_wsl(){
        global $wpdb;
        $page = (int)$this->page;   	 		       	 	    
        $limit = (int)$this->limit; 
        $start = ((int)$page-1)*(int)$limit;
        $count1 = $wpdb->query('select ID from '.$wpdb->prefix . 'posts where seo_baidu_sl=0 and post_status="publish" and post_type="post"',ARRAY_A);
		$count = $count1;
		
    	$article = $wpdb->get_results('select ID,post_title,post_date from '.$wpdb->prefix . 'posts where seo_baidu_sl=0 and post_status="publish" and post_type="post" order by ID desc limit '.$start.','.$limit,ARRAY_A);		  		       				 	 
		$art = [];      					      	  	 	 
		foreach($article as $key=>$val){  
			$art[$key]['num']=$val['ID'];      	    	    	   	 	 
			$art[$key]['title']=$val['post_title'];   
			$art[$key]['link']=get_permalink($val['ID']);
			$art[$key]['time']=$val['post_date'];      
		}  
		
		echo json_encode(['code'=>0,'msg'=>'','count'=>$count,'data'=>$art]);exit; 	
        
    }
    public function baiduseo_day_ts(){
        global $wpdb;
        $page = (int)$this->page;   	 		       	 	    
        $limit = (int)$this->limit; 
        $start = ((int)$page-1)*(int)$limit;
        $count3 = $wpdb->query('select ID from '.$wpdb->prefix . 'posts where baiduseo_ts=1 and post_status="publish" and post_type="post"',ARRAY_A);
		$count = $count3;
		$article = $wpdb->get_results('select ID from '.$wpdb->prefix . 'posts where baiduseo_ts=1 and post_status="publish" and post_type="post" order by ID desc limit '.$start.','.$limit,ARRAY_A);
		$art = [];
		foreach($article as $key=>$val){
		    $art[$key]['id']=$val['ID'];
		    $art[$key]['title'] = get_post($val['ID'])->post_title;
		    $art[$key]['time'] = get_post($val['ID'])->post_date;
		    $art[$key]['link'] = get_permalink($val['ID']);
		    $art[$key]['status']=	'?????????';
		}
		echo json_encode(['code'=>0,'msg'=>'','count'=>$count,'data'=>$art]);exit; 
    }
    public function baiduseo_day_wts(){
        global $wpdb;
        $page = (int)$this->page;   	 		       	 	    
        $limit = (int)$this->limit; 
        $start = ((int)$page-1)*(int)$limit;
        $count3 = $wpdb->query('select ID from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" and baiduseo_ts=0 and post_status="publish" and post_type="post"  order by ID desc ',ARRAY_A);
	    $count = $count3;
	   

			$article = $wpdb->get_results('select ID,post_title,post_date from '.$wpdb->prefix . 'posts where  post_status="publish" and post_type="post" and baiduseo_ts=0  order by ID desc limit '.$start.','.$limit,ARRAY_A);


	    $art = [];
	    foreach($article as $key=>$val){ 

    		    $art[$key]['id']=$val['ID'];
    		    $art[$key]['title'] = $val['post_title'];
        		$art[$key]['time']=$val['post_date'];       			        	   		
        		$art[$key]['link']=get_permalink($val['ID']);
    			$art[$key]['status'] = '<button class="layui-btn seo_day_ts layui-btn-sm" title="'.$val['ID'].'">??????</td>';  
    		
    		    	     	      			 		
    	} 
    	echo json_encode(['code'=>0,'msg'=>'','count'=>$count,'data'=>$art]);exit; 
    }
    public function baiduseo_zhizhu(){
        global $wpdb;
        $data = $this->data;
        $page = (int)$this->page;   	 		       	 	    
        $limit = (int)$this->limit;
        $sta = sanitize_text_field($data['start']);
    	$end = sanitize_text_field($data['end']);
    	$search = sanitize_text_field($data['search']);
    	$timezone_offet = get_option( 'gmt_offset');
        $where1 = '';
        if($search){
            $where1 = 'and address like "%'.$search.'%"';
        }
    	if($sta && $end){
    		$sta = strtotime($sta)-$timezone_offet*3600;
    		$end = strtotime($end)-$timezone_offet*3600;
    		$where = "unix_timestamp(time) >$sta and unix_timestamp(time)<$end";
    	}elseif($sta && !$end){
    		$sta = strtotime($sta)-$timezone_offet*3600;
    		$where = "unix_timestamp(time)>$sta";
    	}elseif(!$sta && $end){
    		$end = strtotime($end)-$timezone_offet*3600;
    		$where = "unix_timestamp(time)<$end";
    	}else{
    		$where ='1=1';
    	}
    	$type = (int)$_GET['type'];
    
    	$start = ($page-1)*$limit;
    	if($type){
    		switch($type){
    			case 1://??????
    				$count = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name="??????" ' .$where1.' group by type,address  ',ARRAY_A);
    				$zhizhu = $wpdb->get_results('select *,count(*) as num from '.$wpdb->prefix . 'baiduseo_zhizhu  where '.$where.' and name="??????" ' .$where1.' group by type,address order by id desc limit '.$start.','.$limit,ARRAY_A);
    				break;
    			case 2://??????
    				$count = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name = "??????"' .$where1.' group by type,address  ',ARRAY_A);
    				$zhizhu = $wpdb->get_results('select *,count(*) as num from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name = "??????" ' .$where1.'  group by type,address order by id desc limit '.$start.','.$limit,ARRAY_A);
    				break;
    			case 3://360
    				$count = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name="360"' .$where1.' group by type,address  ',ARRAY_A);
    				$zhizhu = $wpdb->get_results('select *,count(*) as num from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name="360" ' .$where1.'  group by type,address order by id desc limit '.$start.','.$limit,ARRAY_A);
    				break;
    			case 4://??????
    				$count = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu  where '.$where.' and name="??????"' .$where1.' group by type,address  ',ARRAY_A);
    				$zhizhu = $wpdb->get_results('select *,count(*) as num from '.$wpdb->prefix . 'baiduseo_zhizhu  where '.$where.' and name="??????" ' .$where1.'  group by type,address order by id desc limit '.$start.','.$limit,ARRAY_A);
    				break;
    			case 5://??????
    				$count = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name="??????"' .$where1.' group by type,address  ',ARRAY_A);
    				$zhizhu = $wpdb->get_results('select *,count(*) as num from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name="??????" ' .$where1.'  group by type,address order by id desc limit '.$start.','.$limit,ARRAY_A);
    				break;
    			case 6://??????
    				$count = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name="??????"' .$where1.' group by type,address  ',ARRAY_A);
    				$zhizhu = $wpdb->get_results('select *,count(*) as num from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name="??????" ' .$where1.'  group by type,address order by id desc limit '.$start.','.$limit,ARRAY_A);
    				break;
    			case 7://??????
    				$count = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name="??????"' .$where1.' group by type,address  ',ARRAY_A);
    				$zhizhu = $wpdb->get_results('select *,count(*) as num from '.$wpdb->prefix . 'baiduseo_zhizhu  where '.$where.' and name="??????" ' .$where1.'  group by type,address order by id desc limit '.$start.','.$limit,ARRAY_A);
    				break;
    			case 8://301??????
    				$count = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and type=301 ' .$where1.' group by type,address  ',ARRAY_A);
    				$zhizhu = $wpdb->get_results('select *,count(*) as num from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and type=301 ' .$where1.'  group by type,address order by id desc limit '.$start.','.$limit,ARRAY_A);
    				break;
    			case 9://404??????
    				$count = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and type=404 ' .$where1.' group by type,address  ',ARRAY_A);
    				$zhizhu = $wpdb->get_results('select *,count(*) as num from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and type=404 ' .$where1.'  group by type,address order by id desc limit '.$start.','.$limit,ARRAY_A);
    				break;
    			case 10://200??????
    				$count = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and type=200 ' .$where1.'group by type,address  ',ARRAY_A);
    				$zhizhu = $wpdb->get_results('select *,count(*) as num from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and type=200 ' .$where1.'  group by type,address order by id desc limit '.$start.','.$limit,ARRAY_A);
    				break;
    		}
    	}else{
    	
    		$count = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' '. $where1.'   group by type,address  ',ARRAY_A);
    		$zhizhu = $wpdb->get_results('select *,count(*) as num from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' ' .$where1.'  group by type,address order by id desc limit '.$start.','.$limit,ARRAY_A);
    	}
    	$baidu = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name="??????" ' .$where1,ARRAY_A);
        $guge = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name = "??????"' .$where1,ARRAY_A);
        $a360 = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name="360"' .$where1,ARRAY_A);
        $sougou = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu  where '.$where.' and name="??????"' .$where1,ARRAY_A);
        $shenma = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name="??????"' .$where1,ARRAY_A);
        $biying = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name="??????"' .$where1,ARRAY_A);
        $toutiao = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where '.$where.' and name="??????"' .$where1,ARRAY_A);
       
    	echo json_encode(['code'=>0,'msg'=>'','count'=>$count,'data'=>$zhizhu,'other'=>[$baidu,$guge,$a360,$sougou,$shenma,$biying,$toutiao]]);exit; 
    }
    public function baiduseo_keywords(){
        global $wpdb;
        $page = (int)$this->page;   	 		       	 	    
        $limit = (int)$this->limit;
    	$start = ($page-1)*$limit;
    	$count = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_keywords  ',ARRAY_A);
    	$keywords = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_keywords limit '.$start.','.$limit,ARRAY_A);
    	foreach($keywords as $key=>$val){
    		
    		$keywords[$key]['status'] = '<button  type="button" class="layui-btn  layui-btn-sm layui-btn-danger keywords_delete" title="'.$val['id'].'">??????</td>';
    		if($val['prev']==50){
    			$keywords[$key]['prev'] = $val['prev'].'+';
    		}
    		if($val['prev']==0){
    			$keywords[$key]['prev'] = '--';	
    		}
    		if($val['num']==0){
    			$keywords[$key]['num']='?????????...';
    		}else{
    			
    			if($val['num']==50){
    				$keywords[$key]['num']=$val['num'].'+';
    			}else{
    				if($val['prev']){
    					if($val['prev']>$val['num']){
    						
    						$keywords[$key]['num']='<span style="color:red">'.$val['num'].'???</span>';
    					}elseif($val['prev']==$val['num']){
    						$keywords[$key]['num']=$val['num'];
    					}else{
    						$keywords[$key]['num']='<span style="color:green">'.$val['num'].'???</span>';
    					}
    					
    				}
    			}
    		
    			
    		}
    		if(!$val['title']){
    			$keywords[$key]['title']='??????';
    		}
    		if(!$val['time']){
    			$keywords[$key]['time']='--';
    		}
    		if($val['type']==0){
    			$keywords[$key]['type']='??????pc';
    		}elseif($val['type']==1){
    			$keywords[$key]['type']='????????????';
    		}
    		$keywords[$key]['sort'] =$key+1; 
    		
    	}
    	echo json_encode(['code'=>0,'msg'=>'','count'=>count($count),'data'=>$keywords]);exit; 
    }
    public function baiduseo_neilian(){
        global $wpdb;
        $page = (int)$this->page;  
        $limit = (int)$this->limit;
        $start = ((int)$page-1)*(int)$limit;
        $count = $wpdb->query('select * from '.$wpdb->prefix . 'terms where slug="" and tag_link!=""',ARRAY_A);
        $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'terms where slug="" and tag_link!=""  order by term_id desc limit '.$start.','.$limit,ARRAY_A);
        foreach($article as $k=>$v){
            $article[$k]['tag_target']= $v['tag_target']?'???':"???";
            $article[$k]['tag_nofollow']= $v['tag_nofollow']?'???':"???";
        }
        echo json_encode(['code'=>0,'msg'=>'','count'=>$count,'data'=>$article]);exit;
    }
}
?>