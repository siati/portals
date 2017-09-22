var logOutButton = $('.sgn-ot .btn');

var activityTimeoutSwalTime = 1000 * 60 * 8; //8 minutes

var activityTimeoutTime = 1000 * 60 * 10; //10 minutes

var activityTimeoutSwal = setTimeout(timeOutSwal, activityTimeoutSwalTime);

var activityTimeout = setTimeout(inActive, activityTimeoutTime);

function timeOutSwal() {
    swal(
            {
                title: '<small>You are being signed out...</small>',
                text: '<p>Your session idle time is almost expiring</p>',
                type: 'warning',
                html: true,
                showCancelButton: true,
                confirmButtonColor: '#dd6b55',
                confirmButtonText: 'Stay Signed In',
                cancelButtonText: 'Sign Out',
                closeOnConfirm: false,
                closeOnCancel: true,
                allowEscapeKey: true,
                allowOutsideClick: true
            },
            function (ok) {
                if (ok) {
                    resetActive();
                    swal.close();
                } else
                    inActive();
            }
    );
}

function resetActive() {
    clearTimeout(activityTimeoutSwal);
    activityTimeoutSwal = setTimeout(timeOutSwal, activityTimeoutSwalTime);

    clearTimeout(activityTimeout);
    activityTimeout = setTimeout(inActive, activityTimeoutTime);
}

function inActive() {
    logOutButton.click();
}

function activateListeners() {
    if (logOutButton.length)
        $(document).click(
                function () {
                    resetActive();
                }
        ).mousemove(
                function () {
                    resetActive();
                }
        ).keypress(
                function () {
                    resetActive();
                }
        );
    else {
        clearTimeout(activityTimeout);
        clearTimeout(activityTimeoutSwal);
    }
}

activateListeners();