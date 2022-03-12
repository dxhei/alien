<?php if(!defined('ABSPATH'))exit;?>
<div id="divMain">
  <div class="SubMenu">
  </div>
 <style>
	#wpwrap{
		background-color:#fff;
	}
	.layui-btn{
		background-color:#01AAED;
	}
	#box66{
	    position:right;
	}
	.layui-form-select dl dd.layui-this {
	    background-color:#01AAED;
	}
  </style>

  <div id="divMain2">
  	<!--插件开始-->
		<div class=" wyy_shouye1">
			<ul>
				<li class="admin"><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=1">SEO首页/分类</a></li>
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
		    <div class="layui-show main_list wyy_shouye" style="width: 800px;">
		        <div id="startDemo" title="点击启动插件向导" class="point point-flicker">
			        <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/logo111.jpg">
			        <div class="shuoming">嗨，有不明白的地方可以点我！</div>
			    </div>
				<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				  <legend>SEO首页</legend>
				</fieldset>
				<form class="layui-form" action="" method="post" onsubmit="return false">
				  <div class="layui-form-item">
				    <label class="layui-form-label">关键词</label>
				    <div class="layui-input-block">
				      <input id="box11" type="text" name="keywords" autocomplete="off" placeholder="请输入关键词,多个关键词请使用英文逗号分离！！！" class="layui-input" value="<?php echo $seo['keywords']; ?>" style="margin-bottom: 10px;">
				      <span style="color:red;line-height: 20px;">注意：如果您开启了主题内设置的关键词，请不要重复该功能否则会出现重复情况。</span>
				    </div>
				     
				  </div>
				  
				  <div class="layui-form-item layui-form-text">
				    <label class="layui-form-label">首页描述</label>
				    <div class="layui-input-block">
				      <textarea placeholder="请输入首页描述" class="layui-textarea" name="description"><?php echo esc_textarea($seo['description']); ?></textarea>
				    </div>
				  </div>
				  <div class="layui-form-item">
				  	<input type="hidden" name="BaiduSEO" value="1">
				  	<input type="hidden" name="action" value="BaiduSEO">
				  	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
				    <div class="layui-input-block">
				      <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo1"id="box22">保存</button>
				    </div>
				  </div>
				</form>
				<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				  <legend>分类</legend>
				</fieldset>
                	<form class="layui-form cate_seo" action="" method="post" onsubmit="return false">
                        <div class="layui-form-item">
                          <label class="layui-form-label">请选择分类</label>
                          <div class="layui-input-inline" id="box33">
                            <select  lay-verify="required" lay-search="" name="cate" lay-filter="cate">
                                <?php
                                    foreach($cate as $key=>$val){
                                        echo '<option value="'.$val['term_id'].'">'.$val['name'].'</option>';
                                    }     
                                ?> 
                            </select>
                          </div>
                        </div>
    				  <div class="layui-form-item">
    				    <label class="layui-form-label">关键词</label>
    				    <div class="layui-input-block">
    				      <input id="box44" type="text" name="keywords"   autocomplete="off" placeholder="请输入关键词,多个关键词请使用英文逗号分离！！！" class="layui-input" value="" style="margin-bottom: 10px;">
    				      <span style="color:red;line-height: 20px;">注意：如果您开启了主题内设置的关键词，请不要重复该功能否则会出现重复情况。</span>
    				    </div>
    				     
    				  </div>
    				  
    				  <div class="layui-form-item layui-form-text">
    				    <label class="layui-form-label">描述</label>
    				    <div class="layui-input-block">
    				      <textarea placeholder="请输入描述"  class="layui-textarea" name="description" id="box55"></textarea>
    				    </div>
    				  </div>
    				  <div class="layui-form-item">
    				  	<input type="hidden" name="BaiduSEO" value="23">
    				  	<input type="hidden" name="action" value="BaiduSEO">
    				  	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
    				    <div class="layui-input-block">
    				      <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo23" id="box66">保存</button>
    				    </div>
    				  </div>
    				</form>
		    </div>
		    <ol id="joyRideTipContent">
        		<li data-id="box11" data-text="下一步" class="custom">
        			<h2>step #1</h2>
        			<p>wordpress没有自带TDK功能，只有网站标题功能，所以小编增加了该功能。该功能填写后，只是增加了首页的keywords,"关键词"、description,"描述"，如果您安装的主题已经有了该功能，请勿在重复使用该功能。</p>
        		</li>
        		<li data-id="box22" data-text="下一步">
        			<h2>step #2</h2>
        			<p>填写DK信息以后，保存即可，如果想取消，可以清空后在进行保存。</p>
        		</li>
        		<li data-id="box33" data-text="下一步">
        			<h2>step #3</h2>
        			<p>分栏页面SEO也是十分重要的，如果主题没有分栏填写TDK功能，那么小编的这个插件就十分实用，针对每个分类的栏目进行TDK关键词部署有效提高您的量站点优化质。</p>
        		</li>
        		<li data-id="box44" data-text="下一步">
        			<h2>step #4</h2>
        			<p>填写该栏目下的keywords,关键词</p>
        		</li>
        		<li data-id="box55" data-text="关闭">
        			<h2>step #5</h2>
        			<p>填写该栏目下的description,描述</p>
        		</li>
	      </ol>
	    <script type="text/javascript">		
        jQuery(document).ready(function($){
            
            $(".joyride-close-tip").on("click",function(){
                
                window.location.reload();
            })
            
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
		<script>
		jQuery(document).ready(function($){
			layui.use(['form','layer'], function(){
			  var form = layui.form
			  ,layer = layui.layer;
			 
			 form.on('select(cate)', function(data){
			     
             	$.ajax({
	        		url:'',
	        		type:'post',
	        		data:{data:'{"BaiduSEO":"25","id":'+data.value+',"nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'},
	        		dataType: 'json',
	        		success:function(data){
	        			if(data.msg){
	        			    if(data.cate.description!=undefined){
	        			       $('.cate_seo').find('textarea[name="description"]').val(data.cate.description); 
	        			    }else{
	        			        $('.cate_seo').find('textarea[name="description"]').val('');
	        			    }
	        			    if(data.cate.title!=undefined){
	        			        $('.cate_seo').find('input[name="title"]').val(data.cate.title); 
	        			    }else{
	        			        $('.cate_seo').find('input[name="title"]').val('');
	        			    }
	        			    if(data.cate.keywords!=undefined){
	        			        $('.cate_seo').find('input[name="keywords"]').val(data.cate.keywords); 
	        			    }else{
	        			        $('.cate_seo').find('input[name="keywords"]').val('');
	        			    }
	        			}else{
	        			     $('.cate_seo').find('textarea[name="description"]').val('');
	        			     $('.cate_seo').find('input[name="title"]').val('');
	        			     $('.cate_seo').find('input[name="keywords"]').val('');
	        			}
	        			
	        		}
	        	})
			    return false;
            });
			  //监听提交
			  form.on('submit(demo1)', function(data){
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
			  			}else{
			  				layer.msg('保存失败，请刷新后重试');
			  				
			  			}
			  		}
			  	})
			    return false;
			  });
			  
			form.on('submit(demo23)', function(data){
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
		})
	})
	</script>
  	<!--插件结束-->
<!--代码-->
  </div>
</div>