<div id="editAreaTip">
    <input type="hidden" value="" id="edit-area-id">
    <div id="editAreaTop"><span>修改区域</span><a onclick="fadeOutDialog('editAreaTip')"></a></div>

    <form id="editArea-form">
        <div id="editAreaInfo">
            <p>区域名：</p>
            <input name="areaName" type="text" id="editAreaNameInput" placeholder="长度2~20" required>
        </div>

        <div id="editAreaBtn">
            <input name="" type="submit" id="editAreaSure" value="修改"/>&nbsp;
            <input name="" type="button" id="editAreaCancel" onclick="fadeOutDialog('editAreaTip')" value="取消"/>
        </div>
    </form>
</div>