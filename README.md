# Posty

Posty is a social media application built with Laravel.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Configuration](#configuration)
- [Testing](#testing)
- [Contributing](#contributing)

## Features

- **Authentication**: Register, login, and logout.
- **Posts**: Create, update, delete, and post on posts.
- **Follow system**: Follow others, and others can follow you.
- **Profile management**: update your name, username, email, and avatar.

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/o63id3/Posty.git
   cd Posty
   
2. **Install dependencies:**
    ```bash
    composer install
    npm install

3. **Environment setup:**
    - Copy the .env.example to .env:
       ```bash
        cp .env.example .env
   - Generate the application key:
      ```bash
       php artisan key:generate

4. **Run migrations:**
    ```bash
   php artisan migrate --seed
   
5. **Compile assets:**
   ```bash
   npm run dev

6. **Start the development server:**
    ```bash
    php artisan serve

## Usage

After installing, you can access the application by navigating to http://localhost:8000 in your web browser. From there, you can create posts, like others posts, and follow others.

## Configuration

Ensure you have the correct environment variables set up in your .env file, including the database connection, application URL, and other relevant settings.

## Testing

Posty has a suite of tests to ensure the application's stability and functionality. To run the tests, use the following command:
```bash
./vendor/bin/pest --parallel
   ```
This will execute all the unit, http, and feature tests in the project. You can also run specific tests by specifying the test class or method name.

## Contributing

Contributions are welcome! Please fork this repository and submit a pull request for any feature or bug fix.

