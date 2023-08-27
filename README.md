
# E-Commerce Order Management System

This project encompasses an order management system for an e-commerce platform. It provides an API for customers to create product orders, apply campaigns, and view order details.

## Technologies

- Laravel Framework
- MySQL Database

## Installation

1. Clone the project: `git clone https://github.com/yunusemrgrl/case_laravel.git`
2. Navigate to the project folder: `cd case_laravel`
3. Install required dependencies: `composer install`
4. Create the `.env` file and configure your database connection: `cp .env.example .env`
5. Generate the application key: `php artisan key:generate`
6. Create the database tables: `php artisan migrate`
7. Populate the products table by running the Product seeder: `php artisan db:seed --class=ProductSeeder`

## Usage

- You can use Postman or other API testing tools to make API requests.
- To create an order, use the `POST {{base_endpoint}}/api/orders` endpoint. Example request body:
  ```json
  [
      {
          "product_id": 9,
          "quantity": 1
      },
      {
          "product_id": 3,
          "quantity": 2
      }
  ]
  ```
- To view order details, use the `GET {{base_endpoint}/orders/{orderNumber}` endpoint. Example: `GET {{base_endpoint}/orders/123`


