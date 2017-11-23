function popWindow(url, windowName) {
    window.open(url, windowName, "width=300, height=500, scrollbars=yes, toolbar=0, location=0, directories=0, status=0, menubar=0");
}

function expireResource(pre, url) {
    $.post(pre + 'site/expire-resource', {'nm': url}, function () {});
}

function fileDownload(pre, cat, nm, ttl) {
    $.post(pre + 'site/check-file', {'cat': cat, 'nm': nm},
            function (url) {
                if (url === false)
                    customSwal('Well...', 'The file seems to have been removed<br/><br/>Please close this pop up window and retry', '2500', 'info', false, true, 'ok', '#f27474', false, 'cancel');
                else {
                    expireResource(pre, url, popWindow(url, ttl));
                    swal.close();
                }
            }
    );
}

function yiiModal(heading, url, post, width, height) {

    $('.modal-dialog').removeClass('modal-lg').removeClass('modal-sm').css('margin', '0 auto').width(width + 'px');

    $('.yii-modal-head').html(heading);

    $.post(url, post,
            function (data) {
                $('#yii-modal-pane').css('margin-top', 0).modal().find('#yii-modal-cnt').css('border-radius', '6px').height(height + 'px').html(data);

                $('#yii-modal-pane').css('margin-top', ((margin_top = $(window).height() - $('.modal-dialog').height() - height - 100) > 0 ? (margin_top / 2) : 0) + 'px');
            }
    );
}

function closeDialog() {
    $('#the-modal-close').click();
}
