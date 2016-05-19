@extends('layouts.banner')

@section('title', '住户信息列表')

@section('js')
    <script language="JavaScript" src="{{url('/js/householdList.js')}}"></script>
@endsection
@section('css')
    <link rel="stylesheet" href="{{url('/css/householdList.css')}}">
@endsection


@section('content')
    <div id="user-info-list">
        <table class="tablelist">
            <thead>
            <tr>
                <th></th>
                <th>姓名</th>
                <th>工号</th>
                <th>银行卡号</th>
                <th>是否有房</th>
                <th>是否离职</th>
                <th>租房数</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($householdMsgs as $householdMsg)
                <tr id="{{ $householdMsg['id'] }}">
                    <td></td>
                    <td>{{ $householdMsg['name'] }}</td>
                    <td>{{ $householdMsg['job_number'] }}</td>
                    <td>{{ $householdMsg['card_number'] }}</td>
                    <td>
                        @if($householdMsg['has_house'] == 0)
                            无房
                        @elseif($householdMsg['has_house'] == 1)
                            商品房
                        @else
                            房改房
                        @endif
                    </td>
                    <td>
                        @if($householdMsg['is_dimission'] == 0)
                            否
                        @else
                            是
                        @endif
                    </td>
                    <td>{{ sizeof($householdMsg->householdHouseMsg) }}</td>
                    <td><a href="{{ url('/admin/editHouseholdMsg') }}/{{ $householdMsg['id'] }}" class="tablelink">修改</a> <a
                                href="#" class="tablelink" onclick="showCommonDialog('提示','确定删除？','删除','deleteHouseholdMsg',[['input-id','{{ $householdMsg['id'] }}']])"> 删除</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <input type="hidden" id="" value="">
    @include('layer.commonDialog')

@endsection