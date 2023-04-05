<p align="center">
  <img align="center" height="200" src="public/elephpant.png">
</p>

# BLOG - REST API
This project offers a REST API to handle requests for blog posts.The REST API offered here provides all the essential features needed for a blog post, such as creating, reading, updating, and deleting posts and post categories. The database table structure and API documentation provided make it simple to integrate the API with a front-end web application. The following documentation describes how to get started, how to use the API, and the structure of the database tables. 

## Installation
This app can run using the typical XAMPP configuration; ensure  you have PHP version 7.3 or higher.
1. Ensure you have XAMPP and Composer installed.
2. Configure the environment: 
   ````
   cp .env.example .env
   ````
3. Add your configuration to the .env file
4. Create the database `blog`.
5. Install the PHP dependencies.  
   ````
   composer install
   ````
6. Create the tables. 
   ```
   php vendor/bin/doctrine orm:schema-tool:create 
   ````
7. Run the local web server.
   ```
   php -S localhost:8889 -t public/

## Quality Tools

- Run the unit tests with PHPUnit
  ```
  php vendor/bin/phpunit test/ --colors
  ```
- Run the static analysis with PHPStan
  ```
  php vendor/bin/phpstan
  ```
- Check the code style with PHPCodeSniffer
  ```
  php vendor/bin/phpcs vendor/bin/phpcs src/ --standard=psr12
  ```
- Fix the code style with PHPCodeSniffer
  ```
  php vendor/bin/phpcbf vendor/bin/phpcs src/ --standard=psr12

## Usage

```
php -S localhost:8889 -t public
```
This will start a local development server at http://localhost:8889. You can use any REST API client, such as Postman or Insomnia, to interact with the API.

Images are saved in Uploads folder in public.
### Project Routes:

#### Posts
* [POST] /v1/posts/create: ***Creates a new post.***
* [GET] /v1/posts: ***Returns a list of all posts.***
* [GET] /v1/posts/{id}: ***Returns a post by ID.***
* [GET] /v1/posts/{slug}: ***Returns  post by slug.***
* [PUT] /v1/posts/update/{id}: ***Updates post.***
* [DELETE] /v1/posts/delete/{id}: ***Deletes post.***

#### Categories
* [POST] /v1/categories/create: ***Creates a new category.***
* [GET] /v1/categories: ***Returns a list of all categories.***
* [GET] /v1/categories/{id}: ***Returns a  category by ID.***
* [PUT] /v1/categories/update/{id}: ***Updates category.***
* [DELETE] /v1/categories/delete/{id}: ***Deletes category.***

#### API Documentation
 * [GET] /api-docs
###### You can access the API documentation for Swagger PHP library at /api-docs. The documentation contains information about the endpoints that are available, the parameters you can use, and the responses you can expect.

## Database Tables:

###### Posts
###### Categories
###### Post_Category

#### Fields in Posts Table:

* id (unique post identifier)
* title (title of the post)
* slug (slug for the post)
* content (content of the post)
* thumbnail (thumbnail for the post)
* author (author of the post)
* posted_at (datetime when the post was created)

#### Fields in Categories Table:

* id (unique category identifier)
* name (name of the category)
* description (description of the category)

#### Fields in Post_Category Table:

* post_id (foreign key post identifier)
* category_id (foreign key category identifier)

## Design Patterns

The PHP design patterns used in this project are:

#### Dependency Injection (DI) pattern:
This pattern is used extensively throughout the code, where dependencies are injected into classes via constructor injection or setter injection.
#### Repository pattern:
This pattern is used to encapsulate the data access layer and provide a way for the application to interact with the database. The CategoryRepositoryFromDoctrine and PostRepositoryFromDoctrine classes implement this pattern.
#### MVC pattern:
This pattern  separates an application's logic into three interconnected components: Model, View, and Controller.
The Model represents the data and business logic, the View is responsible for presenting data to the user, and the Controller handles user input and manages the communication between the Model and the View.



