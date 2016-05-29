@extends('layouts.banner')

@section('title', '房租信息列表')

@section('js')
    <script language="JavaScript" src="{{url('/js/importExport.js')}}"></script>
@endsection
@section('css')
    <link rel="stylesheet" href="{{url('/css/importExport.css')}}">
@endsection


@section('content')

    <div class="import-export-content">
        <div class="export-content">
            <form id="export-form" action="{{ url('/admin/export') }}" method="get">
                <div class="export-form-item" onclick="selectAll()"><input type="checkbox" value="1" name="selectAll"
                                                                           id="" class=""><label>全选</label></div>
                <div class="export-form-item" onclick="selectType('inputAllType')"><input type="checkbox" value="1"
                                                                                          name="all" id="inputAllType"
                                                                                          class=""><label>所有</label>
                </div>
                <div class="export-form-item" onclick="selectType('inputSchoolType')"><input type="checkbox" value="1"
                                                                                             name="school"
                                                                                             id="inputSchoolType"
                                                                                             class=""><label>校发</label>
                </div>
                <div class="export-form-item" onclick="selectType('inputProvinceType')"><input type="checkbox" value="1"
                                                                                               name="province"
                                                                                               id="inputProvinceType"
                                                                                               class=""><label>省发</label>
                </div>
                <div class="export-form-item" onclick="selectType('inputLeaseType')"><input type="checkbox" value="1"
                                                                                            name="lease" id="inputLeaseType"
                                                                                            class=""><label>租赁人员</label>
                </div>
                <div class="export-form-item" onclick="selectType('inputPostdoctorType')"><input type="checkbox"
                                                                                                 value="1"
                                                                                                 name="postdoctor"
                                                                                                 id="inputPostdoctorType"
                                                                                                 class=""><label>博士后</label>
                </div>
                <input id="export-rent-btn" type="submit"
                       value="导出房租">
            </form>
        </div>

        <div class="import-content">
            <form id="import-form" action="{{ url('/admin/import') }}" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                <input type="file" name="file">
                <input id="import-rent-btn" type="submit" value="导入">
            </form>
        </div>

    </div>


@endsection