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
    .layui-form-radioed>i {
        color: #01AAED;
    }
    .layui-laypage .layui-laypage-curr .layui-laypage-em {
        background-color: #01AAED;
    }
    .layui-input-block {
	    margin-left: 114px;
	}
	.layui-form-label {
	    width: 84px;
	}
	.wzt_red {
	    color: red;
	}
	.wzt_p p {
	    display: inline-block;
	    margin-right: 30px;
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
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=8">301/404/category</a></li>
				<li class="admin"><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=9">百度收录查询</a></li>
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
		<div class="news" style="width: 1100px; float: left;">
		
		<div class="layui-tab-content">
		     <div id="startDemo" title="点击启动插件向导" class="point point-flicker">
			        <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/logo111.jpg">
			        <div class="shuoming">嗨，有不明白的地方可以点我！</div>
			    </div>
			<div class=" main_list" >
		     	<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				  <legend>百度收录查询</legend>
				</fieldset>
				
				<blockquote class="layui-elem-quote layui-text">
  					注：百度收录查询，算法是根据文章链接及标题在服务器所在IP区域是否收录，并非本地IP，所以各个地区查询及收录不会一致。
				</blockquote>
				<label class="layui-form-label">当天索引量</label>
				<div class="layui-input-block wzt_p" style="padding:10px 0;">
                        <p>百度索引:<span class="wzt_red"><?php if(isset($suoyin_baidu[0]['num'])){echo $suoyin_baidu[0]['num'];}else{echo 0;}?></span>条</p>
                        <p>360索引:<span class="wzt_red"><?php if(isset($suoyin_360[0]['num'])){echo $suoyin_360[0]['num'];}else{echo 0;}?></span>条</p>
                        <p>必应索引:<span class="wzt_red"><?php if(isset($suoyin_biying[0]['num'])){echo $suoyin_biying[0]['num'];}else{echo 0;}?></span>条</p>
                        <p>搜狗索引:<span class="wzt_red"><?php if(isset($suoyin_sougou[0]['num'])){echo $suoyin_sougou[0]['num'];}else{echo 0;}?></span>条</p>
                </div>
				<div style='border-top:1px solid #000;padding:20px 0px;border-bottom:1px solid #000;'>
				      <form class="layui-form" action="" onsubmit="return false">
				      <div class="layui-form-item" >
    				    <label class="layui-form-label">自动计划任务</label>
    				    <div class="layui-input-block" id="box11">
    				    	<?php 
    				    		if(isset($seo_baidu_xzh['sl_plan']) && ($seo_baidu_xzh['sl_plan']==1)){    		 	 		       	echo '<input type="checkbox" name="sl_plan" lay-skin="switch" lay-text="开|关" checked="">';  				  
    				    			 			 	 		       		  	
    				    		}else{     		  	 	       		   
    				    		  echo '<input type="checkbox" name="sl_plan" lay-skin="switch" lay-text="开|关">';   	  			 	     	  	   
    				    		}      	         	       
    				    	?>
    				       <p style="margin-top: 10px;">开启以后，每小时自动查询一定数量的文章是否收录。</p>
    				    </div>
    				  </div>
    				  <div class="layui-form-item">
				    <div class="layui-input-block">
				    	<input type="hidden" name="BaiduSEO" value="32">
				    	<input type="hidden" name="action" value="BaiduSEO">
				    	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
				      <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo4" >保存</button>
				    </div>
				  </div>
    				  </form>
				  </div>
				<div>
    				<form class="layui-form" action="" onsubmit="return false">
    				 <div class="layui-form-item" pane="" >
    				    <label class="layui-form-label">每秒查询条数</label>
    				    <div class="layui-input-block">
    				      <input type="radio" name="num" value="2" title="2条" checked="">
    				      <input type="radio" name="num" value="5" title="5条">
    				      <input type="radio" name="num" value="10" title="10条" >
    				    </div>
    				  </div>
    				  
    				</form>
    				<div class="layui-progress layui-progress-big box_solid" lay-showpercent="true" style="display:none;margin-bottom: 10px;">
    				  
    					 <div class="layui-progress layui-progress-big" lay-showpercent="true" lay-filter="demo">
    					  <div class="layui-progress-bar layui-bg-red" lay-percent="1%"></div>
    					</div>
    				</div>
    				<div class="layui-input-block" style="margin-top:10px;">
    				    <button type="button" class="layui-btn seo_sl_cat">查询</button>
    				</div>
				</div>
				<blockquote class="" id="box22">
				    <form class="layui-form" action="" onsubmit="return false">
					<div class="layui-form-item" pane="">
					    <label class="layui-form-label">每秒推送条数</label>
					    <div class="layui-input-block">
					      <input type="radio" name="wsl_zz_num" value="200" title="200条" checked="">
					      <input type="radio" name="wsl_zz_num" value="300" title="300条">
					      <input type="radio" name="wsl_zz_num" value="500" title="500条" >
					    </div>
					  </div>
					 </form>
					 <div class="layui-progress layui-progress-big wsl_zz_solid" lay-showpercent="true" style="display:none;margin-bottom: 10px;">
				  
						 <div class="layui-progress layui-progress-big" lay-showpercent="true" lay-filter="demo">
						  <div class="layui-progress-bar layui-bg-red" lay-percent="1%"></div>
						</div>
					</div>
				        <button type="button" class="layui-btn layui-btn-warm wsl_plts_zz" id="box22">一键推送未收录文章到百度站长</button>
				</blockquote>
				<div class="layui-tab">
				  <ul class="layui-tab-title">
				    <li class="layui-this" id="box33">未收录</li>
				    <li id="box44">已收录</li>
				  
				  </ul>
				
				
				  <div class="layui-tab-content">
				    <div class="layui-tab-item layui-show">
				      <div class="layui-form">
						<table class="layui-hide" id="test1"></table>
						</div>
						
				    </div>
				    <div class="layui-tab-item">
						<div class="layui-form">

							<table class="layui-hide" id="test2"></table>
						</div>
					
				    </div>
				    
				  </div>
				</div>
		    </div>

		
		  </div>
		</div>
		<script>
		jQuery(document).ready(function($){
			layui.use(['form', 'layedit', 'laydate','laypage','layer','table','element'], function(){
			  var form = layui.form
			  ,layer = layui.layer
			  ,laypage = layui.laypage
			  ,table = layui.table
			  ,element= layui.element;
			  $('.seo_sl_cat').click(function(){
    	  		var page = $('input[name="num"]:checked').val();
    		  	$('.box_solid').css('display','block');
    			 var DISABLED = 'layui-btn-disabled';
    		      if($(this).hasClass(DISABLED)) return;
    		    $(this).addClass(DISABLED);
    			seo_sl_cat(1,element,page);
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
		 	function seo_sl_cat(num,element,page){
        		$.ajax({
	        		url:'',
	        		type:'post',
	        		data:{data:'{"seo":"1","BaiduSEO":"8","seo_url_sl":"1","num":"'+num+'","page":"'+page+'","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'},
	        		dataType: 'json',
	        		success:function(data){
	        			var clock='';
	        			if(data.msg==3){
				  				layer.confirm('该功能，需要点击“确定”后登录官网进行授权才可正常使用。', {
								  btn: ['确定','取消'] //按钮
								}, function(){
								window.open("https://www.rbzzz.com/qxcp.html",'top');    
								}, function(){
								  
								});	
	        			}else if(data.msg){
	        				var no=++data.num;
	        			
						 	element.progress('demo', data.percent);
	        				var clock = setInterval(seo_sl_cat(no,element,page), 10); 
	        			}else{
	        				if(typeof(clock) == undefined ){
								
	        				}else{
	        					clearInterval(clock);
	        				}
	        				$('.box_solid').css('display','none');
							layer.alert('执行完成');
							location.reload();
	        			}
	        			
	        		}
	        	})
        	} 
			 
			  table.render({
			    elem: '#test1'
			    ,url:"<?php echo  admin_url( 'admin.php?page=baiduseo&table=1&sl=2&baiduseo=1' );?>"
			    ,cols: [[
			      {field:'num', width:100, title: 'ID', sort: true}
			       ,{field:'title', width:325, title: '标题'}
			      ,{field:'link',width:380,  title: '链接'}
			      ,{field:'time',width:250,  title: '时间'}
			    
			    ]]
			    ,page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
			      layout: [  'prev', 'page', 'next', 'skip','count'] //自定义分页布局
			      //,curr: 5 //设定初始在第 5 页
			      ,groups: 10 //只显示 10个连续页码
			      ,first: false //不显示首页
			      ,last: false //不显示尾页  
			      ,limit:35
			    }
			    ,request:{
			        pageName:'pages',
			    },
			    
			  });
				  table.render({
				    elem: '#test2'
				    ,url:"<?php echo  admin_url( 'admin.php?page=baiduseo&table=1&sl=1&baiduseo=1' );?>"
				    ,cols: [[
				       {field:'num', width:100, title: 'ID', sort: true}
				      ,{field:'title',width:325, title: '标题'}
				      ,{field:'link', width:380,title: '链接'}
				      ,{field:'time', title: '时间',width:250}
				    ]]
				    ,page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
				      layout: [  'prev', 'page', 'next', 'skip','count'] //自定义分页布局
				      //,curr: 5 //设定初始在第 5 页
				      ,groups: 10 //只显示 1 个连续页码
				      ,first: false //不显示首页
				      ,last: false //不显示尾页  
				      ,limit:35
				      
				    }
				    ,request:{
    			        pageName:'pages',
    			    },
				     
			  });
			  
			  
			 
		
			 
        	
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
	        	$('.wsl_plts_zz').click(function(){
			  		var page = $('input[name="wsl_zz_num"]:checked').val();
					$('.wsl_zz_solid').css('display','block');
					zz_plts(1,element,page,0,1);
			  	})
			  
			});
		
		});
		</script>
			<ol id="joyRideTipContent">
        		<li data-id="box11" data-text="下一步" class="custom">
        			<h2>step #1</h2>
        			<p>该功能是查询你网址下所有的文章链接是否收录，如果文章过多会非常缓慢，因为百度是禁止爬取收录信息，所以我们的算法不一定十分准确（准确率90%以上），仅供参考。可以根据每秒查询几篇文章定义，如果服务器好可以选择的数值大一些。</p>
        		</li>
        		<li data-id="box22" data-text="下一步">
        			<h2>step #2</h2>
        			<p>根据查询收录过的文章，一般会分为已收录和未收录，可以手动一键推送未收录的文章到百度站长加速收录。</p>
        		</li>
        		<li data-id="box33" data-text="下一步">
        			<h2>step #3</h2>
        			<p>该分类是没有查询到百度已收录的文章</p>
        		</li>
        		<li data-id="box44" data-text="关闭">
        			<h2>step #3</h2>
        			<p>该分类是查询到已收录的文章</p>
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