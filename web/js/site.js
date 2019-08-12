// Buy button on product page
$('a.buy-product').click(function() {
    $.ajax({
       type: 'POST',
       url: this.href,
       data: {name: $(this).closest('tr').find('td').eq(0).text(), price: $(this).closest('tr').find('td').eq(1).text()},
       success: function(data) {
           $('.cart strong').text(data.price);
           $('.cart span').text(data.quantity);
       }
    });
    return false;
});

// Reload cart data in menu
$('#cart').on('pjax:success', function() {
    $.pjax.reload({container: '#menu-cart'});
});