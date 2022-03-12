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
				<li class="admin"><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=6">robots</a></li>
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
		<div class="news" style="float: left;">
		
		<div class="layui-tab-content">
			 <div class="main_list" >
			    <div id="startDemo" title="点击启动插件向导" class="point point-flicker">
			        <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/logo111.jpg">
			        <div class="shuoming">嗨，有不明白的地方可以点我！</div>
			    </div>
		     	<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				  <legend>robots生成</legend>
				</fieldset>
		     	<form class="layui-form" action="" onsubmit="return false">
		     	    				  
				  <div>
				  <div class="layui-form-item">
				    <label class="layui-form-label" id="box11">robots开关</label>
				    <div class="layui-input-block">
				    	<?php if(isset($robot['robot_auto'])&&($robot['robot_auto']==1)){
				    		echo '<input type="checkbox" name="close" lay-skin="switch" lay-text="开|关" checked="">';     	  				    	 	 		 	
				    	}else{      		 	 	    		   		 
				    		echo '<input type="checkbox" name="close" lay-skin="switch" lay-text="开|关">';    	 	   		    	   	  	
				    	}    	  		  	      		 	  
				    	?>
				      
				     
				    </div>
				     <span>开启会生成robots,关闭会删除robots</span>
				  </div>
    				       <div class="layui-form-item layui-form-text">
    				    
    				    <div class="layui-input-block">
    				    	<?php if(isset($robot['robot'])){
    				    		echo '<textarea  class="layui-textarea" name="robot">'.$robot['robot'].'</textarea>';     		   		     		 				
    				    	}else{
    				    		echo ' <textarea  class="layui-textarea" name="robot">User-agent: *
    Disallow: /wp-admin/
    Sitemap: </textarea>';
    				    	}     			         		 	 	 
    				    	?>
    				     
    				    </div>
    				  </div>
    			</div>
    				  <div class="layui-form-item">
    				  		<?php  if(isset($robot['url'])){
    				  			echo '<div class="layui-input-block">地址：<a href="'.$robot['url'].'" target="_blank">'.$robot['url'].'</a></div>';    	  					        	   
    				  		}     		  		     	       
    				   		      			 	        	  		
    				   		?>
    				   		<p class="layui-input-block">开启之后请在百度站长robots校验激活</p>
    				   		<?php 
    				   		if(isset($robot['time'])){    	 			 	     	    	 	
    				   			echo '<div class="layui-input-block">生成/删除时间：'.$robot['time'].'</div>';      	 				    			 			 
    				   		}    		 	  	      			    
    				   		?>
    				  </div>
				  
				  
				  
				  
				  <div class="layui-form-item">
				    <div class="layui-input-block">
				    	<input type="hidden" name="BaiduSEO" value="5">
				    	<input type="hidden" name="action" value="BaiduSEO">
				    	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
				      <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo5" id="box22">保存</button>
				    </div>
				  </div>
				</form>
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
			  
			  form.on('submit(demo5)', function(data){
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
			  				layer.msg('操作成功');
			  				location.reload();
			  			}else if(data.msg==3){
			  				layer.confirm('该功能，需要点击“确定”后登录官网进行授权才可正常使用。', {
							  btn: ['确定','取消'] //按钮
							}, function(){
							 window.open("https://www.rbzzz.com/qxcp.html",'top');   
							}, function(){
							  
							});	
			  			}else{
			  				layer.msg('操作失败，请刷新后重试');
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
        			<p>robots相当于蜘蛛的通行证，可以告诉他哪里可以抓取，哪里不可以抓取，注意：抓取和爬取是不一样的，如果您设置了禁止目录，蜘蛛就算爬取了但是不会抓取的.如果您不太了解，可以加Q群1077537009进行探讨学习。</p>
        		</li>
        		<li data-id="box22" data-text="关闭">
        			<h2>step #2</h2>
        			<p>搞定以后记得每次点击保存哦!</p>
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