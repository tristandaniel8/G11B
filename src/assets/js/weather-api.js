/**
 * Intégration API météo
 * Utilise l'API OpenWeatherMap pour afficher les conditions météorologiques actuelles
 */
document.addEventListener('DOMContentLoaded', function() {
    // Clé API OpenWeatherMap (à remplacer par votre propre clé)
    const apiKey = 'YOUR_API_KEY';
    
    // Coordonnées par défaut (à remplacer par les coordonnées réelles du manège)
    const defaultLat = 48.8566; // Paris
    const defaultLon = 2.3522;
    
    // Élément pour afficher la météo
    const weatherContainer = document.getElementById('weather-container');
    if (!weatherContainer) return;
    
    // Vérifier si la géolocalisation est disponible
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            // Succès de la géolocalisation
            function(position) {
                getWeatherData(position.coords.latitude, position.coords.longitude);
            },
            // Erreur de géolocalisation
            function(error) {
                console.log("Erreur de géolocalisation: " + error.message);
                getWeatherData(defaultLat, defaultLon);
            }
        );
    } else {
        // Géolocalisation non supportée
        getWeatherData(defaultLat, defaultLon);
    }
    
    // Fonction pour récupérer les données météo
    function getWeatherData(lat, lon) {
        const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&lang=fr&appid=${apiKey}`;
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                displayWeather(data);
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des données météo:', error);
                weatherContainer.innerHTML = `
                    <div class="weather-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Impossible de charger les données météo</span>
                    </div>
                `;
            });
    }
    
    // Fonction pour afficher les données météo
    function displayWeather(data) {
        const temp = Math.round(data.main.temp);
        const description = data.weather[0].description;
        const icon = data.weather[0].icon;
        const humidity = data.main.humidity;
        const windSpeed = Math.round(data.wind.speed * 3.6); // Conversion de m/s en km/h
        const cityName = data.name;
        
        // Déterminer si les conditions météo sont favorables
        const isFavorable = temp > 5 && temp < 35 && windSpeed < 30;
        
        weatherContainer.innerHTML = `
            <div class="weather-card ${isFavorable ? 'favorable' : 'unfavorable'}">
                <div class="weather-header">
                    <h3><i class="fas fa-map-marker-alt"></i> ${cityName}</h3>
                    <div class="weather-status">
                        ${isFavorable ? 
                            '<span class="status-badge favorable"><i class="fas fa-check-circle"></i> Conditions favorables</span>' : 
                            '<span class="status-badge unfavorable"><i class="fas fa-exclamation-triangle"></i> Conditions défavorables</span>'
                        }
                    </div>
                </div>
                <div class="weather-body">
                    <div class="weather-icon">
                        <img src="https://openweathermap.org/img/wn/${icon}@2x.png" alt="${description}">
                    </div>
                    <div class="weather-info">
                        <div class="weather-temp">${temp}°C</div>
                        <div class="weather-desc">${description}</div>
                    </div>
                </div>
                <div class="weather-details">
                    <div class="weather-detail">
                        <i class="fas fa-tint"></i> Humidité: ${humidity}%
                    </div>
                    <div class="weather-detail">
                        <i class="fas fa-wind"></i> Vent: ${windSpeed} km/h
                    </div>
                </div>
            </div>
        `;
    }
}); 