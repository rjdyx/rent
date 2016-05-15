<div id="editAddressTip">
    <div id="editAddressTop"><span>添加房址</span><a onclick="$('#editAddressTip').fadeOut(200)"></a></div>

    <form id="form_editAddress">
        <input type="hidden" value="" id="edit-address-id">
        <table class="editAddressTable">
            <tr>
                <td><label for="editAddressNameInput">房址名：</label></td>
                <td><input name="AddressName" type="text" id="editAddressNameInput"/></td>
            </tr>
            <tr>
                <td><label for="editAddressNameInput">周转期租金：</label></td>
                <td><input name="TurnoverRent" type="text" id="editTurnoverRentInput"/> 元</td>
            </tr>
            <tr>
                <td><label for="editAddressNameInput">优惠市场租金：</label></td>
                <td><input name="DiscountRent" type="text" id="editDiscountRentInput"/> 元</td>
            </tr>
            <tr>
                <td><label for="editAddressNameInput">市场租金：</label></td>
                <td><input name="MarketRent" type="text" id="editMarketRentInput"/> 元</td>
            </tr>
            <tr>
                <td><span class="lb-area">所属区域：</span></td>
                <td>
                    <select class="" id="editAreaOption" name="ParentId">

                    </select>
                </td>
            </tr>
        </table>
    </form>

    <div id="editAddressBtn">
        <input name="" type="button" id="editAddressSure" value="修改" onclick="editAddress()"/>&nbsp;
        <input name="" type="button" id="editAddressCancel" onclick="$('#editAddressTip').fadeOut(200)" value="取消"/>
    </div>

</div>