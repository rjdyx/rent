<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="{{url('/css/style.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('/css/index.css')}}" rel="stylesheet" type="text/css"/>
    {{--<script language="JavaScript" src="{{url('/js/jquery.js')}}"></script>--}}
    <script src="http://cdn.staticfile.org/jquery/2.1.1-rc2/jquery.min.js"></script>
    <script language="JavaScript" src="{{url('/js/common.js')}}"></script>
    @yield('js')
    @yield('css')


</head>

<body>
<header>
    <h1>房租统计系统</h1>
</header>

<dl class="leftmenu navbar">

    <dd>
        <div class="title">
            <span><img src="{{url('/images/leftico02.png')}}"/></span>配置管理
        </div>
        <ul class="menuson">
            <li @if($active == 'editConfig') class="active" @endif><cite></cite><a href="{{url('admin/editConfig')}}">配置管理</a><i></i></li>
        </ul>
    </dd>

    <dd>
        <div class="title">
            <span><img src="{{url('/images/leftico02.png')}}"/></span>住户管理
        </div>
        <ul class="menuson">
            <li @if($active == 'AddHouseholdView') class="active" @endif><cite></cite><a href="{{url('admin/AddHouseholdView')}}">新增住户</a><i></i></li>
            <li @if($active == 'HouseholdListView') class="active" @endif><cite></cite><a href="{{url('admin/HouseholdListView')}}">住户列表</a><i></i></li>
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
