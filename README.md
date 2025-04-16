# Microfinance - Laravel Landing Page

## 🛠️ Setup Instructions

### Prerequisites
Make sure you have the following installed:
- PHP >= 8.x
- Composer
- MySQL or other supported database
- Node.js & NPM
- xampp

### 1. Clone the Repository
- Ensure that your terminal (CMD) directory is inside /xampp/htdocs/dashboard/Microfinance
- Download the repo
- If you haven't cloned the repo yet follow this steps.

# RUN THIS AT TERMINAL
```bash
    git clone https://github.com/Christian24GH/Microfinance.git
    cd /landing_page

    composer install
    npm install
    cp .env.example .env
    php artisan key:generate
```


# 2. OPEN .env file inside /landing_page and search for this, then copy and past this to the .env file
```bash 
    DB_CONNECTION=mariadb
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=microfinance
    DB_USERNAME=root
    DB_PASSWORD=
```
### 3. run database migration
```bash
    php artisan migrate
```
### 4. SERVE
Pick One and be sure you are inside /landing_page
```bash
    php artisan serve
    
    npm run dev

    npx vite dev
```


# FOLDER STRUCTURE
    xampp/dashboard/Microfinance/
    ├── landing_page/     <-- Laravel App
    ├── LogisticOne/          <-- Other plain php file
    ├── LogisticTwo/          <-- Paste your projects inside Microfinance/

