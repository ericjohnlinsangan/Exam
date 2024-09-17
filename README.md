# README

## Setup
- Copy `.env.example` to `.env`
- Setup your database and update `.env` accordingly
- Run the following commands 
  - `composer install`
  - `npm install`
  - `npm run build`
  - `php artisan key:generate`
  - `php artisan migration --seed`

## Default Admin User

- email: admin@example.com
- password: P@ssword!123

## Notes
- I also created test unit for UsersController only
- Pagination is by 5 
- You can run tests using `php artisan test`
