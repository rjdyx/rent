@extends('layouts.banner')

@section('title', '新增住户信息')

@section('js')
    <script language="JavaScript" src="{{url('/js/addHousehold.js')}}"></script>
@endsection
@section('css')
    <link rel="stylesheet" href="{{url('/css/addHouseHold.css')}}">
@endsection


@section('content')
    <div class="addHouseHold-content">
        <form id="form-household-base-msg">
            <table>
                <tr>
                    <td>
                        <label for="name">姓名：</label>
                    </td>
                    <td class="td-right">
                        <input id="name" name="name" type="text" placeholder="必填，长度1~10">
                    </td>
                    <td>
                        <label for="jobNumber">工号：</label>
                    </td>
                    <td>
                        <input id="jobNumber" name="jobNumber" type="text" placeholder="必填，长度1~12，不可重复" onblur="validateJobNumber()">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="cardNumber">银行卡号：</label>
                    </td>
                    <td class="td-right">
                        <input id="cardNumber" name="cardNumber" type="text" placeholder="非必填，长度1~19">
                    </td>
                    <td>
                        <label for="type">发放方式：</label>
                    </td>
                    <td>
                        <select class="type" id="type" name="type">
                            <option value="0">校发</option>
                            <option value="1">省发</option>
                            <option value="2">租赁人员</option>
                            <option value="3">博士后</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="institution">单位：</label>
                    </td>
                    <td class="td-right">
                        <input id="institution" name="institution" type="text" placeholder="必填，长度1~20">
                    </td>
                    <td>
                        <label for="inputCountTime">累计时间：</label>
                    </td>
                    <td>
                        <input id="inputCountTime" name="inputCountTime" type="text" placeholder="格式为：年.月.日">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="inSchoolTime">入校时间：</label>
                    </td>
                    <td>
                        <input type="text" id="inSchoolTime" name="inSchoolTime" value="" readonly="readonly"
                               placeholder="必选">
                    </td>
                    <td>
                        <label for="hasHouseOrSubsidy">无房改+补贴：</label>
                    </td>
                    <td>
                        <input id="hasHouseOrSubsidy" name="hasHouseOrSubsidy" type="checkbox"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>是否有房：</label>
                    </td>
                    <td>
                        <input class="radio-hasHouse" name="hasHouse" value="0" type="radio"
                               checked="checked"/>&nbsp;<span class="radio-hasHouse-name">无房</span>
                        <input class="radio-hasHouse" name="hasHouse" value="1" type="radio"/>&nbsp;<span
                                class="radio-hasHouse-name">八区内有房</span>
                        <input class="radio-hasHouse" name="hasHouse" value="2" type="radio"/>&nbsp;<span
                                class="radio-hasHouse-name">有房改房</span>
                    </td>
                    <td>
                        <label for="isDimission">是否离职：</label>
                    </td>
                    <td>
                        <input id="isDimission" name="isDimission" type="checkbox"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div class="addHouseHold-content addHouseHold-content-house rent-item">
        <div class="addHouseHold-title">租房1</div>
        <form class="form-rent-item">
            <table>
                <tr>
                    <td>
                        <label for="region">区域：</label>
                    </td>
                    <td class="td-right">
                        <select class="region" id="region1" name="region">
                            @foreach($areas as $area)
                                <option value="{{$area['id']}}">{{$area['name']}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <label for="address">房址：</label>
                    </td>
                    <td>
                        <select class="address" id="address1" name="address">
                            @foreach($addresses as $address)
                                <option value="{{$address['id']}}">{{$address['name']}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="first-check-in-time">入住时间：</label>
                    </td>
                    <td class="td-right">
                        <input type="text" id="checkInTime1" class="first-check-in-time" name="firsttimeCheckIn"
                               value="" readonly="readonly" placeholder="必选">
                    </td>

                    <td>
                        <label for="area">租房面积：</label>
                    </td>
                    <td>
                        <input class="area" name="area" type="text" placeholder="必填，数字">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="roomNumber">房间号：</label>
                    </td>
                    <td class="td-right">
                        <input id="roomNumber" class="roomNumber" name="roomNumber" type="text" placeholder="">
                    </td>
                    <td>
                        <label for="">备注：</label>
                    </td>
                    <td>
                        <textarea id="remark" class="remark" name="remark"></textarea>
                    </td>
                </tr>
            </table>

        </form>
    </div>

    <div class="add-new-rent">
        <button id="btn-right" onclick="addHousehold()">保存</button>
    </div>

    <script src="{{url('/js/jquery.cxcalendar.min.js')}}"></script>
    <link rel="stylesheet" href="{{url('/css/jquery.cxcalendar.css')}}">
    <script type="text/javascript">
        $('#inSchoolTime').cxCalendar();
        $('#checkInTime1').cxCalendar();
    </script>

    @include('layer.successTip')
    @include('layer.errorTip')
    @include('layer.commonErrorTip')
    @include('layer.sureDeleteDialog')

@endsection