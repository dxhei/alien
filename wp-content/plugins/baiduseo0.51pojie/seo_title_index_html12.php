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
    .layui-laypage .layui-laypage-curr .layui-laypage-em {
        background-color: #01AAED;
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
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=11">网站死链</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=15">排名词库</a></li>
				<li class="admin"><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=12">关键词排名</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=17">原创率检测</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=13">功能授权</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=16">推荐插件</a></li>
			</ul>
		</div>
		<div class="wyy_shouye2">
			<a href="<?php echo $siteurl;?><?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo" style="margin-left: 12px;display: inline-block;padding: 8px 15px;background-color: #009688;border-radius:4px;color:#fff;">返回目录</a>
		</div>
		<div class="news" style="width:1000px; float: left;">
		
		<div class="layui-tab-content">
			<div class="main_list" >
			   <div id="startDemo" title="点击启动插件向导" class="point point-flicker">
			        <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/logo111.jpg">
			        <div class="shuoming">嗨，有不明白的地方可以点我！</div>
			    </div>
				<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				  <legend>关键词排名</legend>
				</fieldset>
				<form class="layui-form" action="" onsubmit="return false">
					<div class="layui-form-item keywords_add">
						<div class="layui-block" >
						     <label class="layui-form-label">关键词</label>
						     <div class="layui-input-inline">
						    	<input type="text" name="keyword"  autocomplete="off" class="layui-input" placeholder="输入需要监控排名的关键词" id="box11">
						     </div>
						     <div class="layui-inline" id="box22">
							        <select name="type" lay-verify="required">
							         	<option value="0">百度pc</option>
										<option value="1">百度手机</option>
							        </select>
							      </div>
						     <div class="layui-inline jiahao" style="margin-top:4px">
								 <button type="button" class="layui-btn" lay-submit=""  style="margin-top: -5px;" id="box33">添加</button>
			
							</div>
						</div>
					</div>
					<div class="layui-form-item">
					    <div class="layui-input-block">
					      <p style="margin-top:20px">关键词排名监控，请提交你需要查询及监控的关键词。（只能查询及监控前50名的关键词，关键词添加以后需要等待5-15分钟后查看排名）</p>
					      <p>目前只能监控电脑版，手机版及其他搜索引擎正在逐步开发中。</p>
					      <p style="color:red">提示：关键词每次添加一个</p>
					    </div>
					 </div>
				
						<div class="layui-form">
							<table class="layui-hide" id="test6"></table>
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
			  var laydate = layui.laydate;
				$('.jiahao').click(function(){
					var type = $('select[name="type"]').val();
					var keywords = $('input[name="keyword"]').val();
					if(!keywords){
						layer.msg('请输入关键词');return;
					}
					var index=layer.msg('加载中...', {
						icon: 16,
						time : false,
						shade: 0.01
					});
					$.ajax({
				  		url:'',
				  		data:{data:'{"BaiduSEO":"15","keywords":"'+keywords+'","type":"'+type+'","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'},
				  		type:'post',
				  		dataType:'json',
				  		success:function(data){
				  			if(data.msg==1){
				  				layer.close(index);
				  				layer.msg('添加成功');
				  				
				  				location.reload()
				  			}else if(data.msg==3){
				  				layer.close(index);
				  				layer.confirm('该功能，需要点击“确定”后登录官网进行授权才可正常使用。', {
								  btn: ['确定','取消'] //按钮
								}, function(){
								 window.open("https://www.rbzzz.com/qxcp.html",'top');    
								}, function(){
								  layer.close(index);
								});	
				  			}else if(data.msg==4){
				  				layer.close(index);
				  				layer.msg('超出关键词限制个数');
				  			}else if(data.msg==5){
				  				layer.close(index);
				  				layer.msg('您已添加了该关键字');
				  			}else{
				  				layer.close(index);
				  				layer.msg('添加失败，请刷新后重试');
				  			}
				  		}
				  		});
				})
			
			
			   table.render({
			    elem: '#test6'
			    ,url:'<?php echo  admin_url( 'admin.php?page=baiduseo&keywords=1&table=1&baiduseo=1' );?>'
			    ,cols: [[
			      {field:'sort', width:80, title: '序号', sort: true}
			      ,{field:'post_time', width:160, title: '提交时间'}
			      ,{field:'time', width:160, title: '查询时间', sort: true}
			      ,{field:'keywords', width:100, title: '关键词'}
			       ,{field:'type', width:100, title: '类型'}
			       ,{field:'num', width:80, title: '最新排名'}
			        ,{field:'prev', width:80, title: '历史排名'}
			       ,{field:'title', width:130, title: '网页标题'}
			      ,{field:'status', title: '操作', width: 80}
			    ]]
			    ,page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
			      layout: [  'prev', 'page', 'next', 'skip','count'] //自定义分页布局
			      //,curr: 5 //设定初始在第 5 页
			      ,groups: 10 //只显示 1 个连续页码
			      ,first: false //不显示首页
			      ,last: false //不显示尾页  
			      ,limit:200
			      
			    }
			    ,request:{
			        pageName:'pages',
			    },
			  });
			 
			  
			  $('body').on('click','.keywords_delete',function(){
			  		var id = $(this).attr('title');
			  		$.ajax({
		        		url:'',
		        		type:'post',
		        		data:{data:'{"BaiduSEO":"16","id":'+id+',"nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'},
		        		dataType: 'json',
		        		success:function(data){
		        			if(data.msg==3){
				  				layer.confirm('该功能，需要点击“确定”后登录官网进行授权才可正常使用。', {
								  btn: ['确定','取消'] //按钮
								}, function(){
								window.open("https://www.rbzzz.com/qxcp.html",'top');    
								}, function(){
								  
								});
								layer.close(index);
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
			<ol id="joyRideTipContent">
        		<li data-id="box11" data-text="下一步" class="custom">
        			<h2>Stop #1</h2>
        			<p>输入要监控的关键词即可，切勿提交其他网站的关键词，或者乱七八糟的词，那样根本查不到。</p>
        		</li>
        		<li data-id="box22" data-text="下一步">
        			<h2>Stop #2</h2>
        			<p>目前小编只开发了了百度的手机版和电脑版2个端的关键词排名监控，其他平台正在加速努力中（后期：360、搜狗等）。</p>
        		</li>
        		<li data-id="box33" data-text="关闭">
        			<h2>Stop #3</h2>
        			<p>添加完毕以后，点击添加等待列表的生成，如果失败请尝试重新添加。</p>
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