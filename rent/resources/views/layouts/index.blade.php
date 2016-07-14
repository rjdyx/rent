<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>房租查询</title>
    <link href="{{url('/css/style.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('/css/index.css')}}" rel="stylesheet" type="text/css"/>
</head>

<body>
<div class="index-header">
    房租查询
</div>

<div class="index-search">
    <form id="form-index">
        <label for="index-name">姓名：</label>
        <input type="text" id="index-name" class="index-input" name="name">
        <label for="index-jobNumber">工号：</label>
        <input type="text" id="index-jobNumber" class="index-input" name="jobNumber">
        <label for="index-institution">单位：</label>
        <input type="text" id="index-institution" class="index-input" name="institution">
        <input type="button" id="index-submit" onclick="searchRentByOne()" value="查询">
    </form>
</div>
<div id="index-info-list">
    <table class="tablelist">
        <thead>
        <tr>
            <th>序号</th>
            <th>入住时间</th>
            <th>上次结算时间</th>
            <th>本次结算时间</th>
            <th>房租</th>
            <th>第几间租房</th>
            <th>是否离职</th>
            <th>是否有房</th>
            <th>年限</th>
            <th>区域</th>
            <th>房址</th>
            <th>租金x比例</th>
            <th>租房面积</th>
        </tr>
        </thead>
        <tbody id="index-result-content">
        </tbody>
    </table>
</div>

<script language="JavaScript" src="{{url('/js/jquery-2.1.3.min.js')}}"></script>
<script language="JavaScript" src="{{url('/js/common.js')}}"></script>
<script language="JavaScript" src="{{url('/js/index.js')}}"></script>
<script type="text/javascript">
    var rootUrl = "{{url('/')}}"; //http://127.0.0.1
</script>

</body>
</html>
