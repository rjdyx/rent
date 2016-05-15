<div id="sureDeleteTip">
    <input type="hidden" value="" id="sure-delete-id">
    <input type="hidden" value="" id="sure-delete-flag">

    <div id="sureDeleteTop"><span>提示</span><a onclick="$('#sureDeleteTip').fadeOut(200)"></a></div>

    <div id="sureDeleteInfo">
        <p>确定删除?</p>
    </div>

    <div id="sureDeleteBtn">
        <input name="" type="button" id="sureDeleteSure" value="删除" onclick="chooseDeleteFMethod()"/>&nbsp;
        <input name="" type="button" id="sureDeleteCancel" onclick="$('#sureDeleteTip').fadeOut(200)" value="取消"/>
    </div>

</div>