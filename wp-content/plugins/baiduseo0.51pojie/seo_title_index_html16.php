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
	.wztseo_tj {
	    float: left;
	    width: 500px;
	    height: 150px;
	    /*background-color: #efefef;*/
	    margin-right: 15px;
	    margin-bottom: 15px;
	    color: #fff;
	    background-image: linear-gradient(90deg,#40bdd9 0%,#2271b1 100%);
	    border-radius: 15px;
	    padding: 15px;
	}
	.wztseo_tj_l {
	    float: left;
	    width: 150px;
	    height: 150px;
	    overflow: hidden;
	    border-radius: 6px;
	}
	.wztseo_tj_l img {
	    width: 100%;
	}
	.wztseo_tj_l a {
	    display: inline-block;
	}
	.wztseo_tj_r {
	    float: right;
	    width: 330px;
	    text-align: center;
	}
	.wztseo_tj_r h3 {
	    text-align: center;
	    margin-bottom: 10px;
	}
	.wztseo_tj_r h3 a {
	    color: #fff;
	}
	.wztseo_tj_r p {
	    text-indent:2em;
	    overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
	}
	.wztseo_tj_r span {
	    display: block;
	    padding: 5px 0 3px;
	}
	.wztseo_tj_r>a {
	    color: #fff;
	    display: inline-block;
	    padding: 10px 30px;
	    width: 40px;
	    text-align: center;
	    border: 1px solid #fff;
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
				<li class="admin"><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=16">推荐插件</a></li>
			</ul>
		</div>
		<div class="wyy_shouye2">
			<a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo" style="margin-left: 12px;display: inline-block;padding: 8px 15px;background-color: #009688;border-radius:4px;color:#fff;">返回目录</a>
		</div>
		<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend>推荐插件</legend>
        </fieldset>
    	<a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=auto-reply-wechat"> </a>
    	<div style="width:1200px;overflow: hidden;margin:0 auto;">
    	    <div class="wztseo_tj">
        	    <div class="wztseo_tj_l">
        	        <a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=auto-reply-wechat">
        	            <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/weixingzh.png">
        	        </a>
        	    </div>
        	    <div class="wztseo_tj_r">
        	        <h3><a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=auto-reply-wechat">微信公众号自动回复</a></h3>
        	        <p>根据关键词自动获取网站相关文章：用户通过公众号发送关键词，公众号即可根据用户的关键词读取网站内相关的内容，推送URL回复用户。</p>
        	        <span>作者：郑州沃之涛科技有限公司</span>
        	        <a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=auto-reply-wechat">安装</a>
        	        <a href="https://luntan.rbzzz.com/thread/5" target="_blank">教程</a>
        	    </div>
        	</div>
        	<div class="wztseo_tj">
        	    <div class="wztseo_tj_l">
        	        <a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=nleilian-guanjc">
        	            <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/tagneilian.png">
        	        </a>
        	    </div>
        	    <div class="wztseo_tj_r">
        	        <h3><a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=nleilian-guanjc">内链tag标签</a></h3>
        	        <p>输入需要匹配所有文章的关键词，添加后，该关键词会将所有wordpress的网站文章关键词加超链，内链。（如果设置URL，则所有文章下的该关键词添加超链（内链）到指定地址。）</p>
        	        <span>作者：郑州沃之涛科技有限公司</span>
        	        <a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=nleilian-guanjc">安装</a>
        	        <a href="https://luntan.rbzzz.com/thread/4" target="_blank">教程</a>
        	    </div>
        	</div>
        	<div class="wztseo_tj">
        	    <div class="wztseo_tj_l">
        	        <a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=key-spider">
        	            <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/zhizhugjz.png">
        	        </a>
        	    </div>
        	    <div class="wztseo_tj_r">
        	        <h3><a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=key-spider">蜘蛛-关键字</a></h3>
        	        <p>开启插件以后，如果有百度、谷歌、360、搜狗、神马、必应、头条蜘蛛访问网站就会被统计。无需在查看网站日志，是比较省心的工具。</p>
        	        <span>作者：郑州沃之涛科技有限公司</span>
        	        <a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=key-spider">安装</a>
        	    </div>
        	</div>
        	<div class="wztseo_tj">
        	    <div class="wztseo_tj_l">
        	        <a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=wzbaibaoxiang">
        	            <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/baibaoxiang.png">
        	        </a>
        	    </div>
        	    <div class="wztseo_tj_r">
        	        <h3><a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=wzbaibaoxiang">网站百宝箱</a></h3>
        	        <p>含置顶, 网页宠物, 哀悼, 禁止复制, 禁止查看源码, 弹幕, 在线客服, 留言板, 手机客服, 网站背景, 公告, 跑马灯, 水印, 分享, 打赏, 海报图。</p>
        	        <span>作者：郑州沃之涛科技有限公司</span>
        	        <a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=wzbaibaoxiang">安装</a>
        	    </div>
        	</div>
    	</div>
  </div>
</div>