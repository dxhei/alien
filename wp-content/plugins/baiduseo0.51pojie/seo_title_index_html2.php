<?php if(!defined('ABSPATH'))exit;?>
<div id="divMain">
  <div class="SubMenu">
  </div>
 <style>
  img[src=""],img:not([src]){
          opacity:0;

}
	#wpwrap{
		background-color:#fff;
	}
	.cGuide_content{
	    
	}
	.cGuide_content img{
	    width: 177% !important;
        position: absolute !important;
        right: -508px !important;
        top: 1px !important;
	}
	.customimg{
        position: absolute;
        top: 0px;
        left: 338px;
        width: 327px;
        padding: 20px;
        background: rgba(0,0,0,0.7);
        transition: all 0.3s;
	}
	.customimg:hover{
	    transform: scale(1.5);
	    cursor: pointer;
	    transition: all 0.6s;
	}
	.layui-form-onswitch {
	    border-color: #01AAED;
        background-color: #01AAED;
	}
	.layui-btn {
	    background-color: #01AAED;
	}
	.wzt_red {
	    color: red;
	}
	.layui-form-label {
	    width: 96px;
	}
	.layui-input-block {
	    margin-left: 126px;
	}
  </style>
  <div id="divMain2">
  	<!--插件开始-->
		<div class=" wyy_shouye">
			<div class=" wyy_shouye1">
				<ul>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=1">SEO首页/分类</a></li>
					<li class="admin"><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=2">站长信息</a></li>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=3">批量推送</a></li>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=4">快速收录</a></li>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=5">sitemap</a></li>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=6">robots</a></li>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=7">alt/tag内链</a></li>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=8">301/404/category</a></li>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=9">百度收录查询</a></li>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=10">网站蜘蛛</a></li>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=11">网站死链</a></li>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=15">排名词库</a></li>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=12">关键词排名</a></li>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=17">原创率检测</a></li>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=13">功能授权</a></li>
					<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=16">推荐插件</a></li>
				</ul>
			</div>
			<div class="wyy_shouye2">
				<a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo" style="margin-left: 12px;display: inline-block;padding: 8px 15px;background-color: #009688;border-radius:4px;color:#fff;">返回目录</a>
			</div>
		<div class="layui-tab-content wyy_shouye" style="width: 800px;">
		    <div id="startDemo" title="点击启动插件向导" class="point point-flicker">
			        <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/logo111.jpg">
			        <div class="shuoming">嗨，有不明白的地方可以点我！</div>
			</div>
			<div class="main_list" >
				<form class="layui-form" action="" onsubmit="return false">
				  	<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
					  <legend>百度信息</legend>
					</fieldset>
					<div>
        					<div class="layui-form-item">
        				         <label class="layui-form-label">域名</label>
            				     <div class="layui-input-block">
            				      <input type="text" name="zz_url"   autocomplete="off" placeholder="请输入域名" class="layui-input" value="<?php echo $seo_baidu_xzh['zz_url'] ?>">
            				     </div>
        				  </div>
        				  <div class="layui-form-item">
            				    <label class="layui-form-label">token</label>
            				    <div class="layui-input-block">
            				      <input id="box11" type="text" name="tokens"   autocomplete="off" placeholder="请输入token" class="layui-input" value="<?php echo $seo_baidu_xzh['tokens'] ?>">
            				    </div>
        				  </div>
				  </div>
				 
				  <div class="layui-form-item" >
				    <label class="layui-form-label">自动推送</label>
				    <div class="layui-input-block" id="box22">
				    	<?php 
				    		if(isset($seo_baidu_xzh['auto']) && ($seo_baidu_xzh['auto']==1)){    		 	 		       	echo '<input type="checkbox" name="close" lay-skin="switch" lay-text="开|关" checked="">';  				  
				    			 			 	 		       		  	
				    		}else{     		  	 	       		   
				    		  echo '<input type="checkbox" name="close" lay-skin="switch" lay-text="开|关">';   	  			 	     	  	   
				    		}      	         	       
				    	?>
				      <span style="vertical-align: -webkit-baseline-middle;">该选项，开启以后每当您发布文章，就会自动使用API接口功能通过向百度推送新发布的文章。</span>
				    </div>
				    
				  </div>
				  
				   
				  <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    				  <legend>bing信息</legend>
    			  </fieldset>
    			  <div>
					<div class="layui-form-item" >
				         <label class="layui-form-label">api密钥</label>
    				     <div class="layui-input-block">
    				      <input type="text" name="key" id="box33"  autocomplete="off" placeholder="请输入bing的key" class="layui-input" value="<?php if(isset($seo_baidu_xzh['key'])){echo $seo_baidu_xzh['key'];} ?>">
    				     </div>
				  </div>
				  <div class="layui-form-item" >
				    <label class="layui-form-label" >bing自动推送</label>
				    <div class="layui-input-block" id="box44">
				    	<?php 
				    		if(isset($seo_baidu_xzh['bing_auto']) && ($seo_baidu_xzh['bing_auto']==1)){    		 	 		  echo '<input type="checkbox" name="bing_auto" lay-skin="switch" lay-text="开|关" checked="">';       				  
				    			   			 	 		       		  	
				    		}else{     		  	 	       		   
				    			echo '<input type="checkbox" name="bing_auto" lay-skin="switch" lay-text="开|关">';  	  			 	     	  	   
				    		}      	         	       
				    	?>
				      
				    </div>
				  </div>
				  <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    				  <legend>头条信息</legend>
    			  </fieldset>
    			  <div class="layui-form-item" >
				         <label class="layui-form-label">头条密钥</label>
    				     <div class="layui-input-block">
    				      <input type="text" name="toutiao_key" id="box55"  autocomplete="off" placeholder="请输入头条的key" class="layui-input" value="<?php if(isset($seo_baidu_xzh['toutiao_key'])){echo $seo_baidu_xzh['toutiao_key'];} ?>">
    				     </div>
				  </div>
    			  <div class="layui-form-item" >
				    <label class="layui-form-label">头条js推送</label>
				    <div class="layui-input-block" >
				    	<?php 
				    		if(isset($seo_baidu_xzh['toutiao_auto']) && ($seo_baidu_xzh['toutiao_auto']==1)){    		 	 		  echo '<input type="checkbox" name="toutiao_auto" lay-skin="switch" lay-text="开|关" checked="">';       				  
				    			   			 	 		       		  	
				    		}else{     		  	 	       		   
				    			echo '<input type="checkbox" name="toutiao_auto" lay-skin="switch" lay-text="开|关">';  	  			 	     	  	   
				    		}      	         	       
				    	?>
				      
				    </div>
				  </div>
				   <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    				  <legend>360信息</legend>
    			  </fieldset>
				  <div class="layui-form-item" >
				    <label class="layui-form-label">360JS自动推送</label>
				    <div class="layui-input-block" id="box66" >
				    	<?php 
				    		if(isset($seo_baidu_xzh['360_auto']) && ($seo_baidu_xzh['360_auto']==1)){    		 	 		  echo '<input type="checkbox" name="360_auto" lay-skin="switch" lay-text="开|关" checked="">';       				  
				    			   			 	 		       		  	
				    		}else{     		  	 	       		   
				    			echo '<input type="checkbox" name="360_auto" lay-skin="switch" lay-text="开|关">';  	  			 	     	  	   
				    		}      	         	       
				    	?>
				      
				    </div>
				  </div>
				  <div class="layui-form-item layui-form-text" >
				    <div class="layui-input-block" >百度站长自动提交：<span class="wzt_red"><?php echo $zz_baidu; ?></span>条 </div>
				  </div>
				   <div class="layui-form-item layui-form-text">
				    <div class="layui-input-block">bing自动提交：<span class="wzt_red"><?php echo $baiduseo_bing; ?></span>条 </div>
				  </div>
				  <div class="layui-input-block">
				      360与头条属于是JS推送,数据不够严谨不在提供统计数据。
				  </div>
				  <div class="layui-form-item">
				  	<input type="hidden" name="BaiduSEO" value="2">
				  	<input type="hidden" name="action" value="BaiduSEO">
				  	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
				    <div class="layui-input-block">
				      <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo2" id="box77">保存</button>
				    </div>
				  </div>
				</form>
		    </div>
		    
		  </div>
		</div>
	<script>
		jQuery(document).ready(function($){
			layui.use(['form', 'layer',], function(){
			  var form = layui.form
			  ,layer = layui.layer;
			  form.on('submit(demo2)', function(data){
			       var index = layer.load(1, {
                  shade: [0.7,'#111'] //0.1透明度的白色背景
                });
			  	$.ajax({
			  		url:'',
			  		data:{data:JSON.stringify(data.field)},
			  		type:'post',
			  		dataType:'json',
			  		success:function(data){
			  		    layer.close(index);
			  			if(data.msg==1){
			  				layer.alert('保存成功');
			  			}else if(data.msg==3){
			  				layer.confirm('该功能，需要点击“确定”后登录官网进行授权才可正常使用。', {
							  btn: ['确定','取消'] //按钮
							}, function(){
							  window.open("https://www.rbzzz.com/qxcp.html",'top');
							}, function(){
							    
							});	
			  			}else{
			  				layer.msg('保存失败，请刷新后重试');
			  			}
			  		}
			  	})
			    return false;
			});
		});
	});
</script>
 
    	<ol id="joyRideTipContent">
        		<li data-id="box11" data-text="下一步" class="custom">
        			<h2>step #1</h2>
        			<p>填写内容请填写百度站长的API推送接口，在接口调用地址复制[域名]和[token]切勿整条链接复制过来。API地址所在位置：百度站长→普通收录→资源提交→API提交→接口调用地址. 百度站长地址：ziyuan.baidu.com
        			<img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/chsit1.png" class="customimg">   
        			</p>
        		</li>
        		<li data-id="box22" data-text="下一步">
        			<h2>step #2</h2>
        			<p>该选项，开启以后每当您发布文章，就会自动使用API接口功能通过向百度推送新发布的文章。</p>
        		</li>
        		<!--<li data-id="box33" data-text="下一步">-->
        		<!--	<h2>step #3</h2>-->
        		<!--	<p>百度站长拥有推送JS代码（这个代码是通用的），绑定以后每个页面都会加载JS百度推送代码，无需人工添加。</p>-->
        		<!--</li>-->
        		<li data-id="box33" data-text="下一步" class="custom">
        			<h2>step #3</h2>
        			<p>Bing上线了，没有注册的小伙伴，登录网站“https://www.bing.com/webmaster/home/mysites”进行注册Bing站长，然后进行绑定网站，跟百度站长操作差不多，记得他获取他的API接口秘钥，他的是在右上角，如图所示，复制那一串代码即可。
        			<img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/miyao.png" class="customimg">   
        			</p>
        		</li>
        		<li data-id="box44" data-text="下一步">
        			<h2>step #4</h2>
        			<p>必须绑定过Bing站长之后进行在进行开启，否则是无效的。另外这个是自动执行推送，每天去Bing站长查看数据即可。</p>
        		</li>
        		<li data-id="box55" data-text="下一步">
        			<h2>step #5</h2>
        			<p>该权限需要登录“zhanzhang.toutiao.com”注册头条站长，并且在“自动推送”JS提交栏目下截取图例内的内容粘贴即可。（注意只复制问号以后的内容引号不要复制。）</p>
        			<img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/toutiao.png" class="customimg">   
        		</li>
        		<li data-id="box66" data-text="下一步">
        			<h2>step #6</h2>
        			<p>该功能请登录360站长验证站点以后在开启该功能。</p>
        		</li>
        		<li data-id="box77" data-text="关闭">
        			<h2>step #7</h2>
        			<p>记得您每次的操作都要保存，否则不会生效哦。</p>
        		</li>
	      </ol>
        <script type="text/javascript">		
            jQuery(document).ready(function($){
                $("#startDemo").click(function(){
                   	$(this).joyride();
                })
                var shenchu = setInterval(function() {
			        shengchucd()
			    }, 5000);
            	$("#startDemo").hover(function(){
            	    clearInterval(shenchu);
    		        $(".shuoming").css({"right":"60px","width":"225px"})
    			},function(){
    			    shenchu = setInterval(function() {
    			        shengchucd()
    			    }, 5000);
    			})
			    var shengchucd = function() {
                    if($(".shuoming").css("right") == "10px") {
                        $(".shuoming").css({"right":"60px","width":"225px"})
                    }else {
                        $(".shuoming").css({"right":"10px","width":"0px"})
                    }
                }
            });
        </script>
  	<!--插件结束-->
<!--代码-->
  </div>
</div>