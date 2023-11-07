const formsAjax = document.querySelectorAll('.formAjax');

formsAjax.forEach(form => {
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        Swal.fire({
            title: "¿Estas seguro?",
            text: "¿Quieres realizar la acción solicitada?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, realizar",
            cancelButtonText: "No, cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                let data = new FormData(this);
                let method = this.getAttribute('method');
                let action = this.getAttribute('action');
                let headers = new Headers();
                let config = {
                    method,
                    headers,
                    mode: 'cors',
                    cache: 'no-cache',
                    body: data,
                };

                fetch(action, config)
                    .then(res => res.json())
                    .then(res => {
                        return alertAjax(res);
                    })
                    .catch(err => console.error(err));
            }
        });
    });
});

function alertAjax(alert) {
    if (alert.type === 'simple') {
        Swal.fire({
            icon: alert.icon,
            title: alert.title,
            text: alert.text,
            confirmButtonText: 'Aceptar'
        });
    } else if (alert.type === 'reload') {
        Swal.fire({
            icon: alert.icon,
            title: alert.title,
            text: alert.text,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
            }
        });
    } else if (alert.type === 'clean') {
        Swal.fire({
            icon: alert.icon,
            title: alert.title,
            text: alert.text,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('.formAjax').reset();
            }
        });
    } else if (alert.type === 'redirect') {
        window.location.href = alert.url;
    };
};