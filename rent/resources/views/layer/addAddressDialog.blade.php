<div id="addAddressTip">
    <div id="addAddressTop"><span>添加房址</span><a onclick="fadeOutDialog('addAddressTip')"></a></div>

    <form id="form_addAddress" method="post" action="{{ url('/admin/addAddress') }}">
        {!! csrf_field() !!}
        <table class="addAddressTable">
            <tr>
                <td><label for="addAddressNameInput">房址名：</label></td>
                <td><input name="AddressName" type="text" id="addAddressNameInput" placeholder="长度2~20" minlength="2" maxlength="20"  required></td>
            </tr>
            <tr>
                <td><label for="addAddressNameInput">周转期租金：</label></td>
                <td><input name="TurnoverRent" type="number" id="addTurnoverRentInput" placeholder="" required> 元</td>
            </tr>
            <tr>
                <td><label for="addAddressNameInput">优惠市场租金：</label></td>
                <td><input name="DiscountRent" type="number" id="addDiscountRentInput" placeholder="" required> 元</td>
            </tr>
            <tr>
                <td><label for="addAddressNameInput">市场租金：</label></td>
                <td><input name="MarketRent" type="number" id="addMarketRentInput" placeholder="" required> 元</td>
            </tr>
            <tr>
                <td><span class="lb-area">所属区域：</span></td>
                <td>
                    <select class="" id="addAreaOption" name="ParentId">

                    </select>
                </td>
            </tr>
        </table>


        <div id="addAddressBtn">
            <input name="" type="submit" id="addAddressSure" value="添加"/>&nbsp;
            <input name="" type="button" id="addAddressCancel" onclick="fadeOutDialog('addAddressTip')" value="取消"/>
        </div>
    </form>

</div>