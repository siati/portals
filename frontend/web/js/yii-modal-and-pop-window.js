function popWindow(url, windowName) {
    var deferred = $.Deferred();
    
    setTimeout(
        function () {
            if (!window.open(url, windowName, "width=300, height=500, scrollbars=yes, toolbar=0, location=0, directories=0, status=0, menubar=0"))
                customSwal('Seeking Permission', 'Please allow popups for this site', '2500', 'info', false, true, 'ok', '#f8bb86', false, 'cancel');
            
            deferred.resolve();
        }, 500
    );

    return deferred.promise();
}

function fileDownLoadInPop(url, ttl) {
    var deferred = $.Deferred();
    
    setTimeout(
        function () {
            myFileWindow = window.open('', ttl, "width=300, height=500, scrollbars=yes, toolbar=0, location=0, directories=0, status=0, menubar=0");
            
            if (myFileWindow)
                myFileWindow.document.write("<iframe frameborder=0 src='" + url + "' style='width: 100%; height: 100%'></iframe>");
            else
                customSwal('Seeking Permission', 'Please allow popups for this site', '5000', 'info', false, true, 'ok', '#f8bb86', false, 'cancel');
            
            deferred.resolve();
        }, 500
    );

    return deferred.promise();
}

function expireResource(pre, url) {
    var deferred = $.Deferred();
    
    setTimeout(
        function () {
            $.post(pre + 'site/expire-resource', {'nm': url}, function () {});

            deferred.resolve();
        }, 500
    );

    return deferred.promise();
}

function fileDownload(pre, cat, nm, ttl) {
    $.post(pre + 'site/check-file', {'cat': cat, 'nm': nm},
            function (url) {
                if (url === false)
                    customSwal('Well...', 'The file seems to have been removed<br/><br/>Please close this pop up window and retry', '2500', 'info', false, true, 'ok', '#f27474', false, 'cancel');
                else {
                    
                    var deferred = $.Deferred();
                    
                    sequence = deferred.promise();
                    
                    sequence.then(fileDownLoadInPop(url, ttl)).then(expireResource(pre, url));
                    
                    deferred.promise();
                    
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
