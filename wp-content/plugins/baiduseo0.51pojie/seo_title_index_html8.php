<?php if(!defined('ABSPATH'))exit;?>
<div id="divMain">
  <div class="SubMenu">
  </div>
 <style>
  	input[readonly]{
		background:#fff!important;
	}
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
  </style>
  <div id="divMain2">
  	<!--插件开始-->
		<div class=" wyy_shouye1">
			<ul>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=1">SEO首页/分类</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=2">站长信息</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=3">批量推送</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=4">快速收录</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=5">sitemap</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=6">robots</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=7">alt/tag内链</a></li>
				<li class="admin"><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=8">301/404/category</a></li>
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
		<div class="news" style="float: left;">
		
		<div class="layui-tab-content">
		   <div id="startDemo" title="点击启动插件向导" class="point point-flicker">
			        <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/logo111.jpg">
			        <div class="shuoming">嗨，有不明白的地方可以点我！</div>
			    </div>
			<div class="main_list" >
			    <div>
			        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    				  <legend>301重向</legend>
    				</fieldset>
    				<div style="margin-left:110px;">
    				    <button class="layui-btn url_301" style="padding: 0px 37px;" id="box11">301检测</button>
    				    <span style="display:block;">该功能一般都是给IIS、PHPstudy、护卫神等桌面用户使用，如果您是宝塔用户就无需使用该功能了，只需要重定向即可。</span>
    				</div>
			        
			    </div>
				
				<form class="layui-form" action="" onsubmit="return false">
    				<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    				  <legend>404页面</legend>
    				</fieldset>
    				 <div class="layui-form-item">
    				    <label class="layui-form-label">是否开启</label>
    				    <div class="layui-input-block">
    				    		<?php if(isset($seo_301_404_url['404_url'])&&($seo_301_404_url['404_url']==1)){
    					    		echo '<input type="checkbox" name="open" lay-skin="switch" lay-text="开|关" checked="">';     	  				    	 	 		 	
    					    	}else{      		 	 	    		   		 
    					    		echo '<input type="checkbox" name="open" lay-skin="switch" lay-text="开|关">';    	 	   		    	   	  	
    					    	}    	  		  	      		 	  
    				    	?>
    				    </div>
    				     
    				  </div>
    				  <div class="layui-form-item">
    				   		<div class="layui-input-block">该配置需要服务器环境配置的配合使用</div>
    				  </div>
    				  <div class="layui-form-item">
    				    <div class="layui-input-block">
    				    	<input type="hidden" name="BaiduSEO" value="7">
    				    	<input type="hidden" name="action" value="BaiduSEO">
    				    	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
    				      <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo7" id="box22">保存</button>
    				    </div>
    				  </div>
				</form>
				<div>
    				<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    				  <legend>category</legend>
    				</fieldset>
    				<form class="layui-form" action="" onsubmit="return false">
    				 <div class="layui-form-item">
    				    <label class="layui-form-label">是否开启</label>
    				    <div class="layui-input-block">
    				    		<?php if(isset($category['istrue'])&&($category['istrue']==1)){
    					    		echo '<input type="checkbox" name="open" lay-skin="switch" lay-text="开|关" checked="">';     	  				    	 	 		 	
    					    	}else{      		 	 	    		   		 
    					    		echo '<input type="checkbox" name="open" lay-skin="switch" lay-text="开|关">';    	 	   		    	   	  	
    					    	}    	  		  	      		 	  
    				    	?>
    				      
    				     
    				    </div>
    				     
    				  </div>
    				  <div class="layui-form-item">
    				   		<div class="layui-input-block">去除分类页面链接中的"category"等字段</div>
    				   		
    				  </div>
    				  <div class="layui-form-item">
    				    <div class="layui-input-block">
    				    	<input type="hidden" name="BaiduSEO" value="24">
    				    	<input type="hidden" name="action" value="BaiduSEO">
    				    	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
    				      <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo24" id="box33">保存</button>
    				    </div>
    				  </div>
    				</form>
				</div>
		    </div>
		     
			
		  </div>
		</div>
	
		<script>
		jQuery(document).ready(function($){
			layui.use(['form', 'layedit', 'laydate','laypage','layer','table','element','colorpicker'], function(){
			  var form = layui.form
			  ,layer = layui.layer
			  ,laypage = layui.laypage
			  ,table = layui.table
			  ,colorpicker = layui.colorpicker
			  ,element= layui.element;
			
			  form.on('submit(demo7)', function(data){
			      var index = layer.load(1, {
                          shade: [0.7,'#111'] //0.1透明度的白色背景
                        });
			  	if(data.field.close!=undefined){
				  	layer.confirm('警告：请谨慎该操作，301重向是将不含WWW的地址转向WWW，如果服务器环境已经配置请勿开启该功能，会造成多重跳转。', {
					  btn: ['确定','取消'] //按钮
					}, function(){
					    
						$.ajax({
					  		url:'',
					  		data:{data:JSON.stringify(data.field)},
					  		type:'post',
					  		dataType:'json',
					  		success:function(data){
					  		    layer.close(index);
					  			if(data.msg==1){
					  				layer.alert('操作成功');
					  			}else{
					  				layer.msg('操作失败，请刷新后重试');
					  			}
					  		}
					  	})
					}, function(){
					  	location.reload()
					});
			  	}else{
			  			$.ajax({
					  		url:'',
					  		data:{data:JSON.stringify(data.field)},
					  		type:'post',
					  		dataType:'json',
					  		success:function(data){
					  		    layer.close(index);
					  			if(data.msg==1){
					  				layer.alert('操作成功');
					  			}else{
					  				layer.msg('操作失败，请刷新后重试');
					  			}
					  		}
					  	})
			  	}
			  	
			    return false;
			  });
			  
			form.on('submit(demo24)', function(data){
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
			  			}else if(data.msg==1){
			  				layer.alert('保存成功');
			  			}else{
			  				layer.msg('保存失败，请刷新后重试');
			  			}
			  		}
			  	})
			    return false;
			});
			
			  	$('.url_301').click(function(){
			  	    var index = layer.load(1, {
                          shade: [0.7,'#111'] //0.1透明度的白色背景
                        });
			  			$.ajax({
		        		url:'',
		        		type:'post',
		        		data:{data:'{"BaiduSEO":"14","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'},
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
		        			}else if(data.status){
			        				if(data.msg){
			        					layer.alert(data.msg);
			        					
			        				}
			        				
		        			}else{
		        				layer.confirm('很抱歉，您的301状态不正确，需要配置服务器环境进行改善。可登陆我们官网联系客服协助处理。官网：www.rbzzz.com', {
								  btn: ['确定','取消'] //按钮
								}, function(){
								 window.open("https://www.rbzzz.com/qxcp.html",'top');    
								}, function(){

								});
		        			}
		        			
		        		}
		        	})
			  	})
			  
			});
		
		});
			</script>
			<ol id="joyRideTipContent">
        		<li data-id="box11" data-text="下一步" class="custom">
        			<h2>step #1</h2>
        			<p>该功能一般都是给IIS、PHPstudy、护卫神等桌面用户使用，如果您是宝塔用户就无需使用该功能了，只需要重定向即可。</p>
        		</li>
        		<li data-id="box22" data-text="下一步">
        			<h2>step #2</h2>
        			<p>404页面这个需要在宝塔开启404页面信息以及根据不同的服务器环境开启404页面。大家可以根据百度查一下，不懂可以来QQ群1077537009问问</p>
        		</li>
        		<li data-id="box33" data-text="关闭">
        			<h2>step #3</h2>
        			<p>这个是wordpress的通病，设置分类以后会显示这个字段，如果按照层级关系的话，这个确实不利于SEO建议隐藏。如果你是老站切勿使用该功能，否则会出现大量的死链，建议新站使用。</p>
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