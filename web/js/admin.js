// Editing and creating settings
$('body').on('beforeSubmit', '#paramForm, #createSettingForm, #clearData', function () {
    var form = $(this);
    if (form.find('.has-error').length) {
        return false;
    }
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function(data){ 
            if (form.attr('id') == 'paramForm') {
                if (data.name == 'skin') {
                    location.reload();
                } else {
                    $('#modalContent').html(data.message);$('#' + data.name).text(data.value);
                }
            } else {
                $('#modalContent').html(data);
            } 
        }
    });
    return false;
});
function settings(label,field,url)
{
    $.ajax({
       type: 'POST',
       cache: false,
       url: url,
       data: {field: field},
       success: function(data) {
           $('#modalContent').html(data);
           $('#modal').modal('show').find('#modalTitle').text(label);
       }
    });
}
function createSetting(title,url)
{
    $.ajax({
       type: 'POST',
       cache: false,
       url: url,
       success: function(data) {
           $('#modalContent').html(data);
           $('#modal').modal('show').find('#modalTitle').text(title);
       }
    });
}

//delete orders and traffic
function clearData(confirm_text, title, url)
{
    krajeeDialog.confirm(confirm_text, function (confirmed) {
        if (confirmed) {
            createSetting(title, url);
        } else {
            !cancel || cancel();
        }
    });
    return false;
}

// conclusion of the confirm dialog in the style of bootstrap
yii.confirm = function (message, ok, cancel) {
    krajeeDialog.confirm(message, function (confirmed) {
        if (confirmed) {
            !ok || ok();
        } else {
            !cancel || cancel();
        }
    });
    return false;
}

// Update datepicker on created_at field after pjax ends
if ($('input[id$="created_at"], input[id$="date_from"], input[id$="date_to"]').length) {
    $(document).on('pjax:success', function() {
        $('input[id$="created_at"], input[id$="date_from"], input[id$="date_to"]').datepicker($.extend({}, $.datepicker.regional['en'], {"dateFormat":"dd.mm.yy"}));
    });
}

// Show info modal on content page
$('#blocks-info-btn').click(function() {
    $('#modal').modal('show').find('#modalTitle').text($('#blocks-info h2').text());
    $('#modal').find('.modal-body').html($('#blocks-info div').html());
    return false;
});