<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    {{--<link href="{{url('/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>--}}
    <link href="{{url('/css/style.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('/css/index.css')}}" rel="stylesheet" type="text/css"/>
    <script language="JavaScript" src="{{url('/js/jquery-2.1.3.min.js')}}"></script>
    <script language="JavaScript" src="{{url('/js/jquery.validate.min.js')}}"></script>
    <script language="JavaScript" src="{{url('/js/messages_zh.js')}}"></script>
    <script language="JavaScript" src="{{url('/js/common.js')}}"></script>
    @yield('js')
    @yield('css')


</head>

<body>
<header class="index-header">
    <span>房租查询</span>
</header>

<div class="index-search">
    <form>
        <label for="index-name">姓名：</label>
        <input type="text" id="index-name" class="index-input" name="name">
        <label for="index-jobNumber">工号：</label>
        <input type="text" id="index-jobNumber" class="index-input" name="jobNumber">
        <label for="index-cardNumber">单位：</label>
        <input type="text" id="index-cardNumber" class="index-input" name="cardNumber">
        <input type="button" id="index-submit" value="查询">
    </form>
</div>

</body>
</html>
