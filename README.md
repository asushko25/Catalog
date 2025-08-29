# Catalog (React + Symfony + SQLite)

A simple product catalog application with **React + Vite** frontend and **Symfony (PHP) + SQLite** backend.

---

## 🚀 Quick Start

```bash
# 1. Clone repo
git clone https://github.com/<your-username>/Catalog.git
cd Catalog

# 2. Backend setup (Symfony + SQLite)
cd backend
composer install
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate -n
php -S 127.0.0.1:8000 -t public
# Backend runs on → http://127.0.0.1:8000

# 3. Frontend setup (React + Vite)
cd ../frontend
npm install
npm run dev

# Frontend runs on → http://localhost:3000
