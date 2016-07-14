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
                            <a onclick="showEditAreaDialog('{{ $arrItem[0]['id'] }}','{{ $arrItem[0]['name'] }}')">编辑</a><a
                                    onclick="showSureDeleteDialog('{{ $arrItem[0]['id'] }}',0)">删除</a>
                        </span>
                    </div>
                    <div class="item-content">
                        <table>
                            <tr class="item-content-th">
                                <td>房址</td>
                                <td>周转租金</td>
                                <td>优惠租金</td>
                                <td>市场租金</td>
                                <td>标准租金单价</td>
                                <td>单项装修标准租金</td>
                                <td>操作</td>
                            </tr>
                            @for($i=1;$i<sizeof($arrItem);$i++)

                                <tr id="{{ $arrItem[$i][0]['id'] }}">
                                    <td>
                                        @for($j=0;$j<sizeof($arrItem[$i]);$j++)
                                            <a onclick="showEditAddressDialog('{{ $arrItem[$i][$j]['id'] }}','{{ $arrItem[$i][$j]['name'] }}','{{ $arrItem[$i][$j]['turnover_rent'] }}','{{ $arrItem[$i][$j]['discount_rent'] }}','{{ $arrItem[$i][$j]['market_rent'] }}','{{ $arrItem[$i][$j]['standad_rent_single'] }}','{{ $arrItem[$i][$j]['standad_rent_decorate'] }}')">
                                                {{ $arrItem[$i][$j]['name'] }}
                                            </a>
                                            @if($j != sizeof($arrItem[$i])-1)
                                                、
                                            @endif
                                        @endfor
                                    </td>
                                    <td>{{ $arrItem[$i][0]['turnover_rent'] }}</td>
                                    <td>{{ $arrItem[$i][0]['discount_rent'] }}</td>
                                    <td>{{ $arrItem[$i][0]['market_rent'] }}</td>
                                    <td>{{ $arrItem[$i][0]['standad_rent_single'] }}</td>
                                    <td>{{ $arrItem[$i][0]['standad_rent_decorate'] }}</td>
                                    <td>
                                        <a onclick="showAddSameAddress('{{ $arrItem[$i][0]['id'] }}','{{ $arrItem[$i][0]['name'] }}','{{ $arrItem[$i][0]['turnover_rent'] }}','{{ $arrItem[$i][0]['discount_rent'] }}','{{ $arrItem[$i][0]['market_rent'] }}','{{ $arrItem[$i][0]['standad_rent_single'] }}','{{ $arrItem[$i][0]['standad_rent_decorate'] }}')">
                                            添加
                                        </a>&nbsp;&nbsp;&nbsp;
                                        <a onclick="showSureDeleteDialog('{{ $arrItem[$i][0]['id'] }}',1)">删除</a>
                                    </td>
                                </tr>

                            @endfor

                        </table>
                    </div>
                </div>
            @endforeach

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
