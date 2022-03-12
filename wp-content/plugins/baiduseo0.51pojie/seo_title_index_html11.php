<?php if(!defined('ABSPATH'))exit;?>
<div id="divMain">
  <div class="SubMenu">
  </div>
 <style>
	#wpwrap{
		background-color:#fff;
	}
	.layui-btn {
	    background-color: #01AAED;
	}
	.layui-form-onswitch {
	    border-color: #01AAED;
        background-color: #01AAED;
	}
	.layui-input-block {
	    margin-left: 114px;
	}
	.layui-form-label {
	    width: 84px;
	}
  </style>
  <div id="divMain2">
  	<!--插件开始-->
		<div class=" wyy_shouye1">
			<ul>
				<li ><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=1">SEO首页/分类</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=2">站长信息</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=3">批量推送</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=4">快速收录</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=5">sitemap</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=6">robots</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=7">alt/tag内链</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=8">301/404/category</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=9">百度收录查询</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=10">网站蜘蛛</a></li>
				<li class="admin"><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=11">网站死链</a></li>
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
		<div class="news" style="width: 1000px;float: left;">
		<div class="layui-tab-content">    

			<div class="main_list">
			    <div id="startDemo" title="点击启动插件向导" class="point point-flicker">
			        <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/logo111.jpg">
			        <div class="shuoming">嗨，有不明白的地方可以点我！</div>
			    </div>
				<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				  <legend>网站死链</legend>
				</fieldset>
				 	<div style='padding:20px 0px;margin-bottom:20px'>
				 	    <form class="layui-form" action="" onsubmit="return false">
				 	    <div class="layui-form-item">
				 	        <label class="layui-form-label">死链开关</label>
        				    <div class="layui-input-block">
        				    	<input type="hidden" name="BaiduSEO" value="13">
        				    	<input type="hidden" name="action" value="BaiduSEO">
        				    	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
        				        <!--<button type="submit" class="layui-btn" lay-submit="" lay-filter="demo13" >生成</button>-->
        				        <?php if($BaiduSEO_silian_kg==0){?>
        				       <input type="checkbox" name="silian_kaiguan" lay-skin="switch" lay-filter="switchTest" lay-text="开|关">
        				       <?php }else{?>
        				       <input type="checkbox" name="silian_kaiguan" lay-skin="switch" lay-filter="switchTest" checked="" lay-text="开|关">
        				       <?php }?>
        				    </div>
        				  </div>
        				  <?php if($BaiduSEO_silian_kg==1){?>
        				  <div class="biaoge" style="width: 83%;margin-left:11%;" id="box11" >
        				  <ul class="biaoge1">
        					  <li>死链的XML地址</li>
                             <?php 
        				  			if(isset($silian['silian_url']) && !empty($silian['silian_url'])){
        				  			   
        				  			    foreach($silian['silian_url'] as $k=>$v){
        				  			        
        				  			    echo '<li><input type="text" value="'.$v.'" class="neirong"><span onclick="copy(this)">点击复制</span></li>';
        				  			    }
        				  			}    	   	 	       	 		 	
        				   		?>
        				  </ul>
        				  <ul class="biaoge1">
        				       <li>死链的TXT地址</li>
        				       <?php 
        				  			if(isset($silian['silian_htmlurl']) && !empty($silian['silian_htmlurl'])){  
        				  			     foreach($silian['silian_htmlurl'] as $k=>$v){
        				  			    echo '<li><input type="text" value="'.$v.'" class="neirong"><span onclick="copy(this)">点击复制</span></li>';
        				  			     }
        				  			}       	   	      	  			
        				   		?>
        				  </ul>
        				  <ul class="biaoge1">
        				       <li>生成时间</li>
        				       	<?php 
        				  			if(isset($silian['time'])){    
        				  			    echo '<li>'.$silian['time'].'</li>';
        				  			}     	   	 	    	 		 		 
        				   		?>
        				      
        				  </ul>
        				  
        				</div>
        				<!-- 手机样式 -->
        				  <div class="biaoge2">
        					  <ul class="biaoge2-1">
        						  <li>死链的XML地址</li>
        						  <?php 
        				  			if(isset($silian['silian_url']) && !empty($silian['silian_url'])){
        				  			   
        				  			    foreach($silian['silian_url'] as $k=>$v){
        				  			    echo '<li><input type="text" value="'.$v.'" class="neirong"><span onclick="copy(this)">点击复制</span></li>';
        				  			    }
        				  			}    	   	 	       	 		 	
        				   		?>
        						  
        					  </ul>
        					  <ul class="biaoge2-1">
        						  <li>死链的HTML地址</li>
        						  <?php 
        				  			if(isset($silian['silian_htmlurl']) && !empty($silian['silian_htmlurl'])){  
        				  			     foreach($silian['silian_htmlurl'] as $k=>$v){
        				  			    echo '<li><input type="text" value="'.$v.'" class="neirong"><span onclick="copy(this)">点击复制</span></li>';
        				  			     }
        				   		 		           	  	  
        				  			}       	   	      	  			
        				   		?>
        					  
        					  </ul>
        					  <ul class="biaoge2-1">
        						  <li>生成时间</li>
        						  	<?php 
        				  			if(isset($silian['time'])){    
        				  			    echo '<li>'.$silian['time'].'</li>';
        				  			}     	   	 	    	 		 		 
        				   		?>
        					  </ul>
        				  </div>
        				  <?php }?>
        				  <div style="margin-left:110px;margin-top:5px;">死链地址，复制以后可以提交给百度站长或其他搜索引擎站长。</div>
        				  
                        <button type="submit" class="layui-btn xin" lay-submit="" lay-filter="demo13"  style="margin-left: 114px;margin-top: 10px;">保存</button>
        				  <div class="layui-progress layui-progress-big silian_solid" lay-showpercent="true" style="display:none">
        				  
        						 <div class="layui-progress layui-progress-big" lay-showpercent="true" lay-filter="demo1">
        						  <div class="layui-progress-bar layui-bg-red" lay-percent="1%"></div>
        						</div>
        					</div>
    					
        				  
        				</form>
        			 <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    				  <legend>方式一</legend>
    				</fieldset>
				 <div>
				     <div class="layui-input-block wyy_left" style="margin-top:30px;line-height: 60px;">计划任务:<button type="button" class="layui-btn get_plan_url" style="padding: 0 20px;">获取链接</button></div>
    				<div class="layui-input-block  wyy_left">注：死链是根据“网站蜘蛛”读取到的404页面所生成的死链，避免了每日查看网站日志去手动添加死链的烦恼，将生成的死链提交给百度站长。</div>
    				<div class="layui-input-block  wyy_left">（死链可以自动生成或手动生成）</div>
    				<div class="layui-input-block  wyy_left">计划任务需要配合宝塔定时触发：<a href="https://www.seohnzz.com/archives/135.html" target="_blank" >详细点击阅读</a></div>
				 </div>  
					<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    				  <legend>方式二</legend>
    				</fieldset>
				      <form class="layui-form" action="" onsubmit="return false">
				      <div class="layui-form-item" >
    				    <label class="layui-form-label">自动计划任务</label>
    				    <div class="layui-input-block">
    				    	<?php 
    				    		if(isset($seo_baidu_xzh['silian_plan']) && ($seo_baidu_xzh['silian_plan']==1)){    		 	 		       	echo '<input type="checkbox" name="silian_plan" lay-skin="switch" lay-text="开|关" checked="">';  				  
    				    			 			 	 		       		  	
    				    		}else{     		  	 	       		   
    				    		  echo '<input type="checkbox" name="silian_plan" lay-skin="switch" lay-text="开|关">';   	  			 	     	  	   
    				    		}      	         	       
    				    	?>
    				       <p style="margin-top:10px;">该方法适合没有宝塔的用户使用。如果开启该功能，请不要使用计划任务避免重复！</p>
    				    </div>
    				  </div>
    				  <div class="layui-form-item">
				    <div class="layui-input-block">
				    	<input type="hidden" name="BaiduSEO" value="33">
				    	<input type="hidden" name="action" value="BaiduSEO">
				    	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
				      <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo4">保存</button>
				    </div>
				  </div>
				  </form>
				</div>
				
				
				  <!-- 结束 -->
				  
		    </div>
		    
		  </div>
		</div>
		<script>
			// 点击复制
			function copy(e) {
		        var Url2 = document.getElementsByClassName("neirong");
				for(var i=0;i<Url2.length;i++){
					if(Url2[i]==e.previousSibling){
						var Url3 = Url2[i];
					}
				}
		        Url3.select(); 
		        document.execCommand("Copy"); 
		        layer.msg("已复制好，可贴粘。");
			}
		</script>
		<script>
		function copy(e) {
		        var Url2 = document.getElementsByClassName("neirong");
				for(var i=0;i<Url2.length;i++){
					if(Url2[i]==e.previousSibling){
						var Url3 = Url2[i];
					}
				}
		        Url3.select(); 
		        document.execCommand("Copy"); 
		        layer.msg("已复制好，可贴粘。");
		}
		</script>
		<script>
		jQuery(document).ready(function($){
			layui.use(['form', 'layer',], function(){
			  var form = layui.form
			  ,layer = layui.layer;
			  //监听指定开关
			  form.on('switch(switchTest)', function(data){
			  	if(this.checked){
			  		$(".biaoge").css('display','block');
			  	}else{
			  		$(".biaoge").css('display','none');
			  	}
			  })
			  form.on('submit(demo4)', function(data){
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
			  			if(data.msg==3){
			  				layer.confirm('该功能，需要点击“确定”后登录官网进行授权才可正常使用。', {
							  btn: ['确定','取消'] //按钮
							}, function(){
							 window.open("https://www.rbzzz.com/qxcp.html",'top');    
							}, function(){
							  
							});	
			  			}else{
			  				layer.alert(data.msg);
			  			}
			  		}
			  	})
			    return false;
			  });
			 
			 
			 
			  form.on('submit(demo13)', function(data){
			 var index = layer.load(1, {
                          shade: [0.7,'#111'] //0.1透明度的白色背景
                        });
			    $.ajax({
	        		url:'',
	        		type:'post',
	        		data:{data:JSON.stringify(data.field)},
	        		dataType: 'json',
	        		success:function(data){
	        		    layer.close(index);
                        if(data.msg==3){
			  				layer.confirm('该功能，需要点击“确定”后登录官网进行授权才可正常使用。', {
							  btn: ['确定','取消'] //按钮
							}, function(){
							 window.open("https://www.rbzzz.com/qxcp.html",'top');    
							}, function(){
						      
							});
			  			}else if(data.msg==1){
			  				layer.msg('操作成功');
			  			    location.reload()
			  			}else{
			  				layer.msg('暂未查询到死链，请确定“网站蜘蛛”功能是否开启。');
			  			}
	        		}
	        	})
			    return false;
			  });
			   $('.get_plan_url').click(function(){
			       var index = layer.load(1, {
                          shade: [0.7,'#111'] //0.1透明度的白色背景
                        });
			     $.ajax({
	        		url:'<?php echo  admin_url( 'admin.php?page=baiduseo&baiduseo=1&silian=1&plan=1' );?>',
	        		type:'get',
	        		dataType: 'json',
	        		success:function(data){
	        			var clo='';
	        			layer.close(index);
	        			if(data.msg==3){
			  				layer.confirm('该功能，需要点击“确定”后登录官网进行授权才可正常使用。', {
							  btn: ['确定','取消'] //按钮
							}, function(){
							 window.open("https://www.rbzzz.com/qxcp.html",'top');    
							}, function(){
							  
							});
	        			}else if(data.msg==1){
	        				layer.alert('<input class="fuzhji neirong" type="text" value="'+data.url+'"><span onclick="copy(this)">点击复制</span>');	
	        			}else{
	        			    layer.alert('获取失败，请联系客服人员处理');
	        			}
	        		}
	        	
	        	})
			 })
			});  
		
		});
		
			</script>
			 <ol id="joyRideTipContent">
        		<li data-id="box11" data-text="关闭" class="custom">
        			<h2>step #1</h2>
        			<p>生成的死链地址，复制以后可以提交给百度站长，百度站长有死链的提交窗口。</p>
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