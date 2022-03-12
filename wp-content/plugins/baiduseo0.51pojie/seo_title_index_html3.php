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
	.layui-form-radioed>i {
	    color: #01AAED;
	}
	.layui-form-onswitch {
	    border-color: #01AAED;
        background-color: #01AAED;
	}
	.lbtn {
	    margin-top: 20px;
	}
	.wzt_red {
	    color: red;
	    padding: 0 3px;
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
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=1">SEO首页/分类</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=2">站长信息</a></li>
				<li  class="admin"><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=3">批量推送</a></li>
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
		<div class="news" style="float: left;">
		
		<div class="layui-tab-content">

			<div class="main_list" >
			    <div id="startDemo" title="点击启动插件向导" class="point point-flicker">
			        <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/logo111.jpg">
			        <div class="shuoming">嗨，有不明白的地方可以点我！</div>
			    </div>
			    <div class="fled">
			    <div class="method meth">
				<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				  <legend>方法一：批量推送</legend>
				</fieldset>
				<form class="layui-form" action="" onsubmit="return false">
				  <div class="layui-form-item layui-form-text">
				    <label class="layui-form-label">站点域名</label>
				    <div class="layui-input-block lblock">
				      <textarea placeholder="请输入站点域名（例如：www.baidu.com）" class="layui-textarea" name="url_zhan" style="margin-bottom:10px;"></textarea>
				      <span>一行一条URL,输入一条你自己的官网地址,保存后就可以看到百度官方给你分配了多少配额！</span>
				    </div>

				  </div>
				  <div class="layui-form-item">
				    <div class="layui-input-block lblock">
				    	<input type="hidden" name="BaiduSEO" value="3">
				    	<input type="hidden" name="action" value="BaiduSEO">
				    	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
				      <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo3" id="box11">提交</button>
				    </div>
				  </div>
				</form>
				</div>
				<div class="layui-form-item layui-form-text method meth" >
				    	<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				  <legend>方法二：每秒推送</legend>
				</fieldset>
					<form class="layui-form" action="" onsubmit="return false">
					<div class="layui-form-item" pane="">
					    <label class="layui-form-label">每秒推送条数</label>
					    <div class="layui-input-block lblock">
					      <input type="radio" name="zz_num" value="500" title="500条" checked="">
					      <input type="radio" name="zz_num" value="1000" title="1000条">
					      <input type="radio" name="zz_num" value="2000" title="2000条" >
					    </div>
					  </div>
					 </form>
					 <div class="layui-progress layui-progress-big zz_solid" lay-showpercent="true" style="display:none">
				  
						 <div class="layui-progress layui-progress-big" lay-showpercent="true" lay-filter="demo">
						  <div class="layui-progress-bar layui-bg-red" lay-percent="1%"></div>
						</div>
					</div>
					<div style="margin:10px 0 0 110px;">手动推送方式,根据服务器强度选择条数,推送条数过多将会导致服务器压力过大。</div>
			  		<div class="layui-input-block lblock lbtn"><button type="button" class="layui-btn plts_zz" id="box22">一键推送</button></div>
			  	</div>
			    <div class="layui-form-item layui-form-text method meth">
			        				    	<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				  <legend>方法三：计划任务</legend>
				</fieldset>
				    <div class="layui-input-block lblock">注：请在“百度站长”绑定信息后使用一键推送所有html(文章、tag标签、分页)页面</span></div>
				    <div class="layui-input-block lblock">最近推送时间：<span class="wzt_red"><?php echo $zz_yjts['time'];?></span> 推送条数：<span class="wzt_red"><?php echo $zz_yjts['zz_tsts'];?></span>条，剩余配额：<span class="wzt_red"><?php echo $zz_yjts['zz_kts'];?></span>条</div>
				   <div class="layui-input-block wenti" style="line-height: 60px;">计划任务URL：<button type="button" class="layui-btn get_plan_url" style="padding: 0 20px;">获取链接</button></div>
					<div class="layui-input-block lblock" id="box33">计划任务需要配合宝塔定时触发：<a href="https://www.seohnzz.com/archives/135.html" target="_blank" style="color:#01AAED;" >详细点击阅读</a></div>

				  </div>
				  <div class="method meth" >
				      				    	<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				  <legend>方法四：自动推送（推荐）</legend>
				</fieldset>
				      <form class="layui-form" action="" onsubmit="return false">
				      <div class="layui-form-item" >
    				    <label class="layui-form-label ">自动计划任务</label>
    				    <div class="layui-input-block tex1" id="box22">
    				    	<?php 
    				    		if(isset($seo_baidu_xzh['zz_plan']) && ($seo_baidu_xzh['zz_plan']==1)){    		 	 		       	echo '<input type="checkbox" name="zz_plan" lay-skin="switch" lay-text="开|关" checked="">';  				  
    				    			 			 	 		       		  	
    				    		}else{     		  	 	       		   
    				    		  echo '<input type="checkbox" name="zz_plan" lay-skin="switch" lay-text="开|关">';   	  			 	     	  	   
    				    		}      	         	       
    				    	?>
    				       <p>自动推送，是采用WP的计划任务自动循环24小时执行推送，该方法适合大部分的用户使用。如果开启该功能，请不要使用计划任务（方法三）避免重复！</p>
    				    </div>
    				  </div>
    				  <div class="layui-form-item ">
				    <div class="layui-input-block lblock ">
				    	<input type="hidden" name="BaiduSEO" value="31">
				    	<input type="hidden" name="action" value="BaiduSEO">
				    	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
				      <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo4" id="box11">保存</button>
				    </div>
				  </div>
				  
    				  </form>
				  </div>
				  </div>
		    </div>
		    
			
		  </div>
		</div>
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
			layui.use(['form', 'layer','element'], function(){
			  var form = layui.form
			  ,layer = layui.layer
			  ,element = layui.element;
			  form.on('submit(demo3)', function(data){
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
			 $('.get_plan_url').click(function(){
			     var index = layer.load(1, {
                      shade: [0.7,'#111'] //0.1透明度的白色背景
                    });
			     $.ajax({
	        		url:'<?php echo  admin_url( 'admin.php?page=baiduseo&baiduseo=1&zz=1&plan=1' );?>',
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
        	$('.plts_zz').click(function(){
				var page = $('input[name="zz_num"]:checked').val();
				$('.zz_solid').css('display','block');
				zz_plts(1,element,page);
			  	
			  })
			  	function zz_plts(num,element,page,shyu=0,wsl=0){
		        	$.ajax({
		        		url:'',
		        		type:'post',
		        		data:{data:'{"plts":"1","type":"zz","BaiduSEO":"9","num":"'+num+'","page":"'+page+'","shyu":"'+shyu+'","wsl":"'+wsl+'","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'},
		        		dataType: 'json',
		        		success:function(data){
		        			var clo='';
		        			if(data.msg==3){
				  				layer.confirm('该功能，需要点击“确定”后登录官网进行授权才可正常使用。', {
								  btn: ['确定','取消'] //按钮
								}, function(){
								 window.open("https://www.rbzzz.com/qxcp.html",'top');
								}, function(){
								  
								});
		        			}else if(data.status){
		        					var no=++data.num;
	        			
						 			element.progress('demo', data.percent);
	        						var clo = setInterval(zz_plts(no,element,page,data.shyu,wsl), 10); 
		        				
		        			}else{
		        				if(data.msg){
		        					layer.alert(data.msg,function(){
		        						location.reload()
		        					});
		        				}
		        			}
		        			
		        		}
		        	
		        	})
     
        		} 
			  
			  
			  	
			});
		
		});
			</script>
			<ol id="joyRideTipContent">
        		<li data-id="box11" data-text="下一步" class="custom">
        			<h2>step #1</h2>
        			<p>方法一：可以复制自己网站下的指定URL链接进行手动提交到百度站长。(如果提示：不是本站的URL，请检查wordpress常规设置的URL地址是否有WWW，如果您全局都没有WWW可以忽略，如果有请都加上WWW)</p>
        		</li>
        		<li data-id="box22" data-text="下一步">
        			<h2>step #2</h2>
        			<p>方法二：可以一键推送网站所有URL到百度站长。</p>
        		</li>
        		<li data-id="box33" data-text="关闭">
        			<h2>step #3</h2>
        			<p>方法三：复制URL地址，在宝塔添加计划任务，让宝塔自动执行推送网站内所有链接到百度站长。无需再每天手工推送，省去人工值守烦恼。这个是小编推荐的，做这个插件初衷不就是为了自动嘛，为什么不做wordpress定时计划？因为wp有时候会卡死导致不成功。(配额说明：小编只是开发的插件，没有限制配额功能，配额是百度官方给的，随着你站点质量高配额就会随之增加，当天配额用完次日会更新。)</p>
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