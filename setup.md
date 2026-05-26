# WPoets Full Stack Test

## Project summary
This repository contains a small PHP frontend and admin application built to demonstrate:
- a PHP + MySQL connection using PDO
- CRUD operations for managing slide content
- a public UI with topic tabs, slide content, and a synced image panel
- a simple admin panel for creating, updating, and deleting slides
- `.env` configuration support for database credentials

## What is included
- `index.php` — public slider page showing a topic tab list, slide cards, and a connected image
- `admin.php` — slide management page with create, edit, and delete operations
- `src/Database.php` — database connection class using PDO
- `src/SlideRepository.php` — repository layer for slide CRUD operations
- `config.php` — environment loader and database configuration
- `.env.example` — example database environment values
- `.env` — local environment values for this project
- `.gitignore` — excludes `.env` from version control
- `setup.sql` — MySQL schema and seeded slide data
- `assets/` — frontend CSS and JavaScript used by `index.php` and `admin.php`
- `Answers to technical questions.md` — technical answers for the coding test

## Requirements
- PHP 8 or newer with PDO enabled
- MySQL server
- A local browser

## Setup instructions
1. Copy `.env.example` to `.env` if you want custom local values.
   - The repository already includes `.env` for convenience, but do not commit your own credentials.
2. Create the database and initial table data.
   - If you have direct MySQL access, run:
     ```bash
     mysql -u root -p < setup.sql
     ```
   - If you are using Apache with phpMyAdmin or another GUI, import `setup.sql` there instead.
3. Confirm `.env` matches your MySQL settings:
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=wpoets_test
   DB_USERNAME=root
   DB_PASSWORD=
   ```

## Running the project
### Option 1: Apache
1. Place the project inside your Apache web root, for example:
   - macOS: `/Library/WebServer/Documents/full-stack-test`
   - MAMP: `/Applications/MAMP/htdocs/full-stack-test`
2. Make sure Apache is running and PHP is enabled.
3. Open the project in your browser at the correct Apache URL, for example:
   - `http://localhost/full-stack-test/index.php`
   - `http://localhost/full-stack-test/admin.php`

### Option 2: Built-in PHP server
From the repository root, run:
```bash
php -S localhost:8000
```
Then open:
- `http://localhost:8000/index.php`
- `http://localhost:8000/admin.php`

### How `.env` is used
- `config.php` reads `.env` and loads the database credentials into the app
- `src/Database.php` uses those values to create the PDO connection
- If Apache is configured and PHP works there, this project uses the same MySQL server as long as `.env` is correct

### Note on `php artisan serve`
- `php artisan serve` is a Laravel command
- It is not required for this project
- For this PHP app, use Apache or PHP's built-in server instead

## Why the MySQL setup is included
The MySQL setup is included so the app has a real backend data source:
- it shows how PHP connects to MySQL using PDO
- it supports persistent slide content instead of hard-coded data
- it demonstrates a basic repository pattern for database access

This is not just UI work: it shows the app can read and write slide records from a database.

## How to verify MySQL connection
1. Start the PHP server.
2. Open `index.php` in the browser.
3. If the slider loads data and displays slides, the MySQL connection is working.

Additional verification:
- Open `admin.php` and confirm existing slides appear.
- Create or edit a slide and save it.
- If the change persists and appears in the public view, the database connection is working.

If you see an error like `Table 'wpoets_test.slides' doesn't exist`, run the database initialization script again:
```bash
mysql -u root -p < setup.sql
```
If the database still does not exist, create it first or ensure `.env` contains the correct `DB_DATABASE` value.

If the database cannot connect, the app will fail when creating the PDO connection inside `src/Database.php`.

## Notes
- `.env` is excluded by `.gitignore` so local credentials are not pushed
- `config.php` reads `.env` and uses those values in `src/Database.php`
- `setup.sql` is included to make it easy to initialize the database for this test

## Which files to read
- `README.md` contains setup, run instructions, and project details
- `Answers to technical questions.md` contains the coding test answers

## Technical questions
Answer these in `Answers to technical questions.md`:
- How long did you spend on the coding test? What would you add if you had more time?
- How would you track down a performance issue in production?
- Please describe yourself using JSON.

