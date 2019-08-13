// Reload cart data in menu
$('#cart').on('pjax:success', function() {
    $.pjax.reload({container: '#menu-cart'});
});