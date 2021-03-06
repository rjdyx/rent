@extends('layouts.banner')

@section('title', '编辑住户信息')

@section('js')
    <script language="JavaScript" src="{{url('/js/editHousehold.js')}}"></script>
@endsection
@section('css')
    <link rel="stylesheet" href="{{url('/css/addHouseHold.css')}}">
@endsection


@section('content')
    <div class="addHouseHold-content">
        <form id="form-household-base-msg">
            <input type="hidden" value="{{ $householdMsg['id'] }}" id="householdId">
            <table>
                <tr>
                    <td>
                        <label for="name">姓名：</label>
                    </td>
                    <td class="td-right">
                        <input id="name" name="name" type="text" value="{{ $householdMsg['name'] }}"
                               placeholder="必填，长度1~10">
                    </td>
                    <td>
                        <label for="jobNumber">工号：</label>
                    </td>
                    <td>
                        <input id="jobNumber" name="jobNumber" type="text" value="{{ $householdMsg['job_number'] }}"
                               placeholder="必填，长度1~12，不可重复">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="cardNumber">银行卡号：</label>
                    </td>
                    <td class="td-right">
                        <input id="cardNumber" name="cardNumber" type="text"
                               value="{{ $householdMsg['card_number'] }}" placeholder="非必填，长度19">
                    </td>
                    <td>
                        <label for="type">发放方式：</label>
                    </td>
                    <td>
                        <select class="type" id="type" name="type">
                            <option value="0" @if( $householdMsg['type'] == 0 ) selected="selected" @endif>校发</option>
                            <option value="1" @if( $householdMsg['type'] == 1 ) selected="selected" @endif>省发</option>
                            <option value="2" @if( $householdMsg['type'] == 2 ) selected="selected" @endif>租赁人员</option>
                            <option value="3" @if( $householdMsg['type'] == 3 ) selected="selected" @endif>博士后</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="institution">单位：</label>
                    </td>
                    <td class="td-right">
                        <input id="institution" name="institution" type="text"
                               value="{{ $householdMsg['institution'] }}" placeholder="必填，长度1~20">
                    </td>
                    <td>
                        <label for="inputCountTime">累计时间：</label>
                    </td>
                    <td>
                        <input id="inputCountTime" name="inputCountTime" type="text"
                               value="{{ $householdMsg['input_count_time'] }}" placeholder="格式为：年.月.日">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="inSchoolTime">入校时间：</label>
                    </td>
                    <td>
                        <input type="text" id="inSchoolTime" name="inSchoolTime"
                               value="{{ date('Y-m-d',strtotime($householdMsg['in_school_time'])) }}" readonly="readonly"
                               placeholder="必选">
                    </td>
                    <td>
                        <label for="hasHouseOrSubsidy">无房改+补贴：</label>
                    </td>
                    <td>
                        <input id="hasHouseOrSubsidy" name="hasHouseOrSubsidy" type="checkbox"
                               @if( $householdMsg['has_house_or_subsidy'] == 1 ) checked="checked" @endif/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>是否有房：</label>
                    </td>
                    <td>
                        <input class="radio-hasHouse" name="hasHouse" value="0" type="radio"
                               @if( $householdMsg['has_house'] == 0 ) checked="checked" @endif/>&nbsp;<span
                                class="radio-hasHouse-name">无房</span>
                        <input class="radio-hasHouse" name="hasHouse" value="1" type="radio"
                               @if( $householdMsg['has_house'] == 1 ) checked="checked" @endif/>&nbsp;<span
                                class="radio-hasHouse-name">八区内有房</span>
                        <input class="radio-hasHouse" name="hasHouse" value="2" type="radio"
                               @if( $householdMsg['has_house'] == 2 ) checked="checked" @endif/>&nbsp;<span
                                class="radio-hasHouse-name">有房改房</span>
                    </td>
                    <td>
                        <label for="isDimission">是否离职：</label>
                    </td>
                    <td>
                        <input id="isDimission" name="isDimission" type="checkbox"
                               @if( $householdMsg['is_dimission'] == 1 ) checked="checked" @endif/>
                    </td>
                </tr>
            </table>
        </form>
        <div class="opt-rent-btn">
            <button onclick="addRent()">新增租房</button>
            <button onclick="saveChange({{ $householdMsg['id'] }})">保存</button>
        </div>
    </div>


    @foreach($rents as $rent)
        <div class="addHouseHold-content addHouseHold-content-house rent-item">
            <div class="addHouseHold-title">
                租房<input class="order-number" id="order-number-{{ $rent['order'] }}" value="{{ $rent['order'] }}">
                <button  class="rent-save-btn" onclick="saveRentMsg({{ $rent['id'] }},{{ $rent['order'] }})">
                    保存
                </button>
            </div>
            <form class="form-rent-item" id="form-item{{ $rent['order'] }}">
                <table>
                    <tr>
                        <td>
                            <label for="region">区域：</label>
                        </td>
                        <td class="td-right">
                            <input class="region" id="region{{ $rent['order'] }}" name="region" type="text"
                                   value="{{ $rent->regionMsg()->first()->name }}" readonly="readonly"/>
                        </td>
                        <td>
                            <label for="address">房址：</label>
                        </td>
                        <td>
                            <input class="address" id="address{{ $rent['order'] }}" name="address" type="text"
                                   value="{{ $rent->addressMsg()->first()->name }}" readonly="readonly"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="first-check-in-time">入住时间：</label>
                        </td>
                        <td>
                            <input type="text" class="first-check-in-time" name="firsttimeCheckIn"
                                   value="{{ date('Y-m-d',strtotime($rent['firsttime_check_in'])) }}"
                                   readonly="readonly">
                        </td>
                        <td>
                            <label for="area">租房面积：</label>
                        </td>
                        <td class="td-right">
                            <input class="area" name="area" type="text"
                                   value="{{ $rent['area'] }}" readonly="readonly"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="roomNumber">房间号：</label>
                        </td>
                        <td class="td-right">
                            <input id="roomNumber{{ $rent['order'] }}" class="roomNumber" name="roomNumber" type="text" placeholder="" value="{{ $rent['room_number'] }}" readonly="readonly">
                        </td>
                        <td>
                            <label for="">备注：</label>
                        </td>
                        <td>
                            <textarea id="remark{{ $rent['order'] }}" class="remark" name="remark" readonly="readonly">{{ $rent['remark'] }}</textarea>
                        </td>
                    </tr>
                </table>
            </form>
            <div class="opt-rent-btn">
                <button onclick="showCommonDialog('退房','确认退房？','退房','checkOutRent',[['input-id','{{ $rent['id'] }}']])">
                    退房
                </button>
                <button onclick="showCommonDialog('作废','确认作废租房记录？','作废','deleteRent',[['input-id','{{ $rent['id'] }}']])">
                    作废
                </button>
            </div>
        </div>
    @endforeach


    <div class="new-rent-area"></div>

    @foreach($rentsCheckOut as $rent)
        <div class="addHouseHold-content addHouseHold-content-house rent-item">
            <div class="addHouseHold-title">
                租房——已退房&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;退房时间：{{ date('Y-m-d',strtotime($rent['lasttime_pay_rent'])) }}</div>
            <form class="form-rent-item">
                <table>
                    <tr>
                        <td>
                            <label for="region">区域：</label>
                        </td>
                        <td class="td-right">
                            <input class="region" id="region{{ $rent['order'] }}" name="region" type="text"
                                   value="{{ $rent->regionMsg()->first()->name }}" readonly="readonly"/>
                        </td>
                        <td>
                            <label for="address">房址：</label>
                        </td>
                        <td>
                            <input class="address" id="address{{ $rent['order'] }}" name="address" type="text"
                                   value="{{ $rent->addressMsg()->first()->name }}" readonly="readonly"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="first-check-in-time">入住时间：</label>
                        </td>
                        <td>
                            <input type="text" class="first-check-in-time" name="firsttimeCheckIn"
                                   value="{{ date('Y-m-d',strtotime($rent['firsttime_check_in'])) }}"
                                   readonly="readonly">
                        </td>
                        <td>
                            <label for="area">租房面积：</label>
                        </td>
                        <td class="td-right">
                            <input class="area" name="area" type="text"
                                   value="{{ $rent['area'] }}" readonly="readonly"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="roomNumber">房间号：</label>
                        </td>
                        <td class="td-right">
                            <input id="roomNumber" class="roomNumber" name="roomNumber" type="text" placeholder="" value="{{ $rent['room_number'] }}" readonly="readonly">
                        </td>
                        <td>
                            <label for="">备注：</label>
                        </td>
                        <td>
                            <textarea id="remark" class="remark" name="remark" readonly="readonly">{{ $rent['remark'] }}</textarea>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    @endforeach

    <script src="{{url('/js/jquery.cxcalendar.min.js')}}"></script>
    <link rel="stylesheet" href="{{url('/css/jquery.cxcalendar.css')}}">
    <script type="text/javascript">
        var count = {{ sizeof($rents) }};
    </script>

    @include('layer.successTip')
    @include('layer.errorTip')
    @include('layer.sureDeleteDialog')
    @include('layer.commonDialog')

@endsection