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
