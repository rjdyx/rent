<div id="editAddressTip">
    <div id="editAddressTop"><span>修改房址</span><a onclick="fadeOutDialog('editAddressTip')"></a></div>

    <form id="form_editAddress" method="post" action="{{ url('/admin/editAddress') }}">
        {!! csrf_field() !!}
        <input type="hidden" value="" id="edit-address-id" name="id">
        <table class="editAddressTable">
            <tr>
                <td><label for="editAddressNameInput">房址名：</label></td>
                <td><input name="AddressName" type="text" id="editAddressNameInput" placeholder="长度2~20" minlength="2" maxlength="20"  required></td>
            </tr>
            <tr>
                <td><label for="editTurnoverRentInput">周转期租金：</label></td>
                <td><input name="TurnoverRent" type="text" id="editTurnoverRentInput" placeholder="" required> 元</td>
            </tr>
            <tr>
                <td><label for="editDiscountRentInput">优惠市场租金：</label></td>
                <td><input name="DiscountRent" type="text" id="editDiscountRentInput" placeholder="" required> 元</td>
            </tr>
            <tr>
                <td><label for="editMarketRentInput">市场租金：</label></td>
                <td><input name="MarketRent" type="text" id="editMarketRentInput" placeholder="" required> 元</td>
            </tr>
            <tr>
                <td><label for="editStandadRentSingleInput">标准租金单价：</label></td>
                <td><input name="StandadRentSingle" type="text" id="editStandadRentSingleInput" placeholder="" required> 元</td>
            </tr>
            <tr>
                <td><label for="editStandadRentDecorateInput">单项装修标准租金：</label></td>
                <td><input name="StandadRentDecorate" type="text" id="editStandadRentDecorateInput" placeholder="" required> 元</td>
            </tr>
            <tr>
                <td><span class="lb-area">所属区域：</span></td>
                <td>
                    <select class="" id="editAreaOption" name="ParentId">

                    </select>
                </td>
            </tr>
        </table>


        <div id="editAddressBtn">
            <input name="" type="submit" id="editAddressSure" value="修改"/>&nbsp;
            <input name="" type="button" id="editAddressCancel" onclick="fadeOutDialog('editAddressTip')" value="取消"/>
        </div>
    </form>
</div>