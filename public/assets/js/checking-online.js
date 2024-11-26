function checkNetwork() {
    if (navigator.onLine) {
        checkConnectionQuality();
    } else {
        if (sessionStorage.getItem('networkStatus') !== 'offline') {
            toastr.error('You are offline, check your network again!', 'Error');
            sessionStorage.setItem('networkStatus', 'offline');
        }
    }
}

function checkConnectionQuality() {
    let connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;

    if (connection) {
        let effectiveType = connection.effectiveType;
        let rtt = connection.rtt;
        let downlink = connection.downlink;

        if (effectiveType === '4g' && rtt < 150 && downlink > 2) {
            if (sessionStorage.getItem('networkStatus') !== 'online') {
                toastr.success('You are back online!', 'Success');
                sessionStorage.setItem('networkStatus', 'online');
            }
        } else {
            if (sessionStorage.getItem('networkStatus') !== 'poor') {
                toastr.error('Your network is bad, check your network again!', 'Error');
                sessionStorage.setItem('networkStatus', 'poor');
            }
        }
    } else {
        if (sessionStorage.getItem('networkStatus') !== 'online') {
            toastr.success('You are back online!', 'Success');
            sessionStorage.setItem('networkStatus', 'online');
        }
    }
}

window.addEventListener('online', checkNetwork);
window.addEventListener('offline', checkNetwork);

$(document).ready(function(event) {
    checkNetwork();
});