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
	.layui-table-cell {
        height: 30px;
        line-height: 30px;
        
    }
    .layui-btn {
	    background-color: #01AAED;
	}
	.layui-form-onswitch {
	    border-color: #01AAED;
        background-color: #01AAED;
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
	.wzt_seotc {
	    cursor: default;
	    text-decoration:underline;
	    display: inline-block;
	}
	.wzt_seotc_img {
	    position: absolute;
	    top: 240px;
	    left: 49%;
	    width: 200px;
	    height: 200px;
	    display: none;
	    transition: all 0.5s;
	    background-color: #111;
	}
	.wzt_seotc_img img {
	    width: 100%;
	    height: 100%;
	}
	.wzt_seotc:hover {
	    color: #01AAED;
	}
   @media screen and (max-width: 750px) {
       #box33{
           width:100% !important;
       }
       #shujubiao{
           width:100% !important;
       }
   }
	
  </style>
  <div id="divMain2">
  	<!--插件开始-->
		<div class=" wyy_shouye1">
			<ul>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=1">SEO首页/分类</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=2">百度站长</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=3">批量推送</a></li>
				<li  class="admin"><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=4">快速收录</a></li>
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
		<div class="">
		
		<div class="layui-tab-content">
			
			<div class="main_list" >
			    <div id="startDemo" title="点击启动插件向导" class="point point-flicker">
			        <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/logo111.jpg">
			        <div class="shuoming">嗨，有不明白的地方可以点我！</div>
			    </div>
		    	<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				  <legend>方法一</legend>
				</fieldset>
						<form class="layui-form" action="" onsubmit="return false">
        				  <div class="layui-form-item" style="position:relative;">
        				      <div class="layui-input-block wyy_yijian" style="padding:20px 0px;">
        					  	  <button type="button" class="layui-btn plts_day"  id="box11">一键推送</button>
        					  	  <span>如果一直提示“当日配额已用完！”可能是你百度站长根本就没有“快速收录”的权限。</span>
        					   </div>
        					   <div class="layui-input-block wyy_chong" >
        				      	 <button type="button" class="layui-btn plcz" title="一键重置，会还原已经推送成功的链接。可以重新在推送！"  id="box22">一键重置</button>
        				      	 <span>如果文章都已经被推送过了，怎么办？这个功能就诞生了，他可以手动触发，让您重新在推送一遍，让百度在过滤一遍。</span>
        				      </div>
        					   <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                				  <legend>方法二</legend>
                				</fieldset>
        				    <label class="layui-form-label">自动推送</label>
        				    <div class="layui-input-block" id="box33" style="width: 60px;">
        				     <?php 
        				    		if(isset($baiduseo_day_ts['auto']) && ($baiduseo_day_ts['auto']==1)){    	 	    	      				 	    					 	      		 				
        				    			 echo '<input type="checkbox" name="close" lay-skin="switch" lay-text="开|关" checked="">';    	   			     	 	  	 	      	 		 	       					
        				    		}else{    	  	         		  	      				          	 				
        				    			echo '<input type="checkbox" name="close" lay-skin="switch" lay-text="开|关">';     				  	      	 		 	    	   		 	     			 		 
        				    		}     	 		 	     		 	 		      		  	 	     	     	
        				    	?>
        				    </div>
        				     <p>开启以后，每次发布文章或是修改文章都会自动推送。<span class="wzt_red">注意:大部分用户都没有"快速收录"权限,获取方法可以查阅公众号
        				     <span class="wzt_seotc">
        				         (郑州沃之涛科技有限公司)
        				         
        				     </span></span></p>
        				     <div class="wzt_seotc_img">
        				         <img src="<?php echo  plugin_dir_url( __FILE__ );?>image/wztgzh.jpg">
        				     </div>
        				  </div>
        				   <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            				  <legend>方法三</legend>
            				</fieldset>
        				  <div class="layui-form-item">
        				    <label class="layui-form-label">自动计划任务</label>
        				    <div class="layui-input-block" id="box11" style="width: 60px;">
        				     <?php 
        				    		if(isset($baiduseo_day_ts['plan']) && ($baiduseo_day_ts['plan']==1)){    	 	    	      				 	    					 	      		 				
        				    			 echo '<input type="checkbox" name="plan" lay-skin="switch" lay-text="开|关" checked="">';    	   			     	 	  	 	      	 		 	       					
        				    		}else{    	  	         		  	      				          	 				
        				    			echo '<input type="checkbox" name="plan" lay-skin="switch" lay-text="开|关">';     				  	      	 		 	    	   		 	     			 		 
        				    		}     	 		 	     		 	 		      		  	 	     	     	
        				    	?>
        				    </div>
        				     <p>自动推送，是采用WP的计划任务自动循环24小时执行推送，该方法适合大部分的用户使用。如果开启该功能，请不要使用计划任务避免重复！</p>
        				     <p style="margin-left:114px;">因为"快速收录"权限配额很少,所以单独一个计划任务,请谨慎选项！！！</p>
        				  </div>
        			       
        				  <div class="layui-form-item wyy_he">
        				    <div class="layui-input-block">
        				    	<input type="hidden" name="BaiduSEO" value="19">
        				    	<input type="hidden" name="action" value="BaiduSEO">
        				    	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
        				      <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo19"  id="box44">保存</button>
        					  <!-- 原本两块合并为一块 -->
        				      
        					  
        				    </div>
        				  </div>
				        </form>
			        <div style="width:580px">
				    <div class="layui-input-block wenti" style="margin-top:20px">最后一次批量推送时间:<span class="wzt_red"><?php if(isset($baiduseo_pltsdayts['time'])){echo $baiduseo_pltsdayts['time'];}else{echo '暂无记录';}?></span>,推送条数:<span class="wzt_red"><?php if(isset($baiduseo_pltsdayts['count'])){echo $baiduseo_pltsdayts['count'];}else{echo 0;}?></span>条,共推送:<span class="wzt_red"><?php echo $baiduseo_dayts_num;?></span>条</div>
				  	<!-- <div class="layui-input-block wenti">计划任务URL：<?php echo get_option('siteurl').'?BaiduSEO=1&dayts=1&zhou=1'?></div> -->
    					
				  	</div>
				    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    				  <legend>方法四</legend>
    				</fieldset>
				    <div class="layui-input-block wenti" style="line-height: 60px;">计划任务URL：<button type="button" class="layui-btn get_plan_url" style="padding: 0 20px;">获取链接</button></div>
				  	<div class="layui-input-block wenti" id="box55">计划任务需要配合宝塔定时触发：<a href="https://www.seohnzz.com/archives/135.html" target="_blank" style="color:#01AAED;">详细点击阅读</a></div>
    				
				  	
				  	
				  	
				  	<div style="width:1100px" id="shujubiao">
				  	    <div class="layui-form">
        					<div class="layui-tab">
            				  <ul class="layui-tab-title">
            				    <li class="layui-this" id="box66">未推送</li>
            				    <li id="box77">已推送</li>
            				  </ul>
            				  <div class="layui-tab-content">
                				    <div class="layui-tab-item layui-show">
                				      <div class="layui-form">
                								<table class="layui-hide" id="test4"></table>
                						</div>
                				    </div>
                				    <div class="layui-tab-item">
                						 <div class="layui-form">
                							<table class="layui-hide" id="test5"></table>
                						</div>
                				    </div>
            				  </div>
        				  
        				   </div>
        				  
        				</div>
				  	</div>
		    </div>
		    
			
		  </div>
		</div>
			<ol id="joyRideTipContent">
        		<li data-id="box11" data-text="下一步" class="custom">
        			<h2>step #1</h2>
        			<p>方法一：这个功能是您想每次都手动一键推送，不想劳烦程序了。</p>
        		</li>
        		<li data-id="box22" data-text="下一步">
        			<h2>step #2</h2>
        			<p>如果文章都已经被推送过了，怎么办？这个功能就诞生了，他可以手动触发，让您重新在推送一遍，让百度在过滤一遍。</p>
        			
        		</li>
        		<li data-id="box33" data-text="下一步">
        			<h2>step #3</h2>
        			<p>方法二：开启该功能以后，每当您发布文章都会自动使用百度站长[快速收录]接口进行推送，快速收录的推送配额比较少，注意使用方法。（大部分网站或新站是没有快速收录权限的，是百度站长没给，不是我们插件这个功能不好用，快速收录权限需要开通百度小程序获取，百度不就想推广他们的小程序嘛，不过小编实测确实发现百度APP有流量加持，建议企业用户可以开发百度小程序，到时候联系小编Q1500351892开发哦！）</p>
        		</li>
        		<li data-id="box44" data-text="下一步">
        			<h2>step #4</h2>
        			<p>注意：每次开启或关闭，都要保存下，否则不生效哦^^</p>
        		</li>
        		<li data-id="box55" data-text="下一步">
        			<h2>step #5</h2>
        			<p>（推荐）方法四：有了自动推送为什么还需要定时推送呢？原因是自动推送是根据发布文章触发的，如果您不发文章就不会触发，所以定时任务就诞生了，他就是不管您发不发文章，都会把您百度站长的快速收录配额一次性全部用完进行推送。</p>
        		</li>
        		<li data-id="box66" data-text="下一步">
        			<h2>step #6</h2>
        			<p>这里可以查看没有推送的文章，您可以在文章尾部点击推送，推送指定文章。</p>
        		</li>
        		<li data-id="box77" data-text="关闭">
        			<h2>step #7</h2>
        			<p>这里可以查看已经通过快速收录API推送给站长的文章</p>
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
                $(".wzt_seotc").hover(function() {
                    $(".wzt_seotc_img").css("display","block")
                },function() {
                    $(".wzt_seotc_img").css("display","none")
                })
            });
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
			layui.use(['form', 'laypage','layer','table','element'], function(){
			  var form = layui.form
			  ,layer = layui.layer
			  ,laypage = layui.laypage
			  ,table = layui.table
			  ,element=layui.element;
			   table.render({
			    elem: '#test4'
			    ,url:'<?php echo  admin_url( 'admin.php?page=baiduseo&ts=0&table=1&baiduseo=1' );?>'
			    ,cols: [[
			      {field:'id', width:75, title: 'ID', sort: true}
			      ,{field:'title', width:384, title: '标题'}
			      ,{field:'time', width:165, title: '时间', sort: true}
			      ,{field:'link', width:370, title: '链接'}
			      ,{field:'status', title: '操作', minWidth: 80}
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
			  table.render({
			    elem: '#test5'
			    ,url:'<?php echo  admin_url( 'admin.php?page=baiduseo&ts=1&table=1&baiduseo=1' );?>'
			    ,cols: [[
			      {field:'id', width:75, title: 'ID', sort: true}
			      ,{field:'title', width:384, title: '标题'}
			      ,{field:'time', width:165, title: '时间', sort: true}
			      ,{field:'link', width:370, title: '链接'}
			      ,{field:'status', title: '操作', minWidth: 80}
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
	         $('.get_plan_url').click(function(){
	             var index = layer.load(1, {
                      shade: [0.7,'#111'] //0.1透明度的白色背景
                    });
			     $.ajax({
	        		url:'<?php echo  admin_url( 'admin.php?page=baiduseo&baiduseo=1&day=1&plan=1' );?>',
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
			form.on('submit(demo19)', function(data){
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
		
			  
        	
			  	$('body').on('click','.seo_day_ts',function(){
			  	    var index = layer.load(1, {
                      shade: [0.7,'#111'] //0.1透明度的白色背景
                    });
			  		var id=$(this).attr('title');
				  	$.ajax({
		        		url:'',
		        		type:'post',
		        		data:{data:'{"id":"'+id+'","BaiduSEO":"10","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'},
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
		        					layer.msg(data.msg);
		        				}
		        				location.reload()
		        			}else{
		        				if(data.msg){
		        					layer.msg(data.msg);
		        				}
		        			}
		        			
		        		}
		        	})
			  	})
			  	$('body').on('click','.plcz',function(){
			  	var index = layer.load(1, {
                      shade: [0.7,'#111'] //0.1透明度的白色背景
                    });
				  	$.ajax({
		        		url:'',
		        		type:'post',
		        		data:{data:'{"BaiduSEO":"20","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'},
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
		        				layer.msg('重置成功');
		        				location.reload()
		        			}
		        			
		        		}
		        	})
			  	})
			  	$('body').on('click','.plts_day',function(){
			  	var index = layer.load(1, {
                      shade: [0.7,'#111'] //0.1透明度的白色背景
                    });
				  	$.ajax({
		        		url:'',
		        		type:'post',
		        		data:{data:'{"BaiduSEO":"27","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'},
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
			        				layer.msg(data.msg);
			        			}
		        				location.reload()
		        			}else{
		        			    if(data.msg){
			        				layer.msg(data.msg);
			        			}
		        			}
		        			
		        		}
		        	})
			  	})
			  
			  
			});
		
		});
			</script>
  	<!--插件结束-->
<!--代码-->
  </div>
</div>