-- Create database
CREATE DATABASE IF NOT EXISTS greenhive;

USE greenhive;

-- Create users table
CREATE TABLE
    IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        phone_number VARCHAR(15),
        age INT,
        gender ENUM ('Male', 'Female', 'Other'),
        role ENUM ('Customer', 'Farmer', 'Admin') DEFAULT 'Customer',
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

-- Create products table
CREATE TABLE
    IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        farmer_id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        category ENUM ('Vegetables', 'Fruits', 'Grains', 'Leafy Veg') NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        unit ENUM ('kg', 'piece') NOT NULL,
        total_stock INT NOT NULL,
        image_url VARCHAR(500) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (farmer_id) REFERENCES users (id) ON DELETE CASCADE
    );

-- Create orders table
CREATE TABLE
    IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        total_price DECIMAL(10, 2) NOT NULL,
        status ENUM ('Pending', 'Confirmed', 'Shipped', 'Delivered') DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
    );

-- Create meal_plans table
CREATE TABLE
    IF NOT EXISTS meal_plans (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category ENUM (
            'BP',
            'Diabetes',
            'Thyroid',
            'Weight Gain',
            'Weight Loss'
        ) NOT NULL,
        menu TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

-- Create subscriptions table
CREATE TABLE
    IF NOT EXISTS subscriptions (
        id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each subscription
        user_id INT NOT NULL, -- ID of the user subscribing
        height INT NOT NULL, -- Height of the user (in cm)
        weight INT NOT NULL, -- Weight of the user (in kg)
        age INT NOT NULL, -- Age of the user
        health_goals TEXT NOT NULL, -- Comma-separated list of health goals (e.g., High BP, Diabetes)
        plan ENUM ('Weekly', 'Monthly') NOT NULL, -- Subscription plan (Weekly or Monthly)
        start_date DATE NOT NULL, -- Start date of the subscription
        end_date DATE NOT NULL, -- End date of the subscription
        notes TEXT, -- Additional notes from the user
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp when the subscription was created
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE -- Link to the users table
    );

-- Create cart table
CREATE TABLE
    IF NOT EXISTS cart (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
    );

--create weekly menu
CREATE TABLE
    IF NOT EXISTS weekly_menu (
        id INT AUTO_INCREMENT PRIMARY KEY,
        day ENUM (
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday'
        ) NOT NULL,
        meal_type ENUM ('Morning', 'Afternoon', 'Night') NOT NULL,
        category ENUM (
            'BP',
            'Diabetes',
            'Thyroid',
            'Weight Gain',
            'Weight Loss'
        ) NOT NULL,
        menu TEXT NOT NULL,
        imgurl VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );