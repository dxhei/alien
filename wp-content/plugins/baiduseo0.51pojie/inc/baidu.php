<?php
class baiduseo_baidu{
    public $str;
    function __construct() {
        //更新计划任务
        $cron = new cron();
        $cron->init();
        $baiduseo_wzt_log = get_option('baiduseo_wzt_log');
        $this->log = $baiduseo_wzt_log;
        
        if($_POST){
            if(isset($_POST['data']) && is_string($_POST['data'])){
                $BaiduSEO = json_decode($_POST['data'],true);
                if(isset($BaiduSEO['BaiduSEO'])){
                    $baiduseo_post = new baiduseo_post($BaiduSEO);
                    add_action('init',[$baiduseo_post,'BaiduSEO_post']);
                }
            }
        }
        if(is_admin()){
            if(isset($_GET['page']) && $_GET['page']=='baiduseo'){
                add_action( 'admin_enqueue_scripts', [$this,'baiduseo_enqueue'] );
                if(isset($_GET['table']) && isset($_GET['pages']) && isset($_GET['baiduseo'])){
                    $BaiduSEOget = $_GET; 
                    $baiduseo_get = new baiduseo_get($BaiduSEOget);
                    if(isset($_GET['sl']) || isset($_GET['ts'])){
                        if($_GET['sl']==1){
                            $baiduseo_get->baiduseo_day_sl();
                        }elseif($_GET['sl']==2){
                            $baiduseo_get->baiduseo_day_wsl();
                        }elseif($_GET['ts']==1){ 
                            $baiduseo_get->baiduseo_day_ts();
                        }elseif($_GET['ts']==0){
                            $baiduseo_get->baiduseo_day_wts();
                        }
                    }elseif(isset($_GET['zhizhu'])){
                        $baiduseo_get->baiduseo_zhizhu();
                    }elseif($_GET['keywords']){
                        $baiduseo_get->baiduseo_keywords();
                    }elseif(isset($_GET['neilian'])){
                        $baiduseo_get->baiduseo_neilian();
                    }
                }elseif(isset($_GET['plan'])){
                    $this->BaiduSEO_plan_geturl();
                }
            }
            add_filter('plugin_action_links_'.BAIDUSEO_NAME, [$this,'baiduseo_plugin_action_links']);
            add_action('admin_menu', [$this,'baiduseo_addpages']);
           //文章发布时调用
            add_action('publish_post',[$this,'baiduseo_articlepublish']);
        }else{
            //seo首页
            add_action( 'wp_head', [$this,'baiduseo_mainpage'],1 );
            add_action( 'wp', [$this,'baiduseo_zhizhu'] );
        }
        if(!isset($_COOKIE['baiduseo_data_category']) || !$_COOKIE['baiduseo_data_category']){
            $baiduseo_json = new baiduseo_json();
            $baiduseo_data_category = $baiduseo_json->baiduseo_category();
            if(!empty($baiduseo_data_category)){
                setcookie('baiduseo_data_category',json_encode($baiduseo_data_category),time()+3600*24*30);
            }
        }else{
            $baiduseo_data_category = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_category']),true);
        }
        if(isset($baiduseo_data_category['book'])){
            $category = get_option($baiduseo_data_category['book']);
        }
        if(isset($category['istrue']) && ($category['istrue']==1)){
            $pay = baiduseo_paymoney('/api/index/pay_money');
            if(!empty($pay)){
                if($this->log){
                    if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
                        add_action('init',              [$this,'baiduseo_refreshrules']);
                        add_action('created_category',  [$this,'baiduseo_refreshrules']);
                        add_action('delete_category',   [$this,'baiduseo_refreshrules']);
                        add_action('edited_category',   [$this,'baiduseo_refreshrules']);
                        add_action('init',              [$this,'baiduseo_permastruct']);
                        add_filter('category_rewrite_rules', [$this,'baiduseo_rewriterules']);
                        add_filter('query_vars',             [$this,'baiduseo_queryvars']);   
                        add_filter('request',                [$this,'baiduseo_request']);
                	}
                }
            }
        }else{
            remove_filter( 'category_rewrite_rules', [$this,'baiduseo_rewriterules'] );
            add_action('init',              [$this,'baiduseo_refreshrules']);
        }
        //插件激活时调用
        register_activation_hook(BAIDUSEO_FILE, [$this,'baiduseo_pluginaction']);
        register_deactivation_hook(BAIDUSEO_FILE,  [$this,'baiduseo_deactivate']);
        
	}
	public function BaiduSEO_preg(){
	    $str = $this->str;
	    $str=strtolower(trim($str));
    	$replace=array('\\','+','*','?','[','^',']','$','(',')','{','}','=','!','<','>','|',':','-',';','\'','\"','/','%','&','_','`');
        return str_replace($replace,"",$str);
	}
	//tag标签
    public  function BaiduSEO_addlink($content){
    	global $wpdb;
    	
    	$post_title = get_the_title();
        if(!isset($_COOKIE['baiduseo_data_alt'])){
            $baiduseo_json = new baiduseo_json();
            $baiduseo_data_alt = $baiduseo_json->baiduseo_alt();
	        if(!empty($baiduseo_data_alt)){
                setcookie('baiduseo_data_alt',json_encode($baiduseo_data_alt),time()+3600*24*30);
	        }
        }else{
            $baiduseo_data_alt = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_alt']),true);
        }
        if(!isset($_COOKIE['baiduseo_data_tag'])){
            $baiduseo_json = new baiduseo_json();
            
	        $baiduseo_data_tag = $baiduseo_json->baidutag();
	        if(!empty($baiduseo_data_tag)){
                setcookie('baiduseo_data_tag',json_encode($baiduseo_data_tag),time()+3600*24*30);
	        }
        }else{
            $baiduseo_data_tag = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_tag']),true);
        }
        if(isset($baiduseo_data_alt['book'])){
        	$alt = get_option($baiduseo_data_alt['book']);
        }
        
    	if((isset($alt['alt']) && $alt['alt']) || (isset($alt['title']) && $alt['title'])){
        	if(preg_match_all('#<img([^>]+)>#is',$content,$match)){
        
                $img_seo = array();
                $img_idx = -1;
                foreach($match[0] as $k=>$img){
                    if(!preg_match('#src=.+#',$img)){
                        continue;
                    }
        
                    $src_img = $img;
                    $img = str_replace(array('alt=""',"alt=''",'title=""',"title=''"),'',$img);
        
                    $img_key = md5($img);
        
                    if(!isset($img_seo[$img_key])){
                        $img_idx ++;
                        $img_seo[$img_key] = $img_idx;
                    }
                    $img_k = $img_seo[$img_key];
        
                    $add_html = '';
        
                    if(isset($alt['title']) && $alt['title'] && !preg_match('#\s+title=.+?#is',$img)){
        
                        if($alt['title']=='2'){
                            if(preg_match('#/.+?\.(jpg|jpeg|gif|webp|png|bmp)#is',$img,$name_match)){
                                $add_html .= ' title="'.esc_attr(basename($name_match[0])).'插图'.($img_k?'('.$img_k.')':'').'"';
                            }
                        }else if($post_title){
        
                            $add_html .= ' title="'.esc_attr($post_title).'插图'.($img_k?'('.$img_k.')':'').'"';
                        }
                    }
                    if(isset($alt['alt']) && $alt['alt'] && !preg_match('#\s+alt=.+?#is',$img)){
        
                        if($alt['alt'] == '2'){
                            if(preg_match('#/.+?\.(jpg|jpeg|gif|webp|png|bmp)#is',$img,$name_match)){
                                $add_html .= ' alt="'.esc_attr(basename($name_match[0])).'插图'.($img_k?'('.$img_k.')':'').'"';
                            }
                        }else if($post_title){
                            $add_html .= ' alt="'.esc_attr($post_title).'插图'.($img_k?'('.$img_k.')':'').'"';
                        }
                    }
                    if(!$add_html){
                        continue;
                    }
        
                    $original = str_replace(array('alt=""',"alt=''",'title=""',"title=''"),'',$match[1][$k]);
        
                    $new_img = '<img'.$add_html.' '.$original.'>';
                    $content = str_replace($src_img,$new_img,$content);
                }//end foreach match
        
            }
    	}
    	$id = get_the_ID();
    	if(isset($baiduseo_data_tag['book'])){
    	    $Tag_manage = get_option($baiduseo_data_tag['book']);
    	}
    
        if($Tag_manage){
            
        	if(isset($Tag_manage['open']) && ($Tag_manage['open']==1)){
    			$tags=$wpdb->get_results('select a.* from ('.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id) left join '.$wpdb->prefix . 'term_relationships as c on b.term_taxonomy_id =c.term_taxonomy_id  where b.taxonomy="post_tag" and c.object_id='.$id,ARRAY_A);
    			$tags1 = $wpdb->get_results('select * from '.$wpdb->prefix.'terms where slug= "" and tag_link!=""',ARRAY_A);
    			$tags = array_merge($tags,$tags1);
    	        if(is_array($tags)||is_object($tags))
    	        {
    	            foreach ($tags as $val)
    	            {
    					
    					$val['url'] =get_tag_link($val['term_id']);
    					$this->str = $val['name'];
    	                if(preg_match('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i',$content,$matches))
    	                {
    	                    
    	                	if($val['tag_link']){
    	                	    
    		                	if(isset($Tag_manage['bold']) && isset($Tag_manage['color']) && $Tag_manage['color']){
    		                		if($Tag_manage['bold']==1){
    		                		    
        		                		if($val['tag_target'] && $val['tag_nofollow']){
    		                		        
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" target="_blank" rel="nofollow"><b style="color:'.$Tag_manage['color'].'">'.$val['name'].'</b></a>',$content,1);
    		                		    }elseif($val['tag_target'] && !$val['tag_nofollow']){
    		                		       
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" target="_blank"><b style="color:'.$Tag_manage['color'].'">'.$val['name'].'</b></a>',$content,1);
    		                		    }elseif(!$val['tag_target'] && $val['tag_nofollow']){
    		                		        
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" rel="nofollow"><b style="color:'.$Tag_manage['color'].'">'.$val['name'].'</b></a>',$content,1);
    		                		    }else{
    		                		        
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'"><b style="color:'.$Tag_manage['color'].'">'.$val['name'].'</b></a>',$content,1);
    		                		    }
    		                		}else{
    		                			if($val['tag_target'] && $val['tag_nofollow']){
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" style="color:'.$Tag_manage['color'].'" target="_blank" rel="nofollow">'.$val['name'].'</a>',$content,1);
    		                		    }elseif($val['tag_target'] && !$val['tag_nofollow']){
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" style="color:'.$Tag_manage['color'].'" target="_blank">'.$val['name'].'</a>',$content,1);
    		                		    }elseif(!$val['tag_target'] && $val['tag_nofollow']){
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" style="color:'.$Tag_manage['color'].'" rel="nofollow">'.$val['name'].'</a>',$content,1);
    		                		    }else{
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" style="color:'.$Tag_manage['color'].'">'.$val['name'].'</a>',$content,1);
    		                		    }
    		                		}
    		                		
    		                	}elseif(isset($Tag_manage['bold']) && (!isset($Tag_manage['color'])||(!$Tag_manage['color']))){
    		                		if($Tag_manage['bold']==1){
    		                			if($val['tag_target'] && $val['tag_nofollow']){
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" target="_blank" rel="nofollow"><b>'.$val['name'].'</b></a>',$content,1);
    		                		    }elseif($val['tag_target'] && !$val['tag_nofollow']){
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" target="_blank"><b>'.$val['name'].'</b></a>',$content,1);
    		                		    }elseif(!$val['tag_target'] && $val['tag_nofollow']){
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" rel="nofollow"><b>'.$val['name'].'</b></a>',$content,1);
    		                		    }else{
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'"><b>'.$val['name'].'</b></a>',$content,1);
    		                		    }
    		                		}else{
    		                			if($val['tag_target'] && $val['tag_nofollow']){
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" target="_blank" rel="nofollow">'.$val['name'].'</a>',$content,1);
    		                		    }elseif($val['tag_target'] && !$val['tag_nofollow']){
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" target="_blank">'.$val['name'].'</a>',$content,1);
    		                		    }elseif(!$val['tag_target'] && $val['tag_nofollow']){
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" rel="nofollow">'.$val['name'].'</a>',$content,1);
    		                		    }else{
    		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" >'.$val['name'].'</a>',$content,1);
    		                		    }
    		                		}
    		                	}elseif(!isset($Tag_manage['bold']) && isset($Tag_manage['color']) && $Tag_manage['color']){
    		                		if($val['tag_target'] && $val['tag_nofollow']){
		                	            $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" style="color:'.$Tag_manage['color'].'" target="_blank" rel="nofollow">'.$val['name'].'</a>',$content,1);
		                		    }elseif($val['tag_target'] && !$val['tag_nofollow']){
		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" style="color:'.$Tag_manage['color'].'" target="_blank" >'.$val['name'].'</a>',$content,1);
		                		    }elseif(!$val['tag_target'] && $val['tag_nofollow']){
		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" style="color:'.$Tag_manage['color'].'" rel="nofollow">'.$val['name'].'</a>',$content,1);
		                		    }else{
		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" style="color:'.$Tag_manage['color'].'">'.$val['name'].'</a>',$content,1);
		                		    }
    		                	}else{
    		                		if($val['tag_target'] && $val['tag_nofollow']){
		                	            $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" target="_blank" rel="nofollow">'.$val['name'].'</a>',$content,1);
		                		    }elseif($val['tag_target'] && !$val['tag_nofollow']){
		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" target="_blank" >'.$val['name'].'</a>',$content,1);
		                		    }elseif(!$val['tag_target'] && $val['tag_nofollow']){
		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'" rel="nofollow">'.$val['name'].'</a>',$content,1);
		                		    }else{
		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['tag_link'].'">'.$val['name'].'</a>',$content,1);
		                		    }
    		                	}
    	                	}else{
    	                		if(isset($Tag_manage['bold']) &&isset($Tag_manage['color']) && $Tag_manage['color']){
    		                		if($Tag_manage['bold']==1){
    		                			
    		                		    $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['url'].'"><b style="color:'.$Tag_manage['color'].'">'.$val['name'].'</b></a>',$content,1);
    		                		    
    		                		}else{
    		                			
    		                		    $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['url'].'" style="color:'.$Tag_manage['color'].'">'.$val['name'].'</a>',$content,1);
    		                		    
    		                		}
    		                		
    		                	}elseif(isset($Tag_manage['bold']) && (!isset($Tag_manage['color'])||(!$Tag_manage['color']))){
        		                	if($Tag_manage['bold']==1){
        		                			
    		                		    $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['url'].'"><b>'.$val['name'].'</b></a>',$content,1);
    		                		    
    		                		}else{
    		                			
    		                		    $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['url'].'">'.$val['name'].'</a>',$content,1);
    		                		    
    		                		}
    		                	}elseif(!isset($Tag_manage['bold']) && isset($Tag_manage['color']) && $Tag_manage['color']){
    		                		
		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['url'].'" style="color:'.$Tag_manage['color'].'">'.$val['name'].'</a>',$content,1);
		                		    
    		                	}else{
    		                		
		                		    $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.BaiduSEO_preg($val['name']).')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a href="'.$val['url'].'">'.$val['name'].'</a>',$content,1);
		                		    
    		                	}
    	                	}
    	                }
    	               
    	            }
    	            
    	        }
    	    	return $content;
        	}else{
    	    	$tags=$wpdb->get_results('select a.* from ('.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id) left join '.$wpdb->prefix . 'term_relationships as c on b.term_taxonomy_id =c.term_taxonomy_id  where b.taxonomy="post_tag" and c.object_id='.$id,ARRAY_A);
    			$tags1 = $wpdb->get_results('select * from '.$wpdb->prefix.'terms where slug= "" and tag_link!=""',ARRAY_A);
    			$tags = array_merge($tags,$tags1);
    	        if(is_array($tags)||is_object($tags))
    	        {
    	            foreach ($tags as $val)
    	            {
    			        $this->str = $val['name'];
    	                if(preg_match('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i',$content,$matches))
    	                { 
    	                	if(isset($Tag_manage['bold']) && isset($Tag_manage['color']) &&$Tag_manage['color']){
    	                		if($Tag_manage['bold']==1){
    	                			$content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<b style="color:'.$Tag_manage['color'].'">'.$val['name'].'</b>',$content,1);
    	                		}else{
    	                			if($val['tag_target'] && $val['tag_nofollow']){
	                		        	$content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a  style="color:'.$Tag_manage['color'].'" target="_blank" rel="nofollow">'.$val['name'].'</a>',$content,1);
		                		    }elseif($val['tag_target'] && !$val['tag_nofollow']){
		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a  style="color:'.$Tag_manage['color'].'" target="_blank">'.$val['name'].'</a>',$content,1);
		                		    }elseif(!$val['tag_target'] && $val['tag_nofollow']){
		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a  style="color:'.$Tag_manage['color'].'"  rel="nofollow">'.$val['name'].'</a>',$content,1);
		                		    }else{
		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a  style="color:'.$Tag_manage['color'].'">'.$val['name'].'</a>',$content,1);
		                		    }
    	                		}
    	                		
    	                	}elseif(isset($Tag_manage['bold']) && (!isset($Tag_manage['color']) || !$Tag_manage['color'])){
    	                		if($Tag_manage['bold']==1){
    	                			$content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<b>'.$val['name'].'</b>',$content,1);
    	                		}
    	                	}elseif(!isset($Tag_manage['bold']) && isset($Tag_manage['color']) && $Tag_manage['color']){
    	                		    if($val['tag_target'] && $val['tag_nofollow']){
	                	                $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a  style="color:'.$Tag_manage['color'].'" target="_blank" rel="nofollow">'.$val['name'].'</a>',$content,1);
		                		    }elseif($val['tag_target'] && !$val['tag_nofollow']){
		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a  style="color:'.$Tag_manage['color'].'" target="_blank" >'.$val['name'].'</a>',$content,1);
		                		    }elseif(!$val['tag_target'] && $val['tag_nofollow']){
		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a  style="color:'.$Tag_manage['color'].'" rel="nofollow">'.$val['name'].'</a>',$content,1);
		                		    }else{
		                		        $content=preg_replace('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i','<a  style="color:'.$Tag_manage['color'].'">'.$val['name'].'</a>',$content,1);
		                		    }
    	                	}
    	                    
    	                }
    	            }
    	
    	        }
    	        return $content;	
        	}
        }
        return $content;
    }
	public  function baiduseo_zhizhu(){
	    global $wpdb;
	    $pay = baiduseo_paymoney('/api/index/pay_money');

        if(isset($pay['msg']) && $pay['msg']==1 && isset($pay['url']) && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){

		$timezone_offet = get_option( 'gmt_offset');
		$sta =strtotime(date('Y-m-d 00:00:00'))-$timezone_offet*3600;
    	$end = strtotime(date('Y-m-d 00:00:00'))+24*3600-$timezone_offet*3600;
    	$where = "unix_timestamp(time) >$sta and unix_timestamp(time)<$end";
        $currnetTime= current_time( 'Y/m/d H:i:s');
	    if(isset($_SERVER['HTTP_USER_AGENT'])){
	        $type = strtolower($_SERVER['HTTP_USER_AGENT']);
	        $zhizhu = get_option('baiduseo_zhizhu');
	        if(isset($zhizhu['auto']) && ($zhizhu['auto']==1)){
	           
	            if (strpos($type, 'googlebot') !== false){
            		$data_array['name'] ='谷歌';
            	}
            	if (strpos($type, 'baiduspider') !== false){
            // 	if(1){
            		$data_array['name'] ='百度';
            		$suoyin = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin where '.$where.' and name="百度"  ',ARRAY_A);
            	
            		if(empty($suoyin)){
            		    $num = $this->baiduseo_sitebaidu();
            		   
            		    if($num){
            		        $wpdb->insert($wpdb->prefix."baiduseo_zhizhu_suoyin",['name'=>'百度','num'=>$num,'time'=>$currnetTime]);
            		    }
            		}
            	}
            	if (strpos($type, '360spider') !== false){
            		$data_array['name'] ='360';
            		$suoyin = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin where '.$where.' and name="360"  ',ARRAY_A);
            		if(empty($suoyin)){
            		    $num = $this->baiduseo_site360();
            		    if($num){
            		        $wpdb->insert($wpdb->prefix."baiduseo_zhizhu_suoyin",['name'=>'360','num'=>$num,'time'=>$currnetTime]);
            		    }
            		}
            	}
            	if (strpos($type, 'sogou') !== false){
            		$data_array['name'] ='搜狗';
            		$suoyin = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin where '.$where.' and name="搜狗"  ',ARRAY_A);
            		if(empty($suoyin)){
            		    $num = $this->baiduseo_sitesougou();
            		    if($num){
            		        $wpdb->insert($wpdb->prefix."baiduseo_zhizhu_suoyin",['name'=>'搜狗','num'=>$num,'time'=>$currnetTime]);
            		    }
            		}
            	}
            	if (strpos($type, 'yisouspider') !== false){
            		$data_array['name'] ='神马';
            	}
            // 	if(1){
            	if (strpos($type, 'bingbot') !== false){
            		$data_array['name'] ='必应';
            		$suoyin = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin where '.$where.' and name="必应"  ',ARRAY_A);
            		if(empty($suoyin)){
            		    $num = $this->baiduseo_sitebing();
            		    if($num){
            		        $wpdb->insert($wpdb->prefix."baiduseo_zhizhu_suoyin",['name'=>'必应','num'=>$num,'time'=>$currnetTime]);
            		    }
            		}
            	}
            	if (strpos($type, 'bytespider') !== false){
            		$data_array['name'] ='头条';
            	}
            	if(isset($data_array['name'])){
		            
            	 
            		//获取当前时间
            		
            		$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
            	
                		$data_array['address'] = $http_type.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                		if(strlen($data_array['address'])<1024){
                    		$defaults = array(
                    	        'timeout' => 300,
                    	        'redirection' => 3,
                    	        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                    	        'sslverify' => FALSE,
                    	    );
                    		$result = wp_remote_get($data_array['address'],$defaults);
                    		if(!is_wp_error($result)){
                    		    if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
                    		        $data_array['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    		    }elseif(isset($_SERVER['REMOTE_ADDR'])){
                    		        $data_array['ip'] = $_SERVER['REMOTE_ADDR'];
                    		    }else{
                    		        $data_array['ip'] = '';
                    		    }
                        		$data_array['time'] = $currnetTime;
                        		$data_array['type'] = $result['response']['code'];
                        		if($data_array['type'] =='404'){
                            		if($_SERVER['REQUEST_URI']!='/' && $_SERVER['REQUEST_URI']!='' ){
                            		    $res = $wpdb->insert($wpdb->prefix."baiduseo_zhizhu",$data_array);
                            		}
                        		}else{
                        		    $res = $wpdb->insert($wpdb->prefix."baiduseo_zhizhu",$data_array);
                        		}
                    		}
                		
            		}
            	}
	        }
	        
	        }
        }
	}
	public  function baiduseo_plugin_action_links ( $links) {
        $links[] = '<a href="' . admin_url( 'admin.php?page=baiduseo' ) . '">设置</a>';
        return $links;
    }
    public  function baiduseo_enqueue($hook){
        if( 'toplevel_page_baiduseo' != $hook ) return;
        wp_enqueue_style( 'layui',  plugin_dir_url( BAIDUSEO_FILE ). 'layui/css/layui.css',false,'','all');
        wp_enqueue_style( 'new',  plugin_dir_url( BAIDUSEO_FILE ). 'css/new.css',false,'','all');
        wp_enqueue_style( 'animate',  plugin_dir_url( BAIDUSEO_FILE ). 'css/animate.css',false,'','all');
        wp_enqueue_style( 'style',  plugin_dir_url( BAIDUSEO_FILE ). 'css/demo-style.css',false,'','all');
        wp_enqueue_style( 'joyrides',  plugin_dir_url( BAIDUSEO_FILE ). 'css/joyride-1.0.2.css',false,'','all');
        wp_enqueue_script( 'script', plugin_dir_url( BAIDUSEO_FILE ).'layui/layui.js', '', '', false);
        wp_enqueue_script( 'echarts', plugin_dir_url( BAIDUSEO_FILE ).'layui/echarts.min.js', '', '', false);
        wp_enqueue_script( 'joyride', plugin_dir_url( BAIDUSEO_FILE ).'layui/jquery.joyride-1.0.2.js', '', '', false);
        $baiduseo_wzt_log = get_option('baiduseo_wzt_log');
        if(!$baiduseo_wzt_log){
            $this->BaiduSEO_getlog();
        }

    }
    public function BaiduSEO_plan_renwu(){
        
        if(isset($_GET['zz']) && $_GET['zz']){
            $data = [];
    	    $baiduseo_post = new baiduseo_post($data);
    	    $baiduseo_post->baiduseo_plan_zz($_GET['str']);
    	    exit;
    	}elseif(isset($_GET['dayts']) && $_GET['dayts']){
    	    global $wpdb;
    	    $pay = baiduseo_paymoney('/api/index/pay_money');
            if(!$pay){
        		echo '授权功能，请授权后使用';exit;
        	}
            if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
        	}else{
        		echo '授权功能，请授权后使用';exit;
        	}
        	if(!$this->log){
        	    echo '授权功能，请授权后使用';exit;
        	}
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
        	if(!$this->log){
        	    echo '授权功能，请授权后使用';exit;
        	}
    	    $data = [];
    	    $baiduseo_post = new baiduseo_post($data);
    	    $baiduseo_post->baiduseo_siliansc(1);
    	    exit;
    	}elseif(isset($_GET['map']) && $_GET['map']){
            $data = [];
            $baiduseo_post = new baiduseo_post($data);
            $baiduseo_post->baiduseo_plan_sitemap(1);exit;
    	}
    }
    public function baiduseo_mainpage(){
            if(!isset($_COOKIE['baiduseo_data_zz'])){
                $baiduseo_json = new baiduseo_json();
		        $baiduseo_data_zz = $baiduseo_json->baiduseo_zz();
		        if(!empty($baiduseo_data_zz)){
                    setcookie('baiduseo_data_zz',json_encode($baiduseo_data_zz),time()+3600*24*30);
		        }
            }else{
                $baiduseo_data_zz = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zz']),true);
            }
    	    if(isset($baiduseo_data_zz['book'])){
    	        $baidu = get_option($baiduseo_data_zz['book']);
    	        if(isset($baidu['js_auto']) && $baidu['js_auto']){
    	            
    	            echo file_get_contents(plugin_dir_path( BAIDUSEO_FILE ) . 'inc/push.php');
    	        }
    	        if(isset($baidu['360_auto']) && $baidu['360_auto']){
    	            
    	            echo file_get_contents(plugin_dir_path( BAIDUSEO_FILE ) . 'inc/soso.php');
    	        }
    	        if(isset($baidu['toutiao_auto']) && $baidu['toutiao_auto'] && $baidu['toutiao_key'] ){
    	            echo '<script>
    	          /*seo合集头条推送*/ 
(function(){
var el = document.createElement("script");
el.src = "https://sf1-scmcdn-tos.pstatp.com/goofy/ttzz/push.js?'.$baidu['toutiao_key'].'";
el.id = "ttzz";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(el, s);
})(window)
</script>';
    	        }
    	    }
        if(is_home() || is_front_page()){
            
            //计划任务
            if(isset($_GET['zhou']) && isset($_GET['BaiduSEO'])){
                
               $this->BaiduSEO_plan_renwu();
            }
            if(!isset($_COOKIE['baiduseo_data_seo'])){
                $baiduseo_json = new baiduseo_json();
		        $baiduseo_data_seo = $baiduseo_json->baiduseo_seo();
		        if(!empty($baiduseo_data_seo)){
                    setcookie('baiduseo_data_seo',json_encode($baiduseo_data_seo),time()+3600*24*30);
		        }
            }else{
                $baiduseo_data_seo = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_seo']),true);
                
            }
		    if(isset($baiduseo_data_seo['book'])){
		        $seo = get_option($baiduseo_data_seo['book']);
                if($seo){
                    
                    if(isset($seo['keywords']) && $seo['keywords']){
                        echo '<meta name="keywords" content="'.$seo['keywords'].'">'."\n\r";
                    }
                    if(isset($seo['description']) && $seo['description']){
                        echo '<meta name="description" content="'.$seo['description'].'">'."\n\r";
                    }
                    
                    
                }
		    }
		   
            
        }elseif(is_category()){
            $cate = get_the_category();
            if(isset($cate[0]->cat_ID)){
                $seo = get_option('baiduseo_cate_'.$cate[0]->cat_ID);
                if($seo){
                    if(isset($seo['title']) && $seo['title']){
                        echo '<title>'.$seo['title'].'</title>'."\n";
                    }
                    if(isset($seo['keywords']) && $seo['keywords']){
                        echo sprintf('<meta name="keywords" content="%s" />'."\n",$seo['keywords']);
                    }
                    if(isset($seo['description']) && $seo['description']){
                        echo sprintf('<meta name="description" content="%s" />'."\n",$seo['description']);
                    }
                }
            }
        }elseif(is_single()){
            add_action( 'the_content', [$this,'BaiduSEO_addlink']);
        }
        
    }
    public function BaiduSEO_getlog(){
        $log = get_option('baiduseo_log');
        if($log){
            BaiduSEO_jiemi($log);
        }else{
            $data = 'www.seohnzz.com';
            $url = 'https://www.rbzzz.com/api/money/log?url='.$data;
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
                if($content){
                    if($log!==false){
            	        update_option('baiduseo_log',$content);
            	    }else{
            	        add_option('baiduseo_log',$content);
            	    }
            	    BaiduSEO_jiemi($content);
                }
        	}
        }
    }
    //文章发布时的调用
    public function  baiduseo_articlepublish($post_ID){
        global $wpdb;
        //ini_set('memory_limit','512M');
        //周级推送
        $url = get_permalink($post_ID);
        $urls =explode(',',$url);
       if(!isset($_COOKIE['baiduseo_data_zz'])){
            $baiduseo_json = new baiduseo_json();
    	    $baiduseo_data_zz = $baiduseo_json->baiduseo_zz();
	        if(!empty($baiduseo_data_zz)){
                setcookie('baiduseo_data_zz',json_encode($baiduseo_data_zz),time()+3600*24*30);
	        }
        }else{
            $baiduseo_data_zz = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zz']),true);
        }
	    if(isset($baiduseo_data_zz['day'])){
            $day = get_option($baiduseo_data_zz['day']);
        	if($day){
        		if(isset($day['auto']) && ($day['auto']==1)){
        			 baiduseo_bddayts($urls,$post_ID,0);	
        		}	
        	}
        	if(isset($baiduseo_data_zz['book'])){
            	$baidu = get_option($baiduseo_data_zz['book']);
                if(isset($baidu['auto']) && ($baidu['auto']==1)){
            	    // //站长自动推送
            	    baiduseo_bdzzts($urls,0,1,0);
                }
        	}
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
                if(isset($sitemap['open']) && ($sitemap['open']==1)){
                	baiduseo_sitemapsc_1(1);
                }
		    }
            //自动关联标签
            if(!isset($_COOKIE['baiduseo_data_tag'])){
                $baiduseo_json = new baiduseo_json();
		        $baiduseo_data_tag = $baiduseo_json->baidutag();
		        if(!empty($baiduseo_data_tag)){
                    setcookie('baiduseo_data_tag',json_encode($baiduseo_data_tag),time()+3600*24*30);
		        }
            }else{
                $baiduseo_data_tag = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_tag']),true);
            }
		    if(isset($baiduseo_data_tag['book'])){
                $baiduseo_tag_manage = get_option($baiduseo_data_tag['book']);
		    }
            if($baiduseo_tag_manage){
                if(isset($baiduseo_tag_manage['auto']) && $baiduseo_tag_manage['auto']){
                    if(!isset($baiduseo_tag_manage['num']) || !$baiduseo_tag_manage['num']){
                        $tags=$wpdb->get_results('select * from '.$wpdb->prefix . 'terms',ARRAY_A);
                        foreach($tags as $k=>$v){
                    	    if(preg_match('{(?!((<.*?)|(<a.*?)))('.BaiduSEO_preg($v['name']).')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i',get_post($post_ID)->post_content,$matches))
                    		{
                    			$res = $wpdb->get_results('select * from '.$wpdb->prefix . 'term_taxonomy where taxonomy="post_tag" and term_id='.$v['term_id'],ARRAY_A);
                    			if($res){
                    				$re = $wpdb->get_results('select * from '.$wpdb->prefix . 'term_relationships where object_id='.$post_ID.' and term_taxonomy_id='.$res[0]['term_taxonomy_id'],ARRAY_A);
                    				
                    				if(!$re){
                    				    
                    					$wpdb->insert($wpdb->prefix."term_relationships",['object_id'=>$post_ID,'term_taxonomy_id'=>$res[0]['term_taxonomy_id']]);
                    					$term_taxonomy = $wpdb->get_results('select * from '.$wpdb->prefix . 'term_taxonomy where  term_taxonomy_id='.$res[0]['term_taxonomy_id'] ,ARRAY_A);
                    				
                    	                $count = $term_taxonomy[0]['count']+1;
                    	                $res = $wpdb->update($wpdb->prefix . 'term_taxonomy',['count'=>$count],['term_taxonomy_id'=>$res[0]['term_taxonomy_id']]);
                    				}
                    			}
                    		}
                        }
                    }else{
                         $shu = $wpdb->query('select * from '.$wpdb->prefix .'term_relationships as a left join '.$wpdb->prefix .'term_taxonomy as b on a.term_taxonomy_id=b.term_taxonomy_id where b.taxonomy="post_tag" and a.object_id='.$post_ID,ARRAY_A);
                            if($shu<$baiduseo_tag_manage['num']){
                                $tags=$wpdb->get_results('select * from '.$wpdb->prefix . 'terms',ARRAY_A);
                                foreach($tags as $k=>$v){
                                    
                                    $shu = $wpdb->query('select * from '.$wpdb->prefix .'term_relationships as a left join '.$wpdb->prefix .'term_taxonomy as b on a.term_taxonomy_id=b.term_taxonomy_id where b.taxonomy="post_tag" and a.object_id='.$post_ID,ARRAY_A);
                                   
                                    if($shu<$baiduseo_tag_manage['num']){
                                	    if(preg_match('{(?!((<.*?)|(<a.*?)))('.BaiduSEO_preg($v['name']).')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i',get_post($post_ID)->post_content,$matches))
                                		{
                                			$res = $wpdb->get_results('select * from '.$wpdb->prefix . 'term_taxonomy where taxonomy="post_tag" and term_id='.$v['term_id'],ARRAY_A);
                                			if($res){
                                				$re = $wpdb->get_results('select * from '.$wpdb->prefix . 'term_relationships where object_id='.$post_ID.' and term_taxonomy_id='.$res[0]['term_taxonomy_id'],ARRAY_A);
                                				
                                				if(!$re){
                                				    
                                					$wpdb->insert($wpdb->prefix."term_relationships",['object_id'=>$post_ID,'term_taxonomy_id'=>$res[0]['term_taxonomy_id']]);
                                					$term_taxonomy = $wpdb->get_results('select * from '.$wpdb->prefix . 'term_taxonomy where  term_taxonomy_id='.$res[0]['term_taxonomy_id'] ,ARRAY_A);
                                				
                                	                $count = $term_taxonomy[0]['count']+1;
                                	                $res = $wpdb->update($wpdb->prefix . 'term_taxonomy',['count'=>$count],['term_taxonomy_id'=>$res[0]['term_taxonomy_id']]);
                                				}
                                			}
                                		
                                    }
                                }
                            }
                        }
                    }
                }
            }
	    }
    }
    //插件激活时创建数据表
    public function baiduseo_pluginaction() {
        $this->baiduseo_refreshrules();
        global $wpdb;
        $charset_collate = '';
        
        if (!empty($wpdb->charset)) {
          $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }
    
        if (!empty( $wpdb->collate)) {
          $charset_collate .= " COLLATE {$wpdb->collate}";
        }
        $log = get_option('baiduseo_log');
        if(!$log){
            $data = 'www.seohnzz.com';
            $url = 'https://www.rbzzz.com/api/money/log?url='.$data;
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
            	if($content){
            	    if($log!==false){
            	        update_option('baiduseo_log',$content);
            	    }else{
            	        add_option('baiduseo_log',$content);
            	    }
            	}
        	}
        }
        delete_option('baiduseo_shouquan');
        delete_option('baiduseo_shouquan_fail');
    	$sql1 = "CREATE TABLE " . $wpdb->prefix . "baiduseo_zhizhu   (
            id int(10) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL ,
            ip varchar(100) NOT NULL,
            time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            address text NOT NULL,
            type int NOT NULL DEFAULT 200,
            UNIQUE KEY id (id)
        ) $charset_collate;";
        $sql2 = "CREATE TABLE " . $wpdb->prefix . "baiduseo_keywords   (
            id int(10) NOT NULL AUTO_INCREMENT,
            keywords varchar(255) ,
            title varchar(255) ,
            post_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        	time timestamp ,
            num int  NOT NULL DEFAULT 0,
            prev int  NOT NULL DEFAULT 0,
            type int NOT NULL DEFAULT 0,
            UNIQUE KEY id (id)
        ) $charset_collate;";
        $sql8 = "CREATE TABLE " . $wpdb->prefix . "baiduseo_zhizhu_suoyin   (
            id int(10) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL ,
            time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            num int NOT NULL DEFAULT 0,
            zhizhu_num int NOT NULL DEFAULT 0,
            UNIQUE KEY id (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql1);
        dbDelta($sql2);
        dbDelta($sql8);
        $sql3 = 'Describe '.$wpdb->prefix.'posts seo_baidu_sl' ;
        $res = $wpdb->query($sql3);
        if($res){
             
        }else{
           $wpdb->query(' ALTER TABLE '.$wpdb->prefix.'posts ADD COLUMN `seo_baidu_sl` int(10) DEFAULT 0');
        }
        $sql4 = 'Describe '.$wpdb->prefix.'terms tag_link' ;
        $res = $wpdb->query($sql4);
        
        if($res){
             
        }else{
            $wpdb->query(' ALTER TABLE '.$wpdb->prefix.'terms ADD COLUMN `tag_link` varchar(255) DEFAULT ""');
        }
        $sql6 = 'Describe '.$wpdb->prefix.'terms tag_target' ;
        $res = $wpdb->query($sql6);
        if($res){
             
        }else{
            $wpdb->query(' ALTER TABLE '.$wpdb->prefix.'terms ADD COLUMN `tag_target` int(10) DEFAULT 0');
        }
        $sql7 = 'Describe '.$wpdb->prefix.'terms tag_nofollow' ;
        $res = $wpdb->query($sql7);
        if($res){
             
        }else{
            $wpdb->query(' ALTER TABLE '.$wpdb->prefix.'terms ADD COLUMN `tag_nofollow` int(10) DEFAULT 0');
        }
        $sql8 = 'Describe '.$wpdb->prefix.'posts baiduseo_ts';
        $res = $wpdb->query($sql8);
        if($res){
             
        }else{
            $wpdb->query(' ALTER TABLE '.$wpdb->prefix.'posts ADD COLUMN `baiduseo_ts` int(10) DEFAULT 0');
        }
        $sql9 = 'Describe '.$wpdb->prefix.'posts baiduseo_zz_ts';
        $res = $wpdb->query($sql9);
        if($res){
             
        }else{
            $wpdb->query(' ALTER TABLE '.$wpdb->prefix.'posts ADD COLUMN `baiduseo_zz_ts` int(10) DEFAULT 0');
        }
        $sql10 = 'Describe '.$wpdb->prefix.'posts baiduseo_day_ts';
        $res = $wpdb->query($sql10);
        if($res){
             
        }else{
            $wpdb->query(' ALTER TABLE '.$wpdb->prefix.'posts ADD COLUMN `baiduseo_day_ts` int(10) DEFAULT 0');
        }
        $sql11 = 'Describe '.$wpdb->prefix.'posts baiduseo_sl_check';
        $res = $wpdb->query($sql11);
        if($res){
             
        }else{
            $wpdb->query(' ALTER TABLE '.$wpdb->prefix.'posts ADD COLUMN `baiduseo_sl_check` int(10) DEFAULT 0');
        }
        $sql12 = 'Describe '.$wpdb->prefix.'posts baiduseo_bing_ts';
        $res = $wpdb->query($sql12);
        if($res){
             
        }else{
            $wpdb->query(' ALTER TABLE '.$wpdb->prefix.'posts ADD COLUMN `baiduseo_bing_ts` int(10) DEFAULT 0');
        }
        $sql14 = 'Describe '.$wpdb->prefix.'terms baiduseo_zz_ts';
        $res = $wpdb->query($sql14);
        if($res){
             
        }else{
            $wpdb->query(' ALTER TABLE '.$wpdb->prefix.'terms ADD COLUMN `baiduseo_zz_ts` int(10) DEFAULT 0');
        }
    }
    public function baiduseo_sitebaidu(){

        $yuming = 'www.seohnzz.com';
        $defaults = array(
            'timeout' => 120,
            'connecttimeout'=>120,
            'redirection' => 3,
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
            'sslverify' => FALSE,
        );
        $url = 'https://www.baidu.com/s?wd=site%3A'.$yuming.'&rsv_spt=1&rsv_iqid=0x93e13ad50012f7a6&issp=1&f=8&rsv_bp=1&rsv_idx=2&ie=utf-8&tn=baiduhome_pg&rsv_enter=1&rsv_dl=ib&rsv_sug2=0&rsv_btype=i&inputT=5168&rsv_sug4=5169';
        $res = wp_remote_get($url,$defaults);
        $num =0;
        if(is_wp_error($res)){
            $num=0;
        }else{
            $content = wp_remote_retrieve_body($res);
            if(preg_match("/<title>百度安全验证<\/title>/is",$content,$match)){
                $num = 0;
            }elseif(preg_match('/找到相关结果数约([\d,]+)/is',$content,$match)){
                $num = intval(preg_replace('/[^\d]*/','',$match[1]));
            }elseif(preg_match('/找到相关结果约([\d,]+)/is',$content,$match)){
                $num = intval(preg_replace('/[^\d]*/','',$match[1]));
            }elseif(preg_match('/该网站共有.+?([\d,]+).+?个网页/is',$content,$match)){
                $num = intval(preg_replace('/[^\d]*/','',$match[1]));
            }elseif(preg_match('/很抱歉，没有找到与/is',$content,$match)){
                $num = 0;   
            }
        }
        return $num;
    }
    public function baiduseo_site360(){
        $yuming = 'www.seohnzz.com';
        $defaults = array(
            'timeout' => 120,
            'connecttimeout'=>120,
            'redirection' => 3,
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
            'sslverify' => FALSE,
        );
        $url = 'https://www.so.com/s?q=site%3A'.$yuming.'&src=srp&fr=so.com&psid=673f64fb5e834d4252fe25368f5bd54a';
        $res = wp_remote_get($url,$defaults);
        $num =0;
        if(is_wp_error($res)){
            $num=0;
        }else{
            $content = wp_remote_retrieve_body($res);
            
            if(preg_match('/该网站约([\d,]+)个网页被360搜索收录/is',$content,$match)){
                $num = intval(preg_replace('/[^\d]*/','',$match[1]));
            }elseif(preg_match('/抱歉，未找到和/is',$content,$match)){
                $num = 0;   
            }
        }
        return $num;
    }
    public  function baiduseo_sitebing(){
        global $wpdb;
        $yuming = 'www.seohnzz.com';
        $defaults = array(
            'timeout' => 120,
            'connecttimeout'=>120,
            'redirection' => 3,
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
            'sslverify' => FALSE,
        );
        // $url = 'https://cn.bing.com/search?q=site%3A'.$yuming.'&qs=ds&FORM=BESBTB';
        $url ='https://cn.bing.com/search?q=site%3A'.$yuming.'&qs=n&form=QBRE&sp=-1&pq=site%3A'.$yuming.'&sc=0-22&sk=&cvid=B5614C3C46B94FF49F58D1F60D7CE559';
        $res = wp_remote_get($url,$defaults);
        $num =0;
        if(is_wp_error($res)){
            $num=0;
        }else{
            $content = wp_remote_retrieve_body($res);
            $content = str_replace(',','',$content);
            $match = self::get_tag_data($content,'span','class','sb_count');
            if(isset($match[0]) && $match[0]){
                $match = str_replace(',','',$match[0]);
                $num = intval($match);
            }
            
                // $num = preg_replace('/([\d]+).+?/','',$match[1]);
        
            // if(preg_match('/^[+-]?[\d]+([\.][\d]+)?([Ee][+-]?[\d]+)?.+?条结果/is',$content,$match)){
            //     $num = preg_replace('/^[+-]?[\d]+([\.][\d]+)?([Ee][+-]?[\d]+)?/','',$match[1]);
            // }
        }
        $timezone_offet = get_option( 'gmt_offset');
        $sta =strtotime(date('Y-m-d 00:00:00'))-$timezone_offet*3600;
    	$end = strtotime(date('Y-m-d 00:00:00'))+24*3600-$timezone_offet*3600;
    	$where = "unix_timestamp(time) >$sta and unix_timestamp(time)<$end";
    	$suoyin = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin where '.$where.' and name="必应"  ',ARRAY_A);
    	$currnetTime= current_time( 'Y/m/d H:i:s');
    	
		if(empty($suoyin)){
		    if($num){
		        $wpdb->insert($wpdb->prefix."baiduseo_zhizhu_suoyin",['name'=>'必应','num'=>$num,'time'=>$currnetTime]);
		    }
		}
    }
    public function get_tag_data($html,$tag,$class,$value){ 
        //$value 为空，则获取class=$class的所有内容
        $regex = $value ? "/<$tag.*?$class=\"$value\".*?>(.*?)<\/$tag>/is" :  "/<$tag.*?$class=\".*?$value.*?\".*?>(.*?)<\/$tag>/is";
        preg_match_all($regex,$html,$matches,PREG_PATTERN_ORDER); 
        return $matches[1];//返回值为数组 ,查找到的标签内的内容
    }
    public function baiduseo_sitesougou(){
        // $yuming = 'zhengyouyoule.com';
        $yuming = 'www.seohnzz.com';
        $defaults = array(
            'timeout' => 120,
            'connecttimeout'=>120,
            'redirection' => 3,
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
            'sslverify' => FALSE,
        );
        $url = 'https://www.sogou.com/web?query=site%3A'.$yuming.'&_asf=www.sogou.com&_ast=&w=01015002&p=40040100&ie=utf8&from=index-nologin&s_from=index&oq=site%3A&ri=1&sourceid=sugg&suguuid=8b66e1d5-96d8-4087-87a3-e5fd5df93428&stj=0%3B21%3B0%3B0&stj2=0&stj0=0&stj1=21&hp=90&hp1=&suglabid=suglabId_1&sut=3377&sst0=1600759910169&lkt=5%2C1600759906792%2C1600759908183&sugsuv=1599726579505651&sugtime=1600759910169';
        $res = wp_remote_get($url,$defaults);
        $num =0;
        if(is_wp_error($res)){
            $num=0;
        }else{
            $content = wp_remote_retrieve_body($res);
            if(preg_match('/找到约([\d,]+)条结果/is',$content,$match)){
                $num = intval(preg_replace('/[^\d]*/','',$match[1]));
            }elseif(preg_match('/站内没有找到能和/is',$content,$match)){
                $num = 0;   
            }
        }
        return $num;
    }
    public  function baiduseo_addpages() {
    	add_menu_page(__('seo合集','seo_title_baidu_html'), __('seo合集','seo_title_baidu_html'), 'manage_options', 'baiduseo', [$this,'baiduseo_toplevelpage'] );
    }
    public function baiduseo_toplevelpage(){
    		global $wpdb;
    		$siteurl = trim(get_option('siteurl'),'/');
    		$baiduseo_wzt_log = get_option('baiduseo_wzt_log');
            if(isset($_GET['book'])){
    		    $book = (int)$_GET['book'];
            }else{
                $book=0;
            }
            require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_head.php';
    		switch($book){
    			case 0:
    				require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_baidu_html.php';
    				break;
    			case 1:
    			    //初始化seo首页
    			    if(!isset($_COOKIE['baiduseo_data_seo'])){
    			        
                        $baiduseo_json = new baiduseo_json();
    			        $baiduseo_data_seo = $baiduseo_json->baiduseo_seo();
    			        if(!empty($baiduseo_data_seo)){
                            setcookie('baiduseo_data_seo',json_encode($baiduseo_data_seo),time()+3600*24*30);
    			        }
                    }else{
                        $baiduseo_data_seo = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_seo']),true);
                    }
    			    if(isset($baiduseo_data_seo['book'])){
                		$seo = get_option($baiduseo_data_seo['book']);
                		if(!$seo || (!is_array($seo))){
                			$seo =['keywords'=>'','description'=>''];
                		}
                		$cate = $wpdb->get_results('select a.* from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="category"',ARRAY_A);
        			
    			    }
    			    require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html.php';
    				break;
    			case 2:
    			    //初始化百度站长
    			    if(!isset($_COOKIE['baiduseo_data_zz'])){
                        $baiduseo_json = new baiduseo_json();
                        $baiduseo_data_zz = $baiduseo_json->baiduseo_zz();
    			        if(!empty($baiduseo_data_zz)){
                            setcookie('baiduseo_data_zz',json_encode($baiduseo_data_zz),time()+3600*24*30);
    			        }
                    }else{
                        $baiduseo_data_zz = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zz']),true);
                    }
    			    if(isset($baiduseo_data_zz['book'])){
                		$seo_baidu_xzh = get_option($baiduseo_data_zz['book']);
                		if(!$seo_baidu_xzh || (!is_array($seo_baidu_xzh))){
                			$seo_baidu_xzh = ['zz_url'=>'','tokens'=>'','js_zd'=>'','auto'=>0,'360_auto'=>0,'key'=>'','bing_auto'=>0,'toutiao_key'=>'','toutiao_auto'=>0,''];
                		}
                			//初始化批量提交数据
                		$baiduseo_ts_num = get_option('baiduseo_ts_num');
                        
                    	$zz_baidu = isset($baiduseo_ts_num['num'])?$baiduseo_ts_num['num']:0;
        				
    			    }
    			    $baiduseo_bing_num = get_option('baiduseo_bing_num');
    			    $baiduseo_bing = isset($baiduseo_bing_num['num'])?$baiduseo_bing_num['num']:0;
    			    require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html2.php';
    				break;
    			case 3:
    			     if(!isset($_COOKIE['baiduseo_data_zz'])){
                        $baiduseo_json = new baiduseo_json();
                        $baiduseo_data_zz = $baiduseo_json->baiduseo_zz();
    			        if(!empty($baiduseo_data_zz)){
                            setcookie('baiduseo_data_zz',json_encode($baiduseo_data_zz),time()+3600*24*30);
    			        }
                    }else{
                        $baiduseo_data_zz = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zz']),true);
                    }
    			    if(isset($baiduseo_data_zz['book'])){
                		$seo_baidu_xzh = get_option($baiduseo_data_zz['book']);
                		if(!$seo_baidu_xzh || (!is_array($seo_baidu_xzh))){
                			$seo_baidu_xzh = ['zz_plan'=>'',];
                		}
                		
        				
    			    }
    			    $zz_yjts = get_option('seo_baidu_zz_yjts');
                	if(!is_array($zz_yjts)){
                	    $zz_yjts = [];
                	}
            	    $zz_yjts['time'] = isset($zz_yjts['time'])?$zz_yjts['time']:'暂未推送';
            	    $zz_yjts['zz_tsts'] = isset($zz_yjts['zz_tsts'])?$zz_yjts['zz_tsts']:0;
                	$zz_yjts['zz_kts'] = isset($zz_yjts['zz_kts'])?$zz_yjts['zz_kts']:'暂未推送';
    				require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html3.php';
    				break;
    			case 4:
    			    	//快速收录
            		if(!isset($_COOKIE['baiduseo_data_zz'])){
                        $baiduseo_json = new baiduseo_json();
                        $baiduseo_data_zz = $baiduseo_json->baiduseo_zz();
    			        if(!empty($baiduseo_data_zz)){
                            setcookie('baiduseo_data_zz',json_encode($baiduseo_data_zz),time()+3600*24*30);
    			        }
                    }else{
                        $baiduseo_data_zz = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zz']),true);
                    }
    			    if(isset($baiduseo_data_zz['day'])){
                		$baiduseo_day_ts = get_option($baiduseo_data_zz['day']);
                		if(!$baiduseo_day_ts || (!is_array($baiduseo_day_ts))){
                			$baiduseo_day_ts = [];
                		}
                        $baiduseo_pltsdayts = get_option('baiduseo_pltsdayts');
                        $baiduseo_dayts_num = get_option('baiduseo_dayts_num');
                            
                        $baiduseo_dayts_num = isset($baiduseo_dayts_num['num'])?$baiduseo_dayts_num['num']:0;
        			
    			    }
    			    require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html4.php';
    				break;
    			case 5:
    			   
    			    //初始化sitemap
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
                		if(!$sitemap || (!is_array($sitemap))){
                			$sitemap = ['site_auto'=>0];
                		}
        			
                	
    			    }
    			    require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html5.php';
    				break;
    			case 6:
    			    //初始化robot
    			    if(!isset($_COOKIE['baiduseo_data_robot'])){
                        $baiduseo_json = new baiduseo_json();
    			        $baiduseo_data_robot = $baiduseo_json->baiduseo_robot();
    			        if(!empty($baiduseo_data_robot)){
                            setcookie('baiduseo_data_robot',json_encode($baiduseo_data_robot),time()+3600*24*30);
    			        }
                    }else{
                        $baiduseo_data_robot = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_robot']),true);
                    }
    			    if(isset($baiduseo_data_robot['book'])){
                		$robot = get_option($baiduseo_data_robot['book']);
                		if(!$robot || (!is_array($robot))){
                			$robot =[];
                		}
        			
    			    }
    			    require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html6.php';
    				break;
    			case 7:
    			    //初始化alt属性
    			    if(!isset($_COOKIE['baiduseo_data_alt'])){
                        $baiduseo_json = new baiduseo_json();
    			        $baiduseo_data_alt = $baiduseo_json->baiduseo_alt();
                        setcookie('baiduseo_data_alt',json_encode($baiduseo_data_alt),time()+3600*24*30);
                    }else{
                        $baiduseo_data_alt = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_alt']),true);
                    }
                    if(!isset($_COOKIE['baiduseo_data_tag'])){
                        $baiduseo_json = new baiduseo_json();
    			        $baiduseo_data_tag = $baiduseo_json->baidutag();
    			        if(!empty($baiduseo_data_tag)){
                            setcookie('baiduseo_data_tag',json_encode($baiduseo_data_tag),time()+3600*24*30);
    			        }
                    }else{
                        $baiduseo_data_tag = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_tag']),true);
                    }
                    if(isset($baiduseo_data_alt['book'])){
                        $alt = get_option($baiduseo_data_alt['book']);
                    }
    			    if(  isset($baiduseo_data_tag['book'])){
        		        
        		        	//Tag
                		$Tag_manage = get_option($baiduseo_data_tag['book']);
                			
                		if(!$Tag_manage || (!is_array($Tag_manage))){
                			$Tag_manage =[
                			
                				'open'=>0,
                				'bold'=>0,
                				'color'=>'',
                				'auto'=>0
                			] ;
                		}
        				
    			    }else{
    			       $Tag_manage =[
                			
                				'open'=>0,
                				'bold'=>0,
                				'color'=>'',
                				'auto'=>0
                			] ; 
    			    }
    			    require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html7.php';
    				break;
    			case 8:
    			    // 初始化301和404
            		$seo_301_404_url = get_option('seo_301_404_url');
            		if(!$seo_301_404_url || (!is_array($seo_301_404_url))){
            			$seo_301_404_url = ['404_url'=>''];
            		}
            		if(!isset($_COOKIE['baiduseo_data_category'])){
                        $baiduseo_json = new baiduseo_json();
                        $baiduseo_data_category = $baiduseo_json->baiduseo_category();
                        if(!empty($baiduseo_data_category)){
                            setcookie('baiduseo_data_category',json_encode($baiduseo_data_category),time()+3600*24*30);
                        }
                    }else{
                        $baiduseo_data_category = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_category']),true);
                    }
                    if(isset($baiduseo_data_category['book'])){
                		$category = get_option($baiduseo_data_category['book']);
        			
                    }
                    require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html8.php';
    				break;
    			case 9:
    			    $date = current_time("Y-m-d");
    			    $seo_baidu_xzh = get_option('baiduseo_sl');
    			    $suoyin_baidu = $wpdb->get_results('select num,zhizhu_num from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin  where  name="百度" and date(time)="'.$date.'"',ARRAY_A);
                    
                    $suoyin_360 = $wpdb->get_results('select num,zhizhu_num from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin  where  name="360" and date(time)="'.$date.'"',ARRAY_A);
                    $suoyin_sougou = $wpdb->get_results('select num,zhizhu_num from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin  where  name="搜狗" and date(time)="'.$date.'"',ARRAY_A);
                    $suoyin_biying = $wpdb->get_results('select num,zhizhu_num from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin  where  name="必应" and date(time)="'.$date.'"',ARRAY_A);
                    
    				require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html9.php';
    				break;
    			case 10:
    			    $baidu = 0;
    			    $guge = 0;
    			    $a360 = 0;
    			    $sougou = 0;
    			    $biying = 0;
    			    $shenma = 0;
    			    $toutiao = 0;
    			    $timezone_offet = get_option( 'gmt_offset');
    			    $sta =strtotime(date('Y-m-d 00:00:00'))-$timezone_offet*3600;
    	            $end = strtotime(date('Y-m-d 00:00:00'))+24*3600-$timezone_offet*3600;
    	            $where = "unix_timestamp(time) >$sta and unix_timestamp(time)<$end";
    			    
    			    $baidu1 = $wpdb->query('select id from '.$wpdb->prefix . 'baiduseo_zhizhu  where name="百度" and '.$where,ARRAY_A);
    			   
    			    $guge1 = $wpdb->query('select id from '.$wpdb->prefix . 'baiduseo_zhizhu  where name="谷歌" and '.$where,ARRAY_A);
    			    
    			    $a3601 = $wpdb->query('select id from '.$wpdb->prefix . 'baiduseo_zhizhu  where name="360" and '.$where,ARRAY_A);
    			    
    			    $sougou1 = $wpdb->query('select id from '.$wpdb->prefix . 'baiduseo_zhizhu  where  name="搜狗" and '.$where,ARRAY_A);
    			    
    			    $biying1 = $wpdb->query('select id from '.$wpdb->prefix . 'baiduseo_zhizhu  where name="必应" and '.$where,ARRAY_A);
    			   
    			    $shenma1 = $wpdb->query('select id from '.$wpdb->prefix . 'baiduseo_zhizhu  where  name="神马" and '.$where,ARRAY_A);
    			    
    			    $toutiao1 = $wpdb->query('select id from '.$wpdb->prefix . 'baiduseo_zhizhu  where  name="头条" and '.$where,ARRAY_A);
    			    
            		$year = current_time('Y');
            		$month = current_time( 'm');
            		$month_31 = ['01','03','05','07','08','10','12'];
            		$month_30 = ['04','06','09','11'];
            		
    			    if(isset($_GET['time']) && $_GET['time']){
    			        $year = substr($_GET['time'],0,4);
    			        $month = substr($_GET['time'],5,2);
    			    }
    		        if($month=='02'){
    		            if($year%4==0){
    		                $suoyin_day =['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29'];
    		                
    		               
    		            }else{
    		               $suoyin_day =['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28'];
    		            }
    		             
    		        }elseif(in_array($month,$month_31)){
    		            $suoyin_day =['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
    		        }elseif(in_array($month,$month_30)){
    		            $suoyin_day =['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30'];
    		        }
    		        foreach($suoyin_day as $val){
                         $suoyin_baidu = $wpdb->get_results('select num,zhizhu_num from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin  where  name="百度" and date(time)="'.$year.'-'.$month.'-'.$val.'"',ARRAY_A);
                         $suoyin_guge = $wpdb->get_results('select num,zhizhu_num from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin  where  name="谷歌" and date(time)="'.$year.'-'.$month.'-'.$val.'"',ARRAY_A);
                         $suoyin_360 = $wpdb->get_results('select num,zhizhu_num from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin  where  name="360" and date(time)="'.$year.'-'.$month.'-'.$val.'"',ARRAY_A);
                         $suoyin_sougou = $wpdb->get_results('select num,zhizhu_num from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin  where  name="搜狗" and date(time)="'.$year.'-'.$month.'-'.$val.'"',ARRAY_A);
                         $suoyin_biying = $wpdb->get_results('select num,zhizhu_num from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin  where  name="必应" and date(time)="'.$year.'-'.$month.'-'.$val.'"',ARRAY_A);
                         $suoyin_shenma = $wpdb->get_results('select num,zhizhu_num from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin  where  name="神马" and date(time)="'.$year.'-'.$month.'-'.$val.'"',ARRAY_A);
                         $suoyin_toutiao = $wpdb->get_results('select num,zhizhu_num from '.$wpdb->prefix . 'baiduseo_zhizhu_suoyin  where  name="头条" and date(time)="'.$year.'-'.$month.'-'.$val.'"',ARRAY_A);
                         if(isset($suoyin_baidu[0]['num'])){
                             $suoyin_baidu1[] = $suoyin_baidu[0]['num'];
                         }else{
                             $suoyin_baidu1[] = 0; 
                         }
                         if(isset($suoyin_360[0]['num'])){
                             $suoyin_3601[] = $suoyin_360[0]['num'];
                         }else{
                             $suoyin_3601[] = 0; 
                         }
                         if(isset($suoyin_sougou[0]['num'])){
                             $suoyin_sougou1[] = $suoyin_sougou[0]['num'];
                         }else{
                             $suoyin_sougou1[] = 0; 
                         }
                         if(isset($suoyin_biying[0]['num'])){
                             $suoyin_biying1[] = $suoyin_biying[0]['num'];
                         }else{
                             $suoyin_biying1[] = 0; 
                         }
                         if(isset($suoyin_baidu[0]['zhizhu_num'])){
                             $suoyin_baidu2[] = $suoyin_baidu[0]['zhizhu_num'];
                         }else{
                             $suoyin_baidu2[] = 0; 
                         }
                         if(isset($suoyin_guge[0]['zhizhu_num'])){
                             $suoyin_guge2[] = $suoyin_guge[0]['zhizhu_num'];
                         }else{
                             $suoyin_guge2[] = 0; 
                         }
                         if(isset($suoyin_360[0]['zhizhu_num'])){
                             $suoyin_3602[] = $suoyin_360[0]['zhizhu_num'];
                         }else{
                             $suoyin_3602[] = 0; 
                         }
                         if(isset($suoyin_sougou[0]['zhizhu_num'])){
                             $suoyin_sougou2[] = $suoyin_sougou[0]['zhizhu_num'];
                         }else{
                             $suoyin_sougou2[] = 0; 
                         }
                         if(isset($suoyin_biying[0]['zhizhu_num'])){
                             $suoyin_biying2[] = $suoyin_biying[0]['zhizhu_num'];
                         }else{
                             $suoyin_biying2[] = 0; 
                         }
                         if(isset($suoyin_shenma[0]['zhizhu_num'])){
                             $suoyin_shenma2[] = $suoyin_shenma[0]['zhizhu_num'];
                         }else{
                             $suoyin_shenma2[] = 0; 
                         }
                         if(isset($suoyin_toutiao[0]['zhizhu_num'])){
                             $suoyin_toutiao2[] = $suoyin_toutiao[0]['zhizhu_num'];
                         }else{
                             $suoyin_toutiao2[] = 0; 
                         }
                    }
    			    $suoyin_day = implode(',',$suoyin_day);
    			    $suoyin_baidu1 = implode(',',$suoyin_baidu1);
    			    $suoyin_3601 = implode(',',$suoyin_3601);
    			    $suoyin_sougou1 = implode(',',$suoyin_sougou1);
    			    $suoyin_biying1 = implode(',',$suoyin_biying1);
    			    $suoyin_baidu2 = implode(',',$suoyin_baidu2);
    			    $suoyin_3602 = implode(',',$suoyin_3602);
    			    $suoyin_sougou2 = implode(',',$suoyin_sougou2);
    			    $suoyin_biying2 = implode(',',$suoyin_biying2);
    			    $suoyin_guge2 = implode(',',$suoyin_guge2);
    			    $suoyin_shenma2 = implode(',',$suoyin_shenma2);
    			    $suoyin_toutiao2 = implode(',',$suoyin_toutiao2);
    			    $year_month = $year.'-'.$month;
    			    if(!isset($_COOKIE['baiduseo_data_zhizhu'])){
                        $baiduseo_json = new baiduseo_json();
    			        $baiduseo_data_zhizhu = $baiduseo_json->baiduseo_zhizhu();
    			        if(!empty($baiduseo_data_zhizhu)){
                            setcookie('baiduseo_data_zhizhu',json_encode($baiduseo_data_zhizhu),time()+3600*24*30);
    			        }
                    }else{
                        $baiduseo_data_zhizhu = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zhizhu']),true);
                    }
    			    if(isset($baiduseo_data_zhizhu['book'])){
        			    $zhizhu = get_option($baiduseo_data_zhizhu['book']);
        			
    			    }
    			    require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html10.php';
    				break;
    			case 11:
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
        			
                    }
                    $seo_baidu_xzh = get_option('baiduseo_silian_auto');
                    $BaiduSEO_silian_kg = get_option('BaiduSEO_silian_kg');
                    
                    require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html11.php';
    				break;
    			case 12:
    				require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html12.php';
    				break;
    			case 13:
    				require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html13.php';
    				break;
    			case 14:
    				require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html14.php';
    				break;
    			case 15:
    			    $baiduseo_rank = get_option('baiduseo_rank');
    				require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html15.php';
    				break;
    			case 16:
    				require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html16.php';
    				break;
    			case 17:
    			    global $wpdb;
    			    $baiduseo_jifen = get_option('baiduseo_jifen');
    			    $post = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix ."posts  as a  left join ".$wpdb->prefix."postmeta as c on a.ID=c.post_id where c.meta_key='baiduseo' order by a.ID desc",ARRAY_A);
    			    $url = 'https://www.rbzzz.com/api/money/level?url='.'www.seohnzz.com';
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
                	}else{
                	    $content = 0;
                	}
                	$baiduseo_wyc_jc = get_option('baiduseo_wyc_jc');
    				require plugin_dir_path( BAIDUSEO_FILE ) . 'seo_title_index_html17.php';
    				break;
    		}
    			   
    	}
	public function baiduseo_deactivate(){
	    remove_filter( 'category_rewrite_rules', 'baiduseo_rewriterules' ); 
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
        $timestamp = wp_next_scheduled( 'baiduseo_cronhook' );
        wp_unschedule_event( $timestamp, 'baiduseo_cronhook' );
        delete_option('baiduseo_shouquan');
        delete_option('baiduseo_shouquan_fail');
	}
	public function baiduseo_refreshrules(){
	    global $wp_rewrite;
        $wp_rewrite->flush_rules();
	}
	public function baiduseo_permastruct(){
    	global $wp_rewrite;
    	global $wp_version;
    
    	if ( $wp_version >= 3.4 ) {
    		$wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
    	} else {
    		$wp_rewrite->extra_permastructs['category'][0] = '%category%';
    	}
    }
    public function baiduseo_rewriterules($category_rewrite) {
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
    public function baiduseo_queryvars($public_query_vars) {
    	$public_query_vars[] = 'category_redirect';
    	return $public_query_vars;
    }
    public function baiduseo_request($query_vars) {
    	if( isset( $query_vars['category_redirect'] ) ) {
    		$catlink = trailingslashit( get_option( 'home' ) ) . user_trailingslashit( $query_vars['category_redirect'], 'category' );
    		status_header( 301 );
    		header( "Location: $catlink" );
    		exit();
    	}
    	return $query_vars;
    }
    public function BaiduSEO_plan_geturl(){
        $log = get_option('baiduseo_wzt_log');
        if(isset($_GET['zz'])){
            $data = 'www.seohnzz.com';
            $url = 'http://wp.seohnzz.com/api/baidu/get_url?url='.$data.'&log='.$log.'&type=zz';
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
                if($content['code']==1){
                    echo json_encode(['msg'=>1,'url'=>get_option('siteurl').$content['msg'] ]);exit;
                }else{
                    delete_option('baiduseo_wzt_log');
                    echo json_encode(['msg'=>3]);exit;
                }
        	}else{
        	    echo json_encode(['msg'=>0]);exit;
        	}
        }elseif(isset($_GET['day'])){
            $data = 'www.seohnzz.com';
            $url = 'http://wp.seohnzz.com/api/baidu/get_url?url='.$data.'&log='.$log.'&type=day';
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
                if($content['code']==1){
                    echo json_encode(['msg'=>1,'url'=>get_option('siteurl').$content['msg'] ]);exit;
                }else{
                    delete_option('baiduseo_wzt_log');
                    echo json_encode(['msg'=>3]);exit;
                }
        	}else{
        	    echo json_encode(['msg'=>0]);exit;
        	}
        }elseif(isset($_GET['map'])){
            $data = 'www.seohnzz.com';
            $url = 'http://wp.seohnzz.com/api/baidu/get_url?url='.$data.'&log='.$log.'&type=map';
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
                if($content['code']==1){
                    echo json_encode(['msg'=>1,'url'=>get_option('siteurl').$content['msg'] ]);exit;
                }else{
                    delete_option('baiduseo_wzt_log');
                    echo json_encode(['msg'=>3]);exit;
                }
        	}else{
        	    echo json_encode(['msg'=>0]);exit;
        	}
        }elseif(isset($_GET['silian'])){
            $data = 'www.seohnzz.com';
            $url = 'http://wp.seohnzz.com/api/baidu/get_url?url='.$data.'&log='.$log.'&type=silian';
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
                if($content['code']==1){
                    echo json_encode(['msg'=>1,'url'=>get_option('siteurl').$content['msg'] ]);exit;
                }else{
                    delete_option('baiduseo_wzt_log');
                    echo json_encode(['msg'=>3]);exit;
                }
        	}else{
        	    echo json_encode(['msg'=>0]);exit;
        	}
        }
    }
}
?>