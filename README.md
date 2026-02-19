<p align="center"><img src="public/assets/images/fluffy-blue.png" width="200" alt="Fluffy Logo"></p>

# Fluffy - Premium Pet Supplies Store

Fluffy is a modern e-commerce platform built with **Laravel 12**, designed for selling premium pet supplies. It features a complete shopping experience for customers and a comprehensive management dashboard for employees.

## 🚀 Technology Stack

- **Framework**: Laravel 12
- **Frontend**: Livewire 3 + Blade Components
- **Styling**: Tailwind CSS
- **Authentication**: Laravel Jetstream (Fortify + Sanctum)
- **Authorization**: Spatie Laravel Permission (RBAC)
- **Database**: MySQL
- **Payments**: Stripe API Integration
- **API**: RESTful API (v1) with Sanctum Token Authentication

## ✨ Key Features

### 🛍️ Client Side (Customers)
- **Product Catalog**: Browse products by category (Accessories, Food, Grooming and Toys) or animal (Cat/Dog/Hamster/Rabbit).
- **Filtering**: Filter by price, stock status, and category.
- **Shopping Cart**: Real-time cart management (Add/Remove/Update).
- **Checkout**: Integrated Stripe payment flow (Secure Card Processing).
- **Order History**: Track past orders and delivery status.
- **Favorites**: Wishlist functionality for authenticated users.

### 🏢 Admin Side (Employees)
- **Dashboard**: Overview of key metrics.
- **Product Management**: CRUD operations for products (Create, Edit, Delete).
- **Inventory Control**: Manage stock levels, prices, and specifications (variants).
- **Order Management**: View and process customer orders.
- **Role-Based Access**: Strict separation between Employee and Customer areas.

### 📱 API (Mobile Ready)
- **Sanctum Authentication**: Secure token-based auth for mobile apps.
- **Endpoints**: Full coverage for Products, Orders, and Auth.
- **Versioning**: `v1` prefix for future-proofing.
- **Security**: Rate limiting and Input validation enforced.

## 🛠️ Installation & Setup

1.  **Clone the repository**
    ```bash
    git clone https://github.com/yourusername/fluffy.git
    cd fluffy
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup**
    Copy the example env file and configure your database and Stripe credentials.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Update `.env` with your DB credentials (`DB_DATABASE`, `DB_USERNAME`, etc.) and Stripe keys (`STRIPE_KEY`, `STRIPE_SECRET`).*

4.  **Database Migration & Seeding**
    Run migrations and seed the database with default roles, permissions, and products.
    ```bash
    php artisan migrate --seed
    ```
    *This will create the `customer` and `employee` roles and a default employee user.*

5.  **Run the Application**
    Start the Vite dev server and the PHP server.
    ```bash
    npm run dev
    # In a separate terminal
    php artisan serve
    ```

## 👤 Default Accounts

The seeder creates the following default accounts for testing:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Employee** | `employee@fluffy.com` | Employee@12345
| **Customer** | `customer@fluffy.com` | Customer@12345

## 🔒 Security Measures

- **CSRF Protection**: Enabled on all web forms.
- **XSS Prevention**: Automatic escaping in Blade templates.
- **SQL Injection**: Uses Eloquent ORM / Parameterized queries.
- **HTTPS**: Enforced in Production environment.
- **Secure Cookies**: HttpOnly and Secure flags enabled for sessions.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
