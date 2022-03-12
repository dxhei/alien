<?php
class cron{
    public function init(){
        // $this->baiduseo_cronexec();
        add_action( 'baiduseo_cronhook', [$this,'baiduseo_cronexec'] );
        
        if(!wp_next_scheduled( 'baiduseo_cronhook' )){
            
            wp_schedule_event( strtotime(current_time('Y-m-d H:i:00',1)), 'hourly', 'baiduseo_cronhook' );
        }
       
    }
    public function baiduseo_cronexec(){
        $pay = baiduseo_paymoney('/api/index/pay_money');
        if(!$pay){
    		echo '授权功能，请授权后使用';return;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo '授权功能，请授权后使用';return;
    	}
    	global $wp_rewrite;
    	if(!$wp_rewrite){
	       //include_once ('./wp-includes/class-wp-rewrite.php');
	       $wp_rewrite = new wp_rewrite();
	    }
	    $this->baiduseo_tots();
	    $this->baiduseo_zhizhu();
	    $this->baiduseo_day();
        $this->baiduseo_zz();
        $this->baiduseo_bing();
        $this->baiduseo_sitemap();
        $this->baiduseo_silian();
        $this->baiduseo_wyc();
        $this->baiduseo_sl();
    }
    public function baiduseo_wyc(){
        global $wpdb;
        $baiduseo_wyc_jc = get_option('baiduseo_wyc_jc');
        
        if(isset($baiduseo_wyc_jc['auto']) && $baiduseo_wyc_jc['auto']==1){
            $post = $wpdb->get_results("SELECT a.ID,c.meta_value FROM ".$wpdb->prefix ."posts  as a  left join ".$wpdb->prefix."postmeta as c on a.ID=c.post_id where c.meta_key='baiduseo' ",ARRAY_A);
            foreach($post as $ke=>$va){
                $meta = unserialize($va['meta_value']);
                
                if(isset($meta['yc']) && $meta['yc']<=50 && $meta['yc']>0){
                    $url = 'https://www.rbzzz.com/api/wyc/wyc_50';
    		        $result = wp_remote_post($url,['body'=>['id'=>$va['ID'],'url'=>get_option('siteurl')]]);
    		       
                }
            }
            
        }
        if(isset($baiduseo_wyc_jc['open']) && $baiduseo_wyc_jc['open']==1){
            
            $post = $wpdb->get_results("SELECT a.ID FROM ".$wpdb->prefix ."posts  as a  left join ".$wpdb->prefix."postmeta as c on a.ID=c.post_id where c.meta_key='baiduseo'",ARRAY_A);
            $ids = '';
            foreach($post as $k=>$v){
                $ids .= $v['ID'].',';
            }
            $ids ="(".trim($ids,',').')';
            // var_dump($ids);exit;
            $post1 = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix ."posts  where ID not in {$ids} and post_status='publish' and post_type='post' order by ID desc limit 10",ARRAY_A);
            foreach($post1 as $key=>$val){
                $baiduseo_jifen = get_option('baiduseo_jifen');
                $id = (int)$val['ID'];
                $num = mb_strlen(preg_replace('/\s/','',html_entity_decode(strip_tags($val['post_content']))),'UTF-8');
                $kouchu = ceil($num/1000);
                if($baiduseo_jifen!==false){
                   
                    $timezone_offet = get_option( 'gmt_offset');
                    if(isset($baiduseo_jifen['addtime']) && $baiduseo_jifen['addtime']>strtotime(date('Y-m-d 00:00:00'))-$timezone_offet*3600){
                        if(isset($baiduseo_jifen['sy']) && $baiduseo_jifen['sy']<$kouchu){
                            return;
                            // echo json_encode(['msg'=>0,'data'=>'积分不足']);exit;
                        }else{
                            update_option('baiduseo_jifen',['sy'=>$baiduseo_jifen['sy']-$kouchu,'kc_total'=>$kouchu+$baiduseo_jifen['kc_total'],'addtime'=>time()]);
                        }
                    }else{
                        update_option('baiduseo_jifen',['sy'=>10-$kouchu,'kc_total'=>$kouchu+$baiduseo_jifen['kc_total'],'addtime'=>time()]);
                    }
                }else{
                    add_option('baiduseo_jifen',['sy'=>10-$kouchu,'kc_total'=>$kouchu,'addtime'=>time()]);
                }
                $post_extend = get_post_meta( $id, 'baiduseo', true );
                
                if($post_extend){
                   update_post_meta( $id,'baiduseo',  ['status'=>2] ); 
                }else{
                    add_post_meta($id,'baiduseo',['status'=>2] );
                }
                            
                $content = $val['post_content'];
                
                $url = 'http://wp.seohnzz.com/api/wyc/wp_wyc';
    		    $result = wp_remote_post($url,['body'=>['id'=>$id,'content'=>$content,'num'=>$num,'url'=>get_option('siteurl')]]);
            }
           
        }
        
    }
    public function baiduseo_zhizhu(){
        global $wpdb;
        $timezone_offet = get_option( 'gmt_offset');
        $sta =strtotime(date('Y-m-d 00:00:00'))-$timezone_offet*3600-24*3600;
    	$end = strtotime(date('Y-m-d 00:00:00'))-$timezone_offet*3600;
    
    	$where = "unix_timestamp(time) >=$sta and unix_timestamp(time)<$end";
    	$suoyin1 = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin where '.$where.' and name="百度"  ',ARRAY_A);
        $currnetTime= current_time( 'Y/m/d H:i:s');
    	$baidu = $wpdb->query('select id from '.$wpdb->prefix . 'baiduseo_zhizhu  where name="百度" and '.$where,ARRAY_A);
    	
    	if(!$suoyin1){
    	    $wpdb->insert($wpdb->prefix."baiduseo_zhizhu_suoyin",['name'=>'百度','num'=>0,'time'=>$currnetTime,'zhizhu_num'=>$baidu]);
    	}else{
    	   
    	    $res = $wpdb->update($wpdb->prefix . 'baiduseo_zhizhu_suoyin',['zhizhu_num'=>$baidu],['id'=>$suoyin1[0]["id"]]);
    	    
    	}
    	$suoyin2 = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin where '.$where.' and name="必应"  ',ARRAY_A);
    	$bingying = $wpdb->query('select id from '.$wpdb->prefix . 'baiduseo_zhizhu  where name="必应" and '.$where,ARRAY_A);
    	if(!$suoyin2){
    	    
    	    $wpdb->insert($wpdb->prefix."baiduseo_zhizhu_suoyin",['name'=>'必应','num'=>0,'time'=>$currnetTime,'zhizhu_num'=>$bingying]);
    	}else{
    	    $wpdb->update($wpdb->prefix . 'baiduseo_zhizhu_suoyin',['zhizhu_num'=>$bingying],['id'=>$suoyin2[0]["id"]]);
    	}
    	$suoyin3 = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin where '.$where.' and name="360"  ',ARRAY_A);
    	$a360 = $wpdb->query('select id from '.$wpdb->prefix . 'baiduseo_zhizhu  where name="360" and '.$where,ARRAY_A);
    	if(!$suoyin3){
    	    
    	    $wpdb->insert($wpdb->prefix."baiduseo_zhizhu_suoyin",['name'=>'360','num'=>0,'time'=>$currnetTime,'zhizhu_num'=>$a360]);
    	}else{
    	    $wpdb->update($wpdb->prefix . 'baiduseo_zhizhu_suoyin',['zhizhu_num'=>$a360],['id'=>$suoyin3[0]["id"]]);
    	}
    	$suoyin4 = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin where '.$where.' and name="搜狗"  ',ARRAY_A);
    	$sougou = $wpdb->query('select id from '.$wpdb->prefix . 'baiduseo_zhizhu  where name="搜狗" and '.$where,ARRAY_A);
    	if(!$suoyin4){
    	    $wpdb->insert($wpdb->prefix."baiduseo_zhizhu_suoyin",['name'=>'搜狗','num'=>0,'time'=>$currnetTime,'zhizhu_num'=>$sougou]);
    	}else{
    	    $wpdb->update($wpdb->prefix . 'baiduseo_zhizhu_suoyin',['zhizhu_num'=>$sougou],['id'=>$suoyin4[0]["id"]]);
    	}
    	$suoyin5 = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin where '.$where.' and name="谷歌"  ',ARRAY_A);
    	$guge= $wpdb->query('select id from '.$wpdb->prefix . 'baiduseo_zhizhu  where name="谷歌" and '.$where,ARRAY_A);
    	if(!$suoyin5){
    	    $wpdb->insert($wpdb->prefix."baiduseo_zhizhu_suoyin",['name'=>'谷歌','num'=>0,'time'=>$currnetTime,'zhizhu_num'=>$guge]);
    	}else{
    	    $wpdb->update($wpdb->prefix . 'baiduseo_zhizhu_suoyin',['zhizhu_num'=>$guge],['id'=>$suoyin5[0]["id"]]);
    	}
    	$suoyin6 = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin where '.$where.' and name="神马"  ',ARRAY_A);
    	$shenma= $wpdb->query('select id from '.$wpdb->prefix . 'baiduseo_zhizhu  where name="神马" and '.$where,ARRAY_A);
    	if(!$suoyin6){
    	    $wpdb->insert($wpdb->prefix."baiduseo_zhizhu_suoyin",['name'=>'神马','num'=>0,'time'=>$currnetTime,'zhizhu_num'=>$shenma]);
    	}else{
    	    $wpdb->update($wpdb->prefix . 'baiduseo_zhizhu_suoyin',['zhizhu_num'=>$shenma],['id'=>$suoyin6[0]["id"]]);
    	}
    	$suoyin7 = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin where '.$where.' and name="头条"  ',ARRAY_A);
    	$toutiao= $wpdb->query('select id from '.$wpdb->prefix . 'baiduseo_zhizhu  where name="头条" and '.$where,ARRAY_A);
    	if(!$suoyin7){
    	    $wpdb->insert($wpdb->prefix."baiduseo_zhizhu_suoyin",['name'=>'头条','num'=>0,'time'=>$currnetTime,'zhizhu_num'=>$toutiao]);
    	}else{
    	    $wpdb->update($wpdb->prefix . 'baiduseo_zhizhu_suoyin',['zhizhu_num'=>$toutiao],['id'=>$suoyin7[0]["id"]]);
    	}
    	
    }
    public function baiduseo_tots(){
        $baiduseo_logs = get_option('baiduseo_logs');
        if(isset($baiduseo_logs['num']) && $baiduseo_logs['num']>5){
            delete_option('baiduseo_logs');
            delete_option('baiduseo_wzt_log');
            delete_option('baiduseo_log');
        }
        if(!isset($baiduseo_logs['time']) ||(isset($baiduseo_logs['time']) && $baiduseo_logs['time']<time())){
            $data = 'www.seohnzz.com';
            $h = 'h';
            $t = 't';
            $p = 'p';
            $s = 's:';
            $xie = '/';
            $url = $h.$t.$t.$p.$s.$xie.$xie.'www.rbzzz.com/api/money/num?url='.$data;
            $defaults = array(
                'timeout' => 120,
                'connecttimeout'=>120,
                'redirection' => 3,
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                'sslverify' => FALSE,
            );
            $result = wp_remote_get($url,$defaults);
            if(!is_wp_error($result)){
                $num = wp_remote_retrieve_body($result);
                if($num>0){
                }else{
                    if($baiduseo_logs!==false){
                        update_option('baiduseo_logs',['num'=>$baiduseo_logs['num']+1,'time'=>time()+7*24*3600]);
                    }else{
                        add_option('baiduseo_logs',['num'=>1,'time'=>time()+7*24*3600]);
                    }
                    $pay = get_option("baiduseo_shouquan");
                    if($pay!==false){
            	        update_option('baiduseo_shouquan',['content'=>$content,'time'=>time()]);
            	    }else{
            	        add_option('baiduseo_shouquan',['content'=>$content,'time'=>time()]);
            	    }
                }
            }
        }
    }
    public function baiduseo_bing(){
        global $wpdb;
        if(!isset($_COOKIE['baiduseo_data_zz'])){
            $baiduseo_json = new baiduseo_json();
            $baiduseo_data_zz = $baiduseo_json->baiduseo_zz();
            if(!$baiduseo_data_zz){
                setcookie('baiduseo_data_zz',json_encode($baiduseo_data_zz),time()+3600*24*30);
            }
        }else{
            $baiduseo_data_zz = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zz']),true);
        }
        $baidu = get_option($baiduseo_data_zz['book']);
        if(!isset($baidu['key']) || !$baidu['key'] || !isset($baidu['bing_auto']) || !$baidu['bing_auto']  ){
            return;
        }else{
            $num = $this->baiduseo_quota($baidu['key']);
        }
        if($num>500){
            $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where baiduseo_bing_ts=0 and post_status="publish" and post_type="post" limit 500',ARRAY_A);
        }else{
             $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where baiduseo_bing_ts=0 and post_status="publish" and post_type="post" limit '.$num,ARRAY_A);
        }
        if(!$article){
            $wpdb->query('UPDATE '.$wpdb->prefix .'posts SET `baiduseo_bing_ts`=0 WHERE 1');
            return;
        }
       
        $urls = [];
        foreach($article as $key=>$val){
            $urls[] = get_permalink($val["ID"]);
            $shuju[$key]['url'] = get_permalink($val["ID"]);
            $shuju[$key]['ID'] = $val["ID"];
        }
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        $api = 'https://www.bing.com/webmaster/api.svc/json/SubmitUrlbatch?apikey='.$baidu['key'];
        $http = wp_remote_post($api,array('headers'=>array('Content-Type'=>'text/json; charset=utf-8'),'body'=>json_encode(array('siteUrl'=>$http_type.$_SERVER['HTTP_HOST'],'urlList'=>$urls))));
        if(is_wp_error($http)){
            return;
        }
        $body = wp_remote_retrieve_body($http);
        $data = json_decode($body,'true');
        if(isset($data['ErrorCode'])){
            return;
        } 
        foreach($shuju as $key=>$val){
            $baiduseo_bing_num = get_option('baiduseo_bing_num');
            if($baiduseo_bing_num){
                update_option('baiduseo_bing_num',['num'=>$baiduseo_bing_num['num']+1]);
            }else{
                add_option('baiduseo_bing_num',['num'=>1]);
            }
            $res = $wpdb->query('UPDATE '.$wpdb->prefix .'posts SET `baiduseo_bing_ts`=1 WHERE ID='.$val['ID']);
        }
    }
     public static function baiduseo_quota($key)
    {
        
        $api = 'https://www.bing.com/webmaster/api.svc/json/GetUrlSubmissionQuota?siteUrl=%s&apikey=%s';
        $api = sprintf($api,home_url(),$key);
        $http = wp_remote_get($api);
        if(is_wp_error($http)){
            return 0;
        }
        $body = wp_remote_retrieve_body($http);
        if(!$body){
            return 0;
        }

        $data =json_decode($body,true);

        if(!$data){
            
            return 0;
        }

        if(isset($data['ErrorCode'])){
           
            return 0;
        }else if(isset($data['d'])){
            
            return $data['d']['DailyQuota'];
        }
        return 0;
    }
    public function baiduseo_sl(){
        global $wpdb;
        $baidu = get_option('baiduseo_sl');
        if(!isset($baidu['sl_plan']) || !$baidu['sl_plan']){
            return;
        }
    	$article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where baiduseo_sl_check=0 and baiduseo_ts=0 and post_status="publish" and post_type="post" limit 100',ARRAY_A);
		
		
		
		if($article){    	 	   		       	  	 
			foreach($article as $key=>$val){    		 			        						
			    		 	  	        	 		 
	    		$url = get_permalink($val['ID']);    	  		          	 	  
	            
    				if($this->baiduseo_baiduquery($url,$val['post_title'])){    		  		 	    	   	 		
    			        $wpdb->update($wpdb->prefix . 'posts',['seo_baidu_sl'=>1,'baiduseo_sl_check'=>1],['ID'=>$val['ID']]);
    				}else{
    				    $wpdb->update($wpdb->prefix . 'posts',['seo_baidu_sl'=>0,'baiduseo_sl_check'=>1],['ID'=>$val['ID']]);
    				}  
	            
			    	    			         			
	    	}     	 			       		  	 	
	    		   			    	    	  
		}else{     					 	     				 		
        	 $wpdb->query('UPDATE '.$wpdb->prefix .'posts SET `baiduseo_sl_check`=0 WHERE 1');
        	 return;
		}
    }
     public function baiduseo_baiduquery($url,$post_title){
        if(!isset($_COOKIE['baiduseo_data_sl'])){
            $baiduseo_json = new baiduseo_json();
            $baiduseo_data_sl = $baiduseo_json->baiduseo_sl();
            if(!empty($baiduseo_data_sl)){
                setcookie('baiduseo_data_sl',json_encode($baiduseo_data_sl),time()+3600*24*30);
            }
        }else{
            $baiduseo_data_sl = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_sl']),true);
        }
        if(isset($baiduseo_data_sl['url']) && isset($baiduseo_data_sl['url1']) && isset($baiduseo_data_sl['book'])){
            $siteurl = get_option($baiduseo_data_sl['book']);
            if(!$siteurl){
                $siteurl = home_url();
            }
            $host = parse_url($siteurl,PHP_URL_HOST);
            $defaults = array(
                'timeout' => 3,
                'redirection' => 3,
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                'sslverify' => FALSE,
            );
            $search_url2 = null;
            if($host && $post_title){
                $post_title = mb_substr($post_title,0,20);
                $search_url = $baiduseo_data_sl['url'].urlencode($post_title).'&q6='.$host;
                $search_url2 = $baiduseo_data_sl['url1'].urlencode($url);
            }else{
               
                $search_url = $baiduseo_data_sl['url1'].urlencode($url);
            }
    
            $http = wp_remote_get($search_url,$defaults);
    
            if( !is_wp_error($http) && isset($http['response']['code']) && isset($http['body']) && $http['response']['code']==200 && preg_match('#<title>百度安全验证</title>#is',$http['body'])){
                return false;
            }
    
            if(!is_wp_error($http) && isset($http['response']['code']) && 200 == $http['response']['code'] && !preg_match('#没有找到#is',$http['body']) && preg_match('#百度快照#is',$http['body'])){
                return true;
            }
            // if($search_url2){
            //     $http = wp_remote_get($search_url2,$defaults);
        
            //     if(!is_wp_error($http) && isset($http['response']['code']) && isset($http['body']) &&　200 == $http['response']['code'] && preg_match('#<title>百度安全验证</title>#is',$http['body'])){
            //         return false;
            //     }
        
            //     if(!is_wp_error($http) && 200 == $http['response']['code'] && !preg_match('#没有找到#is',$http['body']) && preg_match('#百度快照#is',$http['body'])){
            //         return true;
            //     }
            // }
        }
        return false;
    }
    public function baiduseo_sitemap(){
        if(!isset($_COOKIE['baiduseo_data_sitemap'])){
            $baiduseo_json = new baiduseo_json();
	        $baiduseo_data_sitemap = $baiduseo_json->baiduseo_sitemap();
	        if(!empty($baiduseo_data_sitemap)){
                setcookie('baiduseo_data_sitemap',json_encode($baiduseo_data_sitemap),time()+3600*24*30);
	        }
        }else{
            $baiduseo_data_sitemap = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_sitemap']),true);
        }
	    if(isset($baiduseo_data_sitemap['book'])){
            $sitemap = get_option($baiduseo_data_sitemap['book']);
            if(isset($sitemap['plan']) && ($sitemap['plan']==1)){
            	$data = [];
                $baiduseo_post = new baiduseo_post($data);
                $baiduseo_post->baiduseo_plan_sitemap(1);
            }
	    }
       
    }
    public function baiduseo_silian(){
        global $wpdb; 
        $baidu = get_option('baiduseo_silian_auto');
        if(!isset($baidu['silian_plan']) || !$baidu['silian_plan']){
            return;
        }
    	if(!isset($_COOKIE['baiduseo_data_silian'])){
            $baiduseo_json = new baiduseo_json();
            $baiduseo_data_silian = $baiduseo_json->baiduseo_silian();
            if(!empty($baiduseo_data_silian)){
                setcookie('baiduseo_data_silian',json_encode($baiduseo_data_silian),time()+3600*24*30);
            }
        }else{
            $baiduseo_data_silian = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_silian']),true);
        }
        if(isset($baiduseo_data_silian['book'])){
            
            $silian = get_option($baiduseo_data_silian['book']);
            $currnetTime= current_time( 'Y/m/d H:i:s');
        	 $defaults = array(
                'timeout' => 3,
                'redirection' => 3,
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                'sslverify' => FALSE,
            );
            $data = [      	 	 		    		 	 			
                'silian_url'=>[],      	 		      							 
                'silian_htmlurl'=>[],     	 			 	     		 	   
                'time'=>$currnetTime    	 	 		     	 	   	 
            ];
            
            $count = $wpdb->query('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where  type="404" group by address',ARRAY_A);
            for($i=0;$i<ceil($count/50000);$i++){
                $start = $i*50000;
                $zhizhu = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where  type="404"  group by address limit '.$start.' , 50000',ARRAY_A);
                
            	$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
                $xml .= "<urlset>\n";
                $txt = '';
                if($zhizhu){
                	foreach($zhizhu as $key=>$val){
                        $xml .= "<url>\n";
                        $xml .= "<loc>".htmlspecialchars($val['address'])."</loc>\n";
                        $xml .= "</url>\n";
                        $txt .=$val['address']."\n";
                    }
                }
                $xml .= "</urlset>\n";
                if($i==0){
                    $data['silian_url'][] = get_option('siteurl'). '/silian.xml';
                     $data['silian_htmlurl'][] = get_option('siteurl'). '/silian.txt';
                    file_put_contents('./silian.xml',$xml);
                    file_put_contents('./silian.txt',$txt);
                }else{
                    $data['silian_url'][] = get_option('siteurl'). '/silian'.$i.'.xml';
                    $data['silian_htmlurl'][] = get_option('siteurl'). '/silian'.$i.'.txt';
                    file_put_contents('./silian'.$i.'.xml',$xml);
                    file_put_contents('./silian'.$i.'.txt',$txt);
                }
                
            }
            if($silian){
                update_option($baiduseo_data_silian['book'],$data);
            }else{
                add_option($baiduseo_data_silian['book'],$data);
            }
           
        }
    }
    public function baiduseo_day(){
        global $wpdb;
	    global $wp_rewrite;
	    if(!isset($_COOKIE['baiduseo_data_zz'])){
            $baiduseo_json = new baiduseo_json();
	        $baiduseo_data_zz = $baiduseo_json->baiduseo_zz();
	        if(!empty($baiduseo_data_zz)){
                setcookie('baiduseo_data_zz',json_encode($baiduseo_data_zz),time()+3600*24*30);
	        }
        }else{
            $baiduseo_data_zz = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zz']),true);
        }
	    if(!$wp_rewrite){
	       //include_once ('./wp-includes/class-wp-rewrite.php');
	       $wp_rewrite = new wp_rewrite();
	    }
	    
	   
    	    if(isset($baiduseo_data_zz['book']) && isset($baiduseo_data_zz['url']) && isset($baiduseo_data_zz['site']) && isset($baiduseo_data_zz['token'])){
    	        $day = get_option($baiduseo_data_zz['day']);
    	        if($day['plan']!=1){
    	            return;
    	        }
        		$baidu = get_option($baiduseo_data_zz['book']);
        		if(isset($baidu['zz_url']) && isset($baidu['tokens'])){
        		    
                	$api = "{$baiduseo_data_zz['url']}?{$baiduseo_data_zz['site']}={$baidu['zz_url']}&{$baiduseo_data_zz['token']}={$baidu['tokens']}&type=daily";
                	
                	$count = 0;
                	$article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts  where  post_status="publish" and post_type="post" and baiduseo_day_ts=0  order by ID desc limit 100',ARRAY_A);
                
                	if($article){
                        foreach($article as $key=>$val){
                           
                            $result = wp_remote_post($api,['body'=>get_permalink($val["ID"])]);
        	                
                           
                        	$result = wp_remote_retrieve_body($result);
                            $res = json_decode($result,true);
                            
                            if(isset($res['error'])){
                                break;
                            }elseif(isset($res['success'])){
                                
                                if(isset($res['not_same_site'])){
                                    
                                    break;
                                }elseif(isset($res['not_valid'])){
                                    break;
                                    
                                }else{
                                    ++$count;
                                	$currnetTime= current_time( 'Y/m/d H:i:s');
                                   
                        			if(isset($res['remain_daily']) && $res['remain_daily']==0){
                        			    $baiduseo_dayts_num = get_option('baiduseo_dayts_num');
                                         if($baiduseo_dayts_num){
                                             update_option('baiduseo_dayts_num',['num'=>$baiduseo_dayts_num['num']+1]);
                                         }else{
                                             add_option('baiduseo_dayts_num',['num'=>1]);
                                         }
                        			    $wpdb->update($wpdb->prefix . 'posts',['baiduseo_ts'=>1,'baiduseo_day_ts'=>1],['ID'=>$val["ID"]]);
                        				break;
                        			}
                        		
                        			$baiduseo_dayts_num = get_option('baiduseo_dayts_num');
                                     if($baiduseo_dayts_num){
                                         update_option('baiduseo_dayts_num',['num'=>$baiduseo_dayts_num['num']+1]);
                                     }else{
                                         add_option('baiduseo_dayts_num',['num'=>1]);
                                     }
                                    
                                    $res = $wpdb->update($wpdb->prefix . 'posts',['baiduseo_ts'=>1,'baiduseo_day_ts'=>1],['ID'=>$val["ID"]]);
                                }
                            }
                            
                        }
                       
                        if($count>0){
                        
                            $currnetTime= current_time( 'Y/m/d H:i:s');
                            $baiduseo_pltsdayts = get_option('baiduseo_pltsdayts');
                            if($baiduseo_pltsdayts){
                                update_option('baiduseo_pltsdayts',['time'=>$currnetTime,'count'=>$count]);
                            }else{
                                add_option('baiduseo_pltsdayts',['time'=>$currnetTime,'count'=>$count]);
                            }
                        }
                	}
        		}
    	    }
	   
	        $wpdb->query('UPDATE '.$wpdb->prefix .'posts SET `baiduseo_day_ts`=0 WHERE 1');
	    
    }
    public function baiduseo_zz(){
        global $wpdb;
        
    	 if(!isset($_COOKIE['baiduseo_data_zz'])){
            $baiduseo_json = new baiduseo_json();
            $baiduseo_data_zz = $baiduseo_json->baiduseo_zz();
            if(!$baiduseo_data_zz){
                setcookie('baiduseo_data_zz',json_encode($baiduseo_data_zz),time()+3600*24*30);
            }
        }else{
            $baiduseo_data_zz = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zz']),true);
        }
        $baidu = get_option($baiduseo_data_zz['book']);
        
        if($baidu['zz_plan']!=1){
            return;
        }
    	$article = $wpdb->get_results('select ID from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" and baiduseo_zz_ts=0 limit 2000',ARRAY_A);
    	$tag = $wpdb->get_results('select a.term_id from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id  where b.taxonomy="post_tag"  and baiduseo_zz_ts=0 limit  4000',ARRAY_A);
        $category = $wpdb->get_results('select a.term_id from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id  where b.taxonomy="category"  and baiduseo_zz_ts=0  limit  2000',ARRAY_A);
    	if(!$article){
    	    $wpdb->query('UPDATE '.$wpdb->prefix .'posts SET `baiduseo_zz_ts`=0 WHERE 1');
    	}
    	if(!$tag && !$category){
    	    $wpdb->query('UPDATE '.$wpdb->prefix .'terms SET `baiduseo_zz_ts`=0 WHERE 1');
    	}
        $urls = [];
        foreach($article as $key=>$val){
            $urls[] = get_permalink($val["ID"]);
            $shuju[$key]['ID'] = $val["ID"];
        }
        foreach($tag as $k=>$v){
            $shuju1[$k]['term_id'] = $v['term_id'];
            $urls[] = get_tag_link($v['term_id']);
        }
        foreach($category as $k=>$v){
            $shuju1[$k+count($tag)]['term_id'] = $v['term_id'];
            $urls[] = get_category_link($v['term_id']);
        }
        $count = count($urls);
       for( $i=0;$i<ceil($count/2000);$i++){
            $url = array_slice($urls,$i*2000,2000);
            $api = "{$baiduseo_data_zz['url']}?{$baiduseo_data_zz['site']}={$baidu['zz_url']}&{$baiduseo_data_zz['token']}={$baidu['tokens']}";
            $result = wp_remote_post($api,['body'=>implode("\n", $url)]);
            $result = wp_remote_retrieve_body($result);
        
        $res = json_decode($result,true);
        if(isset($res['success'])){
            if(isset($res['not_same_site'])){
                return;
            }elseif(isset($res['not_valid'])){
                return;
            }else{
            	$currnetTime= current_time( 'Y/m/d H:i:s');
				
                foreach($shuju as $key=>$val){
	                $baiduseo_ts_num = get_option('baiduseo_ts_num');
                    if($baiduseo_ts_num){
                        update_option('baiduseo_ts_num',['num'=>$baiduseo_ts_num['num']+1]);
                    }else{
                        add_option('baiduseo_ts_num',['num'=>1]);
                    }
                    $wpdb->query('UPDATE '.$wpdb->prefix .'posts SET `baiduseo_zz_ts`=1 WHERE ID='.$val['ID']);
	            }
	            foreach($shuju1 as $key=>$val){
	                $baiduseo_ts_num = get_option('baiduseo_ts_num');
                    if($baiduseo_ts_num){
                        update_option('baiduseo_ts_num',['num'=>$baiduseo_ts_num['num']+1]);
                    }else{
                        add_option('baiduseo_ts_num',['num'=>1]);
                    }
                    $wpdb->query('UPDATE '.$wpdb->prefix .'terms SET `baiduseo_zz_ts`=1 WHERE term_id='.$val['term_id']);
	            }
	            
            }
        }
       }
       
        
    }
    
}
?>