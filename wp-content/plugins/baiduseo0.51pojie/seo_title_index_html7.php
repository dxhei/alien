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
				<li class="admin"><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=7">alt/tag内链</a></li>
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
		<div class="news" style="float: left;width: 1000px;">
		
		<div class="layui-tab-content"  id="box">

			<div class="main_list" >
			   <div id="startDemo" title="点击启动插件向导" class="point point-flicker">
			        <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/logo111.jpg">
			        <div class="shuoming">嗨，有不明白的地方可以点我！</div>
			    </div>
		    	<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				  <legend>alt/title属性</legend>
				</fieldset>
				<form class="layui-form" action="" onsubmit="return false">
				 <div class="layui-form-item">
                    <label class="layui-form-label">alt属性</label>
                    <div class="layui-input-block">
                      <input type="radio" name="alt" value="0" title="关闭" <?php if((isset($alt['alt']) && ($alt['alt']==0))|| !isset($alt['alt'])){ echo 'checked=""';}?>>
                      <input type="radio" name="alt" value="1" title="文章标题" <?php if(isset($alt['alt']) && ($alt['alt']==1)){ echo 'checked=""';}?>>
                      <input type="radio" name="alt" value="2" title="图片名称" <?php if(isset($alt['alt']) && ($alt['alt']==2)){ echo 'checked=""';}?>>
                    </div>
                  </div>
			      <div class="layui-form-item">
                    <label class="layui-form-label">title属性</label>
                    <div class="layui-input-block">
                      <input type="radio" name="title" value="0" title="关闭" <?php if((isset($alt['title']) && ($alt['title']==0))|| !isset($alt['title'])){ echo 'checked=""';}?>>
                      <input type="radio" name="title" value="1" title="文章标题" <?php if(isset($alt['title']) && ($alt['title']==1)){ echo 'checked=""';}?>>
                      <input type="radio" name="title" value="2" title="图片名称" <?php if(isset($alt['title']) && ($alt['title']==2)){ echo 'checked=""';}?> >
                    </div>
                  </div>
				  <div class="layui-form-item">
				   		<div class="layui-input-block">主要是利于网站优化的一种，因为图片没有Alt标签会被搜索引擎对网站减分降低权重，因为我们不可能每个图片都可以去添加Alt标签，所以该功能可以大大降低人工成本，将Alt标签自动读取标题及相关的关键词，可以批量优化，减少人工添加的烦恼，从而提升网站权重。</div>
				   		<div class="layui-input-block" style="color:red">（注意：atl标签开启后改变的是文章内img图片,缩略图是因主题影响无法添加alt标签，如果您有特殊要求可以联系客服人工处理。）</div>
				   		
				  </div>
				  <div class="layui-form-item">
				    <div class="layui-input-block">
				    	<input type="hidden" name="BaiduSEO" value="6">
				    	<input type="hidden" name="action" value="BaiduSEO">
				    	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
				      <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo6" id="box11">保存</button>
				    </div>
				  </div>
				</form>
					<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				  <legend>tag标签内链</legend>
				</fieldset>
				<form class="layui-form" action="" onsubmit="return false">
				  <div class="layui-form-item">
				    <label class="layui-form-label">标签超链</label>
				    <div class="layui-input-block dianji" id="box22">
				    	<?php 
				    		if(isset($Tag_manage['open'])&& ($Tag_manage['open']==1)){
								echo '<input type="checkbox" name="open" lay-skin="switch" lay-text="开|关" checked="">';		    				  	 
				      		}else{    	   		      	 	    	    			 		 	
				      			echo '<input type="checkbox" name="open" lay-skin="switch" lay-text="开|关" >';		
				      		}        	     
				      	?>	        		 	  
				      	<span style="color:red;vertical-align:-webkit-baseline-middle" class="right">提示：请开启该功能以后，在进行关键词添加。否则无法关联URL！</span>
				    </div>
				  </div>
				
					  <div class="layui-form-item">
					      <label class="layui-form-label">自动添加</label>
					      <div class="layui-input-block dianji" id="box33">
					      	<?php 
					      		if(isset($Tag_manage['auto'])&& ($Tag_manage['auto']==1)){
					  				echo '<input type="checkbox" name="auto" lay-skin="switch" lay-text="开|关" checked="">';		    				  	 
					        		}else{    	   		      	 	    	    			 		 	
					        			 echo '<input type="checkbox" name="auto" lay-skin="switch" lay-text="开|关" >';		
					        		}            	     
					        	?>	        		 	  
					        	<span style="color:red;vertical-align:-webkit-baseline-middle" class="right">提示：开启后添加标签、发布文章、修改文章、文章与标签会自动关联</span>
					      </div>
					    </div>
					    <div class="layui-form-item">
					      <label class="layui-form-label">标签限制</label>
					      <div class="layui-input-block dianji" id="box33">
					      	
					  			<input type="text" name="num" value="<?php if(isset($Tag_manage['num'])&& ($Tag_manage['num'])){echo $Tag_manage['num']; }?>">		    				  	 
					        	  		 	  
					        	<span style="color:red;vertical-align:middle" class="right">提示：开启后，每当您新发布文章或修改文章，都会自动限制TAG标签数量。</span>
					      </div>
					    </div>
					     <div class="layui-form-item">
					      <label class="layui-form-label">是否加粗</label>
					      <div class="layui-input-block" id="box44">
					        <input type="radio" name="bold" value="0" title="否" <?php if($Tag_manage['bold']==0){echo 'checked=""';}?>>
					        <input type="radio" name="bold" value="1" title="是" <?php if($Tag_manage['bold']==1){echo 'checked=""';}?>>
					      </div>
					    </div>
					    <div >
					    	<label class="layui-form-label">改变颜色</label>
					      <div class="layui-input-block" id="box55">
					        <div class="layui-input-inline" style="width: 120px;">
					          <input type="text" value="<?php echo $Tag_manage['color']; ?>" name="color" placeholder="请选择颜色" class="layui-input" id="test-form-input">
					        </div>
					        <div class="layui-inline" style="left: -4px;">
					          <div id="test-form"></div>
					        </div>
					  </div>
					  </div>
					  <div class="layui-form-item" style="margin-top:15px">
					    	<input type="hidden" name="BaiduSEO" value="21">
					    	<input type="hidden" name="action" value="BaiduSEO">
					    	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
					      <div class="layui-input-block">
					        <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo21">保存</button>
					        
					      </div>
					  </div>
					  </form>
					  			
					  	<div class="list_key1">
					  	<form class="layui-form" action="" onsubmit="return false">
					  	<div class="layui-block list" style="overflow: hidden;">
					  		<label class="layui-form-label" style="float: left;">关键词/标签</label>
					  		<textarea placeholder="tag标签:关键词 &#13;&#10超链关键词:关键词,http(s)://www.baidu.com" lay-verify="required" style="width: 298px;height: 200px;float: left;margin-right:15px;" class="wenben" name="content"></textarea>
					  		 <div class="layui-form-item" style="margin-top:50px">
    					    	<input type="hidden" name="BaiduSEO" value="18">
    					    	<input type="hidden" name="action" value="BaiduSEO">
    					    	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
    					      <div class="layui-input-block" style="padding-top:10px;">
    					         <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo18" style="margin-left:0;">提交</button>
    					        <a id="box66" class="layui-btn layui-btn-normal ass" href="/wp-admin/edit-tags.php?taxonomy=post_tag" style="color:#fff;padding:0 36px;margin-left: 0;">标签列表</a>
                                <a id="box77" class="layui-btn layui-btn-normal ass" href="/wp-admin/admin.php?page=baiduseo&book=14" style="color:#fff;padding:0 36px;margin-left: 0;">内链列表</a>
    					      </div>
					        </div>
					  		<div style="float: left;color: red;margin-left: 110px;"  id="box88">
					  			<p>1、tag标签添加方法，只需要添加需要的tag关键词，一行一个提交即可。</p>
					  			<p>2、添加超链关键词，需要“关键词”“（英文逗号）“超链ULR”</p>
					  			<p>3、tag标签，是会生成tag标签列表页面，超链关键词则直接指向添加的超链URL地址。</p>
					  			<p>4、批量添加TAG标签会自动检测重复的tag标签，避免重复。</p>
					  		</div>
					  		
					  	</div>
					  	</form>
					  	</div>
				
					<div style="border-top:1px solid #ccc;border-bottom:1px solid #ccc;min-height:200px;margin-top:20px">
					    <form class="layui-form" action="" onsubmit="return false">
            				 <div class="layui-form-item" pane="">
            				    <label class="layui-form-label">每秒添加文章数</label>
            				    <div class="layui-input-block" id="box99">
            				      <input type="radio" name="tag_num" value="20" title="20条" checked="">
            				      <input type="radio" name="tag_num" value="50" title="50条">
            				      <input type="radio" name="tag_num" value="100" title="100条" >
            				    </div>
            				  </div>
            				   <div class="layui-form-item">
                				    <label class="layui-form-label">每篇文章限制标签数</label>
                				    <div class="layui-input-inline" id="box10">
                				      <input type="text" name="article_tag_num"  lay-verify="required" autocomplete="off" placeholder="请输入" class="layui-input" >
                				    </div>
                				      <label class="layui-form-label" style="width:10px;padding:9px 0px" ></label>
                				  </div>
            			 </form>
            			 <div class="layui-progress layui-progress-big tag_solid" lay-showpercent="true" style="display:none;margin-bottom: 10px;">
				  
    						 <div class="layui-progress layui-progress-big" lay-showpercent="true" lay-filter="demo">
    						  <div class="layui-progress-bar layui-bg-red" lay-percent="1%"></div>
    						</div>
    					</div>
						<div style="margin-left: 110px;">
							<button type="button" class="layui-btn layui-btn-normal pltj_tag"  id="box111">一键添加标签</button>
							<p style="color:red;margin-top:10px;">提示：该功能会批量修改文章所有关联的标签，请谨慎使用</p>
						</div>
					</div>
		    </div>
		  </div>
		</div>
           </script>
    <ol id="joyRideTipContent">
        		<li data-id="box11" data-text="下一步" class="custom">
        			<h2>step #1</h2>
        			<p>该功能是针对文章内的图片如果没有alt或者title标签那么对SEO是非常不利的，如果您的主题带有该功能请不要在开启。</p>
        		</li>
        		<li data-id="box22" data-text="下一步">
        			<h2>step #2</h2>
        			<p>该功能是文章内的TAG标签及关键词加超链链接，这个关闭，则文章内的关键词链接就会消失，可以自己测试下。另外，在加关键词或TAG标签的时候一定要保持这个开启状态，否则无法关联链接。</p>
        		</li>
        		<li data-id="box33" data-text="下一步">
        			<h2>step #3</h2>
        			<p>该功能，是你标签库或内链库已经添加了一批关键词，那么你在发布文章的时候，如果文章内有关键词跟标签库或内链库的关键词匹配到，那么就自动添加及关联该关键词。</p>
        		</li>
        		<li data-id="box44" data-text="下一步">
        			<h2>step #4</h2>
        			<p>该功能就是为了分别已经关联到的关键词或者添加的关键词可以加粗，明显点。</p>
        		</li>
        		<li data-id="box55" data-text="下一步">
        			<h2>step #5</h2>
        			<p>该功能就是为了分别已经关联到的关键词或者添加的关键词可以自定义改变字体颜色，方便确认是否关联。</p>
        		</li>
        		<li data-id="box66" data-text="下一步">
        			<h2>step #6</h2>
        			<p>该功能就是查看已经添加的TAG标签关键词列表，这个是wordpress原生自带的列表，我们只不过给了个跳转，方便使用而已。（注：你在下面添加的TAG标签关键词，在这里可以查看都添加了哪些并且关联了多少篇文章。）</p>
        		</li>
        		<li data-id="box77" data-text="下一步">
        			<h2>step #7</h2>
        			<p>内链列表，是在下列功能中自定义添加的超链地址及关键词，这种自定义的不是标签，所以小编收到一个粉丝要求，发现这个确实需要分开处理，就单独做了一个自定义的内链列表，两个是相互不冲突的。并且可以单独修改自定义的内链关键词。（注：TAG标签是类似于列表页分类，内链关键词是自定义跳转到指定连接，例如首页，或者分类页都可以。）如果不懂使用可以加小编微信或QQ咨询，Q群也会有很多热心粉丝提供帮助。</p>
        		</li>
        		<li data-id="box88" data-text="下一步">
        			<h2>step #8</h2>
        			<p>麻烦你仔细看1-4条的说明，很简单，也就是直接输入关键词，提交。是提交到TAG标签词库。输入关键词逗号加网址，是提交到内链列表，一个是标签词库，一个是自定义关键词词库，用法不同，我是二合一。</p>
        		</li>
        		<li data-id="box99" data-text="下一步">
        			<h2>step #9</h2>
        			<p>该功能，主要是如果你的词库导入了上百上千上万个关键词，那么每篇文章可能匹配到几十个关键词，这样在SEO上属于是堆砌行为，过于优化。所以如果想限制每篇文章有多少个关键词就需要使用功能进行限制，例如：我每篇文章原本有10个关键词，那么想想限制成5个，那么就输入5个，就进行批量限制，每次处理20篇文章、50篇文章，看自己服务器是否强大自己选择。如果你服务器不够强大，就最低20条就行了。</p>
        		</li>
        		<li data-id="box10" data-text="下一步">
        			<h2>step #10</h2>
        			<p>就像上面说的，你每篇文章关键词都有10个以上你想限制成5个，那么就在这里输入自己想要限制的数量即可。</p>
        		</li>
        		<li data-id="box111" data-text="关闭">
        			<h2>step #11</h2>
        			<p>这是最后一步，该功能尽量不要胡乱使用，因为一旦限制以后部分关键词就会关联失效，变成死链。如果你是新站可以随意处理，老站使用该功能尤其要注意啊！有啥问题还是问小编吧！</p>
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
		
		<script>
		jQuery(document).ready(function($){
			layui.use(['form', 'layer','element','colorpicker'], function(){
			  var form = layui.form
			  ,layer = layui.layer
			  ,colorpicker = layui.colorpicker
			  ,element= layui.element;
			 
				var color = "<?php echo $Tag_manage['color']; ?>";
			   //表单赋值
				  colorpicker.render({
				    elem: '#test-form'
				    ,color: color
				    ,done: function(color){
				      $('#test-form-input').val(color);
				    }
				  });
			 
			
			

			  form.on('submit(demo6)', function(data){
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
			  				layer.alert('操作成功');
			  			}
			  		}
			  	})
			    return false;
			  });
			   form.on('submit(demo18)', function(data){
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
			  			    layer.msg('操作成功');
			  			    location.reload()
			  			}else{
			  			    layer.msg('操作失败');
			  			}
			  		}
			  	})
			    return false;
			  });
			  form.on('submit(demo21)', function(data){
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
			  			    layer.msg('保存成功');
			  			    location.reload()
			  				
			  			}else{
			  				layer.msg('保存失败，请刷新后重试');
			  			}
			  		}
			  	})
			    return false;
			});
			
			  $('.pltj_tag').click(function(){
			    layer.confirm('免责声明：该功能一单使用就会批量改变tag标签库，将无法还原。如果您对该功能很了解，可点击“确定”继续操作。如果对该功能不熟悉，请点击“取消”后再群内或联系作者咨询。（建议自行测试后在做决定）', {
                  btn: ['确定','取消'] //按钮
                }, function(){
                  layer.closeAll('dialog');
                  var page = $('input[name="tag_num"]:checked').val();
			      var tag_num = $('input[name="article_tag_num"]').val();
			      if(!tag_num){
			          layer.msg('请输入要添加标签数');return false;
			      }
		      	  $('.tag_solid').css('display','block');
			      pltj_tag(1,element,page,tag_num);
                }, function(){
                    
                });
			      
			  })
			  	function pltj_tag(num,element,page,tag_num){
		        	$.ajax({
		        		url:'',
		        		type:'post',
		        		data:{data:'{"BaiduSEO":"22","num":"'+num+'","page":"'+page+'","tag_num":"'+tag_num+'","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'},
		        		dataType: 'json',
		        		success:function(data){
		        			var cl_tag='';
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
	        						var cl_tag = setInterval(pltj_tag(no,element,page,data.tag_num), 10); 
		        				
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
  	<!--插件结束-->
<!--代码-->
  </div>
</div>