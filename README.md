# National Health Database System

A full-stack web application built with **native PHP (OOP)** and **MySQL**, featuring role-based access control for Admins, Doctors, and Patients.

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Default Accounts](#default-accounts)
- [Roles & Permissions](#roles--permissions)
- [Database Schema](#database-schema)
- [Security](#security)

---

## Features

### Authentication
- Register as Doctor or Patient
- Login with email & password
- Sessions to track logged-in user and role
- Logout clears session completely

### Admin
- View all users in the system
- Add new Doctors or Patients
- Delete any user (cannot delete own account)
- Dashboard with total counts (users, doctors, patients, records)

### Doctor
- Dashboard showing patient count and recent records
- Add medical records linked to specific patients
- Update diagnosis and notes on existing records
- Add prescriptions to any medical record
- Search patients by name or email

### Patient
- View only their own medical records (never another patient's)
- Timeline view of records sorted by visit date
- View prescriptions attached to each visit
- Update personal profile (name, email, password)

---

## Tech Stack

| Layer | Technology |
|------------|-----------------------------------|
| Backend | PHP 8.x (Native, OOP) |
| Database | MySQL 5.7+ / MariaDB |
| Frontend | HTML5, CSS3 (custom design) |
| Auth | PHP Sessions + `password_hash()` |
| DB Access | PDO with Prepared Statements |

---

## Project Structure

```
health_system/
config/
database.php # DB credentials
init.sql # Database & tables setup

classes/
Database.php # Singleton PDO connection
Model.php # Abstract base model (CRUD)
User.php # Register, login, search
Admin.php # Extends User — admin actions
Doctor.php # Extends User — doctor stats
MedicalRecord.php # Records CRUD + ownership checks
Prescription.php # Prescriptions CRUD
Auth.php # Session management + role middleware

includes/
autoload.php # PSR-style class autoloader
header.php # Sidebar + navigation
footer.php # Closing HTML + JS

pages/
admin/
dashboard.php # Stats overview
users.php # View & delete users
add_user.php # Create doctor/patient

doctor/
dashboard.php # Stats + recent records
patients.php # Search patients
records.php # All doctor records
add_record.php # New medical record
edit_record.php # Update diagnosis
view_record.php # Record detail + add prescription

patient/
dashboard.php # Timeline view
records.php # All patient records
view_record.php # Record + prescriptions
profile.php # Update personal info

assets/
css/
style.css # Full UI design system

index.php # Login page
register.php # Registration page
```

---

## Installation

### Requirements
- PHP 8.0 or higher
- MySQL 5.7+ or MariaDB
- Apache or Nginx (XAMPP / WAMP / Laragon recommended)

### Steps

**1. Clone or extract the project**
```bash
# Place the folder inside your web server root
# e.g. C:/xampp/htdocs/health_system
```

**2. Create the database**

Open **phpMyAdmin** or any MySQL client and run:
```sql
SOURCE /path/to/health_system/config/init.sql;
```

Or paste the contents of `init.sql` directly into phpMyAdmin's SQL tab.

**3. Configure database credentials**

Open `config/database.php` and update:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'health_system');
define('DB_USER', 'root'); // your MySQL username
define('DB_PASS', ''); // your MySQL password
```

**4. Start the server and open the app**
```
http://localhost/health_system/
```

---

## Default Accounts

| Role | Email | Password |
|---------|---------------------|------------|
| Admin | admin@health.com | password |

> You can register new Doctor or Patient accounts from the Register page, or add them through the Admin panel.

---

## Roles & Permissions

| Action | Admin | Doctor | Patient |
|-------------------------------|:-----:|:------:|:-------:|
| View all users | | | |
| Add / Delete users | | | |
| Add medical records | | | |
| Edit diagnosis | | | |
| Add prescriptions | | | |
| Search patients | | | |
| View own records | | | |
| View own prescriptions | | | |
| Update own profile | | | |

---

## Database Schema

### `users`
| Column | Type | Notes |
|--------------|-----------------------------------|------------------|
| id | INT AUTO_INCREMENT PK | |
| name | VARCHAR(100) | |
| email | VARCHAR(150) UNIQUE | |
| password | VARCHAR(255) | Hashed |
| role | ENUM('admin','doctor','patient') | |
| phone | VARCHAR(20) | Optional |
| created_at | TIMESTAMP | Auto |

### `medical_records`
| Column | Type | Notes |
|--------------|---------------|--------------------------|
| id | INT PK | |
| patient_id | INT FK | → users.id CASCADE |
| doctor_id | INT FK | → users.id CASCADE |
| diagnosis | TEXT | |
| notes | TEXT | Optional |
| visit_date | DATE | |
| created_at | TIMESTAMP | Auto |

### `prescriptions`
| Column | Type | Notes |
|-----------------|---------------|------------------------------|
| id | INT PK | |
| record_id | INT FK | → medical_records.id CASCADE |
| medication_name | VARCHAR(150) | |
| dosage | VARCHAR(100) | |
| instructions | TEXT | Optional |
| prescribed_at | TIMESTAMP | Auto |

---

## Security

- All passwords stored using `password_hash()` and verified with `password_verify()`
- Every database query uses **PDO Prepared Statements** — no raw SQL concatenation
- All user output escaped with `htmlspecialchars()` to prevent XSS
- Every restricted page checks session and redirects unauthorized users
- Patients can only access their own records — enforced at the query level
- Doctors can only edit records they created
- Admin cannot delete their own account

---

## Pages Overview

| Page | Description |
|------|-------------|
| `/index.php` | Login |
| `/register.php` | Register as Doctor or Patient |
| `/pages/admin/dashboard.php` | Admin stats overview |
| `/pages/admin/users.php` | Manage all users |
| `/pages/doctor/dashboard.php` | Doctor home with recent records |
| `/pages/doctor/patients.php` | Search patients |
| `/pages/doctor/add_record.php` | Create new medical record |
| `/pages/doctor/view_record.php` | View record + add prescriptions |
| `/pages/patient/dashboard.php` | Patient timeline |
| `/pages/patient/records.php` | Full records list |
| `/pages/patient/profile.php` | Update profile |

---

## OOP Architecture

```
Model (abstract)
User
Admin
Doctor

MedicalRecord extends Model
Prescription extends Model
Auth (static session/role middleware)
Database (Singleton PDO)
```

---

> Built with using native PHP — no frameworks required.

---

**Developed by:** 
- Ahmed Saeed
- Ibrahem Saeed
