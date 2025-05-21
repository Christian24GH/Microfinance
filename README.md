<<<<<<< HEAD
# Microfinance - Laravel Landing Page

## 🛠️ Setup Instructions

### Prerequisites
Make sure you have the following installed:
- Composer
- Node.js & NPM
- XAMPP

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
    npm run build
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

# SETUP Role Authorization and Redirects
- After placing your folder, copy paste the session.php and index.php logout element at the /testapp
- place the /testapp/session.php file at your project root folder.
- paste the element inside /testapp/index.php at your desired file.
    ```bash
        <form action="./session.php" method="POST">
            <button type="submit" name="logout">Logout</button> # feel free to modify the button BUT not the FORM action and method!
        </form>
    ```
- Open the landing_page/app/Http/Controllers/Login.php
# ROLES TO SELECT
    [   
        'HRSupervisor',
        'FinanceOfficer',
        'AuditOfficer',
        'MaintenanceStaff',
        'MaintenanceAdmin',
        'ProjectManager',
        'TeamMember',
        'AssetAdmin',
        'AssetStaff',
        'AssetAnalyst',
        'WarehouseManager',
        'InventoryStaff',
        'Supplier',
        'ProcurementAdministrator',
        'QualityInspector',
        'ProcurementAnalyst',
        'CommunicationOfficer',
        'PayrollOfficer',
        'ADMIN',
        'EMPLOYEE',
        'HRAdministrator',
        'Manager/Supervisor'
    ]
## FOR Plain PHP USERS
- add a case to this switch so that your specific role will be selected on redirect
    ```bash
        switch($user->role){
            case 'EMPLOYEE':
                return response()->redirectTo("http://localhost/dashboard/Microfinance/testapp/index.php?sid=$sessionKey");
                break;
            case 'YOUR_ROLE_HERE':
                return response()->redirectTo("YOUR_PROJECT_INDEX_FILE_ABSOLUTE_FILE_HERE/index.php?sid="$sessionKet);
                break;
            default:
                return back()->with(['fail' =>'Invalid Role']);
                break;
        }
    ```
## FOR Laravel 
## FOR Django
=======
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
>>>>>>> 84da9d8 (Initial commit for hr3 only)
