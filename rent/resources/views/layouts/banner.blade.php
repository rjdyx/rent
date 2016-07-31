<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    {{--<link href="{{url('/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>--}}
    <link href="{{url('/css/style.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('/css/index.css')}}" rel="stylesheet" type="text/css"/>
    <script language="JavaScript" src="{{url('/js/jquery-2.1.3.min.js')}}"></script>
    <script language="JavaScript" src="{{url('/js/jquery.validate.min.js')}}"></script>
    <script language="JavaScript" src="{{url('/js/jquery.form.min.js')}}"></script>
    <script language="JavaScript" src="{{url('/js/messages_zh.js')}}"></script>
    <script language="JavaScript" src="{{url('/js/common.js')}}"></script>
    @yield('js')
    @yield('css')


</head>

<body>
<header>
    <h1>房租统计系统</h1>
    <div>
        <span>{{ Auth::user()->name }}</span>
        <span><a href="/logout">退出</a></span>
    </div>
</header>

<dl class="leftmenu navbar">

    <dd>
        <div class="title">
            <span><img src="{{url('/images/leftico02.png')}}"/></span>配置管理
        </div>
        <ul class="menuson editConfig">
            <li @if($active == 'editConfig') class="active" @endif><cite></cite><a href="{{url('admin/editConfig')}}">配置管理</a><i></i></li>
        </ul>
    </dd>

    <dd>
        <div class="title">
            <span><img src="{{url('/images/leftico01.png')}}"/></span>住户管理
        </div>
        <ul class="menuson household">
            <li @if($active == 'AddHouseholdView') class="active" @endif><cite></cite><a href="{{url('admin/AddHouseholdView')}}">新增住户</a><i></i></li>
            <li @if($active == 'AddShareHouseholdView') class="active" @endif><cite></cite><a href="{{url('admin/AddShareHouseholdView')}}">合租住户</a><i></i></li>
            <li @if($active == 'HouseholdListView') class="active" @endif><cite></cite><a href="{{url('admin/HouseholdListView')}}">住户列表</a><i></i></li>
        </ul>
    </dd>

    <dd>
        <div class="title">
            <span><img src="{{url('/images/leftico03.png')}}"/></span>导入导出
        </div>
        <ul class="menuson importExportView">
            <li @if($active == 'importExportView') class="active" @endif><cite></cite><a href="{{url('admin/importExportView')}}">导入导出</a><i></i></li>
        </ul>
    </dd>

    @can('admin')
        <dd>
            <div class="title">
                <span><img src="{{url('/images/leftico04.png')}}"/></span>超级管理员
            </div>
            <ul class="menuson userView">
                <li @if($active == 'addUserView') class="active" @endif><cite></cite><a href="{{url('admin/addUserView')}}">新增账号</a><i></i></li>
                <li @if($active == 'manageUserView') class="active" @endif><cite></cite><a href="{{url('admin/manageUserView')}}">管理账号</a><i></i></li>
            </ul>
        </dd>
    @endcan



</dl>

<div class="content">
    @yield('content')
</div>

<script type="text/javascript">
    var rootUrl = "{{url('/')}}"; //http://127.0.0.1
//    $('dd').find('.menuson').slideUp();
    $('.{{ $sildedown }}').css('display','block');
</script>

</body>
</html>
