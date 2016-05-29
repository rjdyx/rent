@extends('layouts.banner')

@section('title', '房租信息列表')

@section('js')
    <script language="JavaScript" src="{{url('/js/rentMsgList.js')}}"></script>
@endsection
@section('css')
    <link rel="stylesheet" href="{{url('/css/rentMsgList.css')}}">
@endsection


@section('content')
    <div class="search">
        <form class="form-search" action="{{ url('/admin/viewRent/') }}/{{ $id }}" method="get">
            <div class="search-item"><label for="firsttimeCheckIn">入住时间:</label><input type="text" id="firsttimeCheckIn"
                                                                                       name="firsttimeCheckIn"
                                                                                       class="search-input"
                                                                                       value="{{ $input['firsttimeCheckIn'] }}">
            </div>
            <div class="search-item">
                <label for="firsttimeCheckIn">结算时间:</label>
                <input type="text" id="beginPayTime" name="beginPayTime" class="search-input"
                       value="{{ $input['beginPayTime'] }}">
                ~
                <input type="text" id="endPayTime" name="endPayTime" class="search-input"
                       value="{{ $input['endPayTime'] }}">
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
                <th>工号</th>
                <th>姓名</th>
                <th>单位</th>
                <th>入住时间</th>
                <th>上次结算时间</th>
                <th>本次结算时间</th>
                <th>房租</th>
                <th>详情</th>
            </tr>
            </thead>
            <tbody>
            @foreach( $rents as $rent)
                <tr>
                    <td></td>
                    <td>{{ $rent->householdMsg()->first()->job_number }}</td>
                    <td>{{ $rent->householdMsg()->first()->name }}</td>
                    <td>{{ $rent->householdMsg()->first()->institution }}</td>
                    <td>{{ date('Y-m-d',strtotime($rent->firsttime_check_in)) }}</td>
                    <td>{{ ($rent->lasttime_pay_rent == null? '' :date('Y-m-d',strtotime($rent->lasttime_pay_rent))) }}</td>
                    <td>{{ date('Y-m-d',strtotime($rent->time_pay_rent)) }}</td>
                    <td>{{ $rent->rent }}</td>
                    <td>
                        <button class="detail-btn" onclick="showDetail({{ $rent->id }})">查看</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination-content">{!! $rents->appends(
    [
        'firsttimeCheckIn' => $input['firsttimeCheckIn'],
        'beginPayTime' => $input['beginPayTime'],
        'endPayTime' => $input['endPayTime']
    ])->links() !!}</div>


    <input type="hidden" id="" value="">
    @include('layer.showDetailDialog')


    <script src="{{url('/js/jquery.cxcalendar.min.js')}}"></script>
    <link rel="stylesheet" href="{{url('/css/jquery.cxcalendar.css')}}">
    <script type="text/javascript">
        $('#firsttimeCheckIn').cxCalendar();
        $('#beginPayTime').cxCalendar();
        $('#endPayTime').cxCalendar();
        $('.tablelist tbody tr:odd').addClass('odd');
    </script>

@endsection