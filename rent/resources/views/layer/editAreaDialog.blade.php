<div id="editAreaTip">
    <input type="hidden" value="" id="edit-area-id">
    <div id="editAreaTop"><span>修改区域</span><a onclick="$('#editAreaTip').fadeOut(200)"></a></div>

    <div id="editAreaInfo">
        <p>区域名：</p>
        <input name="areaName" type="text" id="editAreaNameInput"/>
    </div>

    <div id="editAreaBtn">
        <input name="" type="button" id="editAreaSure" value="修改" onclick="editArea()"/>&nbsp;
        <input name="" type="button" id="editAreaCancel" onclick="$('#editAreaTip').fadeOut(200)" value="取消"/>
    </div>

</div>