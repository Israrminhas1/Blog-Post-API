# Module 4 Project - REST API for Blog Posts
This project offers a REST API to handle requests for blog posts.The REST API offered here provides all the essential features needed for a blog post, such as creating, reading, updating, and deleting posts and post categories. The database table structure and API documentation provided make it simple to integrate the API with a front-end web application. The following documentation describes how to get started, how to use the API, and the structure of the database tables. 

## Getting started
To use this REST API, make sure you have PHP version 7.3 or higher, a supported database management system (e.g., MySQL), and Composer dependency manager installed. To get started:
- Clone repository: `git clone git@gitlab.com:israrminhas99/module-4-api-project.git`
- Create a new MySQL database and import the db.sql file to create the required tables: `php database/create-blog.php`
- Install the composer dependencies: `composer install`
- Configure the environment: `cp .env.example .env`
- Configure the settings to match your database configuration to `.env` file

## Usage

```
php -S localhost:8889 -t public
```
This will start a local development server at http://localhost:8889. You can use any REST API client, such as Postman or Insomnia, to interact with the API.

### The available route are:

#### Posts
* [POST] /v1/posts/create: ***Creates a new post.***
* [GET] /v1/posts: ***Returns a list of all posts.***
* [GET] /v1/posts/{id}: ***Returns a specific post by its id.***
* [GET] /v1/posts/{id}: ***Returns a specific post by its slug.***
* [PUT] /v1/posts/update/{id}: ***Updates an existing post.***
* [DELETE] /v1/posts/delete/{id}: ***Deletes an existing post.***

#### Categories
* [POST] /v1/categories/create: ***Creates a new category.***
* [GET] /v1/categories: ***Returns a list of all categories.***
* [GET] /v1/categories/{id}: ***Returns a specific category by its ID.***
* [PUT] /v1/categories/update/{id}: ***Updates an existing category.***
* [DELETE] /v1/categories/delete/{id}: ***Deletes an existing category.***

### You can access the API documentation for Swagger PHP library at http://localhost:8999/api-docs. The documentation contains information about the endpoints that are available, the parameters you can use, and the responses you can expect.

### Database Table Structure
The tables in the database contain the following fields:

#### Posts
* ***id:*** Unique post identifier.
* ***title:*** The title of the post.
* ***slug:*** The slug for the post URL.
* ***content:*** The content of the post.
* ***thumbnail:*** The URL of the thumbnail image for the post.
* ***author:*** The author of the post.
* ***posted_at:*** The date and time when the post was posted.

#### Categories
* ***id:*** Unique category identifier.
* ***name:*** The name of the category.
* ***description:*** The description of the category.

#### Posts Categories
* ***post_id:*** Foreign key post identifier.
* ***category_id:*** Foreign key category identifier.






