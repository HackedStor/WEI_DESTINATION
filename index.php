<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Météo des Villes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        
        .container {
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .auth {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 5vh;

        }
        form#registerForm {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 2vh;
            width: 25vh;
        }
        form#connexionForm {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 2vh;
            width: 25vh;
        }
        form {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }
        input[type="text"], input[type="password"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: .4vh;
        }
        button {
            padding: 10px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        td {
            background-color: #fff;
        }
        .error {
            color: #ff0000;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
        .suggestions {
            position: absolute;
            width: 60%;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            z-index: 1000;
            max-height: 150px;
            overflow-y: auto;
        }
        .suggestion-item {
            padding: 10px;
            cursor: pointer;
        }
        .suggestion-item:hover {
            background-color: #f0f0f0;
        }
        
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .navbar .right {
            float: right;
        }
    </style>
</head>
<body>   
    <div class="container">
        <section class="auth">
            <div class="register">
                <h1>S'inscrire</h1>
                <form method="post" action="auth/register.php" id="registerForm" class="registerForm">
                    <input type="text" id="registeridentifiant" name="registeridentifiant" placeholder="Créer votre identifiant" required>
                    <input type="password" id="registerMdp" name="registerMdp" placeholder="Créer votre mot de passe" required>
                    <button type="submit">Valider</button>                    
                </form>
            </div>
            <div class="connexion">
                <h1>Se connecter</h1>
                <form method="post" action="auth/connexion.php" id="connexionForm" class="connexionForm">
                    <input type="text" id="connexionidentifiant" name="connexionidentifiant" placeholder="Entrez votre identifiant" required>
                    <input type="password" id="connexionMdp" name="connexionMdp" placeholder="Entrez votre mot de passe" required>
                    <button type="submit">Valider</button>                    
                </form>
            </div>
        </section>
    </div>
    <script>
        const apiKey = 'YOUR_API_KEY';
        const minTemp = YOUR_MIN_TEMP; 
        const maxTemp = YOUR_MAX_TEMP;
        const tolerance = YOUR_TOLERANCE;
        const startDate = new Date('YOUR_START_DATE');
        const endDate = new Date('YOUR_END_DATE'); 

        let cities = []; 

        async function fetchCities(query) {
            const url = `https://api.openweathermap.org/data/2.5/weather?q=${query},fr&appid=${apiKey}`;
            try {
                const response = await fetch(url);
                const data = await response.json();
                if (data && data.name) {
                    return [data.name];
                } else {
                    return [];
                }
            } catch (error) {
                console.error(`Erreur lors de la requête pour les villes: `, error);
                return [];
            }
        }

        async function fetchWeather(city) {
            const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city},fr&units=metric&appid=${apiKey}`;
            try {
                const response = await fetch(url);
                const data = await response.json();

                if (response.ok) {
                    return data;
                } else {
                    console.error(`Erreur API pour la ville: ${city}, Code: ${data.cod}, Message: ${data.message}`);
                    return null;
                }
            } catch (error) {
                console.error(`Erreur lors de la requête pour ${city}: `, error);
                return null;
            }
        }

        function getDailyTemps(data) {
            if (!data || !data.list) {
                console.error("Les données météo sont invalides ou n'ont pas été trouvées.");
                return [];
            }

            const dailyTemps = {};

            data.list.forEach(item => {
                const date = new Date(item.dt_txt);
                const day = date.toISOString().split('T')[0]; 

                if (date >= startDate && date <= endDate) {
                    if (!dailyTemps[day]) {
                        dailyTemps[day] = {
                            max: item.main.temp_max,
                            min: item.main.temp_min
                        };
                    } else {
                        dailyTemps[day].max = Math.max(dailyTemps[day].max, item.main.temp_max);
                        dailyTemps[day].min = Math.min(dailyTemps[day].min, item.main.temp_min);
                    }
                }
            });

            return dailyTemps;
        }

        function addRowToTable(date, maxTemp, minTemp) {
            const tableBody = document.querySelector('#weatherTable tbody');
            const row = document.createElement('tr');

            const dateCell = document.createElement('td');
            dateCell.textContent = date;
            row.appendChild(dateCell);

            const maxTempCell = document.createElement('td');
            maxTempCell.textContent = `${maxTemp} °C`;
            row.appendChild(maxTempCell);

            const minTempCell = document.createElement('td');
            minTempCell.textContent = `${minTemp} °C`;
            row.appendChild(minTempCell);

            
            const tagCell = document.createElement('td');
            const tagButton = document.createElement('button');
            tagButton.textContent = 'Tag as Potential Destination';
            tagButton.onclick = () => tagCity(date, maxTemp, minTemp);
            tagCell.appendChild(tagButton);
            row.appendChild(tagCell);

            tableBody.appendChild(row);
        }

        function showError(message) {
            document.getElementById('errorMessage').textContent = message;
        }

        function displaySuggestions(suggestions) {
            const suggestionsContainer = document.getElementById('suggestions');
            suggestionsContainer.innerHTML = '';
            suggestions.forEach(suggestion => {
                const div = document.createElement('div');
                div.textContent = suggestion;
                div.className = 'suggestion-item';
                div.addEventListener('click', () => {
                    document.getElementById('cityInput').value = suggestion;
                    suggestionsContainer.innerHTML = '';
                });
                suggestionsContainer.appendChild(div);
            });
        }

        document.getElementById('cityInput').addEventListener('input', async (event) => {
            const query = event.target.value.trim();
            if (query.length > 2) {
                const suggestions = await fetchCities(query);
                displaySuggestions(suggestions);
            } else {
                document.getElementById('suggestions').innerHTML = '';
            }
        });


        async function tagCity(date, maxTemp, minTemp) {
            const city = document.getElementById('cityInput').value.trim();
            const response = await fetch('php/tag_city.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ city, date, maxTemp, minTemp }),
            });

            if (response.ok) {
                alert('Ville étiquetée comme destination potentielle!');
            } else {
                alert('Échec du marquage de la ville. Veuillez réessayer.');
            }
        }
        
        
        document.getElementById('weatherForm').addEventListener('submit', async (event) => {
            event.preventDefault();
            const city = document.getElementById('cityInput').value.trim();
            const tableBody = document.querySelector('#weatherTable tbody');
            tableBody.innerHTML = '';
            const errorMessage = document.getElementById('errorMessage');

            if (city === '') {
                showError('Veuillez entrer le nom d\'une ville.');
                return;
            }

            const data = await fetchWeather(city);

            if (data) {
                const dailyTemps = getDailyTemps(data);
                let cityMatches = false;

                Object.keys(dailyTemps).forEach(date => {
                    const temps = dailyTemps[date];
                    const maxTempInRange = temps.max >= (maxTemp - tolerance) && temps.max <= (maxTemp + tolerance);
                    const minTempInRange = temps.min >= (minTemp - tolerance) && temps.min <= (minTemp + tolerance);

                    if (maxTempInRange && minTempInRange) {
                        cityMatches = true;
                        addRowToTable(date, temps.max, temps.min);
                    }
                });

                if (!cityMatches) {
                    showError(`La ville ${city} ne correspond pas aux attentes.`);
                } else {
                    errorMessage.textContent = '';
                }
            } else {
                showError(`Données indisponibles pour la ville: ${city}, pour les villes en 2 mots mettre un tiret du 6`);
            }
        });
        const input = document.getElementById('cityInput');
        const elementToHide = document.getElementById('suggestions');

        input.addEventListener('blur', () => {
            elementToHide.style.display = 'none';
        });

        input.addEventListener('focus', () => {
            elementToHide.style.display = 'block';
        });
    </script>
</body>
</html>
