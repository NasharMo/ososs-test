# Laravel Project Setup

Follow the steps below to set up and run the Laravel project locally.

## 📦 Installation

1. **Clone the repository**

```bash
git clone https://github.com/your-username/your-project.git
cd your-project
```

2. **Install PHP dependencies using Composer**

```bash
composer install
```

3. **Install JavaScript dependencies using NPM**

```bash
npm install
```

4. **Build frontend assets**

```bash
npm run dev
```

## ⚙️ Environment Setup

1. **Copy `.env.example` to `.env`**

```bash
cp .env.example .env
```

2. **Generate application key**

```bash
php artisan key:generate
```

3. **Create a MySQL database**

Create a new database in your MySQL server (e.g., `laravel_db`).

4. **Update your `.env` file**

Open `.env` and configure the database connection section:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

## 🧬 Run Migrations and Seeders

```bash
php artisan migrate --seed
```

> This will create the tables and seed them with initial data if seeders are defined.

## 🚀 Start the Laravel Development Server

```bash
php artisan serve
```

Your project should now be running at: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## ✅ Optional: Run Tests

```bash
php artisan test
```

Make sure to configure `.env.testing` if you’re running automated tests.

---