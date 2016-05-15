<div id="addAreaTip">
    <div id="addAreaTop"><span>添加区域</span><a onclick="$('#addAreaTip').fadeOut(200)"></a></div>

    <div id="addAreaInfo">
        <p>区域名：</p>
        <input name="areaName" type="text" id="addAreaNameInput"/>
    </div>

    <div id="addAreaBtn">
        <input name="" type="button" id="addAreaSure" value="添加" onclick="addArea()"/>&nbsp;
        <input name="" type="button" id="addAreaCancel" onclick="$('#addAreaTip').fadeOut(200)" value="取消"/>
    </div>

</div>