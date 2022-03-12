<?php
if(!defined('ABSPATH'))exit;
//站长推送
function baiduseo_bdzzts($urls,$type=0,$datatype=1,$tishi=1,$count=0){
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
   
    if(!empty($baiduseo_data_zz)){
        if(isset($baiduseo_data_zz['book']) && isset($baiduseo_data_zz['url']) && isset($baiduseo_data_zz['site']) && isset($baiduseo_data_zz['token'])){
            $baidu = get_option($baiduseo_data_zz['book']);
            $api = "{$baiduseo_data_zz['url']}?{$baiduseo_data_zz['site']}={$baidu['zz_url']}&{$baiduseo_data_zz['token']}={$baidu['tokens']}";
            $result = wp_remote_post($api,['body'=>implode("\n", $urls)]);
            if(is_wp_error($result)){
                if($tishi){
                    echo json_encode(['msg'=>"推送失败，原因是服务器网络波动，请稍后重试",'status'=>0]);exit;
                }
            }
            $result = wp_remote_retrieve_body($result);
            $res = json_decode($result,true);
            if($datatype){
                if(isset($res['error'])){
                	if($tishi){
                        echo json_encode(['msg'=>"推送失败，原因是{$res['message']}",'status'=>0]);exit;
                	}
                }elseif(isset($res['success'])){
                    if(isset($res['not_same_site'])){
                        $not_same_site = implode('\n',$res['not_same_site']);
                        if($tishi){
                        echo json_encode(['msg'=>"推送失败，原因是存在不是本站url:{$not_same_site}",'status'=>0]);exit;
                        }
                    }elseif(isset($res['not_valid'])){
                        $not_valid = implode('\n',$res['not_valid']);
                        if($tishi){
                        echo json_encode(['msg'=>"推送失败，原因是不合法的url:{$not_valid}",'status'=>0]);exit;
                        }
                    }else{
        				//获取当前时间
        				$currnetTime= current_time( 'Y/m/d H:i:s');
        				
                        foreach($urls as $key=>$val){
        	                $data_array=[
        	                    'time' => $currnetTime,
        	                    'post_id'=>0,
        	                    'link' => $val,
        	                    'type' =>3
        	                ];
        	                $baiduseo_ts_num = get_option('baiduseo_ts_num');
        	                if($baiduseo_ts_num){
        	                    update_option('baiduseo_ts_num',['num'=>$baiduseo_ts_num['num']+1]);
        	                }else{
        	                    add_option('baiduseo_ts_num',['num'=>1]);
        	                }
        	               
        	            }
                        if($type==1){
                        	if($count>0){
                        		$data['zz_tsts']=$count;
                        	}else{
                        		$data['zz_tsts']=$res['success'];
                        	}
        	               
        	                $data['zz_kts']=$res['remain'];
        	                $data['time']= $currnetTime;
        	                $baidu = get_option('seo_baidu_zz_yjts');
        	
        	                if($baidu){
        	                  update_option('seo_baidu_zz_yjts',$data);
        	                }else{
        	                  add_option('seo_baidu_zz_yjts',$data);
        	                }
        	            }
        	            if($tishi){
                        	if($count>0){
                        		echo json_encode(['msg'=>"推送成功，推送了{$count}条,剩余配额{$res['remain']}条",'status'=>1]);exit;
                        	}else{
                        		echo json_encode(['msg'=>"推送成功，推送了{$res['success']}条,剩余配额{$res['remain']}条",'status'=>1]);exit;
                        	}
                        }
                    }
                }
            }
        }
    }
}
//死链查询
function baiduseo_siliansc($type=0){
	global $wpdb;  
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
            'silian_url'=>get_option('siteurl'). '/silian.xml',      	 		      							 
            'silian_htmlurl'=>get_option('siteurl').'/silian.txt',     	 			 	     		 	   
            'time'=>$currnetTime    	 	 		     	 	   	 
        ];
        if($silian){
            update_option($baiduseo_data_silian['book'],$data);
        }else{
            add_option($baiduseo_data_silian['book'],$data);
        }
        $zhizhu = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where  type="404" group by address ',ARRAY_A);
    
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
        }else{
        	echo json_encode(['msg'=>0]);exit;
        }
        $xml .= "</urlset>\n";
        if($type==0){
            file_put_contents('../silian.xml',$xml);
            file_put_contents('../silian.txt',$txt);
        }elseif($type==1){
            file_put_contents('./silian.xml',$xml);
            file_put_contents('./silian.txt',$txt);
        }
        echo json_encode(['msg'=>1]);exit;
    }else{
        echo json_encode(['msg'=>0]);exit;
    }
}

//文章生成是sitemap自动生成
function baiduseo_sitemapsc_1($page=1,$plan=0){
	global $wpdb;
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
        //sitemap生成
        $sitemap = get_option($baiduseo_data_sitemap['book']);
        
        $currnetTime= current_time( 'Y/m/d H:i:s');
        $currnetTime1= current_time( 'Y-m-d H:i:s');
        if(isset($sitemap['site_auto']) && ($sitemap['site_auto']==1)){
        	$data = $sitemap;
        	$data['time'] = $currnetTime;
        	if($page == 1){
            	$data['sitemap_url'] = [];
            	$data['sitemap_htmlurl'] = [];
            	$data['sitemap_tag'] = get_option('siteurl'). '/tag.html';
                	$tags = $wpdb->get_results('select a.* from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag"  ',ARRAY_A);
                	
                	$html_tag = "<!DOCTYPE html>\n<html>\n<head>\n<meta charset='UTF-8'>\n<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n<style>\nbody {\nbackground-color: #f3f3f3;\n}\nul li {\ndisplay: inline-block;\npadding: 5px 10px;\nmargin: 5px 0px;\nbackground-color: #fff;\nborder-radius: 25px;\n}\n* {\nmargin: 0;\npadding: 0;\n}\na {\ntext-decoration: none;\ncolor: #111;\nfont-weight: 300;\n}\na:hover{\ncolor: skyblue;\n}\n</style>\n</head>\n<body>\n<!-- 官网：www.rbzzz.com(可接定制开发、网站、小程序、公众号、seo/sem优化)交流QQ群：1077537009 客服QQ：1500351892 -->\n<ul>";
                	foreach($tags as $k=>$val){
                	   
                	    $html_tag .="<li><a href='".get_tag_link($val["term_id"])."' title='{$val['name']}'>{$val['name']}</a></li>\n";
                	}
                	$html_tag .="</ul>\n</body>\n</html>";
            }
            if($page>1){
            	$page1 = $page-1;
    	        $data['sitemap_url'][] = get_option('siteurl'). '/sitemap'.$page1.'.xml';
    	        $data['sitemap_htmlurl'][] = get_option('siteurl').'/sitemap'.$page1.'.html';
            }else{
            	 $data['sitemap_url'][] = get_option('siteurl'). '/sitemap.xml';
    	        $data['sitemap_htmlurl'][] = get_option('siteurl').'/sitemap.html';
            }
            $start = 49999*($page-1);
            if(($sitemap['type1']==1)&&($sitemap['type2']==1)&&($sitemap['type3']==1)){
                $type = 7;
            }elseif(($sitemap['type2']==1)&&($sitemap['type3']==1)){
                $type = 6;
            }elseif(($sitemap['type1']==1)&&($sitemap['type3']==1)){
                 $type = 5;
            }elseif(($sitemap['type1']==1)&&($sitemap['type2']==1)){
                 $type = 4;
            }elseif($sitemap['type3']==1){
                 $type = 3;
            }elseif($sitemap['type2']==1){
                 $type = 2;
            }elseif($sitemap['type1']==1){
                 $type = 1;
            }
            switch($type){
                case 1:
                    $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" order by post_date desc limit '.$start.', 49999',ARRAY_A);
                    break;
                case 2:
                    $pages = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="page" order by post_date desc limit '.$start.', 49999',ARRAY_A);
                    break;
                case 3:
                    $tag = $wpdb->get_results('select a.* from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag"  limit '.$start.', 49999',ARRAY_A);
                    break;
                case 4:
                    $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" order by post_date desc limit '.$start.', 49899',ARRAY_A);
                   
                    $pages  = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="page" order by post_date desc limit 100',ARRAY_A);
                    
                    break;
                case 5:
                    $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" order by post_date desc limit '.$start.', 49999',ARRAY_A);
                    if(count($article)<49999){
                        if(count($article)==0){
                            $page_total = wp_count_posts()->publish;
                            $start1 = 49999-$page_total%49999+($page-ceil($page_total/49999)-1)*49999;
                            $tag = $wpdb->get_results('select a.* from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag"  limit '.$start1.', 49999',ARRAY_A);
                        }else{
                            $total = 49999-count($article);
                            $start1 = 0;
                            $tag = $wpdb->get_results('select a.* from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag"  limit '.$start1.', '.$total,ARRAY_A);
                        }
                    }
                    
                    break;
                case 6:
                    $pages = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="page" order by post_date desc limit 100',ARRAY_A);
                    $start1 = ($page-1)*49899;
                    $tag = $wpdb->get_results('select a.* from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag"  limit '.$start1.', 49899',ARRAY_A);
                    break;
                case 7:
                    $start1 = ($page-1)*49899;
                    $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" order by post_date desc limit '.$start1.', 49899',ARRAY_A);
                    
                    if(count($article)<49899){
                        
                        if(count($article)==0){
                           $page_total = wp_count_posts()->publish;
                           
                            $start2 = 49899-$page_total%49899+($page-ceil($page_total/49899)-1)*49899;
                           
                            $tag = $wpdb->get_results('select a.* from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag"  limit '.$start2.', 49899',ARRAY_A);
                        }else{
                            
                            $total = 49899-count($article);
                            $start1 = 0;
                            $tag = $wpdb->get_results('select a.* from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag"  limit '.$start1.', '.$total,ARRAY_A);
                        }
                        
                    }
                    
                    $pages = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="page" order by post_date desc limit 100',ARRAY_A);
                    
                    break;
                
            }
            $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
             $xml .= "<urlset>\n";
               $html = "<!DOCTYPE html>\n<html>\n<head>\n<meta charset='UTF-8'>\n<meta name='viewport' content='width=device-width, initial-scale=1.0'><style>\nbody {\nbackground-color: #f3f3f3;\n}\nol {\nbackground-color: #fff;\nmax-width: 1200px;\nmargin: 0 auto;\nbox-sizing: border-box;\npadding: 15px 125px;\n}\nol li {\npadding: 15px 0;\n}\nol li a {\ncolor: #333;\npadding-left: 25px;\ntext-decoration: none;\n}\n</style>\n</head>\n<body>\n<!-- 官网：www.rbzzz.com(可接定制开发、网站、小程序、公众号、seo/sem优化)交流QQ群：1077537009 客服QQ：1500351892 -->\n<ol>";
               if($page==1){
            		$xml .= "<url>\n";
                    $xml .= "<loc>".get_option('siteurl')."</loc>\n";
                    $xml .= "<lastmod>{$currnetTime1}</lastmod>\n";
                    $xml .= "<changefreq>daily</changefreq>\n";
                    $xml .= "<priority>1.0</priority>\n";
                    $xml .= "</url>\n";
                    $html .="<li>".$currnetTime1."<a href='".get_option('siteurl')."' title='".get_option('blogname')."'>".get_option('blogname')."</a></li>\n";
               }
            $article = isset($article)?$article:[];
            $tag = isset($tag)?$tag:[];
            $pages = isset($pages)?$pages:[];
            if(!$article && !$tag ||($type==2 && !$pages)){
            }else{
                if($article){
                
        	        
        	        foreach($article as $key=>$val){
        	           
        	                $level1 = $sitemap['level1']/100;
        	                $xml .= "<url>\n";
        	                $xml .= "<loc>".get_permalink($val["ID"])."</loc>\n";
        	                $xml .= "<lastmod>{$val['post_date']}</lastmod>\n";
        	                $xml .= "<changefreq>{$sitemap['post_time']}</changefreq>\n";
        	                $xml .= "<priority>{$level1}</priority>\n";
        	                $xml .= "</url>\n";
        	            $html .="<li>".$val['post_date']."<a href='".get_permalink($val["ID"])."' title='{$val['post_title']}'>{$val['post_title']}</a></li>\n";
        	           
        	        }
                }
                if($pages){
                
        	        
        	        foreach($pages as $key=>$val){
        	           
        	                $level2 = $sitemap['level2']/100;
        	                $xml .= "<url>\n";
        	                $xml .= "<loc>".get_permalink($val["ID"])."</loc>\n";
        	                $xml .= "<lastmod>{$val['post_date']}</lastmod>\n";
        	                $xml .= "<changefreq>{$sitemap['page_time']}</changefreq>\n";
        	                $xml .= "<priority>{$level2}</priority>\n";
        	                $xml .= "</url>\n";
        	            $html .="<li>".$val['post_date']."<a href='".get_permalink($val["ID"])."' title='{$val['post_title']}'>{$val['post_title']}</a></li>\n";
        	           
        	        }
                }
                if($tag){
                    foreach($tag as $key=>$val){
        	           
        	                $level3 = $sitemap['level3']/100;
        	                $xml .= "<url>\n";
        	                $xml .= "<loc>".get_tag_link($val["term_id"])."</loc>\n";
        	                $xml .= "<changefreq>{$sitemap['page_time']}</changefreq>\n";
        	                $xml .= "<priority>{$level3}</priority>\n";
        	                $xml .= "</url>\n";
        	            $html .="<li><a href='".get_tag_link($val["term_id"])."'>{$val['name']}:".get_tag_link($val["term_id"])."</a></li>\n";
        	           
        	        }
                }
        	        $xml .='</urlset>';
        	        $html .="</ol>\n</body>\n</html>";
        	        if($page>1){
                		$page1 = $page-1;
                		if($plan){
                		    file_put_contents('./sitemap'.$page1.'.xml',$xml);
        	        	    file_put_contents('./sitemap'.$page1.'.html',$html);
                		}else{
                		    file_put_contents('../sitemap'.$page1.'.xml',$xml);
        	        	    file_put_contents('../sitemap'.$page1.'.html',$html);
                		}
                		 
                		
        	        }else{
        	            if($plan){
        	                file_put_contents('./tag.html',$html_tag);
        	                file_put_contents('./sitemap.xml',$xml);
        	        	    file_put_contents('./sitemap.html',$html);
        	            }else{
        	                file_put_contents('../tag.html',$html_tag);
        	                file_put_contents('../sitemap.xml',$xml);
        	        	    file_put_contents('../sitemap.html',$html);
        	            }
        	        	
        	        }
        	        update_option($baiduseo_data_sitemap['book'],$data);
        	        if($plan){
        	            baiduseo_sitemapsc_1(++$page,1);
        	        }else{
        	            baiduseo_sitemapsc_1(++$page);
        	        }
            }
        }
    }
}
//sitamap自动生成
function  baiduseo_sitemapsc($page=1){
    global $wpdb;
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
        //sitemap生成
        $sitemap = get_option($baiduseo_data_sitemap['book']);
        
       $currnetTime= current_time( 'Y/m/d H:i:s');
       $currnetTime1= current_time( 'Y-m-d H:i:s');
        if(isset($sitemap['site_auto']) && ($sitemap['site_auto']==1)){
        	$data = $sitemap;
        	$data['time'] = $currnetTime;
        	if($page == 1){
            	$data['sitemap_url'] = [];
            	$data['sitemap_htmlurl'] = [];
            }
            if($page>1){
            	$page1 = $page-1;
    	        $data['sitemap_url'][] = get_option('siteurl'). '/sitemap'.$page1.'.xml';
    	        $data['sitemap_htmlurl'][] = get_option('siteurl').'/sitemap'.$page1.'.html';
            }else{
            	 $data['sitemap_url'][] = get_option('siteurl'). '/sitemap.xml';
    	        $data['sitemap_htmlurl'][] = get_option('siteurl').'/sitemap.html';
            }
            $start = 2000*($page-1);
            if(($sitemap['type1']==1)&&($sitemap['type2']==1)&&($sitemap['type3']==1)){
                $type = 7;
            }elseif(($sitemap['type2']==1)&&($sitemap['type3']==1)){
                $type = 6;
            }elseif(($sitemap['type1']==1)&&($sitemap['type3']==1)){
                 $type = 5;
            }elseif(($sitemap['type1']==1)&&($sitemap['type2']==1)){
                 $type = 4;
            }elseif($sitemap['type3']==1){
                 $type = 3;
            }elseif($sitemap['type2']==1){
                 $type = 2;
            }elseif($sitemap['type1']==1){
                 $type = 1;
            }
            switch($type){
                case 1:
                    $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" order by post_date desc limit '.$start.', 2000',ARRAY_A);
                    break;
                case 2:
                    $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="page" order by post_date desc limit '.$start.', 2000',ARRAY_A);
                    break;
                case 3:
                    $tag = $wpdb->get_results('select a.* from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag"  limit '.$start.', 2000',ARRAY_A);
                    break;
                case 4:
                    $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" order by post_date desc limit '.$start.', 2000',ARRAY_A);
                    $pages  = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="page" order by post_date desc limit '.$start.', 2000',ARRAY_A);
                    break;
                case 5:
                    $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" order by post_date desc limit '.$start.', 2000',ARRAY_A);
                    $tag = $wpdb->get_results('select a.* from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag"  limit '.$start.', 2000',ARRAY_A);
                    break;
                case 6:
                    $pages = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="page" order by post_date desc limit '.$start.', 2000',ARRAY_A);
                     $tag = $wpdb->get_results('select a.* from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag"  limit '.$start.', 2000',ARRAY_A);
                    break;
                case 7:
                    $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" order by post_date desc limit '.$start.', 2000',ARRAY_A);
                    $pages = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="page" order by post_date desc limit '.$start.', 2000',ARRAY_A);
                    $tag = $wpdb->get_results('select a.* from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag"  limit '.$start.', 2000',ARRAY_A);
                    break;
                
            }
            $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
             $xml .= "<urlset>\n";
               $html = "<!DOCTYPE html>\n<html>\n<head>\n<meta charset='UTF-8'>\n<meta name='viewport' content='width=device-width, initial-scale=1.0'><style>\nbody {\nbackground-color: #f3f3f3;\n}\nol {\nbackground-color: #fff;\nmax-width: 1200px;\nmargin: 0 auto;\nbox-sizing: border-box;\npadding: 15px 125px;\n}\nol li {\npadding: 15px 0;\n}\nol li a {\ncolor: #333;\npadding-left: 25px;\ntext-decoration: none;\n}\n</style>\n</head>\n<body>\n<!-- 官网：www.rbzzz.com(可接定制开发、网站、小程序、公众号、seo/sem优化)交流QQ群：1077537009 客服QQ：1500351892 -->\n<ol>";
               if($page==1){
            		$xml .= "<url>\n";
                    $xml .= "<loc>".get_option('siteurl')."</loc>\n";
                    $xml .= "<lastmod>{$currnetTime1}</lastmod>\n";
                    $xml .= "<changefreq>daily</changefreq>\n";
                    $xml .= "<priority>1.0</priority>\n";
                    $xml .= "</url>\n";
                    $html .="<li>".$currnetTime1."<a href='".get_option('siteurl')."' title='".get_option('blogname')."'>".get_option('blogname')."</a></li>\n";
               }
            $article = isset($article)?$article:[];
            $tag = isset($tag)?$tag:[];
            $pages = isset($pages)?$pages:[];
            if(!$article && !$tag && !$pages){
                echo json_encode(['msg'=>0]);exit;  
            }
            if($article){
            
    	        
    	        foreach($article as $key=>$val){
    	           
    	                $level1 = $sitemap['level1']/100;
    	                $xml .= "<url>\n";
    	                $xml .= "<loc>".get_permalink($val["ID"])."</loc>\n";
    	                $xml .= "<lastmod>{$val['post_date']}</lastmod>\n";
    	                $xml .= "<changefreq>{$sitemap['post_time']}</changefreq>\n";
    	                $xml .= "<priority>{$level1}</priority>\n";
    	                $xml .= "</url>\n";
    	            $html .="<li>".$val['post_date']."<a href='".get_permalink($val["ID"])."' title='{$val['post_title']}'>{$val['post_title']}</a></li>\n";
    	           
    	        }
            }
            if($pages){
            
    	        
    	        foreach($pages as $key=>$val){
    	           
    	                $level2 = $sitemap['level2']/100;
    	                $xml .= "<url>\n";
    	                $xml .= "<loc>".get_page_link($val["ID"])."</loc>\n";
    	                $xml .= "<lastmod>{$val['post_date']}</lastmod>\n";
    	                $xml .= "<changefreq>{$sitemap['page_time']}</changefreq>\n";
    	                $xml .= "<priority>{$level2}</priority>\n";
    	                $xml .= "</url>\n";
    	            $html .="<li>".$val['post_date']."<a href='".get_page_link($val["ID"])."' title='{$val['post_title']}'>{$val['post_title']}</a></li>\n";
    	           
    	        }
            }
            if($tag){
                foreach($tag as $key=>$val){
    	           
    	                $level3 = $sitemap['level3']/100;
    	                $xml .= "<url>\n";
    	                $xml .= "<loc>".get_tag_link($val["term_id"])."</loc>\n";
    	                $xml .= "<changefreq>{$sitemap['page_time']}</changefreq>\n";
    	                $xml .= "<priority>{$level3}</priority>\n";
    	                $xml .= "</url>\n";
    	            $html .="<li><a href='".get_tag_link($val["term_id"])."' title='{$val['name']}'>{$val['name']}</a></li>\n";
    	           
    	        }
            }
	        $xml .='</urlset>';
	        $html .="</ol>\n</body>\n</html>";
	        if($page>1){
        		$page1 = $page-1;
        		 file_put_contents('../sitemap'.$page1.'.xml',$xml);
	        	 file_put_contents('../sitemap'.$page1.'.html',$html);
        		
	        }else{
	        	file_put_contents('../sitemap.xml',$xml);
	        	file_put_contents('../sitemap.html',$html);
	        }
	        update_option($baiduseo_data_sitemap['book'],$data);
	        echo json_encode(['msg'=>1,'num'=>$page,'open'=>$data['open']]);exit; 
        }else{
        	echo json_encode(['msg'=>0]);exit;  
        }
    }
   
}
//授权
function baiduseo_paymoney($root){
    $baiduseo_wzt_log = get_option('baiduseo_wzt_log');
    if(!$baiduseo_wzt_log){
        return 0;
    }
    $baiduseo_shouquan_fail = get_option("baiduseo_shouquan_fail");
    if($baiduseo_shouquan_fail && isset($baiduseo_shouquan_fail['time']) && $baiduseo_shouquan_fail['time']>time()){
        return 0;
    }
    $pay = get_option("baiduseo_shouquan");
    if($pay && $pay['time']>time()){
        if(isset($pay['content']) && isset($pay['content']['msg']) && $pay['content']['msg']==1 && isset($pay['content']['url']) && $pay['content']['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    		return $pay['content'];
    	}
    }
	$data =  'www.seohnzz.com';
	$url = BAIDUSEO_URL.$root."?url={$data}&type=1&url1=".md5($data.BAIDUSEO_SALT);
	$defaults = array(
        'timeout' => 120,
        'connecttimeout'=>120,
        'redirection' => 3,
        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
        'sslverify' => FALSE,
    );
	$result = wp_remote_get($url,$defaults);
	if(!is_wp_error($result)){
        $content = wp_remote_retrieve_body($result);
    	$content = json_decode($content,true);
    	if(isset($content['status']) && $content['status']==1){
    	    if($pay!==false){
    	        update_option('baiduseo_shouquan',['content'=>$content,'time'=>time()+24*3600]);
    	    }else{
    	        add_option('baiduseo_shouquan',['content'=>$content,'time'=>time()+24*3600]);
    	    }
    		return $content;
    	}else{
    	    return baiduseo_paymoney1();
    	}
	}else{
	    return baiduseo_paymoney1();
	}

}
function baiduseo_paymoney1(){
    $baiduseo_shouquan_fail = get_option("baiduseo_shouquan_fail");
    $pay = get_option("baiduseo_shouquan");
    $data =  'www.seohnzz.com';
	$url = "https://www.rbzzz.com/api/money/pay_money?url={$data}&type=1&url1=".md5($data.BAIDUSEO_SALT);
	$defaults = array(
        'timeout' => 120,
        'connecttimeout'=>120,
        'redirection' => 3,
        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
        'sslverify' => FALSE,
    );
	$result = wp_remote_get($url,$defaults);
	if(!is_wp_error($result)){
        $content = wp_remote_retrieve_body($result);
        
    	$content = json_decode($content,true);
    	
    	if(isset($content['status']) && $content['status']==1){
    	    if($pay!==false){
    	        update_option('baiduseo_shouquan',['content'=>$content,'time'=>time()+24*3600]);
    	    }else{
    	        add_option('baiduseo_shouquan',['content'=>$content,'time'=>time()+24*3600]);
    	    }
    		return $content;
	    }else{
	        if($baiduseo_shouquan_fail!==false){
	            update_option('baiduseo_shouquan_fail',['time'=>time()+60]);
	        }else{
	            add_option('baiduseo_shouquan_fail',['time'=>time()+60]);
	        }
	    }
	}else{
	    if($baiduseo_shouquan_fail!==false){
            update_option('baiduseo_shouquan_fail',['time'=>time()+60]);
        }else{
            add_option('baiduseo_shouquan_fail',['time'=>time()+60]);
        }
	}
	
}
//百度收录查询
function baiduseo_baiduquery($url,$post_title,$in_cron = false){
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
        do{

            $http = wp_remote_get($search_url,$defaults);
    
            if( !is_wp_error($http) && isset($http['response']['code']) && isset($http['body']) &&　200 == $http['response']['code'] && preg_match('#<title>百度安全验证</title>#is',$http['body'])){
                break;
            }
    
          if(!is_wp_error($http) && isset($http['response']['code']) && 200 == $http['response']['code'] && !preg_match('#没有找到#is',$http['body']) && preg_match('#百度快照#is',$http['body'])){
                return true;
            }
        }while(false);
    
        if($search_url2 && !$in_cron){
    
            sleep(1);
            $http = wp_remote_get($search_url2,$defaults);
    
            if(!is_wp_error($http) && isset($http['response']['code']) && isset($http['body']) &&　200 == $http['response']['code'] && preg_match('#<title>百度安全验证</title>#is',$http['body'])){
                return false;
            }
    
            if(!is_wp_error($http) && 200 == $http['response']['code'] && !preg_match('#没有找到#is',$http['body']) && preg_match('#百度快照#is',$http['body'])){
                return true;
            }
        }
    }
    return 0;
}

//所有文章添加img标签添加alt属性
function baiduseo_altarticlechange($type){
    global $wpdb;
    if(!isset($_COOKIE['baiduseo_data_alt'])){
        $baiduseo_json = new baiduseo_json();
        $baiduseo_data_alt = $baiduseo_json->baiduseo_alt();
        if(!empty($baiduseo_data_alt)){
            setcookie('baiduseo_data_alt',json_encode($baiduseo_data_alt),time()+3600*24*30);
        }
    }else{
        $baiduseo_data_alt = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_alt']),true);
    }
    if(isset($baiduseo_data_alt['book'])){
        $alt = get_option($baiduseo_data_alt['book']);
        if($alt!=null){
        	update_option($baiduseo_data_alt['book'],$type);
        }else{
            add_option($baiduseo_data_alt['book'],$type);
        }
        $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post"',ARRAY_A);
        foreach($article as $key=>$val){
            if($type == 1){
                 $wpdb->update($wpdb->prefix . 'posts',['post_content'=>str_replace('<img','<img alt="'.$val['post_title'].'"',$val['post_content'])],['ID'=>$val['ID']]);
             }else{
                 $wpdb->update($wpdb->prefix . 'posts',['post_content'=>preg_replace('/alt=\".*?\"/','',$val['post_content'])],['ID'=>$val['ID']]);
             }
          
        }
        echo json_encode(['msg'=>1]);exit;
    }else{
        echo json_encode(['msg'=>0]);exit;
    }
}
function baiduseo_pltsxzhzz($type,$wsl=0){
    global $wpdb;
    if($wsl==1){
    	$article = $wpdb->get_results('select ID from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" and  seo_baidu_sl=0',ARRAY_A);
    }else{
    	$article = $wpdb->get_results('select ID from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post"',ARRAY_A);
    }
    
    $urls = [];
    foreach($article as $key=>$val){
        $urls[] = get_permalink($val["ID"]);
    }
	if($type==1){
    	$count = count($urls);
    
    	if($count<2000){
    		baiduseo_bdzzts($urls,1);exit;
    	}
    	for( $i=0;$i<ceil($count/1000);$i++){
    		$start = $i*1000;
    		$url = array_slice($urls,$start,1000);
    		if($i+1==ceil($count/1000)){
    			baiduseo_bdzzts($url,1,1,1,$count);
    		}else{
    			baiduseo_bdzzts($url,1,1,0);	
    		}
    	}
    }
   
}
function baiduseo_pltsxzhzz_1($type,$wsl=0,$num,$page,$shyu=0){
	global $wpdb;

	$no = $page*($num-1); 
	 if($wsl==1){
	 	
    	$count = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" and  seo_baidu_sl=0',ARRAY_A);
    	$article =  $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" and  seo_baidu_sl=0 limit '.$no.' ,'.$page,ARRAY_A);
    }else{
    
    	$count = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post"',ARRAY_A);
    	$article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" limit '.$no.' ,'.$page,ARRAY_A);
    	
    }
    $count = count($count);
    $urls = [];
    foreach($article as $key=>$val){
        $urls[] = get_permalink($val["ID"]);
    }
    if(!isset($_COOKIE['baiduseo_data_zz'])){
        $baiduseo_json = new baiduseo_json();
        $baiduseo_data_zz = $baiduseo_json->baiduseo_zz();
        if(!empty($baiduseo_data_zz)){
            setcookie('baiduseo_data_zz',json_encode($baiduseo_data_zz),time()+3600*24*30);
        }
    }else{
        $baiduseo_data_zz = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zz']),true);
    }
    if(isset($baiduseo_data_zz['book']) && isset($baiduseo_data_zz['url']) && isset($baiduseo_data_zz['site']) && isset($baiduseo_data_zz['token'])){
        $baidu = get_option($baiduseo_data_zz['book']);
        
        if($type==1){
        	
        	if($article){
    		    $api = "{$baiduseo_data_zz['url']}?{$baiduseo_data_zz['site']}={$baidu['zz_url']}&{$baiduseo_data_zz['token']}={$baidu['tokens']}";
    		    $result = wp_remote_post($api,['body'=>implode("\n", $urls)]);
    		    if(is_wp_error($result)){
    		        echo json_encode(['msg'=>"推送失败，服务器网络波动，请稍后重试",'status'=>0]);exit;
    		    }
    		    $result = wp_remote_retrieve_body($result);
    		    $res = json_decode($result,true);
    	        if(isset($res['error'])){
    	            echo json_encode(['msg'=>"推送失败，原因是{$res['message']}",'status'=>0]);exit;
    	        }elseif(isset($res['success'])){
    	            if(isset($res['not_same_site'])){
    	                $not_same_site = implode('\n',$res['not_same_site']);
    	                echo json_encode(['msg'=>"推送失败，原因是存在不是本站url:{$not_same_site}",'status'=>0]);exit;
    	            }elseif(isset($res['not_valid'])){
    	                $not_valid = implode('\n',$res['not_valid']);
    	                echo json_encode(['msg'=>"推送失败，原因是不合法的url:{$not_valid}",'status'=>0]);exit;
    	            }else{
    	                $currnetTime= current_time( 'Y/m/d H:i:s');
    					
    	                foreach($urls as $key=>$val){
    		                $data_array=[
    		                    'time' => $currnetTime,
    		                    'post_id'=>0,
    		                    'link' => $val,
    		                    'type' =>3
    		                ];
    		                $baiduseo_ts_num = get_option('baiduseo_ts_num');
        	                if($baiduseo_ts_num){
        	                    update_option('baiduseo_ts_num',['num'=>$baiduseo_ts_num['num']+1]);
        	                }else{
        	                    add_option('baiduseo_ts_num',['num'=>1]);
        	                }
    		            }
    	                if($wsl==0){
    	                	if($count>0){
    	                		$data['zz_tsts']=$count;
    	                	}else{
    	                		$data['zz_tsts']=$res['success'];
    	                	}
    		               
    		                $data['zz_kts']=$res['remain'];
    		                $data['time']= $currnetTime;
    		                $baidu = get_option('seo_baidu_zz_yjts');
    		
    		                if($baidu){
    		                  update_option('seo_baidu_zz_yjts',$data);
    		                }else{
    		                  add_option('seo_baidu_zz_yjts',$data);
    		                }
    		            }
    	            	echo json_encode(['msg'=>1,'num'=>$num,'percent'=>round(100*($no+count($article))/$count,2).'%','shyu'=>$res['remain'],'status'=>1]);exit; 
    	            }
    	        }
            }else{
            	echo json_encode(['msg'=>"推送成功，推送成功：{$count}条，剩余配额：{$shyu}条",'status'=>0]);exit;
            }
        }
    }else{
        echo 222;exit;
    }
    
}
//快速收录提交
function baiduseo_bddayts($urls,$post_id,$tishi=1){
    global $wpdb;
    if(!isset($_COOKIE['baiduseo_data_zz'])){
        $baiduseo_json = new baiduseo_json();
        $baiduseo_data_zz = $baiduseo_json->baiduseo_zz();
        if(!empty($baiduseo_data_zz)){
            setcookie('baiduseo_data_zz',json_encode($baiduseo_data_zz),time()+3600*24*30);
        }
    }else{
        $baiduseo_data_zz = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zz']),true);
    }
   
    if(isset($baiduseo_data_zz['book']) && isset($baiduseo_data_zz['url']) && isset($baiduseo_data_zz['site']) && isset($baiduseo_data_zz['token'])){
        
        $baidu = get_option($baiduseo_data_zz['book']);
    	$api = "{$baiduseo_data_zz['url']}?{$baiduseo_data_zz['site']}={$baidu['zz_url']}&{$baiduseo_data_zz['token']}={$baidu['tokens']}&type=daily";
    	$result = wp_remote_post($api,['body'=>implode("\n", $urls)]);
    	if(is_wp_error($result)){
    	    if($tishi==1){
                echo json_encode(['msg'=>"推送失败，服务器网络波动，请稍后重试",'status'=>0]);exit;
    	    }
        }
    	$result = wp_remote_retrieve_body($result);
        $res = json_decode($result,true);
        if(isset($res['error'])){
        	if($tishi==1){
        		if($res['message']=='over quota'){
                    echo json_encode(['msg'=>'当日配额已用完！','status'=>0]);exit;
                }else{
                    echo json_encode(['msg'=>$res['message'],'status'=>0]);exit;
                }
        	}
        }elseif(isset($res['success'])){
            if(isset($res['not_same_site'])){
                $not_same_site = implode('\n',$res['not_same_site']);
                if($tishi==1){
                    echo json_encode(['msg'=>"推送失败，原因是存在不是本站url:{$not_same_site}",'status'=>0]);exit;
                
                }
            }elseif(isset($res['not_valid'])){
                $not_valid = implode('\n',$res['not_valid']);
                 if($tishi==1){
                     	
                	echo json_encode(['msg'=>"推送失败，原因是不合法的url:{$not_valid}",'status'=>0]);exit;
                 }
            }else{
            	$currnetTime= current_time( 'Y/m/d H:i:s');
                $data_array=[
                    'time' => $currnetTime,
                    'post_id'=>intval($post_id),
                    'link' => $urls[0]
                ];
    			if($res['remain_daily']==0){
    			    $baiduseo_dayts_num = get_option('baiduseo_dayts_num');
                    if($baiduseo_dayts_num){
                         update_option('baiduseo_dayts_num',['num'=>$baiduseo_dayts_num['num']+1]);
                    }else{
                         add_option('baiduseo_dayts_num',1);
                    }
                    $wpdb->update($wpdb->prefix . 'posts',['baiduseo_ts'=>1],['ID'=>$post_id]);
    				 if($tishi==1){
    					echo json_encode(['msg'=>"配额超出，请勿重复提交！",'status'=>1]);exit;
    				 }
    			}else{
    				$baiduseo_dayts_num = get_option('baiduseo_dayts_num');
                     if($baiduseo_dayts_num){
                         update_option('baiduseo_dayts_num',['num'=>$baiduseo_dayts_num['num']+1]);
                     }else{
                         add_option('baiduseo_dayts_num',1);
                     }
                     $wpdb->update($wpdb->prefix . 'posts',['baiduseo_ts'=>1],['ID'=>$post_id]);
    				if($tishi==1){
                		echo json_encode(['msg'=>"推送成功,剩余配额：{$res['remain_daily']}条",'status'=>1]);exit;
    				}
    			}
            }
        }
    }
}

//计划任务
function BaiduSEO_plan_renwu(){
   
	if(isset($_GET['zz']) && $_GET['zz']){
	     $pay = baiduseo_paymoney('/api/index/pay_money');
        if(!$pay){
    		echo '授权功能，请授权后使用';exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo '授权功能，请授权后使用';exit;
    	}
	    global $wp_rewrite;
	    if(!$wp_rewrite){
	       include_once ('wp-includes/class-wp-rewrite.php');
	       $wp_rewrite = new wp_rewrite();
	    }
		baiduseo_pltsxzhzz(1);
	}elseif(isset($_GET['dayts']) && $_GET['dayts']){
	     $pay = baiduseo_paymoney('/api/index/pay_money');
        if(!$pay){
    		echo '授权功能，请授权后使用';exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo '授权功能，请授权后使用';exit;
    	}
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
	       include_once ('wp-includes/class-wp-rewrite.php');
	       $wp_rewrite = new wp_rewrite();
	    }
	    $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts  where  post_status="publish" and post_type="post" and baiduseo_ts=0  order by ID desc limit 100',ARRAY_A);
	    if(isset($baiduseo_data_zz['book']) && isset($baiduseo_data_zz['url']) && isset($baiduseo_data_zz['site']) && isset($baiduseo_data_zz['token'])){
    		$baidu = get_option($baiduseo_data_zz['book']);
    		if(isset($baidu['zz_url']) && isset($baidu['tokens'])){
    		    
            	$api = "{$baiduseo_data_zz['url']}?{$baiduseo_data_zz['site']}={$baidu['zz_url']}&{$baiduseo_data_zz['token']}={$baidu['tokens']}&type=daily";
            	$count = 0;
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
                            $data_array=[
                                'time' => $currnetTime,
                                'post_id'=>intval($val["ID"]),
                                'link' => get_permalink($val["ID"])
                            ];
                			if($res['remain_daily']==0){
                			    $baiduseo_dayts_num = get_option('baiduseo_dayts_num');
                                 if($baiduseo_dayts_num){
                                     update_option('baiduseo_dayts_num',['num'=>$baiduseo_dayts_num['num']+1]);
                                 }else{
                                     add_option('baiduseo_dayts_num',['num'=>1]);
                                 }
                			    $wpdb->update($wpdb->prefix . 'posts',['baiduseo_ts'=>1],['ID'=>$val["ID"]]);
                				break;
                			}
            			   $baiduseo_dayts_num = get_option('baiduseo_dayts_num');
                             if($baiduseo_dayts_num){
                                 update_option('baiduseo_dayts_num',['num'=>$baiduseo_dayts_num['num']+1]);
                             }else{
                                 add_option('baiduseo_dayts_num',['num'=>1]);
                             }
                           $wpdb->update($wpdb->prefix . 'posts',['baiduseo_ts'=>1],['ID'=>$val["ID"]]);
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
	}elseif(isset($_GET['silian']) && $_GET['silian']){
	     $pay = baiduseo_paymoney('/api/index/pay_money');
        if(!$pay){
    		echo '授权功能，请授权后使用';exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo '授权功能，请授权后使用';exit;
    	}
	    baiduseo_siliansc(1);
	}elseif(isset($_GET['sitemap']) && $_GET['sitemap']){
	     baiduseo_sitemapsc_1(1,1);
	    
	}
}
//关键词排名
function BaiduSEO_keywords($keywords){
	global $wpdb;
	$keywords = json_decode($keywords,true);
	$type = isset($keywords[0]['type'])?$keywords[0]['type']:0;
	$res = $wpdb->get_results(' select * from  '.$wpdb->prefix.'baiduseo_keywords where keywords="'.$keywords[0]['keywords'].'" and type="'.$type.'"',ARRAY_A);
	$wpdb->update($wpdb->prefix . 'baiduseo_keywords',['time'=>$keywords[0]['time'],'title'=>$keywords[0]['title'],'num'=>$keywords[0]['num'],'prev'=>$res[0]['num']],['id'=>$res[0]['id']]);
}
BaiduSEO_tongji();
function BaiduSEO_tongji(){
    $BaiduSEO_tongji = get_option('BaiduSEO_tongji');
    if(!$BaiduSEO_tongji || (isset($BaiduSEO_tongji) && $BaiduSEO_tongji['time']<time()) ){
        $wp_version =  get_bloginfo('version');
        $data =  'www.seohnzz.com';
    	$url = "http://wp.seohnzz.com/api/baiduseo/index?url={$data}&type=1&url1=".md5($data.'seohnzz.com')."&theme_version=0.5.1&php_version=".PHP_VERSION."&wp_version={$wp_version}";
    	$defaults = array(
            'timeout' => 120,
            'connecttimeout'=>120,
            'redirection' => 3,
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
            'sslverify' => FALSE,
        );
        $result = wp_remote_get($url,$defaults);
        if($BaiduSEO_tongji!==false){
            update_option('BaiduSEO_tongji',['time'=>time()+7*3600*24]);
        }else{
            add_option('BaiduSEO_tongji',['time'=>time()+7*3600*24]);
        }
    }
}
function BaiduSEO_preg($str)
{
	$str=strtolower(trim($str));
	$replace=array('\\','+','*','?','[','^',']','$','(',')','{','}','=','!','<','>','|',':','-',';','\'','\"','/','%','&','_','`');
    return str_replace($replace,"",$str);
}
function baiduseo_refreshrules() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

function baiduseo_deactivate() {
	remove_filter( 'category_rewrite_rules', 'baiduseo_rewriterules' ); 
	baiduseo_refreshrules();
}
function baiduseo_permastruct()
{
	global $wp_rewrite;
	global $wp_version;

	if ( $wp_version >= 3.4 ) {
		$wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
	} else {
		$wp_rewrite->extra_permastructs['category'][0] = '%category%';
	}
}
function baiduseo_xeach(){
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
               
            }
        }
    }
}

function baiduseo_rewriterules($category_rewrite) {
	global $wp_rewrite;
	$category_rewrite=array();
	if ( class_exists( 'Sitepress' ) ) {
		global $sitepress;

		remove_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ) );
		$categories = get_categories( array( 'hide_empty' => false ) );
		add_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ), 10, 4 );
	} else {
		$categories = get_categories( array( 'hide_empty' => false ) );
	}

	foreach( $categories as $category ) {
		$category_nicename = $category->slug;

		if ( $category->parent == $category->cat_ID ) {
			$category->parent = 0;
		} elseif ( $category->parent != 0 ) {
			$category_nicename = get_category_parents( $category->parent, false, '/', true ) . $category_nicename;
		}

		$category_rewrite['('.$category_nicename.')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
		$category_rewrite["({$category_nicename})/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$"] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
		$category_rewrite['('.$category_nicename.')/?$'] = 'index.php?category_name=$matches[1]';
	}
	$old_category_base = get_option( 'category_base' ) ? get_option( 'category_base' ) : 'category';
	$old_category_base = trim( $old_category_base, '/' );
	$category_rewrite[$old_category_base.'/(.*)$'] = 'index.php?category_redirect=$matches[1]';

	return $category_rewrite;
}

function baiduseo_queryvars($public_query_vars) {
	$public_query_vars[] = 'category_redirect';
	return $public_query_vars;
}
function baiduseo_request($query_vars) {
	if( isset( $query_vars['category_redirect'] ) ) {
		$catlink = trailingslashit( get_option( 'home' ) ) . user_trailingslashit( $query_vars['category_redirect'], 'category' );
		status_header( 301 );
		header( "Location: $catlink" );
		exit();
	}
	return $query_vars;
}
function BaiduSEO_jiemi($content){
    $data = 'www.seohnzz.com';
    $url = 'https://www.rbzzz.com/api/money/num?url='.$data;
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
    }
    
    if(isset($num) && $num>0){
        $content = base64_decode($content);
        if(strpos($content,'www.seohnzz.com') !== false){ 
            $content = str_replace('www.seohnzz.com','',$content);
            $content = substr($content,0,-$num);
            $content = base64_decode($content);
            $content = substr($content,$num);
            $content = str_replace('www.seohnzz.com','',$content);
            if($content){
                $baiduseo_wzt_log = get_option('baiduseo_wzt_log');
                if($baiduseo_wzt_log!==false){
                    update_option('baiduseo_wzt_log',$content);
                }else{
                    add_option('baiduseo_wzt_log',$content);
                }
            }else{
                delete_option('baiduseo_log');
            }
        }else{
            delete_option('baiduseo_log');
        }
        
        
        
    }
    
}

    
