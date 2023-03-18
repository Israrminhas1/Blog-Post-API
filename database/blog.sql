CREATE DATABASE IF NOT EXISTS blog;

USE blog;

CREATE TABLE IF NOT EXISTS posts (
    id VARCHAR(100) NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    content TEXT,
    thumbnail VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    posted_at DATETIME,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS categories (
    id VARCHAR(100) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS posts_categories (
    post_id VARCHAR(100) NOT NULL,
    category_id VARCHAR(100) NOT NULL,
    PRIMARY KEY (post_id, category_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
