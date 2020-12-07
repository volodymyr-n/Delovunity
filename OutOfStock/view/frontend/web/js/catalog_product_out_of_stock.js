define([
    'jquery',
    'mage/url'
], function (
    $,
    url,
) {
    function send(data){
        const API_URL= BASE_URL+'rest/all/V1/subscriptions';
        $.ajax ({
            url: API_URL,
            type: "POST",
            headers: {"Content-Type": "application/json; charset=utf-8"},
            data: JSON.stringify({"subscriptions": data}),
            success: function(datas){
                alert(datas);
            }
        });
    }
    function main() {
        let data={
            'email': $('#delovunity_out_of_stock_email').val(),
            'id_product': $('#delovunity_out_of_stock_product_id').val()
        };
            send(data);
    }
    $('#delovunity_out_of_stock_button').click(main);
});
