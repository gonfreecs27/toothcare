# ToothCare Dental Clinic Management System

## Overview

ToothCare is a web-based Dental Clinic Management System designed to streamline clinic operations, appointment scheduling, patient management, billing, and dental service records.

The system provides an intuitive interface for clinic staff, dentists, and administrators to efficiently manage daily dental clinic activities.

---

## Features

### Patient Management

* Patient registration and profile management
* Medical and dental history tracking
* Patient search and records viewing

### Appointment Management

* Appointment scheduling
* Calendar-based appointment monitoring
* Appointment rescheduling and cancellation
* Dentist assignment

### Dentist Management

* Dentist profile management
* Schedule management
* Appointment allocation

### Service Management

* Dental service catalog
* Service pricing configuration
* Treatment tracking

### Payment Management

* Payment recording
* Transaction history
* Billing management

### Feedback Management

* Patient feedback submission
* Feedback monitoring and reporting

### User Management

* User authentication
* Role-based access control
* Account management

---

## Technology Stack

### Backend

* PHP 8+
* MySQL / MariaDB

### Frontend

* HTML5
* CSS3
* Bootstrap 3
* JavaScript
* jQuery
* FullCalendar

### Architecture

* MVC (Model-View-Controller)
* REST-like API Endpoints

---

## Project Structure

```text
toothcare/
│
├── api/
│   ├── appointments/
│   ├── dentists/
│   ├── feedbacks/
│   ├── patients/
│   ├── services/
│   └── users/
│
├── app/
│   ├── Controllers/
│   ├── Core/
│   ├── Middleware/
│   ├── Routes/
│   └── Views/
│
├── configs/
│   ├── app.php
│   ├── database.php
│   └── routes.php
│
├── models/
│   ├── Appointment.php
│   ├── Dentist.php
│   ├── Patient.php
│   ├── Payment.php
│   ├── Service.php
│   └── User.php
│
├── public/
│   ├── assets/
│   ├── css/
│   ├── js/
│   ├── uploads/
│   └── index.php
│
├── schema/
│   └── toothcare.sql
│
├── .htaccess
├── init.php
└── README.md
```

---

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/your-organization/toothcare.git
cd toothcare
```

### 2. Configure Database

Create a database:

```sql
CREATE DATABASE toothcare;
```

Import schema:

```bash
mysql -u root -p toothcare < schema/toothcare.sql
```

### 3. Configure Application

Edit database configuration:

```php
configs/database.php
```

Example:

```php
return [
    'host' => 'localhost',
    'database' => 'toothcare',
    'username' => 'root',
    'password' => '',
];
```

### 4. Configure Web Server

Ensure Apache mod_rewrite is enabled.

Example Apache Virtual Host:

```apache
DocumentRoot /path/to/toothcare/public

<Directory /path/to/toothcare/public>
    AllowOverride All
    Require all granted
</Directory>
```

### 5. Access Application

```text
http://localhost/toothcare
```

---

## API Endpoints

### Appointments

| Method | Endpoint                 |
| ------ | ------------------------ |
| GET    | /api/appointments/list   |
| POST   | /api/appointments/create |
| POST   | /api/appointments/update |
| POST   | /api/appointments/delete |

### Patients

| Method | Endpoint             |
| ------ | -------------------- |
| GET    | /api/patients/list   |
| POST   | /api/patients/create |
| POST   | /api/patients/update |
| POST   | /api/patients/delete |

### Dentists

| Method | Endpoint             |
| ------ | -------------------- |
| GET    | /api/dentists/list   |
| POST   | /api/dentists/create |
| POST   | /api/dentists/update |
| POST   | /api/dentists/delete |

### Services

| Method | Endpoint             |
| ------ | -------------------- |
| GET    | /api/services/list   |
| POST   | /api/services/create |
| POST   | /api/services/update |
| POST   | /api/services/delete |

### Payments

| Method | Endpoint             |
| ------ | -------------------- |
| GET    | /api/payments/list   |
| POST   | /api/payments/create |

---

## User Roles

### Administrator

* Manage users
* Manage dentists
* Manage services
* Manage appointments
* Access reports

### Staff

* Manage appointments
* Manage patients
* Process payments

### Dentist

* View appointments
* Manage patient treatments
* Update treatment records

---

## Security

* Session-based authentication
* Role-based authorization
* CSRF protection
* Input validation and sanitization
* Prepared SQL statements
* Secure password hashing

---

## Development Guidelines

### Naming Conventions

#### Controllers

```php
AppointmentController.php
PatientController.php
DentistController.php
```

#### Models

```php
Appointment.php
Patient.php
Dentist.php
```

#### API Endpoints

```text
/api/appointments/list
/api/appointments/create
/api/patients/list
/api/services/list
```

---

## Future Enhancements

* SMS notifications
* Email notifications
* Online appointment booking
* Electronic prescriptions
* Dental charting
* Inventory management
* Reports and analytics dashboard

---

## License

This project is intended for academic and internal clinic management purposes.

---

## Author

Developed by the ToothCare Development Team.
