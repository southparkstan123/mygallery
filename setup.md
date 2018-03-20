# MyGallery

## Pre-work
Setup the PHP development environment by [Laravel Homestead](https://laravel.com/docs/5.4/homestead) (Optional)

## Setup Server Side Configuration
1. Clone the repository from Github
2. Run 'composer install' to install the package for server-side code
3. Create a database
4. Clone the '.env.example' file and add the database configurations to this file, save the file as '.env'
5. Run 'php artisan migrate' to do migrate
6. Run 'php artisan serve' to start the app on http://localhost:8000/

## Setup Client Side(Vue) Configuration
Following step to startup the client-side of mygallery :
2. Run 'npm install' to install the package for client-side code
3. Run 'npm run dev' to build the app on development environment

## Features
* Home Page
* Custom Error Page 404
* Authentication (registration, login, logout)
* Gallery with images
* Admin dashboard with Weather Widget
* Upload/Fetch/Delete images to Cloudinary
* Update images
* Create/Read/Update/Delete with gallery
* Filter Published/Unpublished gallery