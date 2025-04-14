# Project: AccessCare Connect - Medical Appointment System

## Project Developers:

**MANZI BAHIZI Bertin**
- Branch: group-7-22RP08209-medical-appointment-system
- Contributions: Database design, API development, Appointment management system

**DUSINGIZIMANA Innocent**
- Branch: group-7-22RP00000-medical-appointment-system
- Contributions: UI/UX design, Responsive layouts, Dashboard implementation

A web application designed to facilitate medical appointments between patients with disabilities and healthcare providers, featuring an innovative date suggestion system.

## Project Overview
AccessCare Connect is a Laravel-based web application that enables:

- Schedule and manage medical appointments between patients and doctors
- Handle both virtual and in-person appointment types
- Manage appointment status and scheduling conflicts
- Facilitate alternative date suggestions
- Track appointment history and outcomes
- Generate appointment reports and insights

## Features ##
- **User Authentication:** Secure role-based login system (Patients/Doctors)
- **Appointment Management:** Schedule, approve, reject, and manage appointments
- **Date Suggestion System:** Alternative date proposal for scheduling conflicts
- **Meeting Type Management:** Support for both virtual and in-person appointments
- **Responsive Design:** Works seamlessly on desktop, tablet, and mobile devices
- **Status Tracking:** Complete appointment lifecycle management

## Technology Stack
- **Backend:** Laravel 10.x
- **Frontend:** Bootstrap 5, Blade Templates
- **Database:** MySQL
- **Authentication:** Laravel's built-in auth
- **Color Scheme:** 
  - Primary Black (#000000)
  - Accent Yellow (#DFFF00)
  - Navy Blue (#000080)
  - Primary Green (#008000)

## Installation Instructions

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js and NPM

### Setup Steps

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/accesscare-connect.git
cd accesscare-connect
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install JavaScript dependencies**
```bash
npm install
```

4. **Create environment file**
```bash
cp .env.example .env
```

5. **Generate application key**
```bash
php artisan key:generate
```

6. **Configure database**
Open `.env` file and update database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=accesscare_connect
DB_USERNAME=root
DB_PASSWORD=
```

7. **Run migrations**
```bash
php artisan migrate
```

8. **Compile assets**
```bash
npm run dev
```

9. **Start the development server**
```bash
php artisan serve
```

10. **Access the application**
Open your browser and navigate to http://localhost:8000

## Usage
1. Register a new account as either a patient or doctor
2. For Patients:
   - Browse available doctors
   - Request appointments
   - View appointment status
   - Accept/decline suggested dates
3. For Doctors:
   - Manage appointment requests
   - Suggest alternative dates
   - Update appointment status
   - Track patient history

## Project Structure
- **app/Http/Controllers:**
  - AppointmentController.php
  - Auth/LoginController.php
  - Auth/RegisterController.php
- **app/Models:**
  - User.php
  - Appointment.php
  - DoctorProfile.php
- **database/migrations:** Database schema definitions
- **resources/views:**
  - auth/: Authentication views
  - dashboard.blade.php: Main dashboard
  - layouts/: Layout templates
- **routes:** Application routes

## Developers
This project was developed by:

**MANZI BAHIZI Bertin**
- Branch: group-7-22RP08209-medical-appointment-system
- Contributions: 
  - Database architecture
  - API development
  - Appointment management system
  - Date suggestion functionality

**DUSINGIZIMANA Innocent**
- Branch: group-7-22RP00000-medical-appointment-system
- Contributions:
  - UI/UX design
  - Responsive layouts
  - Dashboard implementation
  - User interface optimization

## License
This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments
- Laravel Framework
- Bootstrap 5
- All contributors and testers
