@extends('layouts.banner')

@section('title', '新增住户信息')

@section('js')
    <script language="JavaScript" src="{{url('/js/addShareHousehold.js')}}"></script>
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
                        <input id="jobNumber" name="jobNumber" type="text" placeholder="必填，长度1~12，不可重复"
                               onblur="validateJobNumber()">
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
                        <label for="institution">单位：</label>
                    </td>
                    <td class="td-right">
                        <input id="institution" name="institution" type="text" placeholder="必填，长度1~20">
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div class="choose-shareHousehold-content">
        <label for="index-name">姓名：</label>
        <input type="text" id="index-name" class="index-input" name="name">
        <label for="index-jobNumber">工号：</label>
        <input type="text" id="index-jobNumber" class="index-input" name="jobNumber">
        <input type="button" id="index-submit" onclick="searchShareHousehold()" value="查询">
    </div>

    <div class="rent-result addHouseHold-content addHouseHold-content-house rent-item">
        <div class="result-title">
            租房
        </div>
        <form class="form-rent-item">
            <table>
                <tr>
                    <td>
                        <label for="region">区域：</label>
                    </td>
                    <td class="td-right">
                        <input id="region" class="result-input" type="text" value="" readonly="readonly"/>
                    </td>
                    <td>
                        <label for="address">房址：</label>
                    </td>
                    <td>
                        <input id="address" class="result-input" type="text" value="" readonly="readonly"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="first-check-in-time">入住时间：</label>
                    </td>
                    <td>
                        <input id="first-check-in-time" type="text" class="result-input" value="" readonly="readonly">
                    </td>
                    <td>
                        <label for="area">租房面积：</label>
                    </td>
                    <td class="td-right">
                        <input id="area" class="result-input" type="text" value="" readonly="readonly"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="roomNumber">房间号：</label>
                    </td>
                    <td class="td-right">
                        <input id="roomNumber" class="result-input" type="text" value="" readonly="readonly">
                    </td>
                    <td>
                        <label for="remark">备注：</label>
                    </td>
                    <td>
                            <textarea id="remark" class="result-input" readonly="readonly"></textarea>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div class="add-new-rent">
        <button id="btn-right" onclick="addShareHousehold()">保存</button>
    </div>

    <script src="{{url('/js/jquery.cxcalendar.min.js')}}"></script>
    <link rel="stylesheet" href="{{url('/css/jquery.cxcalendar.css')}}">

    @include('layer.successTip')
    @include('layer.errorTip')
    @include('layer.commonErrorTip')

@endsection