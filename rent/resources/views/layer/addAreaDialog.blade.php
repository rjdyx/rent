<div id="addAreaTip">
    <div id="addAreaTop"><span>添加区域</span><a onclick="fadeOutDialog('addAreaTip')"></a></div>

    <form id="addArea-form">
        <div id="addAreaInfo">
            <p>区域名：</p>
            <input name="areaName" type="text" id="addAreaNameInput" placeholder="长度2~20" required>
        </div>

        <div id="addAreaBtn">
            <input name="" type="submit" id="addAreaSure" value="添加"/>&nbsp;
            <input name="" type="button" id="addAreaCancel" onclick="fadeOutDialog('addAreaTip')" value="取消"/>
        </div>
    </form>


</div>
