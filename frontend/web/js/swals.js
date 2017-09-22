function customErrorSwal(title, message, timer, type, modal) {
    swal(
            {
                title: title,
                text: message,
                timer: timer,
                type: type,
                html: true,
                showConfirmButton: true,
                closeOnConfirm: true,
                allowEscapeKey: modal,
                allowOutsideClick: modal
            }
    );
}
