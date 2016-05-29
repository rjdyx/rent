@extends('layouts.banner')

@section('title', '住户信息列表')

@section('js')
    <script language="JavaScript" src="{{url('/js/householdList.js')}}"></script>
@endsection
@section('css')
    <link rel="stylesheet" href="{{url('/css/householdList.css')}}">
    <link rel="stylesheet" href="{{url('/css/rentMsgList.css')}}">
@endsection


@section('content')
    <div class="search">
        <form class="form-search" action="{{ url('/admin/HouseholdListView') }}" method="get">
            <div class="search-item"><label for="jobNumber">工号:</label><input type="text" id="jobNumber"
                                                                              name="jobNumber" class="search-input"
                                                                              value="{{ $input['jobNumber'] }}"></div>
            <div class="search-item"><label for="name">姓名:</label><input type="text" id="name" name="name"
                                                                         class="search-input"
                                                                         value="{{ $input['name'] }}"></div>
            <div class="search-item"><label for="institution">单位:</label><input type="text" id="institution"
                                                                                name="institution" class="search-input"
                                                                                value="{{ $input['institution'] }}">
            </div>
            <div class="search-item"><label for="firsttimeCheckIn">是否有房:</label>
            <select id="hasHouse" name="hasHouse" class="search-input">
                <option value="-1" @if( $input['hasHouse'] == -1 ) selected="selected" @endif></option>
                <option value="0" @if( $input['hasHouse'] == 0 ) selected="selected" @endif>无房</option>
                <option value="1" @if( $input['hasHouse'] == 1 ) selected="selected" @endif>商品房</option>
                <option value="2" @if( $input['hasHouse'] == 2 ) selected="selected" @endif>房改房</option>
            </select>
            </div>
            <div class="search-item">
                <label for="isDimission-search">是否离职:</label>
                <select id="isDimission-search" name="isDimission" class="search-input">
                    <option value="-1" @if( $input['isDimission'] == -1 ) selected="selected" @endif></option>
                    <option value="1" @if( $input['isDimission'] == 1 ) selected="selected" @endif>是</option>
                    <option value="0" @if( $input['isDimission'] == 0 ) selected="selected" @endif>否</option>
                </select>
            </div>
            <div class="search-btn">
                <input type="submit" value="查询" />
            </div>
        </form>

    </div>
    <div id="user-info-list">
        <table class="tablelist">
            <thead>
            <tr>
                <th></th>
                <th>姓名</th>
                <th>工号</th>
                <th>银行卡号</th>
                <th>单位</th>
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
                    <td>{{ $householdMsg['institution'] }}</td>
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
                    <td>
                        <a href="{{ url('/admin/viewRent') }}/{{ $householdMsg['id'] }}" class="tablelink">查看</a>
                        <a href="{{ url('/admin/editHouseholdMsg') }}/{{ $householdMsg['id'] }}" class="tablelink">修改</a>
                        <a href="#" class="tablelink" onclick="showCommonDialog('提示','确定删除？','删除','deleteHouseholdMsg',[['input-id','{{ $householdMsg['id'] }}']])"> 删除</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination-content">{!! $householdMsgs->appends(
    [
        'jobNumber' => $input['jobNumber'],
        'name' => $input['name'],
        'institution' => $input['institution'],
        'hasHouse' => $input['hasHouse'],
        'isDimission' => $input['isDimission']
    ])->links() !!}</div>



    <input type="hidden" id="" value="">
    @include('layer.commonDialog')

    <script type="text/javascript">
        $('.tablelist tbody tr:odd').addClass('odd');
    </script>

@endsection