<style>
    .SEO_shouquan {
        display: inline-block;
        padding: 15px 20px;
        background-color: #ffcb3b!important;
        color: #fff!important;
        cursor: pointer;
        height: 0;
        line-height: 0;
    }
    .SEO_shouquantishi {
        color: red;
        margin: 0 15px 0 150px;
    }
    .layui-elem-quote {
        border-left: 5px solid #01AAED;
    }
    .seohj_qc {
        border: 1px solid;
        padding: 5px 10px;
        color: #fff;
        float: right;
        background-color: #007DDB;
        /*display: none;*/
    }
</style>
<blockquote class="layui-elem-quote layui-text" style="overflow: hidden;">
  	官网：<a href="https://www.9ysw.com">www.9ysw.com | 易搜网</a>易搜网
    <!--<button class="seohj_qc">清除缓存</button>-->
</blockquote>
<script>
jQuery(document).ready(function($) {
    layui.use(['form','layer', ], function() {
        var form = layui.form,
            layer = layui.layer;
        $('.seohj_qc').click(function(){
            var index = layer.load(1, {
            shade: [0.7, '#111'] //0.1透明度的白色背景
        }); 
        $.ajax({
            url: '',
            data: {
                data: '{"BaiduSEO":"39","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'
            },
            type: 'post',
            dataType: 'json',
            success: function(data) {
                layer.close(index);
                if (data.msg == 1) {
                    layer.alert('清除成功');
                    location.reload()
                }
            }
        });
        })
          
    })
})
</script>
