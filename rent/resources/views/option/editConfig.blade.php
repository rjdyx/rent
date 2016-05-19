@extends('layouts.banner')

@section('title', '配置管理')

@section('js')
<script language="JavaScript" src="{{url('/js/editConfig.js')}}"></script>
@endsection

@section('content')

    <div class="rightinfo">

        <div class="tools">

            <ul class="toolbar">
                <li class="addArea" onclick="showAddArea()"><span><img
                                src="{{url('/images/t01.png')}}"/></span>添加区域
                </li>
                <li class="addAddress" onclick="showAddAddress()"><span><img
                                src="{{url('/images/t01.png')}}"/></span>添加房址
                </li>
            </ul>


        </div>

        <div id="areaAndAddress">
            @foreach ($arrTotal as $arrItem)
                <div class="item" id="{{ $arrItem[0]['id'] }}">
                    <div class="item-title">
                        <span class="item-area">{{ $arrItem[0]['name'] }}</span>
                        <span class="item-top-operation">
                            <a onclick="showEditAreaDialog('{{ $arrItem[0]['id'] }}','{{ $arrItem[0]['name'] }}')">编辑</a><a onclick="showSureDeleteDialog('{{ $arrItem[0]['id'] }}',0)">删除</a>
                        </span>
                    </div>
                    <div class="item-content">
                        <table>
                            <tr class="item-content-th">
                                <td>房址</td>
                                <td>周转租金</td>
                                <td>优惠市场租金</td>
                                <td>市场租金</td>
                                <td>操作</td>
                            </tr>
                            @foreach($arrItem[1] as $address)
                                <tr id="{{ $address['id'] }}">
                                    <td>{{ $address['name'] }}</td>
                                    <td>{{ $address['turnover_rent'] }}</td>
                                    <td>{{ $address['discount_rent'] }}</td>
                                    <td>{{ $address['market_rent'] }}</td>
                                    <td><a onclick="showEditAddressDialog('{{ $address['id'] }}','{{ $address['name'] }}','{{ $address['turnover_rent'] }}','{{ $address['discount_rent'] }}','{{ $address['market_rent'] }}')">编辑</a>&nbsp;&nbsp;<a onclick="showSureDeleteDialog('{{ $address['id'] }}',1)">删除</a></td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>
            @endforeach

        </div>

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

    @include('layer.addAreaDialog')
    @include('layer.editAreaDialog')
    @include('layer.addAddressDialog')
    @include('layer.editAddressDialog')
    @include('layer.successTip')
    @include('layer.errorTip')
    @include('layer.sureDeleteDialog')

@endsection
