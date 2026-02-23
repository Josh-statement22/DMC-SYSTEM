# DMC-ERP

DMC ERP is a basic enterprise web application built with Laravel 12, TailwindCSS, and Livewire. This setup currently includes a login page and a super admin dashboard with dummy authentication for local testing.

---

Getting Started

These instructions will help you set up the project on your local machine.

Prerequisites

Make sure you have these installed:

- PHP 8.2+
- Composer
- Node.js & npm
- XAMPP or other local server
- Git

---

1. Clone the Repository

git clone <your-repo-url>
cd DMC-ERP

---

2. Install Dependencies

composer install
npm install

---

3. Set Up Environment

cp .env.example .env
php artisan key:generate

This will create the Laravel app key for encryption.

---

4. TailwindCSS Build

To generate CSS with Tailwind:

npx tailwindcss -i resources/css/app.css -o public/css/app.css --watch

Keep this running while developing to auto-compile CSS.

---

5. Run the Laravel Server

php artisan serve

Visit: http://127.0.0.1:8000/login

---

6. Test Dummy Login

- Email: admin@dmc.com
- Password: password123
- You will be redirected to the super admin dashboard (admin-dashboard.blade.php).

- To logout, click Logout, it clears the session and returns to the login page.

---

7. Notes

- This is a dummy authentication setup, no database is required yet.
- TailwindCSS is already configured via CLI.
- Super admin page is ready for future Livewire components and database integration.

---

Future Improvements

- Connect authentication to a database.
- Add user roles and permissions.
- Extend dashboard with Livewire interactive components.
- Add proper form validation and error handling.

---

Tips

- Always keep Tailwind CLI running during development:

npx tailwindcss -i resources/css/app.css -o public/css/app.css --watch

- Use php artisan serve for local testing.

---

Author

Joshua Lacambra – College Student | Web Developer
