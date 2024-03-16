<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Health Care Professional API

Welcome to the Health Care Professional API project! This repository contains a RESTful API built using PHP and the Laravel framework. The API is designed to manage healthcare appointments, allowing users to book, view, and cancel appointments with healthcare professionals.

## Project Setup

To set up the project, follow the steps below:

1. Clone the Repository: Clone this repository to your local machine:

```bash
git clone https://github.com/aakankshakirar/health_care_api.git
```


2. Install Dependencies: Navigate to the project directory and install PHP dependencies using Composer:
```bash
composer install
```

3. Database Setup: Configure the database connection in the .env file and create a MySQL database.

4. Run Migrations and Seeders: Run database migrations and seeders to set up the database schema and populate initial data:

```bash
php artisan migrate
```

```bash
php artisan db:seed --class=UserSeeder
```

```bash
php artisan db:seed --class=HealthcareProfessionalSeeder
```

5. Install Laravel Passport: Install Laravel Passport for API authentication:

```bash
php artisan passport:install --no-interaction
```

6. To create a personal access client, you can use the following command:

Notice: If you do not create it, you will get the error that "Personal access client not found. Please create one."

```bash
php artisan passport:client --personal

```

7. Run Tests: Execute unit tests to ensure the correctness of the application:

Notice: During unit testing, the RefreshDatabase trait will clear the database, potentially requiring you to reseed it afterwards.

```bash
php artisan test --testsuite=Unit
```


## Usage

- **POST /api/register: Register a new user.**
- **POST /api/login: Log in with user credentials and obtain an access token**
- **GET /api/professionals: Retrieve a list of all available healthcare professionals.**
- **POST /api/book-appointment: Book an appointment with a healthcare professional.**
- **GET /api/user/appointments: View all appointments for a user.**
- **DELETE /api/cancel-appointment/{appointmentId}: Cancel an appointment by its ID.**

## Testing

Book an Appointment Test:

```bash
php artisan test --filter testBookAppointment
```

Get User Appointments Test:

```bash
php artisan test --filter testGetUserAppointments
```

Cancel Appointment Test:

```bash
php artisan test --filter testCancelAppointment
```


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
