# Timesheet Module Documentation

## Overview
This module provides robust, real-time employee timesheet management and approval for the HR system. It includes:
- Employee CRUD
- Time Entry CRUD (per employee)
- Timesheet Approval workflow
- Real-time updates via Laravel Echo
- Validation, feedback, and security

---

## API Endpoints

### Employee
- `GET /api/employees` — List/search employees
- `POST /api/employees` — Create employee
- `GET /api/employees/{id}` — View employee
- `PUT /api/employees/{id}` — Update employee
- `DELETE /api/employees/{id}` — Delete employee
- `GET /api/employees/{id}/time-entries` — List time entries for employee

### Time Entry (per employee)
- `POST /api/employees/{id}/time-entries` — Add time entry
- `PUT /api/employees/{id}/time-entries/{entryId}` — Update time entry
- `DELETE /api/employees/{id}/time-entries/{entryId}` — Delete time entry

### Timesheet Approval
- `GET /api/timesheets/pending` — List pending timesheets
- `GET /api/timesheets/{id}` — View timesheet details
- `POST /api/timesheets/{id}/approve` — Approve timesheet
- `POST /api/timesheets/{id}/reject` — Reject timesheet (body: `{ reason: string }`)
- `GET /api/timesheets/stats` — Approval dashboard stats

---

## Real-Time Events & Channels
- `private-employees` — Employee CRUD events
- `private-timesheet.{employeeId}` — Time entry CRUD events
- `private-timesheet.approvals` — Timesheet approval events

Events:
- `EmployeeCreated`, `EmployeeUpdated`, `EmployeeDeleted`
- `TimeEntryCreated`, `TimeEntryUpdated`, `TimeEntryDeleted`
- `TimesheetStatusUpdated`

---

## Testing Instructions

### Manual Testing
1. **Employee Management**
   - Add, edit, delete employees. See real-time updates in all browser tabs.
2. **Time Entry Management**
   - Add, edit, delete time entries for any employee. See real-time updates.
3. **Timesheet Approval**
   - Go to Timesheet Approval page. See pending timesheets.
   - View, approve, or reject a timesheet. See real-time updates and feedback.
   - Stats update in real-time.
4. **Validation & Feedback**
   - Try invalid data (e.g., missing required fields) and see error feedback.
   - All actions show toast notifications.
5. **Security**
   - All API endpoints are protected by authentication.

### Automated Testing
- Run backend feature tests:
  ```bash
  php artisan test --filter=TimesheetApprovalTest
  ```
- (Optional) Add browser tests with Laravel Dusk for real-time UI.

---

## Developer Notes
- All migrations, models, and seeders are up to date for the approval workflow.
- Real-time events use Laravel Echo and Pusher (see `.env` for credentials).
- For new features, follow the same event-driven and validation-first approach.
- For frontend, see `resources/views/testapp/timesheet/employee.blade.php` and `approval.blade.php`.

---

## Production Readiness & Deployment Checklist

1. **.env and Configuration**
   - Set `APP_ENV=production` and `APP_DEBUG=false` in your `.env`.
   - Ensure correct Pusher, database, and mail credentials.
   - Set `BROADCAST_DRIVER=pusher` and correct Pusher keys.

2. **Queue Worker for Broadcasting**
   - Run a queue worker for event broadcasting:
     ```bash
     php artisan queue:work
     ```

3. **Cache and Optimize**
   - Run:
     ```bash
     php artisan config:cache
     php artisan route:cache
     php artisan view:cache
     ```

4. **Security**
   - Enable HTTPS in production.
   - Review user roles and permissions.
   - Ensure all sensitive endpoints are protected by authentication.

5. **Backups and Monitoring**
   - Set up regular database backups.
   - Set up error monitoring (e.g., Sentry, Bugsnag).

6. **Final Documentation**
   - Add deployment and environment setup instructions to your main project README if needed.

---

## Monitoring & Error Alerting

### Error Monitoring (Sentry Example)
1. Sign up at [Sentry.io](https://sentry.io/) and create a new Laravel project.
2. Install the Sentry Laravel SDK:
   ```bash
   composer require sentry/sentry-laravel
   ```
3. Add your Sentry DSN to your `.env`:
   ```
   SENTRY_LARAVEL_DSN=your_sentry_dsn_here
   ```
4. (Optional) Publish the config:
   ```bash
   php artisan vendor:publish --tag=sentry-config
   ```
5. Deploy. All exceptions will now be reported to Sentry.

### Queue Monitoring (Laravel Horizon)
1. Install Horizon:
   ```bash
   composer require laravel/horizon
   php artisan horizon:install
   ```
2. Run migrations:
   ```bash
   php artisan migrate
   ```
3. Start Horizon:
   ```bash
   php artisan horizon
   ```
4. Visit `/horizon` in your app to monitor queues.

### Uptime Monitoring
- Use a service like [UptimeRobot](https://uptimerobot.com/) or [Better Uptime](https://betteruptime.com/) to monitor your app's public URL and get alerts for downtime.

---

## Security & Maintenance

### Route Protection
- Ensure all sensitive routes use `auth` middleware.
- For timesheet approval/rejection, use a custom middleware or policy:
  ```php
  Route::middleware(['auth', 'can:approve-timesheets'])->group(function () {
      Route::post('/api/timesheets/{id}/approve', [TimesheetController::class, 'approveTimesheet']);
      Route::post('/api/timesheets/{id}/reject', [TimesheetController::class, 'rejectTimesheet']);
  });
  ```

### Role-Based Access Control (Policy Example)
- Generate a policy:
  ```bash
  php artisan make:policy TimesheetPolicy
  ```
- In `TimesheetPolicy.php`:
  ```php
  public function approve(User $user)
  {
      return $user->role === 'admin' || $user->role === 'hr';
  }
  ```
- Register in `AuthServiceProvider` and use `can:approve,App\Models\Timesheet` in your routes or controllers.

### Rate Limiting
- Protect sensitive endpoints from abuse:
  ```php
  Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
      // your routes here
  });
  ```

### Regular Maintenance
- Update dependencies regularly (`composer update`).
- Review logs and error reports weekly.
- Back up your database and .env files regularly.

### Feedback Loop
- Provide a way for users to submit feedback or bug reports (e.g., form or email link).
- Review feedback monthly and plan improvements.

---

**For questions or improvements, see the code comments or contact the original developer.** 
