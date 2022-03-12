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
	.layui-input-block{
	    margin-left:30px;
	}
	.layui-btn {
	    background-color: #01AAED;
	}
	.layui-form-onswitch {
	    border-color: #01AAED;
        background-color: #01AAED;
	}
	.layui-form-select dl dd.layui-this {
	    background-color: #01AAED;
	}
	.layui-slider-bar {
	    background-color: #01AAED!important;
	}
	.layui-slider-wrap-btn {
	    border: 2px solid #01AAED!important;
	}
	.layui-input-block {
	    margin-left: 114px;
	}
	.layui-form-label {
	    width: 84px;
	}
	.biaoge1 {
	    width: 25%;
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
				<li class="admin"><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=5">sitemap</a></li>
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
			<a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo" style="margin-left: 12px;display: inline-block;padding: 8px 15px;background-color: #01AAED;border-radius:4px;color:#fff;">返回目录</a>
		</div>
		<div class="news" style="width: 1000px;float: left;">
		
		<div class="layui-tab-content">

			<div class="main_list" >
			    <div id="startDemo" title="点击启动插件向导" class="point point-flicker">
			        <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/logo111.jpg">
			        <div class="shuoming">嗨，有不明白的地方可以点我！</div>
			    </div>
				<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				  <legend>sitemap</legend>
				</fieldset>
				 
				<form class="layui-form" action="" onsubmit="return false">
				  <div class="layui-form-item">
				    <label class="layui-form-label">sitemap开关</label>
				    <div class="layui-input-block wyysh" id="box11">
				    	<?php if(isset($sitemap['site_auto']) && $sitemap['site_auto']==1){
				    		echo '<input type="checkbox" name="close" lay-skin="switch" lay-filter="switchTest" lay-text="开|关" checked="" class="xuanzhong">';       	 			    			  			
				    	}else{    		 	        	   			 
				    		echo '<input type="checkbox" name="close" lay-skin="switch" lay-filter="switchTest" lay-text="开|关">';     	  	 		    	  	  		
				    	}     			  	     	 	  		 
				    	?>
				    </div>
				  </div>
				  <div class="layui-form-item">
				    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    				  <legend>方法一</legend>
    				</fieldset>
    				<div class="layui-form-item auto_sc" <?php if(isset($sitemap['site_auto']) && $sitemap['site_auto']==1){ echo 'style="display:block"';}else{echo 'style="display:block"';}?>>
    				    <label class="layui-form-label">自动生成</label>
    				    <div class="layui-input-block" id="box22">
    				    	<?php if(isset($sitemap['open']) && ($sitemap['open']==1)){
    				    		echo '<input type="checkbox" name="open" lay-skin="switch" lay-text="开|关" checked="">';       	 			    			  			
    				    	}else{    		 	        	   			 
    				    		echo '<input type="checkbox" name="open" lay-skin="switch" lay-text="开|关">';     	  	 		    	  	  		
    				    	}     			  	     	 	  		 
    				    	?>
    
    				    </div>
    				    <span >开启后,每当发布文章就会自动更新sitemap。如果使用采集器会发生负荷问题,建议采集器用户不要开启该选项,建议使用宝塔定时任务更新。</span>
    				  </div>
    				  <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    				  <legend>方法二</legend>
    				</fieldset>
				  <div class="layui-form-item layui-form-text">
    				   <div class="layui-input-block wenti" id="box33" style="line-height: 60px;">计划任务URL：<button type="button" class="layui-btn get_plan_url" style="padding: 0 20px;">获取链接</button></div>
    					<div class="layui-input-block">计划任务需要配合宝塔定时触发：<a href="https://www.seohnzz.com/archives/135.html" target="_blank" style="color:#01AAED;" >详细点击阅读</a></div>
				  </div>
    				    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        				  <legend>方法三</legend>
        				</fieldset>
				    <label class="layui-form-label">自动计划任务</label>
				    <div class="layui-input-block wyysh" id="box11">
				     <?php 
				    		if(isset($sitemap) && ($sitemap['plan']==1)){    	 	    	      				 	    					 	      		 				
				    			 echo '<input type="checkbox" name="plan" lay-skin="switch" lay-text="开|关" checked="">';    	   			     	 	  	 	      	 		 	       					
				    		}else{    	  	         		  	      				          	 				
				    			echo '<input type="checkbox" name="plan" lay-skin="switch" lay-text="开|关">';     				  	      	 		 	    	   		 	     			 		 
				    		}     	 		 	     		 	 		      		  	 	     	     	
				    	?>
				    </div>
				     <p>自动推送,是采用WP的计划任务自动循环24小时执行推送,该方法适合大部分的用户使用。如果开启该功能,请不要使用计划任务避免重复！</p>
				     <p style="margin-left:114px;">因为"快速收录"权限配额很少,所以单独一个计划任务,请谨慎选项！！！</p>
				  </div>
				  
				  <div class="wyykuang" id="box44">
				      <ul>
				          <li>名称</li>
				          <li>别名</li>
				          <li>优先</li>
				          <li>变更频率</li>
				          <li>开关</li>
				      </ul>
				      <ul>
				          <li>文章</li>
				          <li>post</li>
				          <li>
				              <div id="slideTest1" class="demo-slider"></div>
				          </li>
				          <li>
				              <div class="layui-form-item">
                                    <div class="layui-input-block" style="width:90px;margin-left:50px;margin-top:12px;">
                                      <select name="post_time" lay-filter="aihao">
                                        <option value="always" <?php if(isset($sitemap['post_time']) && ($sitemap['post_time']=='always')){echo 'selected';}?>>总是</option>
                                        <option value="hourly" <?php if(isset($sitemap['post_time']) && ($sitemap['post_time']=='hourly')){echo 'selected';}?>>每小时</option>
                                        <option value="daily"  <?php if(isset($sitemap['post_time']) && ($sitemap['post_time']=='daily')){echo 'selected';}?>>每天</option>
                                        <option value="weekly" <?php if(isset($sitemap['post_time']) && ($sitemap['post_time']=='weekly')){echo 'selected';}?>>每周</option>
                                        <option value="monthly" <?php if(isset($sitemap['post_time']) && ($sitemap['post_time']=='monthly')){echo 'selected';}?>>每月</option>
                                        <option value="yearly" <?php if(isset($sitemap['post_time']) && ($sitemap['post_time']=='yearly')){echo 'selected';}?>>每年</option>
                                        <option value="never" <?php if(isset($sitemap['post_time']) && ($sitemap['post_time']=='never')){echo 'selected';}?>>从不</option>
                                      </select>
                                    </div>
                                  </div>
				          </li>
				          <li>
				               <div class="layui-form-item">
                                   <div class="layui-input-block" style="width:90px;margin-left:50px;margin-top:-4px;">
                                       	<?php if(isset($sitemap['type1']) && ($sitemap['type1']==1)){
                				    		echo '<input type="checkbox" name="type1" lay-skin="switch" lay-text="开|关" checked="">';       	 			    			  			
                				    	}else{    		 	        	   			 
                				    		echo '<input type="checkbox" name="type1" lay-skin="switch" lay-text="开|关">';     	  	 		    	  	  		
                				    	}     			  	     	 	  		 
                				    	?>
                                      
                                    </div>
                                  </div>
				          </li>
				      </ul>
				      <ul>
				          <li>页面</li>
				          <li>page</li>
				          <li>
				              <div id="slideTest2" class="demo-slider"></div>
				          </li>
				          <li>
				             <div class="layui-form-item">
                                    <div class="layui-input-block" style="width:90px;margin-left:50px;margin-top:12px;">
                                      <select name="page_time" lay-filter="aihao">
                                         <option value="always" <?php if(isset($sitemap['page_time']) && ($sitemap['page_time']=='always')){echo 'selected';}?>>总是</option>
                                        <option value="hourly" <?php if(isset($sitemap['page_time']) && ($sitemap['page_time']=='hourly')){echo 'selected';}?>>每小时</option>
                                        <option value="daily"  <?php if(isset($sitemap['page_time']) && ($sitemap['page_time']=='daily')){echo 'selected';}?>>每天</option>
                                        <option value="weekly" <?php if(isset($sitemap['page_time']) && ($sitemap['page_time']=='weekly')){echo 'selected';}?>>每周</option>
                                        <option value="monthly" <?php if(isset($sitemap['page_time']) && ($sitemap['page_time']=='monthly')){echo 'selected';}?>>每月</option>
                                        <option value="yearly" <?php if(isset($sitemap['page_time']) && ($sitemap['page_time']=='yearly')){echo 'selected';}?>>每年</option>
                                        <option value="never" <?php if(isset($sitemap['page_time']) && ($sitemap['page_time']=='never')){echo 'selected';}?>>从不</option>
                                      </select>
                                    </div>
                                  </div>
				          </li>
				          <li>
				               <div class="layui-form-item">
                                   <div class="layui-input-block" style="width:90px;margin-left:50px;margin-top:-4px;">
                                      <?php if(isset($sitemap['type2']) && ($sitemap['type2']==1)){
                				    		echo '<input type="checkbox" name="type2" lay-skin="switch" lay-text="开|关" checked="">';       	 			    			  			
                				    	}else{    		 	        	   			 
                				    		echo '<input type="checkbox" name="type2" lay-skin="switch" lay-text="开|关">';     	  	 		    	  	  		
                				    	}     			  	     	 	  		 
                				    	?>
                                    </div>
                                  </div>
				          </li>
				      </ul>
				      <ul>
				          <li>标签</li>
				          <li>tag</li>
				          <li>
				              <div id="slideTest3" class="demo-slider"></div>
				          </li>
				          <li>
				              <div class="layui-form-item">
                                    <div class="layui-input-block" style="width:90px;margin-left:50px;margin-top:12px;">
                                      <select name="tag_time" lay-filter="aihao">
                                        <option value="always" <?php if(isset($sitemap['tag_time']) && ($sitemap['tag_time']=='always')){echo 'selected';}?>>总是</option>
                                        <option value="hourly" <?php if(isset($sitemap['tag_time']) && ($sitemap['tag_time']=='hourly')){echo 'selected';}?>>每小时</option>
                                        <option value="daily"  <?php if(isset($sitemap['tag_time']) && ($sitemap['tag_time']=='daily')){echo 'selected';}?>>每天</option>
                                        <option value="weekly" <?php if(isset($sitemap['tag_time']) && ($sitemap['tag_time']=='weekly')){echo 'selected';}?>>每周</option>
                                        <option value="monthly" <?php if(isset($sitemap['tag_time']) && ($sitemap['tag_time']=='monthly')){echo 'selected';}?>>每月</option>
                                        <option value="yearly" <?php if(isset($sitemap['tag_time']) && ($sitemap['tag_time']=='yearly')){echo 'selected';}?>>每年</option>
                                        <option value="never" <?php if(isset($sitemap['tag_time']) && ($sitemap['tag_time']=='never')){echo 'selected';}?>>从不</option>
                                      </select>
                                    </div>
                                  </div>
				          </li>
				          <li>
				               <div class="layui-form-item">
                                   <div class="layui-input-block" style="width:90px;margin-left:50px;margin-top:-4px;">
                                      <?php if(isset($sitemap['type3']) && ($sitemap['type3']==1)){
                				    		echo '<input type="checkbox" name="type3" lay-skin="switch" lay-text="开|关" checked="">';       	 			    			  			
                				    	}else{    		 	        	   			 
                				    		echo '<input type="checkbox" name="type3" lay-skin="switch" lay-text="开|关">';     	  	 		    	  	  		
                				    	}     			  	     	 	  		 
                				    	?>
                                    </div>
                                  </div>
				          </li>
				      </ul>
				  </div>
				  <div class="biaoge"  id="box55" <?php if(isset($sitemap['site_auto']) && $sitemap['site_auto']==1){ echo 'style="display:block"';}else{echo 'style="display:none"';}?>style="display:none;margin-top:50px;">
					  <ul class="biaoge1">
					      <li>sitemap的XML地址</li>
					       <?php 
				  			if(isset($sitemap['sitemap_url']) && is_array($sitemap['sitemap_url'])){
				  			
				  				foreach($sitemap['sitemap_url'] as $key=>$val){
				  				    echo '<li><input type="text" value="'.$val.'" class="neirong"><span onclick="copy(this)">点击复制</span></li>';
				  				
				  				}
				  			}    
						  ?>
					  </ul>
					  <ul class="biaoge1">
					      <li>sitemap的HTML地址</li>
						  <?php 
				  			if(isset($sitemap['sitemap_htmlurl']) && is_array($sitemap['sitemap_htmlurl'])){   
				  				
				  				foreach($sitemap['sitemap_htmlurl'] as $key=>$val){
				  					echo ' <li><input type="text" value="'.$val.'" class="neirong"><span onclick="copy(this)">点击复制</span></li>';
				  				}
				  			}     
				  		?>
					  </ul>
					  <ul class="biaoge1">
					      <li>TAG的HTML地址</li>
						  <?php 
				  			if(isset($sitemap['sitemap_tag']) && $sitemap['sitemap_tag']){   
				  				
				  				
				  					echo ' <li><input type="text" value="'.$sitemap['sitemap_tag'].'" class="neirong"><span onclick="copy(this)">点击复制</span></li>';
				  				
				  			}     
				  		?>
					  </ul>
					  <ul class="biaoge1">
					      <li>生成时间</li>
						  <?php 
				  			if(isset($sitemap['time'])){    
				  			    echo '<li>'. $sitemap['time'].'</li>';
				  			}     	   	 	    	 		 		 
				   		  ?>
					  </ul>
				  </div>
				<!-- 手机样式 -->
				  <div class="biaoge2">
					  <ul class="biaoge2-1">
						  <li>sitemap的XML地址</li>
						  <?php 
				  			if(isset($sitemap['sitemap_url']) && is_array($sitemap['sitemap_url'])){
				  			
				  				foreach($sitemap['sitemap_url'] as $key=>$val){
				  				    echo '<li><input type="text" value="'.$val.'" class="neirong"><span onclick="copy(this)">点击复制</span></li>';
				  				
				  				}
				  			}    	   	 	       	 		 	
				   		?>
					  </ul>
					  <ul class="biaoge2-1">
						  <li>sitemap的HTML地址</li>
						  	<?php 
				  			if(isset($sitemap['sitemap_htmlurl']) && is_array($sitemap['sitemap_htmlurl'])){   
				  				
				  				foreach($sitemap['sitemap_htmlurl'] as $key=>$val){
				  					echo ' <li><input type="text" value="'.$val.'" class="neirong"><span onclick="copy(this)">点击复制</span></li>';
				  				}
				  			}       	   	      	  			
				   		?>
					  </ul>
					  <ul class="biaoge2-1">
						  <li>TAG的HTML地址</li>
						  	<?php 
				  			if(isset($sitemap['sitemap_tag']) && $sitemap['sitemap_tag']){   
				  				
				  			
				  					echo ' <li><input type="text" value="'.$sitemap['sitemap_tag'].'" class="neirong"><span onclick="copy(this)">点击复制</span></li>';
				  				
				  			}       	   	      	  			
				   		?>
					  </ul>
					  <ul class="biaoge2-1">
						  <li>生成时间</li>
						  <?php 
				  			if(isset($sitemap['time'])){    
				  			    echo '<li>'. $sitemap['time'].'</li>';
				  			}     	   	 	    	 		 		 
				   		  ?>
					  </ul>
				  </div>
				  <!-- 结束 -->
				  <div class="layui-form-item" style="margin-top:30px;">
				    <div class="layui-input-block">
				    	<input type="hidden" name="BaiduSEO" value="4">
				    	<input type="hidden" name="action" value="BaiduSEO">
				        <input type="hidden" name="level1" <?php if(isset($sitemap['level1'])){ echo 'value="'.$sitemap['level1'].'"';}else{echo 'value="70"';} ?>>
				        <input type="hidden" name="level2" <?php if(isset($sitemap['level2'])){ echo 'value="'.$sitemap['level2'].'"';}else{echo 'value="70"';} ?>>
				        <input type="hidden" name="level3" <?php if(isset($sitemap['level3'])){ echo 'value="'.$sitemap['level3'].'"';}else{echo 'value="70"';} ?>>
				    	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
				      <button type="button" class="layui-btn" lay-submit="" lay-filter="demo4" id="box66">保存</button>
				    </div>
				  </div>
				</form>
		    </div>
		     
		  </div>
		</div>
		<script>
			// 点击复制
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
			layui.use(['form', 'layer','element','slider'], function(){
			  var form = layui.form
			  ,layer = layui.layer
			  ,slider = layui.slider
			  ,element= layui.element;
			 //监听指定开关
			  form.on('switch(switchTest)', function(data){
			  	if(this.checked){
			 // 		$('.auto_sc').css('display','block');
			  		$(".biaoge").css('display','block');
			  	}else{
			 // 		$('.auto_sc').css('display','none');
			  		$(".biaoge").css('display','none');
			  	}
			  })
			  var level1 = <?php if(isset($sitemap['level1'])){ echo $sitemap['level1'];}else{echo 70;} ?>;
			  var level2 = <?php if(isset($sitemap['level2'])){ echo $sitemap['level2'];}else{echo 70;} ?>;
			  var level3 = <?php if(isset($sitemap['level3'])){ echo $sitemap['level3'];}else{echo 70;} ?>;
    			 slider.render({
                    elem: '#slideTest1',
                    step:10,
                    value:level1,
                    change: function(vals){
                        $('input[name="level1"]').val(vals);
                        
                    }
                });
                slider.render({
                    elem: '#slideTest2',
                    step:10,
                    value:level2,
                    change: function(vals){
                         $('input[name="level2"]').val(vals);
                    }
                });
                slider.render({
                    elem: '#slideTest3',
                    step:10,
                    value:level3,
                    change: function(vals){
                        $('input[name="level3"]').val(vals);
                    }
                });
			  form.on('submit(demo4)', function(data){
			        if(!data.field.type1 && !data.field.type2 && !data.field.type3){
			            layer.msg('请打开生成的开关');return;
			        }
			        data.field.page =1;
			  		sitemap_cat(data.field);
				    return false;
			  });
			  $('.get_plan_url').click(function(){
			      var index = layer.load(1, {
                      shade: [0.7,'#111'] //0.1透明度的白色背景
                    });
			     $.ajax({
	        		url:'<?php echo  admin_url( 'admin.php?page=baiduseo&baiduseo=1&map=1&plan=1' );?>',
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
			  function sitemap_cat(data_list){
			      var index = layer.load(1, {
                      shade: [0.7,'#111'] //0.1透明度的白色背景
                    });
        		$.ajax({
	        		url:'',
	        		type:'post',
	        		data:{data:JSON.stringify(data_list)},
	        		dataType: 'json',
	        		success:function(data){
	        			var clock1='';
	        			layer.close(index);
	        			if(data.msg==3){
			  				layer.confirm('该功能，需要点击“确定”后登录官网进行授权才可正常使用。', {
							  btn: ['确定','取消'] //按钮
							}, function(){
							 window.open("https://www.rbzzz.com/qxcp.html",'top');   
							}, function(){
							  
							});	
	        			}else if(data.msg==1){
	        				var no=++data.num;
						    data_list.page=no;
	        				var clock1 = setInterval(sitemap_cat(data_list), 10); 
	        			}else{
	        				if(typeof(clock1) == undefined ){
								
	        				}else{
	        					clearInterval(clock1);
	        				}
							layer.msg('操作成功');
							location.reload();
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
        			<p>开启该功能，就会根据您的网站生成sitemap地图，关闭则自动或定时生成失效，但缓存文件还在。</p>
        		</li>
        		<li data-id="box22" data-text="下一步">
        			<h2>step #2</h2>
        			<p>方法一：该功能是您每次发布文章sitemap地图就会跟着更新一次，这种是十分浪费资源的，建议文章在2000以下用户使用。注：采集文章用户请勿使用开启该功能，会导致服务器负荷过大</p>
        		</li>
        		<li data-id="box33" data-text="下一步">
        			<h2>step #3</h2>
        			<p>方法二：宝塔计划任务，建议设置凌晨，服务器访问任谁最少的时候使用，减少服务器压力。</p>
        		</li>
        		<li data-id="box44" data-text="下一步">
        			<h2>step #4</h2>
        			<p>根据自己的SEO习惯可以改变sitemap地图的权重等级，有效的引导蜘蛛快速抓取。不懂的小白可以进入Q群1077537009进行交流学习。</p>
        		</li>
        		<li data-id="box55" data-text="下一步">
        			<h2>step #5</h2>
        			<p>开启以后生成的XML地址可以提交给百度站长进行收录引导，HTML地址可以当做网站地图导航，里面是网站下所有的文章链接标签链接等，建议放在首页底部引导蜘蛛抓取提升SEO效果。</p>
        		</li>
        		<li data-id="box66" data-text="关闭">
        			<h2>step #6</h2>
        			<p>每次操作，都需要保存哦，否则不生效。</p>
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