<?php
class baiduseo_post{
    public $data;
    public $baiduseo_data_zz;
    public $urls;
    public $result;
    public $post_id;
    function __construct($data) {
        $this->data = $data;
        add_action('wp_ajax_BaiduSEO',[$this,'BaiduSEO_post']);
        $baiduseo_wzt_log = get_option('baiduseo_wzt_log');
        $this->log = $baiduseo_wzt_log;
    }
    public function BaiduSEO_post(){
        $data = $this->data;
        
        if($data['BaiduSEO']==17){
            $this->BaiduSEO_keywords(sanitize_text_field($data['keywords']));
        }elseif($data['BaiduSEO']==29){
            $baiduseo_rank = get_option('baiduseo_rank');
            if($baiduseo_rank){
                update_option('baiduseo_rank',json_decode($data['data'],true));
            }else{
                add_option('baiduseo_rank',json_decode($data['data'],true));
            }
        }elseif($data['BaiduSEO']==37){
            $wyc =$data['wyc'];
            $post_extend = get_post_meta( (int)$wyc['wp_id'], 'baiduseo', true );
            add_post_meta((int)$wyc['wp_id'],'baiduseo_wyc_status',1);
            if($post_extend){
               update_post_meta( (int)$wyc['wp_id'],'baiduseo',  ['content_edit'=>$wyc['content_edit'],'status'=>1,'yc'=>(int)$wyc['yc'],'num'=>$wyc['num'],'addtime'=>$wyc['addtime']] ); 
            }else{
                add_post_meta((int)$wyc['wp_id'],'baiduseo',['content_edit'=>$wyc['content_edit'],'status'=>1,'yc'=>(int)$wyc['yc'],'num'=>$wyc['num'],'addtime'=>$wyc['addtime']] );
            }
        }elseif($data['BaiduSEO']==41){
            $content = $data['content'];
            $id = (int)$data['wp_id'];
            global $wpdb;
            $wpdb->update($wpdb->prefix . 'posts',['post_content'=>$content],['id'=>$id]);
            
        }else{
            
            if(isset($data['nonce']) && isset($data['action']) && wp_verify_nonce($data['nonce'],$data['action'])){
              
                switch ($data['BaiduSEO']) {
                    case 1:
                        $this->BaiduSEO_seo();exit;
                        break;
                    case 2:
                        $this->BaiduSEO_zz();exit;
                        break;
                    case 3:
                        $this->BaiduSEO_zzts();exit;
                        break;
                    case 4:
                        $this->BaiduSEO_sitemap();exit;
                        break;
                    case 5:
                        $this->BaiduSEO_robot();exit;
                        break;
                    case 6:
                        $this->BaiduSEO_alt();exit;
                        break;
                    case 7:
                        $this->BaiduSEO_404();exit;
                        break;
                    case 8:
                        $this->BaiduSEO_sl();exit;
                        break;
                    case 9:
                        $this->BaiduSEO_zz_plts();exit;
                        break;
                    case 10:
                        $this->BaiduSEO_dayts();exit;
                        break;
                    case 11:
                        $this->BaiduSEO_zhizhu();exit;
                        break;
                    case 12:
                        $this->BaiduSEO_zhizhu_delete();exit;
                        break;
                    case 13:
                        $this->BaiduSEO_silian();exit;
                        break;
                    case 14:
                        $this->BaiduSEO_301();exit;
                        break;
                    case 15:
                        $this->BaiduSEO_keywords_add();exit;
                        break;
                    case 16:
                        $this->BaiduSEO_keywords_delete();exit;
                        break;
                    case 18:
                        $this->BaiduSEO_tag_pladd();exit;
                        break;
                    case 19:
                        $this->BaiduSEO_day_open();exit;
                        break;
                    case 20:
                        $this->BaiduSEO_day_delete();exit;
                        break;
                    case 21:
                        $this->BaiduSEO_tag();exit;
                        break;
                    case 22:
                        $this->BaiduSEO_tag_pl();exit;
                        break;
                    case 23:
                        $this->BaiduSEO_cate_add();exit;
                        break;
                    case 24:
                        $this->BaiduSEO_cate_open();exit;
                        break;
                    case 25:
                        $this->BaiduSEO_cate_list();exit;
                        break;
                    case 26:
                        $this->BaiduSEO_neilian();exit;
                        break;
                    case 27:
                        $this->BaiduSEO_day_pl();exit;
                        break;
                    case 28:
                        
                        $this->BaiduSEO_zhizhu_id_delete();
                        break;
                    case 30:
                        
                        $this->BaiduSEO_rank();
                        
                        break;
                    case 31:
                        $pay = baiduseo_paymoney('/api/index/pay_money');
                        if(!$pay){
                    		echo json_encode(['msg'=>3]);exit;
                    	}
                        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
                    	}else{
                    		echo json_encode(['msg'=>3]);exit;
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
                        if(isset($baiduseo_data_zz['book'])){
                			$baidu = get_option($baiduseo_data_zz['book']);
                            
                            if($baidu){
                                $seo_baidu_xzh= $baidu;
                                if(isset($data['zz_plan'])){
                                    $seo_baidu_xzh['zz_plan'] =1;
                                }else{
                                    $seo_baidu_xzh['zz_plan'] =0;
                                }
                                update_option($baiduseo_data_zz['book'],$seo_baidu_xzh);
                            }else{
                              add_option($baiduseo_data_zz['book'],$seo_baidu_xzh);
                            }  
                            echo json_encode(['msg'=>'提交成功']);exit;
                		}
                        break;
                    case 32:
                        $pay = baiduseo_paymoney('/api/index/pay_money');
                        if(!$pay){
                    		echo json_encode(['msg'=>3]);exit;
                    	}
                        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
                    	}else{
                    		echo json_encode(['msg'=>3]);exit;
                    	}
                    	$baidu = get_option('baiduseo_sl');
                        if(isset($data['sl_plan'])){
                            $seo_baidu_xzh['sl_plan'] =1;
                        }else{
                            $seo_baidu_xzh['sl_plan'] =0;
                        }   
                        if($baidu){
                            update_option('baiduseo_sl',$seo_baidu_xzh);
                        }else{
                          add_option('baiduseo_sl',$seo_baidu_xzh);
                        }  
                        echo json_encode(['msg'=>'提交成功']);exit;
                        break;
                    case 33:
                        $pay = baiduseo_paymoney('/api/index/pay_money');
                        if(!$pay){
                    		echo json_encode(['msg'=>3]);exit;
                    	}
                        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
                    	}else{
                    		echo json_encode(['msg'=>3]);exit;
                    	}
                    	$baidu = get_option('baiduseo_silian_auto');
                        if(isset($data['silian_plan'])){
                            $seo_baidu_xzh['silian_plan'] =1;
                        }else{
                            $seo_baidu_xzh['silian_plan'] =0;
                        }   
                        if($baidu!==false){
                            update_option('baiduseo_silian_auto',$seo_baidu_xzh);
                        }else{
                          add_option('baiduseo_silian_auto',$seo_baidu_xzh);
                        }  
                        echo json_encode(['msg'=>'提交成功']);exit;
                        break;
                    case 34:
                        global $wpdb;
                        $pay = baiduseo_paymoney('/api/index/pay_money');
                        if(!$pay){
                    		echo json_encode(['msg'=>3]);exit;
                    	}
                        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
                    	}else{
                    		echo json_encode(['msg'=>3]);exit;
                    	}
                    	foreach($data['value'] as $key=>$val){
                    	    $term = (int)$val['term_id'];
                    	    $res = $wpdb->query( "DELETE FROM " . $wpdb->prefix . "terms where term_id=".$term); 
                    	}
                    	echo json_encode(['msg'=>'1']);exit;
                        break;
                    case 35:
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
                    	if($content==1 || $content==2){
                    	    
                    	
                            $baiduseo_jifen = get_option('baiduseo_jifen');
                            // var_dump($baiduseo_jifen);exit;
                            $id = (int)$data['id'];
                            $num = mb_strlen(preg_replace('/\s/','',html_entity_decode(strip_tags(get_post($id)->post_content))),'UTF-8');
                         
                            $kouchu = ceil($num/1000);
                            if($baiduseo_jifen!==false){
                               
                                $timezone_offet = get_option( 'gmt_offset');
                                if(isset($baiduseo_jifen['addtime']) && $baiduseo_jifen['addtime']>strtotime(date('Y-m-d 00:00:00'))-$timezone_offet*3600){
                                    if(isset($baiduseo_jifen['sy']) && $baiduseo_jifen['sy']<$kouchu){
                                        echo json_encode(['msg'=>0,'data'=>'积分不足']);exit;
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
                            
                            $content = get_post($id)->post_content;
                            
                            $url = 'http://wp.seohnzz.com/api/wyc/wp_wyc';
                		    $result = wp_remote_post($url,['body'=>['id'=>$id,'content'=>$content,'num'=>$num,'url'=>get_option('siteurl')]]);
                		    if(!is_wp_error($result)){
                		        echo json_encode(['msg'=>'1']);exit;
                		    }else{
                		        echo json_encode(['msg'=>'0']);exit;
                		    }
                    	}else{
                    	    echo json_encode(['msg'=>'0','data'=>'没有权限']);exit;
                    	}
                        break;
                    case 36:
                        global $wpdb;
                        $pay = baiduseo_paymoney('/api/index/pay_money');
                        if(!$pay){
                    		echo json_encode(['msg'=>3]);exit;
                    	}
                        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
                    	}else{
                    		echo json_encode(['msg'=>3]);exit;
                    	}
                        $timezone_offet = get_option( 'gmt_offset');
                        $end = strtotime(date('Y-m-1 00:00:00'))-$timezone_offet*3600;
                        $where = "unix_timestamp(time)<$end";
                        $res = $wpdb->query( "DELETE FROM " . $wpdb->prefix . "baiduseo_zhizhu  where {$where}" );
                        
                        echo json_encode(['msg'=>'1']);exit;
                        
                        break;
                    case 38:
                        $id = (int)$data['id'];
                        delete_post_meta($id,'baiduseo');
                        echo json_encode(['msg'=>'1']);exit;
                        break;
                    case 39:
                        delete_option('baiduseo_shouquan_fail');
                        delete_option('baiduseo_shouquan');
                        delete_option('baiduseo_log');
                        delete_option('baiduseo_wzt_log');
                        delete_option('baiduseo_logs');
                        echo json_encode(['msg'=>'1']);exit;
                        break;
                    case 40:
                        $pay = baiduseo_paymoney('/api/index/pay_money');
                        if(!$pay){
                    		echo json_encode(['msg'=>3]);exit;
                    	}
                        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
                    	}else{
                    		echo json_encode(['msg'=>3]);exit;
                    	}
                    	$baiduseo_wyc_jc = get_option('baiduseo_wyc_jc');
                        if(isset($data['open'])){
                            $seo_baidu_xzh['open'] =1;
                        }else{
                            $seo_baidu_xzh['open'] =0;
                        }   
                        if(isset($data['auto'])){
                            $seo_baidu_xzh['auto'] =1;
                        }else{
                            $seo_baidu_xzh['auto'] =0;
                        }   
                        if($baiduseo_wyc_jc!==false){
                            update_option('baiduseo_wyc_jc',$seo_baidu_xzh);
                        }else{
                          add_option('baiduseo_wyc_jc',$seo_baidu_xzh);
                        }  
                        echo json_encode(['msg'=>'1']);exit;
                        break;
                    
                    default:
                        // code...
                        break;
                }
            }
        }
        
    }
    public function BaiduSEO_rank(){

        $pay = baiduseo_paymoney('/api/index/pay_money');
        if(!$pay){
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo json_encode(['msg'=>3]);exit;
    	}
        $baiduseo_rank = get_option('baiduseo_rank');
        $timezone_offet = get_option( 'gmt_offset');
		$time = isset($baiduseo_rank[0]['time'])?strtotime($baiduseo_rank[0]['time'])-$timezone_offet*3600:0;
		if(time()-$time<24*3600){
		    echo json_encode(['msg'=>0]);exit;  
		}else{
		    $ur=  'www.seohnzz.com';
		    $url = 'http://wp.seohnzz.com/api/rank/rank?url='.$ur.'&http='.get_option('siteurl');
	        $log = $this->log;
	        if($log){
	            $defaults = array(
                    'timeout' => 300,
                    'redirection' => 300,
                    'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                    'sslverify' => FALSE,
                );
    		    $result = wp_remote_get($url,$defaults);
	        }
    		echo json_encode(['msg'=>1]);exit;
		}
    }
    public  function BaiduSEO_keywords($keywords){
        global $wpdb;
    	$keywords = json_decode($keywords,true);
    	$type = isset($keywords[0]['type'])?$keywords[0]['type']:0;
    	$res = $wpdb->get_results(' select * from  '.$wpdb->prefix.'baiduseo_keywords where keywords="'.$keywords[0]['keywords'].'" and type="'.$type.'"',ARRAY_A);
    	$wpdb->update($wpdb->prefix . 'baiduseo_keywords',['time'=>$keywords[0]['time'],'title'=>$keywords[0]['title'],'num'=>$keywords[0]['num'],'prev'=>$res[0]['num']],['id'=>$res[0]['id']]);
    }
    public function BaiduSEO_seo(){
        $data = $this->data;
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
		    $seo = ['keywords'=>sanitize_text_field($data['keywords']),'description'=>sanitize_textarea_field($data['description'])];
			
			$seo_init = get_option($baiduseo_data_seo['book']);
            if($seo_init){
            	update_option($baiduseo_data_seo['book'],$seo);
            }else{
            	add_option($baiduseo_data_seo['book'],$seo);
        	}  
        	echo json_encode(['msg'=>1]);exit; 
	    }
		echo json_encode(['msg'=>0]);exit;  
    }
    public function BaiduSEO_zz(){
        $data = $this->data;
        
        if(!isset($_COOKIE['baiduseo_data_zz'])){
            $baiduseo_json = new baiduseo_json();
            $baiduseo_data_zz = $baiduseo_json->baiduseo_zz();
	        if(!empty($baiduseo_data_zz)){
                setcookie('baiduseo_data_zz',json_encode($baiduseo_data_zz),time()+3600*24*30);
	        }
        }else{
            $baiduseo_data_zz = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zz']),true);
        }
        
		if(isset($data['close']) || isset($data['open']) || isset($data['360_auto']) || isset($data['zz_plan']) || isset($data['bing_auto']) || isset($data['toutiao_auto'])){
		    $pay = baiduseo_paymoney('/api/index/pay_money');
            if(!$pay){
        		echo json_encode(['msg'=>3]);exit;
        	}
            if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
        	}else{
        		echo json_encode(['msg'=>3]);exit;
        	}

            $log = $this->log;
			 $seo_baidu_xzh =[     		         	      	
                'zz_url'=>esc_url($data['zz_url']),     	 		           		  
                'tokens'=>sanitize_text_field($data['tokens']), 
                'key' =>sanitize_text_field($data['key']),
                'toutiao_key'=>sanitize_text_field($data['toutiao_key']),
            ];  
            if(isset($data['close'])){
                if($log){
                    $seo_baidu_xzh['auto'] =1;
                }else{
                    $seo_baidu_xzh['auto'] =0;
                }
            }else{
                $seo_baidu_xzh['auto'] =0;
            }
            if(isset($data['toutiao_auto'])){
                if($log){
                    $seo_baidu_xzh['toutiao_auto'] =1;
                }else{
                    $seo_baidu_xzh['toutiao_auto'] =0;
                }
            }else{
                $seo_baidu_xzh['toutiao_auto'] =0;
            }
            if(isset($data['bing_auto'])){
                if($log){
                    $seo_baidu_xzh['bing_auto'] =1;
                }else{
                    $seo_baidu_xzh['bing_auto'] =0;
                }
            }else{
                $seo_baidu_xzh['bing_auto'] =0;
            }
            if(isset($data['open'])){
                if($log){
                    $seo_baidu_xzh['js_auto'] =1;
                }else{
                    $seo_baidu_xzh['js_auto'] =0;
                }
            }else{
                $seo_baidu_xzh['js_auto'] =0;
            }
            if(isset($data['360_auto'])){
                if($log){
                    $seo_baidu_xzh['360_auto'] =1;
                }else{
                    $seo_baidu_xzh['360_auto'] =0;
                }
            }else{
                $seo_baidu_xzh['360_auto'] =0;
            }
		}else{    	  	 			    				 		 
			 $seo_baidu_xzh =[    	 		  		    			    	
                'zz_url'=>esc_url($data['zz_url']),     	 			      	   	  	
                'auto'=>0,    	 					       		  	 
                'tokens'=>sanitize_text_field($data['tokens']),    			  		        	
                'js_auto'=>0,
                'key' =>sanitize_text_field($data['key']),
                '360_auto'=>0,
                'bing_auto'=>0,
                'toutiao_auto'=>0,
                'toutiao_key' =>sanitize_text_field($data['toutiao_key']),
                		  			    	   		 	
            ];     	  		      	  	   	
		} 
		if(isset($baiduseo_data_zz['book'])){
			$baidu = get_option($baiduseo_data_zz['book']);
            
            if($baidu!==false){
                $seo_baidu_xzh['zz_plan'] = $baidu['zz_plan'];
                
                update_option($baiduseo_data_zz['book'],$seo_baidu_xzh);
               
            }else{
                add_option($baiduseo_data_zz['book'],$seo_baidu_xzh);
            }  
            echo json_encode(['msg'=>1]);exit;
		}
	
        
    }
    public function BaiduSEO_zzts(){
        $data = $this->data;
        $urls = explode("\n",sanitize_textarea_field($data['url_zhan']));
        $this->urls = $urls;
        $this->BaiduSEO_data_zz();
        $this->baiduseo_bdzzts();
        $this->baiduseo_bdzzts_tishi();exit;
        
    }
    public function BaiduSEO_sitemap(){
        global $wpdb;
        $data = $this->data;
        if(!isset($_COOKIE['baiduseo_data_sitemap'])){
            $baiduseo_json = new baiduseo_json();
	        $baiduseo_data_sitemap = $baiduseo_json->baiduseo_sitemap();
	        if(!empty($baiduseo_data_sitemap)){
                setcookie('baiduseo_data_sitemap',json_encode($baiduseo_data_sitemap),time()+3600*24*30);
	        }
        }else{
            $baiduseo_data_sitemap = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_sitemap']),true);
        }
        $this->baiduseo_data_sitemap = $baiduseo_data_sitemap;
	    if(isset($baiduseo_data_sitemap['book'])){
			$sitemap = get_option($baiduseo_data_sitemap['book']);
		    if($sitemap){
		        $seo_baidu_sitemap = $sitemap;
		    }else{
		        $seo_baidu_sitemap = [];
		    }
			
			if(isset($data['close'])){  
				$seo_baidu_sitemap['site_auto'] =1;
			}else{
			    $seo_baidu_sitemap['site_auto'] = 0;
			}
			if(isset($data['plan'])){  
				$seo_baidu_sitemap['plan'] =1;
			}else{
			    $seo_baidu_sitemap['plan'] = 0;
			}
			if($this->log){
    			if(isset($data['open'])){
    			    $seo_baidu_sitemap['open'] =1;  
    			}else{
    				$seo_baidu_sitemap['open'] =0;
    			}
			}else{
			    if(isset($data['open'])){
    			    $pay = baiduseo_paymoney('/api/index/pay_money');
                    if(!$pay){
                		echo json_encode(['msg'=>3]);exit;
                	}
                    if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
                	}else{
                		echo json_encode(['msg'=>3]);exit;
                	}
    			}else{
    				$seo_baidu_sitemap['open'] =0;
    			}
			}
			$seo_baidu_sitemap['post_time'] = sanitize_text_field($data['post_time']);
			$seo_baidu_sitemap['page_time'] = sanitize_text_field($data['page_time']);
			$seo_baidu_sitemap['tag_time'] = sanitize_text_field($data['tag_time']);
			$seo_baidu_sitemap['level1'] = sanitize_text_field($data['level1']);
			$seo_baidu_sitemap['level2'] = sanitize_text_field($data['level2']);
			$seo_baidu_sitemap['level3'] = sanitize_text_field($data['level3']);
			if(isset($data['type1'])){
			    $seo_baidu_sitemap['type1'] =1;  
			}else{
				$seo_baidu_sitemap['type1'] =0;
			}
			if(isset($data['type2'])){
			    $seo_baidu_sitemap['type2'] =1;  
			}else{
				$seo_baidu_sitemap['type2'] =0;
			}
			if(isset($data['type3'])){
			    $seo_baidu_sitemap['type3'] =1;  
			}else{
				$seo_baidu_sitemap['type3'] =0;
			}
            if($sitemap){
	            update_option($baiduseo_data_sitemap['book'],$seo_baidu_sitemap);
	        }else{
	            add_option($baiduseo_data_sitemap['book'],$seo_baidu_sitemap);     
	        }
	        $this->page = (int)($data['page']);
	        $this->baiduseo_sitemapsc();
	    }else{
	        echo json_encode(['msg'=>3]);exit;
	    }
        
    }
    public function baiduseo_plan_sitemap($page){
        global $wpdb;
        
        $baiduseo_json = new baiduseo_json();
        $baiduseo_data_sitemap = $baiduseo_json->baiduseo_sitemap();
          
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
                    $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="page" order by post_date desc limit '.$start.', 49999',ARRAY_A);
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
                $tongguo =0;
                if($page==1){
                    if(!$article && !$tag && !$pages){
                        $tongguo =1;
                    }
                }else{
                    if(!$article && !$tag ||($type==2 && !$pages)){
                        $tongguo =1;
                    }
                }
                if($tongguo){
                      
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
            	            $html .="<li><a href='".get_tag_link($val["term_id"])."' title='{$val['name']}'>{$val['name']}</a></li>\n";
            	           
            	        }
                    }
        	        $xml .='</urlset>';
        	        $html .="</ol>\n</body>\n</html>";
        	        if($page>1){
                		$page1 = $page-1;
            		    file_put_contents('./sitemap'.$page1.'.xml',$xml);
    	        	    file_put_contents('./sitemap'.$page1.'.html',$html);
        	        }else{
        	            file_put_contents('./tag.html',$html_tag);
    	                file_put_contents('./sitemap.xml',$xml);
    	        	    file_put_contents('./sitemap.html',$html);
        	        }
        	        update_option($baiduseo_data_sitemap['book'],$data);
        	        $this->baiduseo_plan_sitemap(++$page);
        	        
            	        
                }
            }
        }
    }
    public function baiduseo_sitemapsc(){
        
        
        global $wpdb;
        $data = $this->data;
        $baiduseo_data_sitemap=$this->baiduseo_data_sitemap;
        $page = $this->page;
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
            
            if($page==1){
                if(!$article && !$tag && !$pages){
                   
                    echo json_encode(['msg'=>0]);exit;  
                }
            }else{
                if(!$article && !$tag ||($type==2 && !$pages)){
                   
                    echo json_encode(['msg'=>0]);exit;  
                }
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
	            file_put_contents('../tag.html',$html_tag);
	        	file_put_contents('../sitemap.xml',$xml);
	        	file_put_contents('../sitemap.html',$html);
	        }
	        update_option($baiduseo_data_sitemap['book'],$data);
	        echo json_encode(['msg'=>1,'num'=>$page,'open'=>$data['open']]);exit; 
        }else{
        	echo json_encode(['msg'=>0]);exit;  
        }
    }
    public function BaiduSEO_robot(){
        $data = $this->data;
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
			$currnetTime= current_time( 'Y/m/d H:i:s');
			if(isset($data['close'])){     	 	 			    	  	  	 
				$url =get_option('siteurl').'/robots.txt';   
				

                $robot = [        				     		 	  	
                    'robot'=>sanitize_textarea_field($data['robot']),     			   	     							
                    'time'=>$currnetTime,    	 	 			       			 	 
                    'url'=>$url,      	   	     			 		 	
                    'robot_auto'=>1    	     		       	 	  
                ];         	 	      	 	  	
                 file_put_contents('../robots.txt',sanitize_textarea_field($data['robot'])); 		 				      			 	 	
			}else{      				       	  		  
				$robot = [    	 	  			    			  	  
                    'robot'=>sanitize_textarea_field($data['robot']),    	 	  			    	 		 		 
                    'time'=>$currnetTime,    	 	    	    		     	
                    'robot_auto'=>0        	 	     			 	   
                ];     		  		       				 	 
                if(file_exists('../robots.txt')){    							      	 			 	
                   unlink('../robots.txt');     		   	     	 		  	
                }    	  	 	 	    	 	  	  
                     			  	      						 
			}    	   			     			 	   
		    $rootbot = get_option($baiduseo_data_robot['book']);
		    if($rootbot){
               update_option($baiduseo_data_robot['book'],$robot); 
            }else{
                add_option($baiduseo_data_robot['book'],$robot);
            }
            echo json_encode(['msg'=>1]);exit;   
	    }else{
	        echo json_encode(['msg'=>3]);exit; 
	    }
    }
    public function BaiduSEO_alt(){
        $data = $this->data;
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
		    $alt_seo = ['alt'=>(int)$data['alt'],'title'=>(int)$data['title']];
		    $alt = get_option($baiduseo_data_alt['book']);
            if($alt){
            	update_option($baiduseo_data_alt['book'],$alt_seo);
            }else{
                add_option($baiduseo_data_alt['book'],$alt_seo);
            } 
		    echo json_encode(['msg'=>1]);exit;
	    }else{
	        echo json_encode(['msg'=>3]);exit;
	    }
    }
    public function BaiduSEO_404(){
        $data = $this->data;
        if(isset($data['open'])){    	 					     	  		   
			$url_404=1;
			 file_put_contents('../404.html','<!DOCTYPE html>
            <html>
            <head>
            <meta charset="UTF-8">
            <title>System Error404</title>
            <meta name="robots" content="noindex,nofollow" />
            <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
            <style>
                    /* Base */
                    body {
                        color: #333;
                        font: 14px Verdana, "Helvetica Neue", helvetica, Arial, "Microsoft YaHei", sans-serif;
                        margin: 0;
                        padding: 0 20px 20px;
                        word-break: break-word;
                    }
                    h1{
                        margin: 10px 0 0;
                        font-size: 28px;
                        font-weight: 500;
                        line-height: 32px;
                    }
                    h2{
                        color: #4288ce;
                        font-weight: 400;
                        padding: 6px 0;
                        margin: 6px 0 0;
                        font-size: 18px;
                        border-bottom: 1px solid #eee;
                    }
                    h3.subheading {
                        color: #4288ce;
                        margin: 6px 0 0;
                        font-weight: 400;
                    }
                    h3{
                        margin: 12px;
                        font-size: 16px;
                        font-weight: bold;
                    }
                    abbr{
                        cursor: help;
                        text-decoration: underline;
                        text-decoration-style: dotted;
                    }
                    a{
                        color: #868686;
                        cursor: pointer;
                    }
                    a:hover{
                        text-decoration: underline;
                    }
                    .line-error{
                        background: #f8cbcb;
                    }
            
                    .echo table {
                        width: 100%;
                    }
            
                    .echo pre {
                        padding: 16px;
                        overflow: auto;
                        font-size: 85%;
                        line-height: 1.45;
                        background-color: #f7f7f7;
                        border: 0;
                        border-radius: 3px;
                        font-family: Consolas, "Liberation Mono", Menlo, Courier, monospace;
                    }
            
                    .echo pre > pre {
                        padding: 0;
                        margin: 0;
                    }
                    /* Layout */
                    .col-md-3 {
                        width: 25%;
                    }
                    .col-md-9 {
                        width: 75%;
                    }
                    [class^="col-md-"] {
                        float: left;
                    }
                    .clearfix {
                        clear:both;
                    }
                    @media only screen 
                    and (min-device-width : 375px) 
                    and (max-device-width : 667px) { 
                        .col-md-3,
                        .col-md-9 {
                            width: 100%;
                        }
                    }
                    /* Exception Info */
                    .exception {
                        margin-top: 20px;
                    }
                    .exception .message{
                        padding: 12px;
                        border: 1px solid #ddd;
                        border-bottom: 0 none;
                        line-height: 18px;
                        font-size:16px;
                        border-top-left-radius: 4px;
                        border-top-right-radius: 4px;
                        font-family: Consolas,"Liberation Mono",Courier,Verdana,"微软雅黑";
                    }
            
                    .exception .code{
                        float: left;
                        text-align: center;
                        color: #fff;
                        margin-right: 12px;
                        padding: 16px;
                        border-radius: 4px;
                        background: #999;
                    }
                    .exception .source-code{
                        padding: 6px;
                        border: 1px solid #ddd;
            
                        background: #f9f9f9;
                        overflow-x: auto;
            
                    }
                    .exception .source-code pre{
                        margin: 0;
                    }
                    .exception .source-code pre ol{
                        margin: 0;
                        color: #4288ce;
                        display: inline-block;
                        min-width: 100%;
                        box-sizing: border-box;
                    font-size:14px;
                        font-family: "Century Gothic",Consolas,"Liberation Mono",Courier,Verdana;
                        padding-left: 40px;
                    }
                    .exception .source-code pre li{
                        border-left: 1px solid #ddd;
                        height: 18px;
                        line-height: 18px;
                    }
                    .exception .source-code pre code{
                        color: #333;
                        height: 100%;
                        display: inline-block;
                        border-left: 1px solid #fff;
                    font-size:14px;
                        font-family: Consolas,"Liberation Mono",Courier,Verdana,"微软雅黑";
                    }
                    .exception .trace{
                        padding: 6px;
                        border: 1px solid #ddd;
                        border-top: 0 none;
                        line-height: 16px;
                    font-size:14px;
                        font-family: Consolas,"Liberation Mono",Courier,Verdana,"微软雅黑";
                    }
                    .exception .trace ol{
                        margin: 12px;
                    }
                    .exception .trace ol li{
                        padding: 2px 4px;
                    }
                    .exception div:last-child{
                        border-bottom-left-radius: 4px;
                        border-bottom-right-radius: 4px;
                    }
            
                    /* Exception Variables */
                    .exception-var table{
                        width: 100%;
                        margin: 12px 0;
                        box-sizing: border-box;
                        table-layout:fixed;
                        word-wrap:break-word;            
                    }
                    .exception-var table caption{
                        text-align: left;
                        font-size: 16px;
                        font-weight: bold;
                        padding: 6px 0;
                    }
                    .exception-var table caption small{
                        font-weight: 300;
                        display: inline-block;
                        margin-left: 10px;
                        color: #ccc;
                    }
                    .exception-var table tbody{
                        font-size: 13px;
                        font-family: Consolas,"Liberation Mono",Courier,"微软雅黑";
                    }
                    .exception-var table td{
                        padding: 0 6px;
                        vertical-align: top;
                        word-break: break-all;
                    }
                    .exception-var table td:first-child{
                        width: 28%;
                        font-weight: bold;
                        white-space: nowrap;
                    }
                    .exception-var table td pre{
                        margin: 0;
                    }
            
                    /* Copyright Info */
                    .copyright{
                        margin-top: 24px;
                        padding: 12px 0;
                        border-top: 1px solid #eee;
                    }
            
                    /* SPAN elements with the classes below are added by prettyprint. */
                    pre.prettyprint .pln { color: #000 }  /* plain text */
                    pre.prettyprint .str { color: #080 }  /* string content */
                    pre.prettyprint .kwd { color: #008 }  /* a keyword */
                    pre.prettyprint .com { color: #800 }  /* a comment */
                    pre.prettyprint .typ { color: #606 }  /* a type name */
                    pre.prettyprint .lit { color: #066 }  /* a literal value */
                    /* punctuation, lisp open bracket, lisp close bracket */
                    pre.prettyprint .pun, pre.prettyprint .opn, pre.prettyprint .clo { color: #660 }
                    pre.prettyprint .tag { color: #008 }  /* a markup tag name */
                    pre.prettyprint .atn { color: #606 }  /* a markup attribute name */
                    pre.prettyprint .atv { color: #080 }  /* a markup attribute value */
                    pre.prettyprint .dec, pre.prettyprint .var { color: #606 }  /* a declaration; a variable name */
                    pre.prettyprint .fun { color: red }  /* a function name */
                </style>
            </head>
            <body>
            <div class="echo">
            <script>setTimeout(function (){location.href="/";},2000);</script> </div>
            <div class="exception">
            <div class="info"><h1>404页面提醒您，该页面不存在！</h1></div>
            </div>
            <div class="copyright">
            </div>
            </body>
            </html>' );
		}else{     	 		 		    	 	 	 		
			$url_404=0;       			 	     	  			 
		}
		$seo_301_404_url = get_option('seo_301_404_url');
		if($seo_301_404_url){
			update_option('seo_301_404_url',['404_url'=>$url_404]);
		}else{
			add_option('seo_301_404_url',['404_url'=>$url_404]);
		}
        echo json_encode(['msg'=>1]);exit;

    }
    public function BaiduSEO_sl(){
        global $wpdb;
        $data = $this->data;
        
        if($data['num']==1){
            $pay = baiduseo_paymoney('/api/index/pay_money');
            if(!$pay){
        		echo json_encode(['msg'=>3]);exit;
        	}
            if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
        	}else{
        		echo json_encode(['msg'=>3]);exit;
        	}
        }
        if(isset($data['seo_url_sl']) && isset($data['seo'])){    						      								
		$num = (int)$data['page']*((int)$data['num']-1); 
		
    	$post = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where seo_baidu_sl=0 and post_status="publish" and post_type="post"',ARRAY_A);
		$count = count($post);
		
		$article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where seo_baidu_sl=0 and post_status="publish" and post_type="post"  limit '.$num.' ,'.(int)$data['page'],ARRAY_A);
		if($article){    	 	   		       	  	 
			foreach($article as $key=>$val){    		 			        						
			    		 	  	        	 		 
	    		$url = get_permalink($val['ID']);    	  		          	 	  
	             	 	       	  			
	            if($this->log){
    				if($this->baiduseo_baiduquery($url,$val['post_title'])){    		  		 	    	   	 		
    			        $wpdb->update($wpdb->prefix . 'posts',['seo_baidu_sl'=>1],['ID'=>$val['ID']]);
    				}  
	            }
			    	    			         			
	    	}     	 			       		  	 	
	    	echo json_encode(['msg'=>1,'num'=>(int)$data['num'],'percent'=>round(100*($num+count($article))/$count,2).'%']);exit;    		   			    	    	  
		}else{     					 	     				 		
			echo json_encode(['msg'=>0]);exit;    	 					     	  	    
		}     	 				     	 			 		
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
                'timeout' => 300,
                'redirection' => 300,
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
    
            if( !is_wp_error($http) && isset($http['response']['code']) && isset($http['body']) && 200== $http['response']['code'] && preg_match('#<title>百度安全验证</title>#is',$http['body'])){
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
    public function BaiduSEO_zz_plts(){
        $data = $this->data;
        if($data['num']==1){
            $pay = baiduseo_paymoney('/api/index/pay_money');
            if(!$pay){
        		echo json_encode(['msg'=>3]);exit;
        	}
            if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
        	}else{
        		echo json_encode(['msg'=>3]);exit;
        	}
        }
        if($data['plts']==1){    	 	 			     	 		 			
            if($data['type']=='zz'){    		     	     	 	 	  
                if(isset($data['wsl']) && $data['wsl']){  
                    $log = $this->log;
                    $this->wsl = 1;
                    if($log){
                        $this->BaiduSEO_plts_wsl();
                    }
                     		  	      			 	 		
                }else{
                    $this->wsl = 0;
                    $log = $this->log;
                    if($log){
                        $this->BaiduSEO_plts_all();
                    }
                          	 	    				  	 
                }        				       	 		 
            }
        }
       
    }
    public function BaiduSEO_dayts(){
        $data = $this->data;
        $log = $this->log;
        $this->post_id = (int)$data['id'];
        $url = get_permalink((int)$data['id']);
        $urls =explode(',',$url);

        $urls = array_map('esc_url',$urls);
        $this->urls = $urls;
        $this->baiduseo_data_zz();
        $this->baiduseo_bddayts();
        $this->BaiduSEO_dayts_tishi();
    }
    public function BaiduSEO_dayts_tishi(){
        global $wpdb;
        $result = $this->result;
        $urls = $this->urls;
        $post_id = $this->post_id;
        if(is_wp_error($result)){
            echo json_encode(['msg'=>"推送失败，服务器网络波动，请稍后重试",'status'=>0]);exit;
        }
        $result = wp_remote_retrieve_body($result);
        $res = json_decode($result,true);
        if(isset($res['error'])){
    		if($res['message']=='over quota'){
                echo json_encode(['msg'=>'当日配额已用完！','status'=>0]);exit;
            }else{
                echo json_encode(['msg'=>$res['message'],'status'=>0]);exit;
            }
        }elseif(isset($res['success'])){
            if(isset($res['not_same_site'])){
                $not_same_site = implode('\n',$res['not_same_site']);
                echo json_encode(['msg'=>"推送失败，原因是存在不是本站url:{$not_same_site}",'status'=>0]);exit;
            }elseif(isset($res['not_valid'])){
                $not_valid = implode('\n',$res['not_valid']);
                echo json_encode(['msg'=>"推送失败，原因是不合法的url:{$not_valid}",'status'=>0]);exit;
            }else{
            	$currnetTime= current_time( 'Y/m/d H:i:s');
                $data_array=[
                    'time' => $currnetTime,
                    'post_id'=>intval($post_id),
                    'link' => $urls[0]
                ];
    			if($res['remain_daily']==0){
    				echo json_encode(['msg'=>"配额超出，请勿重复提交！",'status'=>1]);exit;
    			}else{
    			    $baiduseo_dayts_num = get_option('baiduseo_dayts_num');
                     if($baiduseo_dayts_num){
                         update_option('baiduseo_dayts_num',['num'=>$baiduseo_dayts_num['num']+1]);
                     }else{
                         add_option('baiduseo_dayts_num',['num'=>1]);
                     }
    				$wpdb->update($wpdb->prefix . 'posts',['baiduseo_ts'=>1],['ID'=>$post_id]);
                	echo json_encode(['msg'=>"推送成功,剩余配额：{$res['remain_daily']}条",'status'=>1]);exit;
    			}
            }
        }
    }
    public function BaiduSEO_zhizhu(){
        $pay = baiduseo_paymoney('/api/index/pay_money');
        if(!$pay){
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo json_encode(['msg'=>3]);exit;
    	}
        $data = $this->data;
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
    		if(isset($data['close'])){
    			$baiduseo_zhizhu['auto'] = 1;
    		}else{
    			$baiduseo_zhizhu['auto'] = 0;
    		}
    		if($zhizhu){
    			update_option($baiduseo_data_zhizhu['book'],$baiduseo_zhizhu);
    		}else{
    			add_option($baiduseo_data_zhizhu['book'],$baiduseo_zhizhu);
    		}
    		echo json_encode(['msg'=>1]);exit; 
        }else{
            echo json_encode(['msg'=>3]);exit; 
        }
       
    }
    public function BaiduSEO_zhizhu_id_delete(){
        global $wpdb;
        $data = $this->data;
        $zhizhu = $wpdb->get_results('select * from '.$wpdb->prefix . 'baiduseo_zhizhu where id='.$data['id'],ARRAY_A);
       
        $res = $wpdb->query( "DELETE FROM " . $wpdb->prefix . "baiduseo_zhizhu where address='".$zhizhu[0]['address']."'" ); 
        echo json_encode(['msg'=>1]);exit; 
    }
    public function BaiduSEO_zhizhu_delete(){
        global $wpdb;
        $pay = baiduseo_paymoney('/api/index/pay_money');
        $log = $this->log;
        if(!$pay){
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo json_encode(['msg'=>3]);exit;
    	}
        $data = $this->data;
        if(!isset($_COOKIE['baiduseo_data_zhizhu'])){
            $baiduseo_json = new baiduseo_json();
	        $baiduseo_data_zhizhu = $baiduseo_json->baiduseo_zhizhu();
	        if(!empty($baiduseo_data_zhizhu)){
                setcookie('baiduseo_data_zhizhu',json_encode($baiduseo_data_zhizhu),time()+3600*24*30);
	        }
        }else{
            $baiduseo_data_zhizhu = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zhizhu']),true);
        }

	    if(isset($baiduseo_data_zhizhu['type']) && $baiduseo_data_zhizhu['type']){
	        if($log){
			    $res = $wpdb->query( "DELETE FROM " . $wpdb->prefix . "baiduseo_zhizhu  " );  
	        }
			echo json_encode(['msg'=>1]);exit; 
			
	    }
		
	    echo json_encode(['msg'=>3]);exit; 
        
    }
    public function BaiduSEO_silian(){
        $data = $this->data;
        $pay = baiduseo_paymoney('/api/index/pay_money');
        if(isset($data['silian_kaiguan'])){
            $silian_kaiguan =1;
        }else{
            $silian_kaiguan =0;
        }
        $BaiduSEO_silian_kg = get_option('BaiduSEO_silian_kg');
        if($BaiduSEO_silian_kg!==false){
            update_option('BaiduSEO_silian_kg',$silian_kaiguan);
        }else{
            add_option('BaiduSEO_silian_kg',$silian_kaiguan);
        }
        $log = $this->log;
        if(!$pay){
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($log){
            if($silian_kaiguan==0){
                echo json_encode(['msg'=>1]);exit;
            }else{
                $this->baiduseo_siliansc();exit;
            }
        }
    }
    public function baiduseo_siliansc($type=0){
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
            	foreach($zhizhu as $key=>$val){
                    $xml .= "<url>\n";
                    $xml .= "<loc>".htmlspecialchars($val['address'])."</loc>\n";
                    $xml .= "</url>\n";
                    $txt .=$val['address']."\n";
                }
                
                $xml .= "</urlset>\n";
                if($type==0){
                    if($i==0){
                         $data['silian_url'][] = get_option('siteurl'). '/silian.xml';
                         $data['silian_htmlurl'][] = get_option('siteurl'). '/silian.txt';
                        file_put_contents('../silian.xml',$xml);
                        file_put_contents('../silian.txt',$txt);
                    }else{
                        $data['silian_url'][] = get_option('siteurl'). '/silian'.$i.'.xml';
                        $data['silian_htmlurl'][] = get_option('siteurl'). '/silian'.$i.'.txt';
                        file_put_contents('../silian'.$i.'.xml',$xml);
                        file_put_contents('../silian'.$i.'.txt',$txt);
                    }
                }elseif($type==1){
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
                
                
            }
            if($silian!==false){
                update_option($baiduseo_data_silian['book'],$data);
            }else{
                add_option($baiduseo_data_silian['book'],$data);
            }
            echo json_encode(['msg'=>1]);exit;
        }else{
            echo json_encode(['msg'=>0]);exit;
        }
    }
    public function BaiduSEO_301(){
        $data = $this->data;
        $defaults = array(
	        'timeout' => 300,
	        'redirection' => 300,
	        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
	        'sslverify' => FALSE,
	    );
	    $search_url =str_replace('www.','', get_option('siteurl'));
	   // var_dump($search_url);exit;
	    $http = wp_remote_get($search_url,$defaults);
	   
	    if(is_wp_error($http)){
	        echo json_encode(['msg'=>0]);exit;
	    }
	    $http = (array)$http['http_response'];

	    if(strpos($http["\0*\0response"]->history[0]->raw,'301 Moved Permanently') !== false){ 
	        if(strpos($http["\0*\0response"]->history[0]->raw,'www') !== false){ 
                echo json_encode(['msg'=>'恭喜您，您的301状态正常，无需设置！','status'=>1]);exit; 
            }else{
             echo json_encode(['msg'=>0]);exit; 
            }
            
        }else{
            echo json_encode(['msg'=>0]);exit; 
        }
       
    }
    public function BaiduSEO_keywords_add(){
        global $wpdb;
        $pay = baiduseo_paymoney('/api/index/pay_money');
        $log = $this->log;
        if(!$pay){
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo json_encode(['msg'=>3]);exit;
    	}
        $data = $this->data;
        $keywords = sanitize_text_field($data['keywords']);
		$list = $wpdb->query(' select * from  '.$wpdb->prefix.'baiduseo_keywords where type="'.(int)$data['type'].'"');
			$defaults = array(
	        'timeout' => 100,
	        'redirection' => 300,
	        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
	        'sslverify' => FALSE,
	    );
		if($list>9){
			$url = 'http://wp.seohnzz.com/api/keywords/num';
			$result = wp_remote_get($url,$defaults);
			$content = wp_remote_retrieve_body($result);
			if($list>$content-1){
				echo json_encode(['msg'=>4]);exit;
			}
		}
		if($log){
		    $re = $wpdb->query(' select * from  '.$wpdb->prefix.'baiduseo_keywords where keywords="'.$keywords.'" and type="'.(int)$data['type'].'"');
		}
		if($re>=1){
			echo json_encode(['msg'=>5]);exit;
		}
		 $currnetTime= current_time( 'Y/m/d H:i:s');
		$res = $wpdb->insert($wpdb->prefix."baiduseo_keywords",['post_time'=>$currnetTime,'keywords'=>$keywords,'type'=>(int)$data['type']]);
		
		$ids = $wpdb->get_results(' select * from  '.$wpdb->prefix.'baiduseo_keywords order by ID desc');
		$id = $ids[0]->id;
		if(!$res){
			echo  json_encode(['msg'=>0]);exit;
		}
	
	    $url = 'http://wp.seohnzz.com/api/keywords/log?url='.get_option('siteurl').'&keywords='.$keywords.'&type='.(int)$data['type'];
	    
		$result = wp_remote_get($url,$defaults);
		if(is_wp_error($result)){
			$wpdb->query( "DELETE FROM " . $wpdb->prefix . "baiduseo_keywords where id=  ".$id );  
			echo  json_encode(['msg'=>0]);exit;
		}
		$content = wp_remote_retrieve_body($result);

		if($content){
			echo  json_encode(['msg'=>1]);exit;
		}else{
			$wpdb->query( "DELETE FROM " . $wpdb->prefix . "baiduseo_keywords where id=  ".$id );  
			echo  json_encode(['msg'=>0]);exit;
		}
       
    }
    public function BaiduSEO_keywords_delete(){
        global $wpdb;
        $pay = baiduseo_paymoney('/api/index/pay_money');
        if(!$pay){
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo json_encode(['msg'=>3]);exit;
    	}
        $data = $this->data;
        $id = (int)$data['id'];
		$list = $wpdb->get_results(' select * from  '.$wpdb->prefix.'baiduseo_keywords where id='.$id,ARRAY_A);
		$res = $wpdb->query( "DELETE FROM " . $wpdb->prefix . "baiduseo_keywords where id=  ".$id );
		if($res){
			$defaults = array(
		        'timeout' => 100,
		        'redirection' => 300,
		        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
		        'sslverify' => FALSE,
		    );
			$url = 'http://wp.seohnzz.com/api/keywords/delete?url='.get_option('siteurl').'&keywords='.$list[0]['keywords'].'&type='.$list[0]['type'];
			wp_remote_get($url,$defaults);
			echo  json_encode(['msg'=>'删除成功','status'=>1]);exit;
		}else{
			echo  json_encode(['msg'=>'删除失败,请稍后重试','status'=>0]);exit;
		}
        
    }
    public function BaiduSEO_tag(){
        $pay = baiduseo_paymoney('/api/index/pay_money');
        $log = $this->log;
        if(!$pay){
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo json_encode(['msg'=>3]);exit;
    	}
        $data = $this->data;
        if(isset($data['open'])){  
			$tag['open']=1;   	
		}else{
			$tag['open']=0;   
		}
		$tag['num'] = (int)$data['num'];
		$tag['bold'] = sanitize_text_field($data['bold']);
		$tag['color'] = sanitize_text_field($data['color']);
		if($log){
    		if(isset($data['auto'])){
    		   $tag['auto']=1;   
    		}else{
    		   $tag['auto']=0;  
    		}
		}
        $baiduseo_link = new baiduseo_link(['http'=>'','url'=>BAIDUSEO_URL,'keywords'=>'baidutag']);
        $baiduseo_data_tag = $baiduseo_link->data();
	    if(isset($baiduseo_data_tag['book'])){
			$baiduseo_tag_manage = get_option($baiduseo_data_tag['book']);
			if($baiduseo_tag_manage){
				update_option($baiduseo_data_tag['book'],$tag);
			}else{
				add_option($baiduseo_data_tag['book'],$tag);
			}
		  			   	     					 		
			echo json_encode(['msg'=>1]);exit;
	    }else{
	        echo json_encode(['msg'=>0]);exit;
	    }
    }
    public function BaiduSEO_tag_pladd(){
        global $wpdb;
        $pay = baiduseo_paymoney('/api/index/pay_money');
        $log = $this->log;
        if(!$pay){
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo json_encode(['msg'=>3]);exit;
    	}
        $data = $this->data;
        if(!$data['content']){
            echo json_encode(['msg'=>0]);exit;
        }
        $content = explode("\n",sanitize_textarea_field($data['content']));
		if($content){
		    foreach($content as $key=>$val){
		        $tag = explode(',',$val);
		        if(isset($tag[1])){
		            if($log){
		                $terms = $wpdb->get_results('select * from '.$wpdb->prefix . 'terms where name="'.$tag[0].'" and tag_link  !=""',ARRAY_A);
		                if(!$terms){
		                    $res = $wpdb->insert($wpdb->prefix."terms",['name'=>$tag[0],'tag_link'=>$tag[1]]);
		                }
		            }else{
		                echo json_encode(['msg'=>1]);exit;
		            }
		        }else{
		           $terms = $wpdb->get_results('select a.* from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag" and a.name="'.$tag[0].'" and a.tag_link="" ',ARRAY_A);
		           if(!$terms){
		               if($log){
		               $res = $wpdb->insert($wpdb->prefix."terms",['name'=>$tag[0]]);
		               }else{
		                   echo json_encode(['msg'=>1]);exit;
		               }
		           }
		        }
                if($res){ 
                    if(!isset($tag[1])){
	                	$id = $wpdb->insert_id;
	                	
	                	$wpdb->update($wpdb->prefix . 'terms',['slug'=>$id],['term_id'=>$id]);
	                	$wpdb->insert($wpdb->prefix."term_taxonomy",['term_id'=>$id,'taxonomy'=>'post_tag']);
	                
                		$id_1 = $wpdb->insert_id;
	                	$baiduseo_tag_manage = get_option('baiduseo_tag_manage');
                    	if($baiduseo_tag_manage){
                    	    
                    	    if(isset($baiduseo_tag_manage['auto']) && $baiduseo_tag_manage['auto']){
                    	        $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where  post_status="publish" and post_type="post" order by ID desc limit 10000',ARRAY_A);
                                if(!isset($baiduseo_tag_manage['num']) || !$baiduseo_tag_manage['num']){
                                    
                                	foreach($article as $k=>$v){
                                	    $this->str = $tag[0];
                                		if(preg_match('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i',$v['post_content'],$matches))
                	                	{
                	                		$wpdb->insert($wpdb->prefix."term_relationships",['object_id'=>$v['ID'],'term_taxonomy_id'=>$id_1]);	
                	                		$term_taxonomy = $wpdb->get_results('select * from '.$wpdb->prefix . 'term_taxonomy where  term_taxonomy_id='.$id_1 ,ARRAY_A);
                        	                		
                        	                $count = $term_taxonomy[0]['count']+1;
                        	                $res = $wpdb->update($wpdb->prefix . 'term_taxonomy',['count'=>$count],['term_taxonomy_id'=>$id_1]);
                	                	}
                                	}
                                }else{
                                    foreach($article as $k=>$v){
                                        $shu = $wpdb->query('select * from '.$wpdb->prefix .'term_relationships as a left join '.$wpdb->prefix .'term_taxonomy as b on a.term_taxonomy_id=b.term_taxonomy_id where b.taxonomy="post_tag" and a.object_id='.$v['ID'],ARRAY_A);
                                        if($shu>=$baiduseo_tag_manage['num']){
                                            break;
                                        }else{
                                            $this->str = $tag[0];
                                            if(preg_match('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i',$v['post_content'],$matches))
                    	                	{
                    	                		$wpdb->insert($wpdb->prefix."term_relationships",['object_id'=>$v['ID'],'term_taxonomy_id'=>$id_1]);	
                    	                		$term_taxonomy = $wpdb->get_results('select * from '.$wpdb->prefix . 'term_taxonomy where  term_taxonomy_id='.$id_1 ,ARRAY_A);
                            	                		
                            	                $count = $term_taxonomy[0]['count']+1;
                            	                $res = $wpdb->update($wpdb->prefix . 'term_taxonomy',['count'=>$count],['term_taxonomy_id'=>$id_1]);
                    	                	}
                                        }
                                    }
                                }
                    	    }
                    	}
	                }
                }   	
		    }
		    echo json_encode(['msg'=>1]);exit;
		}
		
		echo json_encode(['msg'=>0]);exit; 
    }
    public function BaiduSEO_preg(){
	    $str = $this->str;
	    $str=strtolower(trim($str));
    	$replace=array('\\','+','*','?','[','^',']','$','(',')','{','}','=','!','<','>','|',':','-',';','\'','\"','/','%','&','_','`');
        return str_replace($replace,"",$str);
	}
    public function BaiduSEO_day_open(){
        $data = $this->data;
        if(isset($data['close'])){	  
			$list['auto'] = 1;	 
		}else{				 		
			$list['auto'] = 0;   	     			  		 
		} 
		if(isset($data['plan'])){
		    $list['plan'] = 1;	
		}else{
		    $list['plan'] = 0;
		}
        $pay = baiduseo_paymoney('/api/index/pay_money');
        if(!$pay){
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo json_encode(['msg'=>3]);exit;
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
        if(!$this->log){
            echo json_encode(['msg'=>0]);exit;
        }
        if(isset($baiduseo_data_zz['day'])){
			$baiduseo_day_ts = get_option($baiduseo_data_zz['day']);
			
			if($baiduseo_day_ts){
				update_option($baiduseo_data_zz['day'],$list);
			}else{
				add_option($baiduseo_data_zz['day'],$list);
			}
			echo json_encode(['msg'=>1]);exit;
        }else{
            echo json_encode(['msg'=>0]);exit;
        }
    }
    public function BaiduSEO_day_delete(){
        global $wpdb;
        $pay = baiduseo_paymoney('/api/index/pay_money');
        if(!$pay){
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo json_encode(['msg'=>3]);exit;
    	}
        $data = $this->data;
        if(!isset($_COOKIE['baiduseo_data_zz'])){
            $baiduseo_json = new baiduseo_json();
            $baiduseo_data_zz = $baiduseo_json->baiduseo_zz();
	        if(!empty($baiduseo_data_zz)){
                setcookie('baiduseo_data_zz',json_encode($baiduseo_data_zz),time()+3600*24*30);
	        }
        }else{
            $baiduseo_data_zz = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zz']),true);
            
        }
        if(isset($baiduseo_data_zz['type'])){
			 $wpdb->query('UPDATE '.$wpdb->prefix .'posts SET `baiduseo_ts`=0 WHERE 1');
		
			echo json_encode(['msg'=>1]);exit;
        }else{
            echo json_encode(['msg'=>0]);exit;
        }
        
    }
    public function BaiduSEO_tag_pl(){
        global $wpdb;
        $log = $this->log;
        $data = $this->data;
        $num = (int)$data['num'];
        if($num==1){
            $pay = baiduseo_paymoney('/api/index/pay_money');
            if(!$pay){
        		echo json_encode(['msg'=>3]);exit;
        	}
            if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
        	}else{
        		echo json_encode(['msg'=>3]);exit;
        	}
        }
		$page = (int)$data['page'];
		$tag_num = (int)$data['tag_num'];
		$article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post"',ARRAY_A);
		$total = count($article);
		$start = ($num-1)*$page;
		$list = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" limit '.$start.','.$page,ARRAY_A);
		if(!empty($list)){
		foreach($list as $key=>$val){
		    $tag_article = $wpdb->get_results('select a.* from '.$wpdb->prefix . 'term_relationships as a left join '.$wpdb->prefix.'term_taxonomy as b on a.term_taxonomy_id=b.term_taxonomy_id where  a.object_id='.$val['ID'].' and b.taxonomy="post_tag"' ,ARRAY_A);
		    if(!$log){
		        exit;
		    }
		    if(!empty($tag_article)){
		        $count = count($tag_article);
		    }else{
		        $count = 0;
		    }
		    if($count==$tag_num){
		       
		    }elseif($count<$tag_num){
		        
		        $tags=$wpdb->get_results('select * from '.$wpdb->prefix . 'terms',ARRAY_A);
                $nos =0;
                foreach($tags as $k=>$v){
                    
                    $term_taxonomy = $wpdb->get_results('select * from '.$wpdb->prefix . 'term_taxonomy where term_id=  '.$v['term_id'].' and 	taxonomy="post_tag"',ARRAY_A);
                  if(!empty($term_taxonomy)){
                   
                        $res = $wpdb->get_results('select * from '.$wpdb->prefix . 'term_relationships where object_id=  '.$val['ID'].' and term_taxonomy_id='.$term_taxonomy[0]['term_taxonomy_id'],ARRAY_A);
                        
                        if(empty($res)){
                           
                            if($nos<($tag_num-$count)){
                                
                                $this->str = $v['name'];
                        	    if(preg_match('{(?!((<.*?)|(<a.*?)))('.$this->BaiduSEO_preg().')(?!(([^<>]*?)>)|([^>]*?<\/a>))}i',get_post($val['ID'])->post_content,$matches))
                        		{
                        		    
                					$re = $wpdb->insert($wpdb->prefix."term_relationships",['object_id'=>$val['ID'],'term_taxonomy_id'=>$term_taxonomy[0]['term_taxonomy_id']]);
                					if($re){
                					    ++$nos;
                					}
                					$counts = $wpdb->query('select * from '.$wpdb->prefix . 'term_relationships where  term_taxonomy_id='.$term_taxonomy[0]['term_taxonomy_id'],ARRAY_A);

                	                $wpdb->update($wpdb->prefix . 'term_taxonomy',['count'=>$counts],['term_taxonomy_id'=>$term_taxonomy[0]['term_taxonomy_id']]);
                	                
                	                
                        		}
                            }
                        }
                    }
                }
		    }elseif($count>$tag_num){
		       
		        $no = 0;
		        foreach($tag_article as $k=>$v){
		     
		            if($no<($count-$tag_num)){
		                
		                $re = $wpdb->query( "DELETE FROM " . $wpdb->prefix . "term_relationships where object_id=  ".$v['object_id'].' and term_taxonomy_id='.$v['term_taxonomy_id'] );
		                if($re){
		                    ++$no;
		                }
		                $counts = $wpdb->query('select * from '.$wpdb->prefix . 'term_relationships where  term_taxonomy_id='.$v['term_taxonomy_id'],ARRAY_A);
    	                $wpdb->update($wpdb->prefix . 'term_taxonomy',['count'=>$counts],['term_taxonomy_id'=>$v['term_taxonomy_id']]);
		                
		            }
		            
		        }
		        
		    }
		}
	
		echo json_encode(['num'=>$num,'percent'=>round(100*($start+count($list))/$total,2).'%','page'=>$page,'tag_num'=>$tag_num,'status'=>1]);exit;
		}
	   	
		else{
		    echo json_encode(['msg'=>"操作完成",'status'=>0]);exit;
		}
       
    }
    public function BaiduSEO_cate_add(){
        $data = $this->data;
        $cate = (int)$data['cate'];
		$seo = ['keywords'=>sanitize_text_field($data['keywords']),'description'=>sanitize_textarea_field($data['description']),'title'=>sanitize_text_field($data['title'])];   
		$seo_init = get_option('baiduseo_cate_'.$cate);
		
        if($seo_init){
        	update_option('baiduseo_cate_'.$cate,$seo);
        }else{
        	add_option('baiduseo_cate_'.$cate,$seo);
    	} 
    	echo json_encode(['msg'=>1]);exit;
    }
    public function BaiduSEO_cate_open(){
        $pay = baiduseo_paymoney('/api/index/pay_money');
        if(!$pay){
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo json_encode(['msg'=>3]);exit;
    	}
        $data = $this->data;
        if(isset($data['open'])){
           $cate = ['istrue'=>1];
        }else{
           $cate = ['istrue'=>0];
        }
        $baiduseo_link = new baiduseo_link(['http'=>'','url'=>BAIDUSEO_URL,'keywords'=>'category']);
        $baiduseo_data_category = $baiduseo_link->data();
        if(isset($baiduseo_data_category['book'])){
           $seo_init = get_option($baiduseo_data_category['book']);
		
            if($seo_init){
            	update_option($baiduseo_data_category['book'],$cate);
            }else{
            	add_option($baiduseo_data_category['book'],$cate);
        	} 
        	echo json_encode(['msg'=>1]);exit;
        }else{
            echo json_encode(['msg'=>3]);exit;
        }
        
    }
    public function BaiduSEO_cate_list(){
        $data = $this->data;
        $cate = (int)$data['id'];
        $baiduseo_cate = get_option('baiduseo_cate_'.$cate);
        if($baiduseo_cate){
            echo json_encode(['msg'=>1,'cate'=>$baiduseo_cate]);exit;
        }else{
            echo json_encode(['msg'=>0]);exit;
        }
        
    }
    public function BaiduSEO_neilian(){
        global $wpdb;
        $pay = baiduseo_paymoney('/api/index/pay_money');
        if(!$pay){
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo json_encode(['msg'=>3]);exit;
    	}
        $data = $this->data;
        if($data['key']=="tag_target" ||$data['key']=="tag_nofollow" ){
		    if($data['value']!='是' && $data['value']!='否'){
		        echo json_encode(['msg'=>4]);exit;
		    }
		    if($data['value']=='是'){
		        $data['value']=1;
		    }else{
		        $data['value']=0;
		    }
		    
	    }
	    if($this->log){ 
            $res = $wpdb->update($wpdb->prefix . 'terms',[sanitize_text_field($data['key'])=>sanitize_text_field($data['value'])],['term_id'=>(int)$data['term_id']]);
	    }else{
	        $res = 0;
	    }
       if($res){
            echo json_encode(['msg'=>1]);exit;
       }else{
           echo json_encode(['msg'=>0]);exit;
       }
        
    }
    public function BaiduSEO_day_pl(){
        global $wpdb;
        $pay = baiduseo_paymoney('/api/index/pay_money');
        if(!$pay){
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo json_encode(['msg'=>3]);exit;
    	}
        $data = $this->data;
        $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where  post_status="publish" and post_type="post" and baiduseo_ts=0  order by ID desc limit 100',ARRAY_A);
        $this->baiduseo_data_zz();
        $baiduseo_data_zz = $this->baiduseo_data_zz;
		$baidu = get_option($baiduseo_data_zz['book']);
		if(!isset($baidu['zz_url']) || !isset($baidu['tokens']) || !$baidu['zz_url'] || !$baidu['tokens']){
		    echo json_encode(['msg'=>'请填写站长信息！','status'=>0]);exit;
		}
    	$api = "{$baiduseo_data_zz['url']}?{$baiduseo_data_zz['site']}={$baidu['zz_url']}&{$baiduseo_data_zz['token']}={$baidu['tokens']}&type=daily";
    	$count  = 0;
        foreach($article as $key=>$val){
            $result = wp_remote_post($api,['body'=> get_permalink($val["ID"])]);
            if(is_wp_error($result)){
                echo json_encode(['msg'=>'服务器网络波动，请稍后重试！','status'=>0]);exit;
            }
        	$result = wp_remote_retrieve_body($result);
            $res = json_decode($result,true);
            if(isset($res['error'])){
                if($res['message']=='over quota'){
                    echo json_encode(['msg'=>'当日配额已用完！','status'=>0]);exit;
                }else{
                    echo json_encode(['msg'=>$res['message'],'status'=>0]);exit;
                }
            	
            }elseif(isset($res['success'])){
                if(isset($res['not_same_site'])){
                    $not_same_site = implode('\n',$res['not_same_site']);
                    
                    echo json_encode(['msg'=>"推送失败，原因是存在不是本站url:{$not_same_site}",'status'=>0]);exit;
                    
                }elseif(isset($res['not_valid'])){
                    $not_valid = implode('\n',$res['not_valid']);
                    echo json_encode(['msg'=>"推送失败，原因是不合法的url:{$not_valid}",'status'=>0]);exit;
                    
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
            
        $currnetTime= current_time( 'Y/m/d H:i:s');
        $baiduseo_pltsdayts = get_option('baiduseo_pltsdayts');
        if($baiduseo_pltsdayts){
            update_option('baiduseo_pltsdayts',['time'=>$currnetTime,'count'=>$count]);
        }else{
            add_option('baiduseo_pltsdayts',['time'=>$currnetTime,'count'=>$count]);
        }
        echo json_encode(['msg'=>"推送成功{$count}条,剩余配额：{$res['remain_daily']}条",'status'=>1]);exit;
    }
    public function baiduseo_data_zz(){
        if(!isset($_COOKIE['baiduseo_data_zz'])){
            $baiduseo_json = new baiduseo_json();
            $baiduseo_data_zz = $baiduseo_json->baiduseo_zz();
            if(!$baiduseo_data_zz){
                setcookie('baiduseo_data_zz',json_encode($baiduseo_data_zz),time()+3600*24*30);
            }
        }else{
            $baiduseo_data_zz = json_decode(str_replace("\\",'',$_COOKIE['baiduseo_data_zz']),true);
        }
        $this->baiduseo_data_zz =$baiduseo_data_zz;
    }
    public function baiduseo_bdzzts(){
        $urls = $this->urls;
        $baiduseo_data_zz = $this->baiduseo_data_zz;
        $baidu = get_option($baiduseo_data_zz['book']);
        $api = "{$baiduseo_data_zz['url']}?{$baiduseo_data_zz['site']}={$baidu['zz_url']}&{$baiduseo_data_zz['token']}={$baidu['tokens']}";
        $result = wp_remote_post($api,['body'=>implode("\n", $urls)]);
       
        $this->result = $result;
    }
    public function baiduseo_bdzzts_tishi(){
        global $wpdb;
        $result = $this->result;
        $urls = $this->urls;
        if(is_wp_error($result)){
            echo json_encode(['msg'=>"推送失败，原因是服务器网络波动，请稍后重试",'status'=>0]);exit;
        }else{
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
                    echo json_encode(['msg'=>"推送成功，推送了{$res['success']}条,剩余配额{$res['remain']}条",'status'=>1]);exit;
                }
            }
            
        }
        
    }
    public function baiduseo_bdzzts_json(){
        global $wpdb;
        $result = $this->result;
        $data = $this->data;
        $num = (int)$data['num'];
        $page = (int)$data['page'];
        $no = $page*($num-1); 
        $wsl = $this->wsl;
        $count = $this->count;
        $article_num = $this->article_num;
        $urls = $this->urls;
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
            	echo json_encode(['msg'=>1,'num'=>$num,'percent'=>round(100*($no+$article_num)/$count,2).'%','shyu'=>$res['remain'],'status'=>1]);exit; 
            }
        }
    }
    public function BaiduSEO_plts_wsl(){
        global $wpdb;
        $pay = baiduseo_paymoney('/api/index/pay_money');
        
        if(!$pay){
    		echo json_encode(['msg'=>3]);exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo json_encode(['msg'=>3]);exit;
    	}
        $data = $this->data;
        $page = (int)$data['page'];
        $num = (int)$data['num'];
        $no = $page*($num-1); 
        $log = $this->log;
        $count = $wpdb->query('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" and  seo_baidu_sl=0',ARRAY_A);
        $this->count = $count;
    	$article =  $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" and  seo_baidu_sl=0 limit '.$no.' ,'.$page,ARRAY_A);
    	$this->article_num = count($article);
    	if($article){
        	$urls = [];
            foreach($article as $key=>$val){
                $urls[] = get_permalink($val["ID"]);
            }
            $this->urls = $urls;
            $this->baiduseo_data_zz();
            if($log){
                $this->baiduseo_bdzzts();
                $this->baiduseo_bdzzts_json();
            }
    	}else{
    	    echo json_encode(['msg'=>"推送成功，推送成功：{$count}条，剩余配额：{$data['shyu']}条",'status'=>0]);exit;
    	}
    }
    public function BaiduSEO_plts_all(){
        global $wpdb;
        $data = $this->data;
        $page = (int)$data['page'];
        
        $num = (int)$data['num'];

        $no = $page*($num-1); 
        
        $count = $wpdb->query('select ID from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post"',ARRAY_A);
        $count1 = $wpdb->query('select a.term_id from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag"',ARRAY_A);
        $count2 = $wpdb->query('select a.term_id from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="category"',ARRAY_A);
        $this->count = $count+$count1+$count2;
       
        if($no>=$count && $no<ceil($count/$page)*$page+$count1){
                $no1 = ($num-ceil($count/$page)-1)*$page;
                
                $tag = $wpdb->get_results('select a.term_id from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag"   limit '.$no1.', '.$page,ARRAY_A);
                $this->article_num = count($tag)-($no-$count-$no1);
                $urls = [];
                foreach($tag as $k=>$v){
                    $urls[] = get_tag_link($v['term_id']);
                }
                $this->urls = $urls;
                $this->baiduseo_data_zz();
                $this->baiduseo_bdzzts();
                $this->baiduseo_bdzzts_json();
        }elseif($no>=ceil($count/$page)*$page+$count1 && $no<ceil($count/$page)*$page+ceil($count1/$page)*$page+ceil($count2/$page)*$page-$page){
            $no2 = ($num-ceil($count/$page)-ceil($count1/$page)-1)*$page;
            $category = $wpdb->get_results('select a.term_id from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="category"   limit '.$no2.', '.$page,ARRAY_A);
            
            $this->article_num = count($category)-($no-$count-$count1-$no2);
            $urls = [];
            foreach($category as $k=>$v){
                $urls[] = get_category_link($v['term_id']);
            }
            $this->urls = $urls;
            $this->baiduseo_data_zz();
            $this->baiduseo_bdzzts();
            $this->baiduseo_bdzzts_json();
        }elseif($no<$count){
    	    $article = $wpdb->get_results('select * from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" limit '.$no.' ,'.$page,ARRAY_A);
    	    $this->article_num = count($article);
    	    $urls = [];
            foreach($article as $key=>$val){
                $urls[] = get_permalink($val["ID"]);
            }
            $this->urls = $urls;
            $this->baiduseo_data_zz();
            $this->baiduseo_bdzzts();
            $this->baiduseo_bdzzts_json();
        }else{
            echo json_encode(['msg'=>"推送成功，推送成功：{$this->count}条，剩余配额：{$data['shyu']}条",'status'=>0]);exit;
        }
    
    }
    public function baiduseo_bddayts(){
        $baiduseo_data_zz = $this->baiduseo_data_zz;
        $urls = $this->urls;
        
        $baidu = get_option($baiduseo_data_zz['book']);
    	$api = "{$baiduseo_data_zz['url']}?{$baiduseo_data_zz['site']}={$baidu['zz_url']}&{$baiduseo_data_zz['token']}={$baidu['tokens']}&type=daily";
    	$result = wp_remote_post($api,['body'=>implode("\n", $urls)]);
    	$this->result = $result;
    }
    public function baiduseo_plan_zz($str){
        global $wpdb;
        $pay = baiduseo_paymoney('/api/index/pay_money');
        if(!$pay){
    		echo '授权功能，请授权后使用';exit;
    	}
        if($pay['msg']==1 && $pay['url']== md5('www.seohnzz.com'.BAIDUSEO_SALT)){
    	}else{
    		echo '授权功能，请授权后使用';exit;
    	}
        $article = $wpdb->get_results('select ID from '.$wpdb->prefix . 'posts where post_status="publish" and post_type="post" ',ARRAY_A);
        $tag = $wpdb->get_results('select a.term_id from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="post_tag" ',ARRAY_A);
        $category = $wpdb->get_results('select a.term_id from '.$wpdb->prefix . 'terms as a left join '.$wpdb->prefix . 'term_taxonomy as b on a.term_id=b.term_id   where b.taxonomy="category" ',ARRAY_A);
        $urls = [];
        foreach($article as $key=>$val){
            $urls[] = get_permalink($val["ID"]);
        }
        foreach($tag as $k=>$v){
            $urls[] = get_tag_link($v['term_id']);
        }
        foreach($category as $k=>$v){
            $urls[] = get_category_link($v['term_id']);
        }
        $log = $this->log;
        if($log!=$str){
            exit;
        }
    	$count = count($urls);
    	if($count<2000){
    	    $this->urls = $urls;
    	    $this->BaiduSEO_data_zz();
    	    $this->baiduseo_bdzzts();
    	    $this->baiduseo_plan_zz_one();
    	}
    	for( $i=0;$i<ceil($count/2000);$i++){
    		$start = $i*2000;
    		$this->start = $start;
    		$url = array_slice($urls,$start,2000);
    		
    		    $this->urls = $url;
    		    $this->BaiduSEO_data_zz();
    		    $this->baiduseo_bdzzts();
    		    $this->baiduseo_plan_zz_num();
    	}
    }
    public function baiduseo_plan_zz_one(){
        global $wpdb;
        $result = $this->result;
        $urls = $this->urls;
        if(is_wp_error($result)){
           exit;
        }
        $result = wp_remote_retrieve_body($result);
        $res = json_decode($result,true);
        if(isset($res['error'])){
        	exit;
        }elseif(isset($res['success'])){
            if(isset($res['not_same_site'])){
                exit;
            }elseif(isset($res['not_valid'])){
                exit;
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

            	$data['zz_tsts']=$res['success'];
                $data['zz_kts']=$res['remain'];
                $data['time']= $currnetTime;
                $baidu = get_option('seo_baidu_zz_yjts');

                if($baidu){
                  update_option('seo_baidu_zz_yjts',$data);
                }else{
                  add_option('seo_baidu_zz_yjts',$data);
                }
	            exit;
            }
        }
    }
    public function baiduseo_plan_zz_num(){
        global $wpdb;
        $result = $this->result;
        $urls = $this->urls;
        $start = $this->start;
        if(is_wp_error($result)){
           exit;
        }
        $result = wp_remote_retrieve_body($result);
        $res = json_decode($result,true);
        if(isset($res['error'])){
        	exit;
        }elseif(isset($res['success'])){
            if(isset($res['not_same_site'])){
                exit;
            }elseif(isset($res['not_valid'])){
                exit;
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

            	$data['zz_tsts']=$res['success']+$start;
                $data['zz_kts']=$res['remain'];
                $data['time']= $currnetTime;
                $baidu = get_option('seo_baidu_zz_yjts');

                if($baidu){
                  update_option('seo_baidu_zz_yjts',$data);
                }else{
                  add_option('seo_baidu_zz_yjts',$data);
                }
            }
        }
    }
    
}
?>