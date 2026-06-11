<h1 align="center"> System AI - Academic Information System</h1>

<p align="center">
  <strong>Modern Academic Information System built with Laravel 12</strong>
</p>

<p align="center">
  <a href="#features">Features</a> •
  <a href="#tech-stack">Tech Stack</a> •
  <a href="#installation">Installation</a> •
  <a href="#configuration">Configuration</a> •
  <a href="#usage">Usage</a> •
  <a href="#api-documentation">API</a> •
  <a href="#contributing">Contributing</a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-red?style=flat-square&logo=laravel" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php" alt="PHP Version">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-38bdf8?style=flat-square&logo=tailwindcss" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=flat-square&logo=alpine.js" alt="Alpine.js">
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License">
</p>

---

## Overview

**System** is a modern academic management application designed for universities and higher education institutions. Built with Laravel 12 using a clean, scalable, and production-ready architecture.

### Highlights

- **Modern UI** - Responsive design dengan TailwindCSS dan Alpine.js
- **AI-Powered** - Academic Advisor berbasis Gemini AI
- **Secure** - Role-based access control, rate limiting, dan security headers
- **Fast** - Optimized queries, caching strategy, dan database indexes
- **Responsive** - Mobile-friendly interface
- **Multi-language Support** - English interface with Indonesian backward compatibility

---

## Features

### Student

| Feature                 | Description                                          |
| ----------------------- | ---------------------------------------------------- |
| **Study Plan**          | Course registration with automatic credit validation |
| **Transcript**          | View complete academic transcript with GPA/CGPA      |
| **Grade Report**        | Semester grade report                                |
| **Attendance**          | Attendance history per course                        |
| **Schedule**            | Weekly class schedule                                |
| **E-Learning (LMS)**    | Access course materials and assignments              |
| **AI Academic Advisor** | Academic consultation with Gemini AI                 |
| **Thesis**              | Track thesis progress and supervision                |
| **Internship**          | Internship management and logbook                    |
| **Export PDF**          | Download transcript and grade reports in PDF format  |

### Lecturer

| Feature                    | Description                                  |
| -------------------------- | -------------------------------------------- |
| **Grade Input**            | Input student grades per class               |
| **Attendance**             | Manage class meetings and student attendance |
| **Academic Advisory**      | Approve advisee study plans                  |
| **Thesis Supervision**     | Review thesis progress and update status     |
| **Internship Supervision** | Review internship logbooks                   |
| **LMS Management**         | Upload materials and manage assignments      |
| **Presence**               | Lecturer attendance recording                |

### Admin

| Feature                 | Description                                        |
| ----------------------- | -------------------------------------------------- |
| **Dashboard**           | Statistics and academic overview                   |
| **Master Data**         | Manage Faculties, Study Programs, Courses, Classes |
| **User Management**     | Manage Lecturer and Student accounts               |
| **Study Plan Approval** | Monitoring and approval of study plans (view only) |
| **Thesis & Internship** | Assign supervisor and update status                |
| **Room Management**     | Classroom management                               |
| **Lecturer Attendance** | Lecturer attendance monitoring                     |

### Security Features

- ✅ Role-based access control (RBAC)
- ✅ Faculty-scoped admin access
- ✅ Rate limiting on sensitive endpoints
- ✅ CSRF protection
- ✅ Security headers middleware
- ✅ Input validation & sanitization

---

## Tech Stack

### Backend

| Technology            | Version | Description                  |
| --------------------- | ------- | ---------------------------- |
| **PHP**               | 8.2     | Server-side language         |
| **Laravel**           | 12      | PHP Framework                |
| **Laravel Breeze**    | 2       | Authentication scaffolding   |
| **Spatie Permission** | 6       | Role & permission management |

### Frontend

| Technology      | Version | Description                      |
| --------------- | ------- | -------------------------------- |
| **TailwindCSS** | 3       | Utility-first CSS framework      |
| **Alpine.js**   | 3       | Lightweight JavaScript framework |
| **Vite**        | 7       | Frontend build tool              |

### Database

| Technology     | Description                     |
| -------------- | ------------------------------- |
| **MySQL**      | Recommended for production      |
| **PostgreSQL** | Alternative production database |
| **SQLite**     | Development & testing           |

### AI Integration

| Technology            | Description         |
| --------------------- | ------------------- |
| **Google Gemini API** | AI Academic Advisor |

---

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer 2..x
- Node.js 18+ & npm
- MySQL 8.0+ / PostgreSQL 14+ (for production)

### Quick Start

```bash
# 1. Clone the repository
git clone https://github.com/ryandaaa/system.git
cd system

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Install Node.js dependencies
npm install

# 6. Build frontend assets
npm run build

# 7. Run database migrations with seeders
php artisan migrate --seed

# 8. Start the development server
php artisan serve
```

### One-Command Setup

```bash
composer setup
```

This will automatically:

- Install Composer dependencies
- Copy `.env.example` to `.env`
- Generate application key
- Run migrations
- Install npm dependencies
- Build frontend assets

### Development Mode

```bash
composer dev
```

This starts all development services concurrently:

- Laravel development server (`php artisan serve`)
- Queue worker (`php artisan queue:listen`)
- Log viewer (`php artisan pail`)
- Vite dev server (`npm run dev`)

---

## ⚙️ Configuration

### Environment Variables

#### Database (MySQL - Production)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### Database (SQLite - Development)

```env
DB_CONNECTION=sqlite
```

#### AI Integration (Gemini)

```env
# Get your API key at: https://aistudio.google.com/
GEMINI_API_KEY=your_gemini_api_key
```

#### Cache & Session (Production)

```env
SESSION_DRIVER=database
CACHE_STORE=database

# Or with Redis (recommended):
SESSION_DRIVER=redis
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### Academic Configuration

Edit `config/system.php` to customize:

```php
return [
    // Credit limits based on GPA
    'max_credits' => [
        'default' => 24,
        'gpa_rules' => [
            ['min' => 3.51, 'max' => 4.00, 'credits' => 24],
            ['min' => 3.01, 'max' => 3.50, 'credits' => 22],
            ['min' => 2.51, 'max' => 3.00, 'credits' => 20],
            ['min' => 2.00, 'max' => 2.50, 'credits' => 18],
            ['min' => 0.00, 'max' => 1.99, 'credits' => 14],
        ]
    ],

    // Grade conversion
    'grade_conversion' => [
        ['min' => 85, 'max' => 100, 'letter' => 'A',  'weight' => 4.00],
        ['min' => 80, 'max' => 84,  'letter' => 'A-', 'weight' => 3.75],
        ['min' => 75, 'max' => 79,  'letter' => 'B+', 'weight' => 3.50],
        // ... more grades
    ],

    // Default class capacity
    'default_class_capacity' => 40,

    // Pagination
    'pagination' => 15,
];
```

---

## Default Users

After running seeders, you can login with:

| Role              | Email                    | Password   | Description                               |
| ----------------- | ------------------------ | ---------- | ----------------------------------------- |
| **Superadmin**    | `superadmin@system.test` | `password` | Full system access                        |
| **Faculty Admin** | `admin.ftik@system.test` | `password` | Faculty-scoped admin                      |
| **Lecturer**      | `dosen@system.test`      | `password` | Dr. Ahmad Fauzi, M.Kom.                   |
| **Student**       | `mahasiswa@system.test`  | `password` | Budi Santoso (Semester 5, ID: 2022101001) |

> **Important**: Change these passwords immediately in production!

---

## Project Structure

```
system/
├── app/
│   ├── Console/Commands/      # Artisan commands
│   ├── DTOs/                  # Data Transfer Objects
│   ├── Exceptions/            # Custom exceptions
│   ├── Helpers/               # Helper classes
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Admin controllers
│   │   │   ├── Lecturer/      # Lecturer controllers
│   │   │   └── Student/       # Student controllers
│   │   └── Middleware/        # Custom middleware
│   ├── Models/                # Eloquent models
│   └── Services/              # Business logic services
├── config/
│   └── system.php             # Academic configuration
├── database/
│   ├── factories/             # Model factories
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── resources/
│   └── views/
│       ├── admin/             # Admin views
│       ├── lecturer/          # Lecturer views
│       ├── student/           # Student views
│       ├── components/        # Blade components
│       └── layouts/           # Layout templates
└── routes/
    └── web.php                # Web routes
```

---

## Database Schema

### Core Tables

```
┌──────────────┐     ┌──────────────┐     ┌────────────────┐
│    users     │     │  faculties   │     │ study_programs │
├──────────────┤     ├──────────────┤     ├────────────────┤
│ id           │     │ id           │     │ id             │
│ name         │     │ name         │     │ name           │
│ email        │     │ code         │     │ code           │
│ role         │     └──────────────┘     │ faculty_id     │
└──────────────┘                          └────────────────┘
       │                                         │
       ▼                                         ▼
┌──────────────┐                          ┌──────────────┐
│   students   │                          │  lecturers   │
├──────────────┤                          ├──────────────┤
│ id           │                          │ id           │
│ user_id      │                          │ user_id      │
│ student_number│                         │ lecturer_number│
│ study_program_id│                       │ study_program_id│
│ batch        │                          └──────────────┘
│ academic_advisor_id│                           │
└──────────────┘                                 │
       │                                          ▼
       │         ┌────────────┐           ┌─────────────────┐
       │         │  courses   │           │ academic_classes│
       │         ├────────────┤           ├─────────────────┤
       │         │ id         │◄──────────│ course_id       │
       │         │ course_code│           │ lecturer_id     │
       │         │ course_name│           │ name            │
       │         │ credits    │           │ capacity        │
       │         │ semester   │           └─────────────────┘
       │         └────────────┘                  │
       │                                          │
       ▼                                          ▼
┌──────────────┐                          ┌──────────────────┐
│ study_plans  │                          │study_plan_details│
├──────────────┤                          ├──────────────────┤
│ id           │◄─────────────────────────│ study_plan_id    │
│ student_id   │                          │ class_id         │
│ academic_year_id│                       └──────────────────┘
│ status       │
└──────────────┘
```

### Additional Tables

- `grades` - Student grades
- `course_schedules` - Class schedules
- `meetings` - Class meetings
- `attendances` - Attendance records
- `theses` - Thesis management
- `thesis_supervisions` - Thesis guidance records
- `internships` - Internship management
- `internship_logbooks` - Internship logbook entries
- `materials` - Learning materials
- `assignments` - Assignments
- `assignment_submissions` - Assignment submissions
- `notifications` - System notifications
- `ai_conversation_logs` - AI chat logs

---

## Artisan Commands

```bash
# Cache warming (after deployment)
php artisan cache:warm

# Clear all caches
php artisan cache:warm --clear

# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

---

## Testing

```bash
# Run all tests
php artisan test

# Or using Pest directly
./vendor/bin/pest

# Run with coverage
php artisan test --coverage
```

---

## Security

### Rate Limiting

- AI Chat: 10 requests/minute per user
- Study Plan Operations: 10 requests/minute per user
- Grading: 20 requests/minute per user

### Middleware

- `role` - Role-based access control
- `faculty.scope` - Faculty-scoped data access
- `SecurityHeadersMiddleware` - Security headers

### Validation

- All inputs are validated using Laravel Form Requests
- Custom exceptions for business logic errors
- CSRF protection on all forms

---

## Performance

### Optimizations

- Database indexes on frequently queried columns
- Query optimization (N+1 prevention)
- Master data caching (1 hour TTL)
- Eager loading relationships

### Caching Strategy

```php
// Master data cached:
- Active Academic Year
- Faculties list
- Study Programs list
- Courses list
- Lecturers list
```

---

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Keep commits atomic and well-described

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Author

Developed with ❤️ by Ryanda

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com/) - The PHP Framework for Web Artisans
- [TailwindCSS](https://tailwindcss.com/) - A utility-first CSS framework
- [Alpine.js](https://alpinejs.dev/) - A rugged, minimal JavaScript framework
- [Google Gemini](https://deepmind.google/technologies/gemini/) - AI for Academic Advisor

---

<p align="center">
  Made with ☕ and Laravel
</p>
