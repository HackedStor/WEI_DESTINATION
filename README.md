# Weather-Based Travel Destination Finder

## Description

This web application allows users to search for potential travel destinations based on weather forecasts. Users can register, log in, search for cities, view weather forecasts, and tag cities as potential destinations.

## Features

- User Registration and Authentication
- City Search with Autocomplete
- Weather Forecast Display
- Tagging Cities as Potential Destinations
- User Dashboard to View Tagged Destinations

## Technologies Used

- PHP
- MySQL
- HTML/CSS
- JavaScript
- OpenWeatherMap API

## Setup

1. Clone the repository to your local machine.
2. Set up a local web server (e.g., Apache) with PHP support.
3. Create a MySQL database named `travel-destinations-finder`.
4. Import the database schema (you'll find the sql file in the database folder which is in the `to_delete_after_setup_finished` folder) Be careful to use modify the sql file to use the correct database name and the import the sql file to PHPMyAdmin.
5. Update the database connection details in `auth/db_connect.php`.
6. Obtain an API key from OpenWeatherMap and replace `apiKey` in `index.php`.

## File Structure

- `index.php`: Main page with search functionality and weather display
- `public/`: User dashboard to view tagged destinations
  - `dashboard.php`:
- `php/`: Handles tagging cities as potential destinations
  - `tag_city.php`:
- `auth/`:
  - `connexion.php`: Handles user login
  - `register.php`: Handles user registration
  - `logout.php`: Handles user logout
  - `db_connect.php`: Database connection setup

## Usage

1. Register for an account and then log in.
2. On the main page, search for a city.
3. View the weather forecast for the specified date range.
4. Tag cities that meet your temperature preferences.
5. View your tagged destinations in the dashboard.

## API Reference

This project uses the OpenWeatherMap API. You'll need to sign up for an API key at [OpenWeatherMap](https://openweathermap.org/api) and replace the `apiKey` variable in `index.php`.

## Contributing

Contributions, issues, and feature requests are welcome.

## License

[MIT](https://choosealicense.com/licenses/mit/)