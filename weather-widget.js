document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('weather-widget');

    fetch(weatherApiUrl)
        .then(response => response.json())
        .then(data => {
            if (data && data.status === 200) {
                const temperature = data.temp;
                const iconUrl = data.icon;
                const description = data.description;

                container.innerHTML = `
                    <h3>Météo - ${temperature} °C - ${description}</h3>
                    <img src='${iconUrl}' alt='Weather Icon'>
                `;
            } else {
                container.textContent = "Impossible de récupérer les données météo.";
            }
        })
        .catch(error => {
            console.error('Une erreur s\'est produite : ', error);
            container.textContent = "Une erreur s'est produite lors de la récupération des données météo.";
        });
});
