<div id="editAreaTip">

    <div id="editAreaTop"><span>修改区域</span><a onclick="fadeOutDialog('editAreaTip')"></a></div>

    <form id="editArea-form" method="post" action="{{ url('/admin/editArea') }}">
        {!! csrf_field() !!}
        <input type="hidden" value="" id="edit-area-id" name="id">
        <div id="editAreaInfo">
            <p>区域名：</p>
            <input name="name" type="text" id="editAreaNameInput" placeholder="长度2~20" minlength="2" maxlength="20"  required>
        </div>

        <div id="editAreaBtn">
            <input name="" type="submit" id="editAreaSure" value="修改"/>&nbsp;
            <input name="" type="button" id="editAreaCancel" onclick="fadeOutDialog('editAreaTip')" value="取消"/>
        </div>
    </form>
</div>