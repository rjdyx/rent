(function () {

})

var selectFlag = 0;

function selectAll() {
    if(!selectFlag){
        selectFlag = 1;
        $("input[type=checkbox]").each(function () {
            $(this).prop('checked',true);
            $(this).parent().css({
                'backgroundColor': '#3B95C8',
                'color': '#fff',
                'border': '1px solid #3B95C8'
            });
        });
    }else{
        selectFlag = 0;
        $("input[type=checkbox]").each(function () {
            $(this).prop('checked',false);
            $(this).parent().css({
                'backgroundColor': '#fff',
                'color': 'black',
                'border': '1px solid black'
            });
        });
    }
}

function selectType(id) {
    if($("#"+id).prop('checked')){
        $("#"+id).prop('checked',false);
        $("#"+id).parent().css({
            'backgroundColor': '#fff',
            'color': 'black',
            'border': '1px solid black'
        });
    }else{
        $("#"+id).prop('checked',true);
        $("#"+id).parent().css({
            'backgroundColor': '#3B95C8',
            'color': '#fff',
            'border': '1px solid #3B95C8'
        });
    }
}