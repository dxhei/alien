<?php if(!defined('ABSPATH'))exit;?>
<div id="divMain">
  <div class="SubMenu">
  </div>
 <style>
	#wpwrap{
		background-color:#fff;
	}
	.layui-table-cell {
        height: 30px;
        line-height: 30px;
        
    }
    ._chinaz-rank-n5wcwc{
        display:none;
    }
    .R-home{
        display:none;
    }
	.layui-btn {
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
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=8">301/404/category</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=9">百度收录查询</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=10">网站蜘蛛</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=11">网站死链</a></li>
				<li class="admin"><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=15">排名词库</a></li>
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
				  <legend>排名词库</legend>
				  
				</fieldset>
				<form class="layui-form" action="" onsubmit="return false">

				  <div class="layui-progress layui-progress-big silian_solid" lay-showpercent="true" style="display:none">
				  
						 <div class="layui-progress layui-progress-big" lay-showpercent="true" lay-filter="demo1">
						  <div class="layui-progress-bar layui-bg-red" lay-percent="1%"></div>
						</div>
					</div>
				  <div class="layui-form-item">
				    <div class="layui-input-block">
				    	<input type="hidden" name="BaiduSEO" value="30">
				    	<input type="hidden" name="action" value="BaiduSEO">
				    	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
				        <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo13" id="box11">查询排名词</button>
				        <span style="color:red;margin-left:50px">最后查询时间：<?php if(isset($baiduseo_rank[0]['time'])){ echo $baiduseo_rank[0]['time'];} ?></span>
				    </div>
				  </div>
				</form>  
				<div class="layui-table" >
					<table class="layui-table">
                          <thead>
                            <tr>
                              <th>关键词</th>
                              <th>排名</th>
                              <th>pc指数</th>
                              <th>收录量</th>
                              <th>网页标题</th>
                            </tr> 
                          </thead>
                          <tbody>
                            <?php 
                                if(isset($baiduseo_rank[0]['data']) && !empty($baiduseo_rank[0]['data'])){
                                    foreach($baiduseo_rank[0]['data'] as $key=>$val){
                                        echo '<tr>
                                          <td>'.$val['keywords'].'</td>
                                          <td>'.$val['rank'].'</td>
                                          <td>'.$val['pc'].'</td>
                                          <td>'.$val['sl'].'</td>
                                          <td>'.$val['title'].'</td>
                                        </tr>';
                                        
                                    }
                                }else{
                                    echo '<tr>
                                        <td colspan="5">抱歉！您的网站暂未查询到流量关键词排名！</td>
                                    </tr>';
                                }
                            ?>
                           
                           
                          </tbody>
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
			layui.use(['form', 'laypage','layer','table'], function(){
			  var form = layui.form
			  ,layer = layui.layer
			  ,laypage = layui.laypage
			  ,table = layui.table;
			  table.render();
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
			  				layer.alert('执行成功，大约1-10分钟返回数据！',function(){
			  				    location.reload();
			  				});
			  			    
			  			}else{
			  				layer.msg('抱歉！每天只能查询一次！');
			  			}
	        		}
	        	})
			    return false;
			  });

			});
		
		});
			</script>
			<ol id="joyRideTipContent">
        		<li data-id="box11" data-text="关闭" class="custom">
        			<h2>Stop #1</h2>
        			<p>点击查询后，请耐心等待1-10分钟，在刷新该页面，即可看到自己的流量排名词，如果您还是新站，那么可能没查询到流量词，请尝试爱站工具及站长工具查询试试看。</p>
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