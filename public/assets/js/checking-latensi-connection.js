let previousRtt = null;

function updateNetworkInfo() {
    const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;

    if (connection) {
        document.getElementById('rtt').textContent = connection.rtt;

        updateWifiIcon(connection.rtt);

        connection.addEventListener('change', updateNetworkInfo);
    } else {
        document.getElementById('network-info').textContent = 'API Informasi Jaringan tidak didukung oleh browser Anda.';
    }
}

function updateWifiIcon(rtt) {
    const wifiIcon = document.getElementById('wifi-icon');
    
    if (rtt == 0) {
        wifiIcon.style.color = 'rgba(0, 0, 0, 0.5)';
    } else if (rtt <= 100) {
        wifiIcon.style.color = '#3bc346';
    } else if (rtt > 100 && rtt <= 200) {
        wifiIcon.style.color = '#e9de48';
    } else if (rtt > 200) {
        wifiIcon.style.color = '#dc3545';
    } 
}

updateNetworkInfo();