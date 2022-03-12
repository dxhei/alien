<?php if(!defined('ABSPATH'))exit;?>
<div id="divMain">
    <div class="SubMenu">
    </div>

    <style>
        /*#wpwrap{*/
        /*	background-color:#fff;*/
        /*}*/
        /*.zhizhu,.clear_log,#test3{*/
        /*        padding: 0 17px;*/
        /*color:#fff;*/
        /*}*/
        /*.laytable-cell-1-0-1 {*/
        /*     width: 80px !important;*/
        /*   }*/
        /*   .laytable-cell-1-0-2 {*/
        /*       width: 170px !important;*/
        /*   }*/
        /*  .laytable-cell-1-0-3 {*/
        /*       width: 315px !important;*/
        /*    }*/
        /*  .laytable-cell-1-0-4 {*/
        /*       width: 100px !important;*/
        /*   }*/
        /*.laytable-cell-1-0-5 {*/
        /*     width: 250px !important;*/
        /* }*/
        .layui-btn,
        .layui-btn-danger {
            background-color: #01AAED;
        }
        .layui-btn-primary {
            background-color: #Fff;
        }
        .layui-form-onswitch {
            border-color: #01AAED;
            background-color: #01AAED;
        }
        .layui-laypage .layui-laypage-curr .layui-laypage-em {
            background-color: #01AAED;
        }
        .layui-laydate .layui-this {
            background-color: #01AAED!important;
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
                <li class="admin"><a href="/wp-admin/admin.php?page=baiduseo&book=10">网站蜘蛛</a></li>
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

            <div class="layui-tab-content">
                <div class=" main_list">
                    <div id="startDemo" title="点击启动插件向导" class="point point-flicker">
                        <img src="<?php echo $siteurl;?>/wp-content/plugins/baiduseo/image/logo111.jpg">
                        <div class="shuoming">嗨，有不明白的地方可以点我！</div>
                    </div>
                    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                        <legend>网站蜘蛛</legend>
                    </fieldset>
                    <form class="layui-form" action="" onsubmit="return false">
                        <div class="layui-form-item">
                            <label class="layui-form-label">是否开启</label>
                            <div class="layui-input-block" id="box11">
                                <?php 
				    		if(isset($zhizhu['auto']) && ($zhizhu['auto']==1)){
				    			 echo '<input type="checkbox" name="close" lay-skin="switch" lay-text="开|关" checked="">';
				    		}else{
				    			echo '<input type="checkbox" name="close" lay-skin="switch" lay-text="开|关">';
				    		}
				    	?>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">网站蜘蛛，是根据蜘蛛IP定位访问本网站哪个URL，可以根据该信息及时修改Robots.txt引导蜘蛛读取质量高的URL。 代码：200，正常 代码：301，跳转重向 代码：404，无该页面 （建议多注意404/301信息，出现太多相应蜘蛛可以在Robots.txt设置屏蔽目录）。
                            </div>
                            
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <input type="hidden" name="BaiduSEO" value="11">
                                <input type="hidden" name="action" value="BaiduSEO">
                                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('BaiduSEO');?>">
                                <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo11">保存</button>
                            </div>
                        </div>
                    </form>

                    <div id="box22">
                        <div class="layui-inline" style="float: right;">
                            <div class="layui-input-inline" style="width300px">
                                <label class="layui-form-label">历史索引</label>
                                <input type="text" class="layui-input" id="test30" placeholder="yyyy-MM" style="width:120px;">
                            </div>
                        </div>
                        <div style="clear:both;"></div>
                        <div id="main1" style="width: 1000px;height:400px;"></div>
                    </div>
                    <div>
                        <div id="chartmain1" style="width:1000px;height: 500px;"></div>
                    </div>
                    <div id="box33">
                        <div id="chartmain" style="width:1000px;height: 500px;"></div>
                    </div>

                    <div style="clear:both;"></div>
                    <div class="layui-form" id="box44">

                        <button type="button" class="layui-btn layui-btn-sm zhizhu" data-type="0">所有记录</button>
                        <button type="button" class="layui-btn layui-btn-sm zhizhu layui-btn-primary" data-type="1">百度</button>
                        <button type="button" class="layui-btn layui-btn-sm zhizhu layui-btn-primary" data-type="2">谷歌</button>
                        <button type="button" class="layui-btn layui-btn-sm zhizhu layui-btn-primary" data-type="3">360</button>
                        <button type="button" class="layui-btn layui-btn-sm zhizhu layui-btn-primary" data-type="4">搜狗</button>
                        <button type="button" class="layui-btn layui-btn-sm zhizhu layui-btn-primary" data-type="5">神马</button>
                        <button type="button" class="layui-btn layui-btn-sm zhizhu layui-btn-primary" data-type="6">必应</button>
                        <button type="button" class="layui-btn layui-btn-sm zhizhu layui-btn-primary" data-type="7">头条</button>
                        <button type="button" class="layui-btn layui-btn-sm zhizhu layui-btn-primary" data-type="8">301记录</button>
                        <button type="button" class="layui-btn layui-btn-sm zhizhu layui-btn-primary" data-type="9">404记录</button>
                        <button type="button" class="layui-btn layui-btn-sm zhizhu layui-btn-primary" data-type="10">200记录</button>
                        <button type="button" class="layui-btn layui-btn-sm clear_log layui-btn-danger">清除记录</button>
                        <button type="button" class="layui-btn layui-btn-sm clear_bldy layui-btn-danger">保留当月</button>
                    </div>
                    <div class="layui-form" style="margin:20px 0px" id="box55">

                        <div class="layui-inline">
                            <label class="layui-form-label">开始日期</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" name="start" id="test7" placeholder="yyyy-MM-dd">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">结束日期</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" name="end" id="test8" placeholder="yyyy-MM-dd">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">地址</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" name="search" placeholder="请输入地址">
                            </div>
                        </div>
                        <button class="layui-btn baiduseo_search" type="button">搜索</button>
                    </div>
                    <table class="layui-hide" id="test3" lay-filter="demo"></table>
                </div>

            </div>
        </div>
        <ol id="joyRideTipContent">
            <li data-id="box11" data-text="下一步" class="custom">
                <h2>step #1</h2>
                <p>该功能建议必用，无需SEO人员每天查看网站日志，小编直接开发可视化日志，方便用户查阅，一般只保留了3个常用URL链接状态，301、404、200。（注：如果没开启该功能，则无法使用死链功能。）</p>
            </li>
            <li data-id="box22" data-text="下一步">
                <h2>step #2</h2>
                <p>索引量，SEO经常用的SITE：命令，无需我们每天再去各大搜索引擎操作，开发该插件就是为了方便统计和查看常用的几个平台每天索引量信息。</p>
            </li>
            <li data-id="box33" data-text="下一步">
                <h2>step #3</h2>
                <p>该图表是从你开启该功能以来所统计到的访问网站的蜘蛛及状态代码。可以更直观的看到哪一类蜘蛛更喜欢我们，如果出现倾斜，建议做好调整及倾向优化。</p>
            </li>
            <li data-id="box44" data-text="下一步">
                <h2>step #4</h2>
                <p>该功能可以分类蜘蛛访问的URL详细列表信息，并且[清除记录]功能会让统计表和详细列表清空，重新统计。</p>
            </li>
            <li data-id="box55" data-text="关闭">
                <h2>step #5</h2>
                <p>该功能可以根据时间段查询来访蜘蛛，方便按天去计算蜘蛛及查看蜘蛛爬取的URL地址。（注：无法查询该功能启动前的数据，因为只有开启了该功能才开始记录数据。</p>
            </li>
        </ol>
        <script>
            jQuery(document).ready(function($) {
                function fun_date(num) {
                    var date1 = new Date();
                    //今天时间
                    var time1 = date1.getFullYear() + "-" + (date1.getMonth() + 1) + "-" + date1.getDate()
                    console.log(time1);
                    var date2 = new Date(date1);
                    date2.setDate(date1.getDate() + num);
                    //num是正数表示之后的时间，num负数表示之前的时间，0表示今天
                    var time2 = date2.getFullYear() + "-" + (date2.getMonth() + 1) + "-" + date2.getDate();
                    console.log(time2);
                    return time2;
                }


            });
        </script>
        <script>
            jQuery(document).ready(function($) {
                layui.use(['form', 'laydate', 'laypage', 'layer', 'table', 'element'], function() {
                    var form = layui.form,
                        layer = layui.layer,
                        laypage = layui.laypage,
                        table = layui.table,
                        element = layui.element;
                    var laydate = layui.laydate;

                    //常规用法
                    laydate.render({
                        elem: '#test7',
                        type: 'datetime',
                        trigger: 'click'
                    });
                    laydate.render({
                        elem: '#test30',
                        type: 'month',
                        value: "<?php echo $year_month;?>",
                        trigger: 'click',
                        done: function(value, date, endDate) {
                            console.log(value);
                            window.location.href = window.location.href + "&time=" + value
                            //	window.location.href="window.location.href&time"+"value"
                        }
                    });
                    $('.baiduseo_search').click(function() {
                        $('.zhizhu').each(function() {
                            var str = $(this).attr('class');
                            if (str.indexOf('layui-btn-primary') == '-1') {
                                var myChart = echarts.init(document.getElementById('chartmain'));
                                var type = $(this).attr('data-type');
                                var start = $('input[name="start"]').val();
                                var end = $('input[name="end"]').val();
                                var search = $('input[name="search"]').val()
                                myChart.showLoading({
                                    text: 'loading',
                                    color: '#c23531',
                                    textColor: '#000',
                                    maskColor: 'rgba(255, 255, 255, 0.8)',
                                    zlevel: 0
                                });
                                table.render({
                                    elem: '#test3',
                                    url: "<?php echo  admin_url( 'admin.php?page=baiduseo&zhizhu=1&table=1&baiduseo=1' );?>&type=" + type + '&start=' + start + '&end=' + end + '&search=' + search,
                                    cols: [
                                        [{
                                            field: 'id',
                                            width: 100,
                                            title: 'ID',
                                            sort: true
                                        }, {
                                            field: 'name',
                                            width: 100,
                                            title: '蜘蛛名称'
                                        }, {
                                            field: 'time',
                                            width: 170,
                                            title: '抓取时间'
                                        }, {
                                            field: 'address',
                                            title: '抓取地址',
                                            width: 332
                                        }, {
                                            field: 'num',
                                            title: '抓取次数',
                                            width: 100
                                        }, {
                                            field: 'type',
                                            title: '状态码',
                                            width: 100
                                        }, {
                                            field: 'id',
                                            title: '操作',
                                            width: 70,
                                            toolbar: '#barDemo'
                                        }]
                                    ],
                                    page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
                                        layout: ['prev', 'page', 'next', 'skip', 'count'] //自定义分页布局
                                            //,curr: 5 //设定初始在第 5 页
                                            ,
                                        groups: 10 //只显示 1 个连续页码
                                            ,
                                        first: false //不显示首页
                                            ,
                                        last: false //不显示尾页  
                                            ,
                                        limit: 35
                                    },
                                    request: {
                                        pageName: 'pages',
                                    },

                                    done: function(data) {


                                        option = {
                                            title: {
                                                text: '蜘蛛量',
                                            },
                                            color: ['#3398DB'],
                                            tooltip: {
                                                trigger: 'axis',
                                                axisPointer: { // 坐标轴指示器，坐标轴触发有效
                                                    type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
                                                },
                                                formatter: "{a} <br/>{b}: {c} 条" // 这里是鼠标移上去的显示数据
                                            },
                                            grid: {
                                                left: '3%',
                                                right: '4%',
                                                bottom: '3%',
                                                containLabel: true
                                            },
                                            xAxis: [{
                                                type: 'category',
                                                data: ['百度', '谷歌', '360', '搜狗', '神马', '必应', '头条'],
                                                axisTick: {
                                                    alignWithLabel: true
                                                }
                                            }],
                                            yAxis: [{
                                                type: 'value',
                                            }],
                                            series: [{
                                                name: '蜘蛛量',
                                                type: 'bar',
                                                itemStyle: {

                                                    normal: {

                                                        color: function(params) {

                                                            // build a color map as your need.

                                                            var colorList = [

                                                                '#C1232B', '#B5C334', '#FCCE10', '#E87C25', '#27727B',

                                                                '#FE8463', '#9BCA63', '#FAD860', '#F3A43B', '#60C0DD',

                                                                '#D7504B', '#C6E579', '#F4E001', '#F0805A', '#26C0C0'

                                                            ];

                                                            return colorList[params.dataIndex]

                                                        },


                                                        label: {

                                                            show: true,

                                                            position: 'top',


                                                            formatter: '{b}\n{c}'

                                                        }

                                                    }

                                                },
                                                barWidth: '60%',
                                                data: data.other
                                            }]
                                        };



                                        myChart.setOption(option);
                                        myChart.hideLoading();
                                    }

                                });
                            }
                        })
                    })
                    laydate.render({
                        elem: '#test8',
                        type: 'datetime',
                        trigger: 'click',
                        done: function(value, date, endDate) {

                            $('.zhizhu').each(function() {
                                var str = $(this).attr('class');

                                if (str.indexOf('layui-btn-primary') == '-1') {
                                    var type = $(this).attr('data-type');
                                    var start = $('input[name="start"]').val();
                                    var end = value;
                                    var search = $('input[name="search"]').val()
                                    var myChart = echarts.init(document.getElementById('chartmain'));
                                    myChart.showLoading({
                                        text: 'loading',
                                        color: '#c23531',
                                        textColor: '#000',
                                        maskColor: 'rgba(255, 255, 255, 0.8)',
                                        zlevel: 0
                                    });
                                    table.render({
                                        elem: '#test3',
                                        url: "<?php echo  admin_url( 'admin.php?page=baiduseo&zhizhu=1&table=1&baiduseo=1' );?>&type=" + type + '&start=' + start + '&end=' + end + '&search=' + search,
                                        cols: [
                                            [{
                                                field: 'id',
                                                width: 100,
                                                title: 'ID',
                                                sort: true
                                            }, {
                                                field: 'name',
                                                width: 100,
                                                title: '蜘蛛名称'
                                            }, {
                                                field: 'time',
                                                width: 170,
                                                title: '抓取时间'
                                            }, {
                                                field: 'address',
                                                title: '抓取地址',
                                                width: 332
                                            }, {
                                                field: 'num',
                                                title: '抓取次数',
                                                width: 100
                                            }, {
                                                field: 'type',
                                                title: '状态码',
                                                width: 100
                                            }, {
                                                field: 'id',
                                                title: '操作',
                                                width: 70,
                                                toolbar: '#barDemo'
                                            }]
                                        ],
                                        page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
                                            layout: ['prev', 'page', 'next', 'skip', 'count'] //自定义分页布局
                                                //,curr: 5 //设定初始在第 5 页
                                                ,
                                            groups: 10 //只显示 1 个连续页码
                                                ,
                                            first: false //不显示首页
                                                ,
                                            last: false //不显示尾页  
                                                ,
                                            limit: 35
                                        },
                                        request: {
                                            pageName: 'pages',
                                        },
                                        done: function(data) {


                                            option = {
                                                title: {
                                                    text: '蜘蛛量',
                                                },
                                                color: ['#3398DB'],
                                                tooltip: {
                                                    trigger: 'axis',
                                                    axisPointer: { // 坐标轴指示器，坐标轴触发有效
                                                        type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
                                                    },
                                                    formatter: "{a} <br/>{b}: {c} 条" // 这里是鼠标移上去的显示数据
                                                },
                                                grid: {
                                                    left: '3%',
                                                    right: '4%',
                                                    bottom: '3%',
                                                    containLabel: true
                                                },
                                                xAxis: [{
                                                    type: 'category',
                                                    data: ['百度', '谷歌', '360', '搜狗', '神马', '必应', '头条'],
                                                    axisTick: {
                                                        alignWithLabel: true
                                                    }
                                                }],
                                                yAxis: [{
                                                    type: 'value',
                                                }],
                                                series: [{
                                                    name: '蜘蛛量',
                                                    type: 'bar',
                                                    itemStyle: {

                                                        normal: {

                                                            color: function(params) {

                                                                // build a color map as your need.

                                                                var colorList = [

                                                                    '#C1232B', '#B5C334', '#FCCE10', '#E87C25', '#27727B',

                                                                    '#FE8463', '#9BCA63', '#FAD860', '#F3A43B', '#60C0DD',

                                                                    '#D7504B', '#C6E579', '#F4E001', '#F0805A', '#26C0C0'

                                                                ];

                                                                return colorList[params.dataIndex]

                                                            },


                                                            label: {

                                                                show: true,

                                                                position: 'top',


                                                                formatter: '{b}\n{c}'

                                                            }

                                                        }

                                                    },
                                                    barWidth: '60%',
                                                    data: data.other
                                                }]
                                            };



                                            myChart.setOption(option);
                                            myChart.hideLoading();
                                        }

                                    });
                                }


                            })


                        }
                    });
                    var myChart = echarts.init(document.getElementById('chartmain'));
                    myChart.showLoading({
                        text: 'loading',
                        color: '#c23531',
                        textColor: '#000',
                        maskColor: 'rgba(255, 255, 255, 0.8)',
                        zlevel: 0
                    });
                    table.render({
                        elem: '#test3',
                        url: "<?php echo  admin_url( 'admin.php?page=baiduseo&zhizhu=1&table=1&baiduseo=1' );?>",
                        cols: [
                            [{
                                field: 'id',
                                width: 100,
                                title: 'ID',
                                sort: true
                            }, {
                                field: 'name',
                                width: 100,
                                title: '蜘蛛名称'
                            }, {
                                field: 'time',
                                width: 170,
                                title: '抓取时间'
                            }, {
                                field: 'address',
                                title: '抓取地址',
                                width: 332
                            }, {
                                field: 'num',
                                title: '抓取次数',
                                width: 100
                            }, {
                                field: 'type',
                                title: '状态码',
                                width: 100
                            }, {
                                field: 'id',
                                title: '操作',
                                width: 70,
                                toolbar: '#barDemo'
                            }]
                        ],
                        page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
                            layout: ['prev', 'page', 'next', 'skip', 'count'] //自定义分页布局
                                //,curr: 5 //设定初始在第 5 页
                                ,
                            groups: 10 //只显示 1 个连续页码
                                ,
                            first: false //不显示首页
                                ,
                            last: false //不显示尾页  
                                ,
                            limit: 35
                        },
                        request: {
                            pageName: 'pages',
                        },
                        done: function(data) {

                            console.log(data)
                            option = {
                                title: {
                                    text: '蜘蛛量',
                                },
                                color: ['#3398DB'],
                                tooltip: {
                                    trigger: 'axis',
                                    axisPointer: { // 坐标轴指示器，坐标轴触发有效
                                        type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
                                    },
                                    formatter: "{a} <br/>{b}: {c} 条" // 这里是鼠标移上去的显示数据
                                },
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                xAxis: [{
                                    type: 'category',
                                    data: ['百度', '谷歌', '360', '搜狗', '神马', '必应', '头条'],
                                    axisTick: {
                                        alignWithLabel: true
                                    }
                                }],
                                yAxis: [{
                                    type: 'value',
                                }],
                                series: [{
                                    name: '蜘蛛量',
                                    type: 'bar',
                                    itemStyle: {

                                        normal: {

                                            color: function(params) {

                                                // build a color map as your need.

                                                var colorList = [

                                                    '#C1232B', '#B5C334', '#FCCE10', '#E87C25', '#27727B',

                                                    '#FE8463', '#9BCA63', '#FAD860', '#F3A43B', '#60C0DD',

                                                    '#D7504B', '#C6E579', '#F4E001', '#F0805A', '#26C0C0'

                                                ];

                                                return colorList[params.dataIndex]

                                            },


                                            label: {

                                                show: true,

                                                position: 'top',


                                                formatter: '{b}\n{c}'

                                            }

                                        }

                                    },
                                    barWidth: '60%',
                                    data: data.other
                                }]
                            };

                            // 			var myChart = echarts.init(document.getElementById('chartmain'));

                            myChart.setOption(option);
                            myChart.hideLoading();
                        }
                    });

                    $('.zhizhu').click(function() {
                        $(this).removeClass('layui-btn-primary');
                        $(this).siblings('button').addClass('layui-btn-primary');
                        var start = $('input[name="start"]').val();
                        var end = $('input[name="end"]').val();
                        var type = $(this).attr('data-type');
                        var search = $('input[name="search"]').val();
                        var myChart = echarts.init(document.getElementById('chartmain'));
                        myChart.showLoading({
                            text: 'loading',
                            color: '#c23531',
                            textColor: '#000',
                            maskColor: 'rgba(255, 255, 255, 0.8)',
                            zlevel: 0
                        });
                        table.render({
                            elem: '#test3',
                            url: "<?php echo  admin_url( 'admin.php?page=baiduseo&zhizhu=1&table=1&baiduseo=1' );?>&type=" + type + '&start=' + start + '&end=' + end + '&search=' + search,
                            cols: [
                                [{
                                    field: 'id',
                                    width: 100,
                                    title: 'ID',
                                    sort: true
                                }, {
                                    field: 'name',
                                    width: 100,
                                    title: '蜘蛛名称'
                                }, {
                                    field: 'time',
                                    width: 170,
                                    title: '抓取时间'
                                }, {
                                    field: 'address',
                                    title: '抓取地址',
                                    width: 332
                                }, {
                                    field: 'num',
                                    title: '抓取次数',
                                    width: 100
                                }, {
                                    field: 'type',
                                    title: '状态码',
                                    width: 100
                                }, {
                                    field: 'id',
                                    title: '操作',
                                    width: 70,
                                    toolbar: '#barDemo'
                                }]
                            ],
                            page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
                                layout: ['prev', 'page', 'next', 'skip', 'count'] //自定义分页布局
                                    //,curr: 5 //设定初始在第 5 页
                                    ,
                                groups: 10 //只显示 1 个连续页码
                                    ,
                                first: false //不显示首页
                                    ,
                                last: false //不显示尾页  
                                    ,
                                limit: 35
                            },
                            request: {
                                pageName: 'pages',
                            },
                            done: function(data) {


                                option = {
                                    title: {
                                        text: '蜘蛛量',
                                    },
                                    color: ['#3398DB'],
                                    tooltip: {
                                        trigger: 'axis',
                                        axisPointer: { // 坐标轴指示器，坐标轴触发有效
                                            type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
                                        },
                                        formatter: "{a} <br/>{b}: {c} 条" // 这里是鼠标移上去的显示数据
                                    },
                                    grid: {
                                        left: '3%',
                                        right: '4%',
                                        bottom: '3%',
                                        containLabel: true
                                    },
                                    xAxis: [{
                                        type: 'category',
                                        data: ['百度', '谷歌', '360', '搜狗', '神马', '必应', '头条'],
                                        axisTick: {
                                            alignWithLabel: true
                                        }
                                    }],
                                    yAxis: [{
                                        type: 'value',
                                    }],
                                    series: [{
                                        name: '蜘蛛量',
                                        type: 'bar',
                                        itemStyle: {

                                            normal: {

                                                color: function(params) {

                                                    // build a color map as your need.

                                                    var colorList = [

                                                        '#C1232B', '#B5C334', '#FCCE10', '#E87C25', '#27727B',

                                                        '#FE8463', '#9BCA63', '#FAD860', '#F3A43B', '#60C0DD',

                                                        '#D7504B', '#C6E579', '#F4E001', '#F0805A', '#26C0C0'

                                                    ];

                                                    return colorList[params.dataIndex]

                                                },


                                                label: {

                                                    show: true,

                                                    position: 'top',


                                                    formatter: '{b}\n{c}'

                                                }

                                            }

                                        },
                                        barWidth: '60%',
                                        data: data.other
                                    }]
                                };

                                // 			var myChart = echarts.init(document.getElementById('chartmain'));

                                myChart.setOption(option);
                                myChart.hideLoading();
                            }
                        });
                    })

                    $('.clear_log').click(function() {
                        layer.confirm('您确定清除所有记录吗？确认清除所有记录以后，记录将无法恢复，请谨慎操作！！！', {
                            btn: ['确定', '取消'] //按钮
                        }, function() {
                            var index = layer.load(1, {
                                shade: [0.7, '#111'] //0.1透明度的白色背景
                            });
                            $.ajax({
                                url: '',
                                data: {
                                    data: '{"BaiduSEO":"12","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'
                                },
                                type: 'post',
                                dataType: 'json',
                                success: function(data) {
                                    layer.close(index);
                                    if (data.msg == 1) {
                                        layer.alert('清除成功');
                                        location.reload()
                                    } else if (data.msg == 3) {
                                        layer.confirm('该功能，需要点击“确定”后登录官网进行授权才可正常使用。', {
                                            btn: ['确定', '取消'] //按钮
                                        }, function() {
                                            window.open("https://www.rbzzz.com/qxcp.html",'top');    
                                        }, function() {

                                        });
                                    } else {
                                        layer.msg('清除失败，请刷新后重试');
                                    }
                                }
                            });
                        }, function() {
                        });
                    })
                    $('.clear_bldy').click(function() {
                        layer.confirm('是否确认清除本月以外的记录？确认清除记录以后，记录将无法恢复，请谨慎操作！！！', {
                            btn: ['确定', '取消'] //按钮
                        }, function() {
                            var index = layer.load(1, {
                                shade: [0.7, '#111'] //0.1透明度的白色背景
                            });
                            $.ajax({
                                url: '',
                                data: {
                                    data: '{"BaiduSEO":"36","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'
                                },
                                type: 'post',
                                dataType: 'json',
                                success: function(data) {
                                    layer.close(index);
                                    if (data.msg == 1) {
                                        layer.alert('清除成功');
                                        location.reload()
                                    } else if (data.msg == 3) {
                                        layer.confirm('该功能，需要点击“确定”后登录官网进行授权才可正常使用。', {
                                            btn: ['确定', '取消'] //按钮
                                        }, function() {
                                            window.open("https://www.rbzzz.com/qxcp.html",'top');    
                                        }, function() {

                                        });
                                    } else {
                                        layer.msg('清除失败，请刷新后重试');
                                    }
                                }
                            });
                        });
                    })
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
                    table.on('tool(demo)', function(obj) {
                        var index = layer.load(1, {
                            shade: [0.7, '#111'] //0.1透明度的白色背景
                        });
                        var data = obj.data;
                        var id = data.id;
                        $.ajax({
                            url: '',
                            data: {
                                data: '{"id":"' + id + '","BaiduSEO":"28","nonce":"<?php echo wp_create_nonce('BaiduSEO');?>","action":"BaiduSEO"}'
                            },
                            type: 'post',
                            dataType: 'json',
                            success: function(data) {
                                layer.close(index);
                                if (data.msg == 1) {
                                    layer.alert('操作成功');
                                    location.reload()
                                } else {
                                    layer.msg('操作失败，请刷新后重试');
                                }
                            }
                        })

                    });

                });

            });
        </script>
        <script>
            var myChart1 = echarts.init(document.getElementById('main1'));
            var myChart2 = echarts.init(document.getElementById('chartmain1'));

            option1 = {
                title: {
                    text: '索引量',
                },
                tooltip: {
                    trigger: 'axis',
                    formatter: "{b}号 <br/>{a0}:{c0} 条<br/>{a1}:{c1} 条<br/>{a2}:{c2} 条<br/>{a3}:{c3} 条"
                },
                legend: {
                    data: ['百度', '搜狗', "360", '必应']
                },
                toolbox: {
                    show: true,
                    feature: {
                        dataZoom: {
                            yAxisIndex: 'none'
                        },
                        dataView: {
                            readOnly: false
                        },
                        magicType: {
                            type: ['line', 'bar']
                        },
                        restore: {},
                        saveAsImage: {}
                    }
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: [<?php echo $suoyin_day;?>]
                },
                yAxis: {
                    type: 'value',
                    axisLabel: {
                        formatter: '{value}'
                    }
                },
                series: [{
                    name: '百度',
                    type: 'line',
                    data: [<?php echo $suoyin_baidu1; ?>],
                }, {
                    name: '搜狗',
                    type: 'line',
                    data: [<?php echo $suoyin_sougou1; ?>],
                }, {
                    name: '360',
                    type: 'line',
                    data: [<?php echo $suoyin_3601; ?>],
                }, {
                    name: '必应',
                    type: 'line',
                    data: [<?php echo $suoyin_biying1; ?>],
                }]
            };
            option2 = {
                title: {
                    text: '当天蜘蛛',
                },
                tooltip: {
                    trigger: 'axis',
                    formatter: "{b}号 <br/>{a0}:{c0} 条<br/>{a1}:{c1} 条<br/>{a2}:{c2} 条<br/>{a3}:{c3} 条<br/>{a4}:{c4} 条<br/>{a5}:{c5} 条<br/>{a6}:{c6} 条"
                },
                legend: {
                    data: ['百度', '谷歌', '360', "搜狗", '神马', '必应', '头条']
                },
                toolbox: {
                    show: true,
                    feature: {
                        dataZoom: {
                            yAxisIndex: 'none'
                        },
                        dataView: {
                            readOnly: false
                        },
                        magicType: {
                            type: ['line', 'bar']
                        },
                        restore: {},
                        saveAsImage: {}
                    }
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: [<?php echo $suoyin_day;?>]
                },
                yAxis: {
                    type: 'value',
                    axisLabel: {
                        formatter: '{value}'
                    }
                },
                series: [{
                    name: '百度',
                    type: 'line',
                    data: [<?php echo $suoyin_baidu2; ?>],
                }, {
                    name: '谷歌',
                    type: 'line',
                    data: [<?php echo $suoyin_guge2; ?>],
                }, {
                    name: '360',
                    type: 'line',
                    data: [<?php echo $suoyin_3602; ?>],
                }, {
                    name: '搜狗',
                    type: 'line',
                    data: [<?php echo $suoyin_sougou2; ?>],
                }, {
                    name: '神马',
                    type: 'line',
                    data: [<?php echo $suoyin_shenma2; ?>],
                }, {
                    name: '必应',
                    type: 'line',
                    data: [<?php echo $suoyin_biying2; ?>],
                }, {
                    name: '头条',
                    type: 'line',
                    data: [<?php echo $suoyin_toutiao2; ?>],
                }]
            };
            myChart1.setOption(option1);
            myChart2.setOption(option2);
        </script>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $("#startDemo").click(function() {
                    $(this).joyride();
                })
                var shenchu = setInterval(function() {
                    shengchucd()
                }, 5000);
                $("#startDemo").hover(function() {
                    clearInterval(shenchu);
                    $(".shuoming").css({
                        "right": "60px",
                        "width": "225px"
                    })
                }, function() {
                    shenchu = setInterval(function() {
                        shengchucd()
                    }, 5000);
                })
                var shengchucd = function() {
                    if ($(".shuoming").css("right") == "10px") {
                        $(".shuoming").css({
                            "right": "60px",
                            "width": "225px"
                        })
                    } else {
                        $(".shuoming").css({
                            "right": "10px",
                            "width": "0px"
                        })
                    }
                }
            });
        </script>
        <script type="text/html" id="barDemo">
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script>
        <!--插件结束-->
        <!--代码-->
    </div>
</div>