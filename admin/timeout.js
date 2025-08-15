 setTimeout(function() {
            let alertElem = document.getElementById('roomExistsAlert');
            if (alertElem) {
                let bsAlert = new bootstrap.Alert(alertElem);
                bsAlert.close();
            }
        }, 5000); // 5000ms = 5 seconds