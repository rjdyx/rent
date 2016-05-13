<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="{{url('/css/style.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('/css/index.css')}}" rel="stylesheet" type="text/css"/>
    <script language="JavaScript" src="{{url('/js/jquery.js')}}"></script>
    <script language="JavaScript" src="{{url('/js/common.js')}}"></script>
    <script language="JavaScript" src="{{url('/js/editConfig.js')}}"></script>

</head>

<body>
<header>
    <h1>房租统计系统</h1>
</header>

<dl class="leftmenu navbar">

    <dd>
        <div class="title">
            <span><img src="{{url('/images/leftico01.png')}}"/></span>管理信息
        </div>
        <ul class="menuson">

            <li>
                <div class="header">
                    <cite></cite>
                    <a href="index.html" target="rightFrame">首页模版</a>
                    <i></i>
                </div>
                <ul class="sub-menus">
                    <li><a href="javascript:;">文件管理</a></li>
                    <li><a href="javascript:;">模型信息配置</a></li>
                    <li><a href="javascript:;">基本内容</a></li>
                    <li><a href="javascript:;">自定义</a></li>
                </ul>
            </li>

            <li>
                <div class="header">
                    <cite></cite>
                    <a href="right.html" target="rightFrame">数据列表</a>
                    <i></i>
                </div>
                <ul class="sub-menus">
                    <li><a href="javascript:;">文件数据</a></li>
                    <li><a href="javascript:;">学生数据列表</a></li>
                    <li><a href="javascript:;">我的数据列表</a></li>
                    <li><a href="javascript:;">自定义</a></li>
                </ul>
            </li>

            <li class="active"><cite></cite><a href="right.html" target="rightFrame">数据列表</a><i></i></li>
            <li><cite></cite><a href="imgtable.html" target="rightFrame">图片数据表</a><i></i></li>
            <li><cite></cite><a href="form.html" target="rightFrame">添加编辑</a><i></i></li>
            <li><cite></cite><a href="imglist.html" target="rightFrame">图片列表</a><i></i></li>
            <li><cite></cite><a href="imglist1.html" target="rightFrame">自定义</a><i></i></li>
            <li><cite></cite><a href="tools.html" target="rightFrame">常用工具</a><i></i></li>
            <li><cite></cite><a href="filelist.html" target="rightFrame">信息管理</a><i></i></li>
            <li><cite></cite><a href="tab.html" target="rightFrame">Tab页</a><i></i></li>
            <li><cite></cite><a href="error.html" target="rightFrame">404页面</a><i></i></li>
        </ul>
    </dd>


    <dd>
        <div class="title">
            <span><img src="{{url('/images/leftico02.png')}}"/></span>其他设置
        </div>
        <ul class="menuson">
            <li><cite></cite><a href="flow.html" target="rightFrame">流程图</a><i></i></li>
            <li><cite></cite><a href="project.html" target="rightFrame">项目申报</a><i></i></li>
            <li><cite></cite><a href="search.html" target="rightFrame">档案列表显示</a><i></i></li>
            <li><cite></cite><a href="tech.html" target="rightFrame">技术支持</a><i></i></li>
        </ul>
    </dd>


    <dd>
        <div class="title"><span><img src="{{url('/images/leftico03.png')}}"/></span>编辑器</div>
        <ul class="menuson">
            <li><cite></cite><a href="#">自定义</a><i></i></li>
            <li><cite></cite><a href="#">常用资料</a><i></i></li>
            <li><cite></cite><a href="#">信息列表</a><i></i></li>
            <li><cite></cite><a href="#">其他</a><i></i></li>
        </ul>
    </dd>


    <dd>
        <div class="title"><span><img src="{{url('/images/leftico04.png')}}"/></span>日期管理</div>
        <ul class="menuson">
            <li><cite></cite><a href="#">自定义</a><i></i></li>
            <li><cite></cite><a href="#">常用资料</a><i></i></li>
            <li><cite></cite><a href="#">信息列表</a><i></i></li>
            <li><cite></cite><a href="#">其他</a><i></i></li>
        </ul>

    </dd>

</dl>

<div class="content">
    @yield('content')
</div>

<script type="text/javascript">
    var rootUrl = "{{url('/')}}"; //http://127.0.0.1
</script>

</body>
</html>
