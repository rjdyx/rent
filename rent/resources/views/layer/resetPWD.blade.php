<div id="resetPWDTip">
    <div id="resetPWDTop"><span>重置密码</span><a onclick="$('#resetPWDTip').fadeOut(200)"></a></div>

    <input type="hidden" value="" id="resetPWD-id">

    <div id="resetPWDInfo">
        <p>新密码</p>
        <input name="areaName" type="text" id="resetPWDNameInput"/>
    </div>

    <div id="resetPWDBtn">
        <input name="" type="button" id="resetPWDSure" value="重置" onclick="resetPWD()"/>&nbsp;
        <input name="" type="button" id="resetPWDCancel" onclick="$('#resetPWDTip').fadeOut(200)" value="取消"/>
    </div>

</div>