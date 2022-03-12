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
				  <legend>内链列表</legend>
				  
				</fieldset>
				    
				<div class="layui-form" id="box11">
				   
					<table class="layui-form layui-border-box layui-table-view" id="test4" lay-filter="test4" lay-id="test4"></table>
					
				</div>
		    </div>
		    
			
		  </div>
		</div>
		<script type="text/html" id="switchTpl">
            <input type="checkbox" name="kaiguan" value="{{d.tag_target}}" id="{{d.term_id}}" lay-skin="switch" lay-text="开|关" lay-filter="kaiguanx" {{ d.tag_target=='是' ? 'checked' : '' }}>
        </script>
        <script type="text/html" id="switchTplx">
            <input type="checkbox" name="kaiguan2" value="{{d.tag_nofollow}}" id="{{d.term_id}}" lay-skin="switch" lay-text="开|关" lay-filter="kaiguanx2" {{ d.tag_nofollow=='是' ? 'checked' : '' }}>
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
			layui.use(['form', 'laypage','layer','table'], function(){
			  var form = layui.form
			  ,layer = layui.layer
			  ,laypage = layui.laypage
			  ,table = layui.table;
			   table.render({
			    elem: '#test4'
			     ,toolbar:'#toolbarDemo'
			     ,defaultToolbar: []
			    ,url:'<?php echo  admin_url( 'admin.php?page=baiduseo&neilian=1&table=1&baiduseo=1' );?>'
			    ,cols: [[
			      {type:'checkbox', fixed: 'left'}
			      ,{field:'term_id', width:78, title: 'ID', sort: true}
			      ,{field:'name', width:200, title: '关键词 (点击即可编辑)',edit: 'text'}
			      ,{field:'tag_link', min_width:306, title: '链接 (点击即可编辑)',edit: 'text'}
			      ,{field:'tag_target', min_width:306, title: '新页面打开',templet: '#switchTpl', unresize: true}
			      ,{field:'tag_nofollow', min_width:306, title: 'nofollow',templet: '#switchTplx', unresize: true}
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
			   //头工具栏事件
              table.on('toolbar(test4)', function(obj){
                var checkStatus = table.checkStatus(obj.config.id);
                switch(obj.event){
                  case 'getCheckData':
                    var data = checkStatus.data;
                    if(data.length==0){
                        layer.msg('您没有选择数据！');return;
                    }
                    var index = layer.load(1, { shade: [0.7,'#111'] });
                    $.ajax({
				  		url:'',
				  		data:{data:'{"BaiduSEO":"34","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO","value":'+JSON.stringify(data)+'}',},
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
				  			    layer.alert('删除成功',function(){
		        						location.reload()
		        				});
				  			}
				  		}
				    })
                  break;
                };
              });
			  //监听单元格编辑
              table.on('edit(test4)', function(obj){
                  var index = layer.load(1, { shade: [0.7,'#111'] });
                    $.ajax({
				  		url:'',
				  		data:{data:'{"BaiduSEO":"26","value":"'+obj.value+'","key":"'+obj.field+'","term_id":"'+obj.data.term_id+'","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'},
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
				  			}else if(data.msg==4){
				  			    layer.msg('请填写是或者否！');
				  			}
				  		}
				})
              });
              form.on('switch(kaiguanx)', function(obj){
                if(obj.value == "是") {
                    obj.value = "否"
                }else {
                    obj.value = "是"
                }
                var index = layer.load(1, {
                         shade: [0.7,'#111'] //0.1透明度的白色背景
                       });
                   $.ajax({
                         url:'',
                         data:{data:'{"BaiduSEO":"26","value":"'+obj.value+'","key":"tag_target","term_id":"'+obj.elem.id+'","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'},
                         type:'post',
                         dataType:'json',
                         success:function(data){
                             console.log(data);
                             layer.close(index);
                             if(data.msg==3){
            
                                 layer.confirm('该功能，需要点击“确定”后登录官网进行授权才可正常使用。', {
                                 btn: ['确定','取消'] //按钮
                               }, function(){
                                window.open("https://www.rbzzz.com/qxcp.html",'top');   
                               }, function(){
                                 
                               });	
                             }else if(data.msg==4){
                                 layer.msg('修改失败！');
                             }
                         }
               })
              });
              form.on('switch(kaiguanx2)', function(obj){
                  console.log(obj.value);
                  if(obj.value == "是") {
                    obj.value = "否"
                }else {
                    obj.value = "是"
                }
                var index = layer.load(1, {
                         shade: [0.7,'#111'] //0.1透明度的白色背景
                       });
                   $.ajax({
                         url:'',
                         data:{data:'{"BaiduSEO":"26","value":"'+obj.value+'","key":"tag_nofollow","term_id":"'+obj.elem.id+'","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'},
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
                             }else if(data.msg==4){
                                 layer.msg('修改失败！');
                             }
                         }
               })
              });
			});
		
		});
			</script>
			<ol id="joyRideTipContent">
        		<li data-id="box11" data-text="关闭" class="custom">
        			<h2>Stop #1</h2>
        			<p>关键词、链接都可以任意修改（点击即可修改关键词和链接），记得链接都必须要带http(s)://。这个关键词和链接都是自定义的，这种属于指定超链内链。另外新页面打开该功能是点击这个附有超链的关键词，会在新的窗口打开而不是在本页打开。nofollow是不让蜘蛛追踪，不让收录的意思。其他不明白的地方可以加Q群185975495</p>
        		</li>
	         </ol>
	          <script type="text/html" id="toolbarDemo">
              <div class="layui-btn-container">
                <button class="layui-btn layui-btn-sm" lay-event="getCheckData">删除选中行数据</button>
              </div>
            </script>
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