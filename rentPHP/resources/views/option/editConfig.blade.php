@extends('layouts.banner')

@section('content')

<div class="rightinfo">

<div class="tools">

	<ul class="toolbar">
    <li class="addArea"><span><img src="{{url('/images/t01.png')}}" /></span>添加区域</li>
    <li class="addAddress"><span><img src="{{url('/images/t02.png')}}" /></span>添加房址</li>
    <li class="delete"><span><img src="{{url('/images/t03.png')}}" /></span>删除</li>
    </ul>


</div>


<table class="tablelist">
	<thead>
	<tr>
    <th><input name="" type="checkbox" value=""/></th>
    <th>区域</th>
    <th>房址</th>
    <th>周转期租金</th>
    <th>优惠市场租金</th>
    <th>市场租金</th>
    <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td rowspan="3"><input name="" type="checkbox" value="" /></td>
    <td rowspan="3">20130908</td>
    <td>王金平幕僚：马英九声明字字见血 人活着没意思</td>
    <td>admin</td>
    <td>江苏南京</td>
    <td>2013-09-09 15:05</td>
    <td><a href="#" class="tablelink">编辑</a>     <a href="#" class="tablelink"> 删除</a></td>
    </tr> 
    
    <tr>
    <!-- <td><input name="" type="checkbox" value="" /></td> -->
    <!-- <td>20130907</td> -->
    <td>温州19名小学生中毒流鼻血续：周边部分企业关停</td>
    <td>uimaker</td>
    <td>山东济南</td>
    <td>2013-09-08 14:02</td>
    <td><a href="#" class="tablelink">编辑</a>     <a href="#" class="tablelink">删除</a></td>
    </tr>
    
    <tr>
    <!-- <td><input name="" type="checkbox" value="" /></td> -->
    <!-- <td>20130906</td> -->
    <td>社科院:电子商务促进了农村经济结构和社会转型</td>
    <td>user</td>
    <td>江苏无锡</td>
    <td>2013-09-07 13:16</td>
    <td><a href="#" class="tablelink">编辑</a>     <a href="#" class="tablelink">删除</a></td>
    </tr>
    
    <tr>
    <td><input name="" type="checkbox" value="" /></td>
    <td>20130905</td>
    <td>江西&quot;局长违规建豪宅&quot;：局长检讨</td>
    <td>admin</td>
    <td>北京市</td>
    <td>2013-09-06 10:36</td>
    <td><a href="#" class="tablelink">编辑</a>     <a href="#" class="tablelink">删除</a></td>
    </tr>
    
    <tr>
    <td><input name="" type="checkbox" value="" /></td>
    <td>20130904</td>
    <td>中国2020年或迈入高收入国家行列</td>
    <td>uimaker</td>
    <td>江苏南京</td>
    <td>2013-09-05 13:25</td>
    <td><a href="#" class="tablelink">编辑</a>     <a href="#" class="tablelink">删除</a></td>
    </tr>
    
    </tbody>
</table>


<div class="pagin">
	<div class="message">共<i class="blue">1256</i>条记录，当前显示第&nbsp;<i class="blue">2&nbsp;</i>页</div>
    <ul class="paginList">
    <li class="paginItem"><a href="javascript:;"><span class="pagepre"></span></a></li>
    <li class="paginItem"><a href="javascript:;">1</a></li>
    <li class="paginItem current"><a href="javascript:;">2</a></li>
    <li class="paginItem"><a href="javascript:;">3</a></li>
    <li class="paginItem"><a href="javascript:;">4</a></li>
    <li class="paginItem"><a href="javascript:;">5</a></li>
    <li class="paginItem more"><a href="javascript:;">...</a></li>
    <li class="paginItem"><a href="javascript:;">10</a></li>
    <li class="paginItem"><a href="javascript:;"><span class="pagenxt"></span></a></li>
    </ul>
</div>


</div>

<script type="text/javascript">
$('.tablelist tbody tr:odd').addClass('odd');
</script>

@endsection