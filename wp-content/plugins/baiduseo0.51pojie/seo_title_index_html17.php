<?php if(!defined('ABSPATH'))exit;?>
<div id="divMain">
  <div class="SubMenu">
  </div>


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
				<li class="admin"><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=17">原创率检测</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=13">功能授权</a></li>
				<li><a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo&book=16">推荐插件</a></li>
			</ul>
		</div>
		<div class="wyy_shouye2">
			<a href="<?php echo $siteurl;?>/wp-admin/admin.php?page=baiduseo" style="margin-left: 12px;display: inline-block;padding: 8px 15px;background-color: #009688;border-radius:4px;color:#fff;">返回目录</a>
		</div>
		<!--<div id="startDemo" title="点击启动插件向导" class="point point-flicker">-->
	 <!--       <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/logo111.jpg">-->
	 <!--       <div class="shuoming">嗨，有不明白的地方可以点我！</div>-->
	 <!--   </div>-->
		<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend>原创率检测</legend>
        </fieldset>
        <form class="layui-form" action="" onsubmit="return false" style="display: flex;">
            <div class="layui-form-item">
                <label class="layui-form-label" style="width:100px;">自动原创检测：</label>
                <div class="layui-input-block" id="box11" style="margin-left: 131px;">
                    <?php 
	    		if(isset($baiduseo_wyc_jc['open']) && ($baiduseo_wyc_jc['open']==1)){
	    			 echo '<input type="checkbox" name="open" lay-skin="switch" lay-text="开|关" checked="">';
	    		}else{
	    			echo '<input type="checkbox" name="open" lay-skin="switch" lay-text="开|关">';
	    		}
	    	?>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block" style="margin: 0;padding: 13px 5px;">开启后，每天自动消耗所有积分检测文章原创度。
                </div>
                
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label" style="width:100px;">自动伪原创：</label>
                <div class="layui-input-block" id="box11" style="margin-left: 131px;">
                    <?php 
	    		if(isset($baiduseo_wyc_jc['auto']) && ($baiduseo_wyc_jc['auto']==1)){
	    			 echo '<input type="checkbox" name="auto" lay-skin="switch" lay-text="开|关" checked="">';
	    		}else{
	    			echo '<input type="checkbox" name="auto" lay-skin="switch" lay-text="开|关">';
	    		}
	    	?>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block" style="margin: 0;padding: 13px 5px;">文章低于50%以下自动伪原创。<span style="color:red;">伪原创后文章可读性较低且文章无法还原，请谨慎开启。</span>
                </div>
                
            </div>
            
            <div class="layui-form-item">
                <div class="layui-input-block" style="margin:0;text-align: center;">
                    <input type="hidden" name="BaiduSEO" value="40">
                    <input type="hidden" name="action" value="BaiduSEO">
                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo11">保存</button>
                </div>
            </div>
        </form>
        <div class="baiduseo_yaunc">
            <div class="baiduseo_yauncbx" id="box11">
                <p>会员状态</p>
                <span>
                    <?php if($content==0){echo '免费用户';}elseif($content==1){echo '年VIP';}elseif($content==2){echo '永久VIP';}?>
                </span>
                <!--<div>到期时间：<i>------</i></div>-->
            </div>
            <div class="baiduseo_yauncbxs">
                <p>每日积分</p>
                <div>
                    <?php if($content==0){echo 0;}elseif($content==1){echo 10;}elseif($content==2){echo 10;}?>
                </div>
            </div>
            <div class="baiduseo_yauncbxs">
                <p>当前剩余积分</p>
                <div><?php if(isset($baiduseo_jifen['sy']) && $baiduseo_jifen['sy']){echo $baiduseo_jifen['sy'];}else{echo "-";} ?></div>
            </div>
            <div class="baiduseo_yauncbxs">
                <p>共计消费积分</p>
                <div><?php if(isset($baiduseo_jifen['kc_total'])){echo $baiduseo_jifen['kc_total'];}else{echo 0;} ?></div>
            </div>
        </div>
        <div class="baiduseo_yauncsm">
            
        </div>
        <div class="baiduseo_yauncls" >
            <div class="baiduseo_yauncls1">
                <div>文章标题</div>
                <div>字数</div>
                <div>扣除积分</div>
                <div>原创度</div>
                <div>提交时间</div>
                <div >操作</div>
            </div>
            <div id="baiduseo_yc">
                <?php foreach($post as $key=>$val){?>
                <div class="baiduseo_yaunclsbd">
                    <div class="baiduseo_yauncls2">
                        <div><?php if(isset($val['post_title']) && $val['post_title'] ){echo $val['post_title'];}else{echo '我的文章';};?></div>
                        <div><?php if(isset(unserialize($val['meta_value'])['num'])){echo unserialize($val['meta_value'])['num'];}else{echo '检测中';};?></div>
                        <div><?php if(isset(unserialize($val['meta_value'])['num'])){echo ceil(unserialize($val['meta_value'])['num']/1000);}else{echo '检测中';}?></div>
                        <div><?php if(isset(unserialize($val['meta_value'])['yc'])){echo unserialize($val['meta_value'])['yc'].'%';}else{echo '检测中';}?></div>
                        <div><?php if(isset(unserialize($val['meta_value'])['addtime'])){echo unserialize($val['meta_value'])['addtime'];}else{echo '检测中';}?></div>
                        <div>
                            <a href="javascript:;" class="baiduseo_ycck" >查看</a>
                            <a href="javascript:;" class="baiduseo_ycsc" style="background-color:red;" data-id="<?php echo $val['ID']; ?>">删除</a>
                        </div>
                        
                    </div>
                    <div class="baiduseo_yauncbd">
                        <p>检测区</p>
                        <div class="baiduseo_yauncbdjc">
                            <?php if(isset(unserialize($val['meta_value'])['content_edit'])){echo unserialize($val['meta_value'])['content_edit'];}else{echo '<div style="padding:50px 0;text-align:center;">检测中</div>';}?>
                        </div>
                        <div class="baiduseo_ycti">
                            <p>1、原创率检测主要是针对文本在搜索引擎是否已经被收录过进行检测，如果已经收录过那么就认定为抄袭，搜索引擎会不在收录该篇文章。</p>
                            <p>2、建议将文章质量提升到90%以上在进行发布，利于搜索引擎收录抓取。</p>
                            <p>3、因为文章当中含有特殊符号，以检测报告当中的字数为准。</p>
                            <p>4、大量的抄袭文章会导致网站不收录及降权问题。</p>
                            <p>5、检测区域当中的红字代表重复率较高。</p>
                            <p>6、检测一般1~5分钟即可出结果。</p>
                            <p>7、已检测的文章，可以删除记录以后，在文章列表页重新点击检测。</p>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
            <div id="baiduseo_yctibg">

            </div>
            <div class="baiduseo_yts">
                请在文章列表选择要检测的文章
            </div>
        </div>
  	<!--插件结束-->
<!--代码-->
  </div>
</div>
<script type="text/javascript" charset="utf-8">
    jQuery(document).ready(function($){
        layui.use(['form', 'layer'], function(){
			  var form = layui.form
			  ,layer = layui.layer
                form.on('submit(demo11)', function(data) {
                        var index = layer.load(1, {
                            shade: [0.7, '#111'] //0.1透明度的白色背景
                        });
                        $.ajax({
                            url: '',
                            data: {
                                data: JSON.stringify(data.field)
                            },
                            type: 'post',
                            dataType: 'json',
                            success: function(data) {
                                layer.close(index);
                                if (data.msg == 1) {
                                    layer.alert('操作成功');
                                } else if (data.msg == 3) {
                                    layer.confirm('该功能，需要点击“确定”后登录官网进行授权才可正常使用。', {
                                        btn: ['确定', '取消'] //按钮
                                    }, function() {
                                       window.open("https://www.rbzzz.com/qxcp.html",'top');    
                                    }, function() {

                                    });
                                } else {
                                    layer.msg('操作失败，请刷新后重试');
                                }
                            }
                        })
                        return false;
                    });
                $("body").on("click",'.baiduseo_ycsc',function() {
                    // $(this).parents(".testing_jcbdnav").remove()
                    var id = $(this).attr('data-id');
                     $.ajax({
                            url: '',
                            data: {
                                data: '{"id":"' + id + '","BaiduSEO":"38","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'
                            },
                            type: 'post',
                            dataType: 'json',
                            success: function(res) {
                                if(res.msg){
                                    layer.msg('删除成功')
                                    location.reload();
                                }else{
                                   layer.msg('删除失败')
                                }
                            }
                        })
                        return false;
                    alert("删除")
                })
        })
        $("body").on("click",'.baiduseo_ycck',function() {
            var ck_ht = $(this).parents(".baiduseo_yauncls2").siblings().height()
            if (ck_ht > 0) {
                $(this).parents(".baiduseo_yauncls2").siblings().css({ "padding": "0", "height": "0", "margin-bottom": "0" })
            } else {
                $(this).parents(".baiduseo_yauncls2").siblings().css({ "padding": "10px 0", "height": "562px", "margin-bottom": "10px" })
            }
        })
        var wzlist = $("#baiduseo_yc>div").length
        if(wzlist == 0){
            $(".baiduseo_yts").css("display","block")
        }
        function paval() {
            if(wzlist > 20) {
                $("#baiduseo_yc>div").filter(":gt(19)").hide()
            }
        }
        paval()
        // console.log(wzlist)
        var wzpage = wzlist/20
        function jcpage() {
            for(var i = 0;i < wzpage; i++) {
                var pages = i + 1
                $("#baiduseo_yctibg").append(
                    "<a href='javascript:;' id="+ pages +">"+ pages +"</a>" 
                );
            }
            $("#baiduseo_yctibg>a").css("display","inline-block")
            $("#baiduseo_yctibg>a").filter(":gt(4)").hide()
        }
        jcpage()
        $("body").on("click","#baiduseo_yctibg a",function() {
            var pageid = parseInt(this.id)
            // console.log(pageid)
            var qianpa = (pageid - 1)*20
            var houpa = qianpa + 20
            $("#baiduseo_yc>div").css("display","block")
            $("#baiduseo_yc>div").filter(":lt("+ (qianpa ) +")").hide()
            $("#baiduseo_yc>div").filter(":gt("+ (houpa -1) +")").hide()
            $(this).css({"background-color":"skyblue","color":"#fff"}).siblings().css({"background-color":"#fff","color":"#111"})
            if(pageid > 3) {
                $("#baiduseo_yctibg>a").css("display","inline-block")
                $("#baiduseo_yctibg>a").filter(":lt("+ (pageid - 3) +")").hide()
                $("#baiduseo_yctibg>a").filter(":gt("+ (pageid + 1) +")").hide()
            }else {
                 $("#baiduseo_yctibg>a").css("display","inline-block")
                 $("#baiduseo_yctibg>a").filter(":gt(4)").hide()
            }
        })
    })
</script>
<!--	<ol id="joyRideTipContent">-->
<!--    	<li data-id="box11" data-text="关闭" class="custom">-->
<!--    		<h2>step #1</h2>-->
<!--    		<p>生成的死链地址，复制以后可以提交给百度站长，百度站长有死链的提交窗口。</p>-->
<!--    	</li>-->
<!--    </ol>-->
<!--	<script type="text/javascript">		-->
<!--    jQuery(document).ready(function($){-->
<!--        $("#startDemo").click(function(){-->
<!--               	$(this).joyride();-->
<!--            })-->
<!--            var shenchu = setInterval(function() {-->
<!--		        shengchucd()-->
<!--		    }, 5000);-->
<!--        	$("#startDemo").hover(function(){-->
<!--        	    clearInterval(shenchu);-->
<!--		        $(".shuoming").css({"right":"60px","width":"225px"})-->
<!--			},function(){-->
<!--			    shenchu = setInterval(function() {-->
<!--			        shengchucd()-->
<!--			    }, 5000);-->
<!--			})-->
<!--		    var shengchucd = function() {-->
<!--                if($(".shuoming").css("right") == "10px") {-->
<!--                    $(".shuoming").css({"right":"60px","width":"225px"})-->
<!--                }else {-->
<!--                    $(".shuoming").css({"right":"10px","width":"0px"})-->
<!--                }-->
<!--            }-->
<!--    });-->
<!--</script>-->
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
	.baiduseo_yaunc  {
	    display: flex;
	    width: 51%;
        margin: 0 auto;
	}
	.baiduseo_yaunc>div {
	    margin: 20px;
	    box-sizing: border-box;
	    padding: 10px 30px;
	    border-radius: 7px;
        background-image: linear-gradient(90deg,#40bdd9 0%,#2271b1 100%);
        color: #fff;
        min-width: 180px;
        box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.4);
	}
	.baiduseo_yauncbx {
	    text-align: center;
	}
	.baiduseo_yauncbx p {
	    text-align: center;
	    font-size: 18px;
        font-weight: 300;
        padding: 7px 0;
	}
	.baiduseo_yauncbx span {
	    font-size: 24px;
        font-weight: 700;
        color: red;
        background-image: -webkit-linear-gradient(bottom, rgb(255, 203, 0), rgb(255, 246, 0));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
	}
	.baiduseo_yauncbx div {
	    text-align: center;
	    padding: 10px 0;
        font-size: 12px;
	}
	.baiduseo_yauncbxs p {
	    text-align: center;
        font-size: 18px;
        font-weight: 300;
        /*padding: 27px 0 0;*/
        padding: 10px 0 0;
	}
	.baiduseo_yauncbxs div {
	    text-align: center;
        font-size: 25px;
        font-weight: 700;
	}
	i {
	    font-style: normal;
	}
	.baiduseo_yauncls {
        padding: 10px;
        font-size: 16px;
        font-weight: 300;
	}
	.baiduseo_yauncls1 {
	    overflow: hidden;
        border-bottom: 1px solid #ddd;
        padding: 10px 0;
        font-size: 18px;
	}
	.baiduseo_yauncls1>div {
        float: left;
        text-align: center;
    }
    .baiduseo_yauncls2 {
        overflow: hidden;
        text-align: center;
        padding: 10px 0;
        /* background-color: #f0f0f0; */
        border-bottom: 1px dashed #7e7e7e;
    }
    .baiduseo_yauncls2>div {
        float: left;
        font-size: 14px;
    }
    .baiduseo_yauncls2>div:nth-child(6) a {
        padding: 3px 10px;
        background-color: skyblue;
        color: #fff;
    }
    .baiduseo_yauncls1>div:nth-child(1),
    .baiduseo_yauncls2>div:nth-child(1) {
        width: 30%;
        overflow:hidden; 
        text-overflow:ellipsis; 
        white-space:nowrap;
        height: 19px;
    }
    .baiduseo_yauncls1>div:nth-child(2),
    .baiduseo_yauncls2>div:nth-child(2) {
        width: 9%;
    }
    .baiduseo_yauncls1>div:nth-child(3),
    .baiduseo_yauncls2>div:nth-child(3) {
        width: 10%;
    }
    .baiduseo_yauncls1>div:nth-child(4),
    .baiduseo_yauncls2>div:nth-child(4) {
        width: 10%;
    }
    .baiduseo_yauncls1>div:nth-child(5),
    .baiduseo_yauncls2>div:nth-child(5) {
        width: 10%;
        height: 18px;
    }
    .baiduseo_yauncls1>div:nth-child(6),
    .baiduseo_yauncls2>div:nth-child(6) {
        width: 20%;
    }
    .baiduseo_yauncbd {
        font-size: 16px;
        font-weight: 300;
        transition: all 0.5s;
        overflow: hidden;
        height: 0;
        position: relative;
    }
    .baiduseo_yauncbd>p {
        text-align: center;
        padding: 10px 0;
        font-size: 18px;
        font-weight: 700;
    }
    .baiduseo_yauncbdjc {
        width: 60%;
        display: inline-block;
        border: 1px solid #ccc;
        overflow-y: auto;
        box-sizing: border-box;
        padding: 5px;
        /*margin: 0 auto;*/
        height: 500px;
        font-size: 16px;
        font-weight: 300;
        position: relative;
        color: #111;
    }
    .baiduseo_yauncbdjc span {
        color: red;
    }
    .baiduseo_yauncbdjc img {
        display: none;
    }
    .baiduseo_yauncbdjc a {
        color: #111;
        text-decoration: none;
        font-weight: 300;
    }
    .baiduseo_yauncbdjc h1,
    .baiduseo_yauncbdjc h2,
    .baiduseo_yauncbdjc h3,
    .baiduseo_yauncbdjc h4,
    .baiduseo_yauncbdjc h5,
    .baiduseo_yauncbdjc h6 {
        color: #111;
        font-size: 16px;
        font-weight: 300;
    }
    .baiduseo_yauncsm>p {
        text-align: center;
    }
    .baiduseo_ycti {
        float: left;
        display: inline-block;
        padding: 0 32px;
        width: 300px;
    }
    #baiduseo_yctibg {
        text-align: center;
        padding: 10px 0;
    }
    #baiduseo_yctibg>a {
        padding: 5px 15px;
        display: inline-block;
        border: 1px solid #ccc;
        margin-right: 5px;
        font-size: 14px;
        font-weight: 300;
    }
    .baiduseo_yts {
        color: red;
        text-align: center;
        padding: 10px 0;
        display: none;
    }
    @media screen and (max-width: 1000px) {
        .baiduseo_yaunc  {
    	    display: block;
    	    width: 60%;
            margin: 0 auto;
    	}
    	.baiduseo_yauncbxs p {
    	    padding: 10px 0 0;
    	}
    	.baiduseo_yauncls2>div {
    	    float:none;
    	    width: 100%!important;
    	}
    	.baiduseo_yauncls1 {
    	    display: none;
    	}
    	.baiduseo_yauncls2>div:nth-child(6) {
    	    padding: 10px 0;
    	}
    	.baiduseo_yauncbdjc {
    	    width: 100%;
    	}
    	.baiduseo_ycti {
    	    display: none;
    	}
    	.layui-form {
    	    display: block!important;
    	}
    }
  </style>