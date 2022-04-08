# tryApi-GET-POST

# 📦 Dependencies
	> PHP/XAMPP (https://www.apachefriends.org/index.html)
	> Composer (https://getcomposer.org/download/)
	> NPM (https://nodejs.org/en/download/)
	> Postman

# 🏗️ Deployment Instructions
## Cloning Repo
	> Clone Repository and go to its directory in CMD
	> Run composer install
	> Run npm install
	> Run npm run dev
	> Run ren .env.example .env
		> Open .env file then edit DB_DATABASE field to DB_DATABASE=ojt-api
	> Run php artisan key:generate 
	> Run php artisan storage:link 

## Setting Up Database
	> Run XAMPP
	> Start Apache and MySQL,
	> Go to localhost/phpmyadmin on your browser
	> Create a database named ojt-api
	> Go to CMD and run php artisan migrate:fresh

## Starting System and testing API
	> Run php artisan serve
	> Go to the link provided after the serve command
	> Create a User by clicking Register
	> In Postman, Create new HTTP Requests:
		> To test GET:
			> Method: GET
			> Endpoint: http://127.0.0.1:8000/api/userDetails/1
		> To test POST
			> Method: POST
			> Endpoint: http://127.0.0.1:8000/api/login
			> Headers
				> Key: Accept | Value: application/json
			> Body - form-data: 
				> Key: email | Value: {your created account's email}
				> Key: password | Value: {your created account's password}