<div id="addAddressTip">
    <div id="addAddressTop"><span>添加房址</span><a onclick="$('#addAddressTip').fadeOut(200)"></a></div>

    <form id="form_addAddress">
        <table class="addAddressTable">
            <tr>
                <td><label for="addAddressNameInput">房址名：</label></td>
                <td><input name="AddressName" type="text" id="addAddressNameInput"/></td>
            </tr>
            <tr>
                <td><label for="addAddressNameInput">周转期租金：</label></td>
                <td><input name="TurnoverRent" type="text" id="addTurnoverRentInput"/> 元</td>
            </tr>
            <tr>
                <td><label for="addAddressNameInput">优惠市场租金：</label></td>
                <td><input name="DiscountRent" type="text" id="addDiscountRentInput"/> 元</td>
            </tr>
            <tr>
                <td><label for="addAddressNameInput">市场租金：</label></td>
                <td><input name="MarketRent" type="text" id="addMarketRentInput"/> 元</td>
            </tr>
            <tr>
                <td><span class="lb-area">所属区域：</span></td>
                <td>
                    <select class="" id="addAreaOption" name="ParentId">

                    </select>
                </td>
            </tr>
        </table>
    </form>

    <div id="addAddressBtn">
        <input name="" type="button" id="addAddressSure" value="添加" onclick="addAddress()"/>&nbsp;
        <input name="" type="button" id="addAddressCancel" onclick="$('#addAddressTip').fadeOut(200)" value="取消"/>
    </div>

</div>