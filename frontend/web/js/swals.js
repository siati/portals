function customSwal(title, message, timer, type, modal, showConfirmButton, confirmButtonText, confirmButtonColor, showCancelButton, cancelButtonText) {
    timer * 1 > 0 ? timedSwal (title, message, timer, type, !modal, showConfirmButton, confirmButtonText, confirmButtonColor, showCancelButton, cancelButtonText) : untimedSwal (title, message, type, !modal, showConfirmButton, confirmButtonText, confirmButtonColor, showCancelButton, cancelButtonText);
}

function timedSwal (title, message, timer, type, modal, showConfirmButton, confirmButtonText, confirmButtonColor, showCancelButton, cancelButtonText) {
    swal(
            {
                title: title,
                text: message,
                timer: timer,
                type: type,
                html: true,
                showConfirmButton: showConfirmButton,
                confirmButtonText: confirmButtonText,
                confirmButtonColor: confirmButtonColor,
                showCancelButton: showCancelButton,
                cancelButtonText: cancelButtonText,
                closeOnConfirm: true,
                allowEscapeKey: modal,
                allowOutsideClick: modal
            }
    );
}

function untimedSwal (title, message, type, modal, showConfirmButton, confirmButtonText, confirmButtonColor, showCancelButton, cancelButtonText) {
    swal(
            {
                title: title,
                text: message,
                type: type,
                html: true,
                showConfirmButton: showConfirmButton,
                confirmButtonText: confirmButtonText,
                confirmButtonColor: confirmButtonColor,
                showCancelButton: showCancelButton,
                cancelButtonText: cancelButtonText,
                closeOnConfirm: true,
                allowEscapeKey: modal,
                allowOutsideClick: modal
            }
    );
}
