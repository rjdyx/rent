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
                <td><label for="addTurnoverRentInput">周转期租金：</label></td>
                <td><input name="TurnoverRent" type="text" id="addTurnoverRentInput" placeholder="" required> 元</td>
            </tr>
            <tr>
                <td><label for="addDiscountRentInput">优惠市场租金：</label></td>
                <td><input name="DiscountRent" type="text" id="addDiscountRentInput" placeholder="" required> 元</td>
            </tr>
            <tr>
                <td><label for="addMarketRentInput">市场租金：</label></td>
                <td><input name="MarketRent" type="text" id="addMarketRentInput" placeholder="" required> 元</td>
            </tr>
            <tr>
                <td><label for="addStandadRentSingleInput">标准租金单价：</label></td>
                <td><input name="StandadRentSingle" type="text" id="addStandadRentSingleInput" placeholder="" required> 元</td>
            </tr>
            <tr>
                <td><label for="addStandadRentDecorateInput">单项装修标准租金：</label></td>
                <td><input name="StandadRentDecorate" type="text" id="addStandadRentDecorateInput" placeholder="" required> 元</td>
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