@extends('layouts.banner')

@section('title', '管理账号')

@section('js')
    <script language="JavaScript" src="{{url('/js/manageUserView.js')}}"></script>
@endsection
@section('css')
    <link rel="stylesheet" href="{{url('/css/manageUserView.css')}}">
@endsection


@section('content')

    <div id="user-info-list">
        <table class="tablelist user-tablelist">
            <thead>
            <tr>
                <th>账号名</th>
                <th>邮箱</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach( $users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <button class="detail-btn" onclick="showResetPWDDialog({{ $user->id }})">重置密码</button>
                        <button class="detail-btn lock" id="lock-{{ $user->id }}" onclick="
                                @if($user->active == 1)
                                lock({{ $user->id }},0)">冻结</button>
                                @else
                                lock({{ $user->id }},1)">解冻</button>
                                @endif

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>



    @include('layer.resetPWD')
    @include('layer.successTip')
    @include('layer.errorTip')
    <script type="text/javascript">
        $('.tablelist tbody tr:odd').addClass('odd');
    </script>

@endsection