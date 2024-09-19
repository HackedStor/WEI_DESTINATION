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
3. Create a MySQL database named `wei`.
4. Import the database schema (you'll need to create this based on your code).
5. Update the database connection details in `auth/db_connect.php`.
6. Obtain an API key from OpenWeatherMap and replace `apiKey` in `index.php`.

## File Structure

- `index.php`: Main page with search functionality and weather display
- `public/dashboard.php`: User dashboard to view tagged destinations
- `php/tag_city.php`: Handles tagging cities as potential destinations
- `auth/`:
  - `connexion.php`: Handles user login
  - `register.php`: Handles user registration
  - `logout.php`: Handles user logout
  - `db_connect.php`: Database connection setup

## Usage

1. Register for an account or log in.
2. On the main page, search for a city.
3. View the weather forecast for the specified date range.
4. Tag cities that meet your temperature preferences.
5. View your tagged destinations in the dashboard.

## API Reference

This project uses the OpenWeatherMap API. You'll need to sign up for an API key at [OpenWeatherMap](https://openweathermap.org/api) and replace the `apiKey` variable in `index.php`.

## Contributing

Contributions, issues, and feature requests are welcome. Feel free to check [issues page](link-to-your-issues-page) if you want to contribute.

## License

[MIT](https://choosealicense.com/licenses/mit/)