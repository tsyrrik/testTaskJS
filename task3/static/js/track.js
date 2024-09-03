(async function() {
    // Собираем данные
    const ipData = await fetch('https://api.ipify.org?format=json').then(response => response.json());
    const geoData = await fetch(`https://ipinfo.io/${ipData.ip}/json`).then(response => response.json());
    const deviceType = /Mobi/i.test(navigator.userAgent) ? 'Mobile' : 'Desktop';

    // Формируем данные для отправки на сервер
    const visitData = {
        ip: ipData.ip,
        city: geoData.city || 'Unknown',
        device: deviceType,
        timestamp: new Date().toISOString()
    };

    // Отправляем данные на сервер
    fetch('/track.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(visitData),
    });
})();
