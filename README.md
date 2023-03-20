# Module 4 Project - REST API
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
###### You can access the API documentation for Swagger PHP library at http://localhost:8999/api-docs. The documentation contains information about the endpoints that are available, the parameters you can use, and the responses you can expect.

## Database Tables:

###### Posts
###### Categories
###### Posts Categories

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

#### Fields in Posts Categories Table:

* post_id (foreign key post identifier)
* category_id (foreign key category identifier)





