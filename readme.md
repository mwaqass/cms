# 🏢 Modern CMS Admin Panel

A comprehensive Content Management System built with **Laravel 10** and **Vue.js 3**, featuring a modern admin panel with full CRUD operations, analytics, and reporting capabilities.

![CMS Admin Panel](https://img.shields.io/badge/Laravel-10.x-red?style=for-the-badge&logo=laravel)
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green?style=for-the-badge&logo=vue.js)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue?style=for-the-badge&logo=php)


## ✨ Features

### 🎯 Core CMS Features
- **User Management** - Complete user CRUD with role-based access
- **Content Management** - Posts, Pages, and Categories with SEO optimization
- **Media Management** - File upload and management system
- **Role & Permissions** - Advanced role-based access control
- **Analytics Dashboard** - Real-time metrics and performance tracking
- **Reporting System** - Comprehensive reports with export functionality

### 🎨 Modern UI/UX
- **Responsive Design** - Mobile-first approach
- **Modern Dashboard** - Clean, professional interface
- **Interactive Charts** - Data visualization with charts
- **Real-time Updates** - Dynamic content loading
- **Dark/Light Mode Ready** - Extensible theming system

### 🔧 Technical Features
- **RESTful API** - Laravel Sanctum authentication
- **Vue.js SPA** - Single Page Application architecture
- **File Upload** - Secure media management
- **Search & Pagination** - Advanced data filtering
- **Export Functionality** - PDF and CSV export options

## 🚀 Quick Start

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js 16+ and npm
- MySQL/PostgreSQL database
- Web server (Apache/Nginx)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/modern-cms.git
   cd modern-cms
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   ```bash
   # Edit .env file with your database credentials
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Build frontend assets**
   ```bash
   npm run dev
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

9. **Access the application**
   - Visit: `http://localhost:8000`
   - Admin Panel: `http://localhost:8000/admin`
   - Default Login: `admin@example.com` / `password`

## 📁 Project Structure

```
cms/
├── app/
│   ├── Http/Controllers/Api/     # API Controllers
│   ├── Models/                   # Eloquent Models
│   └── Providers/                # Service Providers
├── database/
│   ├── migrations/               # Database Migrations
│   └── seeders/                  # Database Seeders
├── resources/
│   ├── js/
│   │   ├── components/           # Vue.js Components
│   │   └── app.js               # Main Vue App
│   └── views/
│       └── admin.blade.php      # Admin Panel View
├── routes/
│   ├── api.php                  # API Routes
│   └── web.php                  # Web Routes
└── public/                      # Public Assets
```

## 🎯 Admin Panel Sections

### 📊 Dashboard
- **Overview Cards** - Key metrics and statistics
- **User Growth Chart** - Visual data representation
- **Content Performance** - Top-performing content
- **Quick Actions** - Direct access to common tasks
- **Recent Activity** - Real-time activity feed
- **System Status** - Server resource monitoring
- **Traffic Sources** - Visitor analytics

### 👥 User Management
- **User CRUD** - Create, read, update, delete users
- **Role Assignment** - Assign roles to users
- **Search & Filter** - Advanced user search
- **Bulk Operations** - Mass user management
- **User Profiles** - Detailed user information

### 🔐 Role Management
- **Role CRUD** - Complete role management
- **Permission System** - Granular permissions
- **Role Hierarchy** - Hierarchical role structure
- **Permission Matrix** - Visual permission management

### 📝 Content Management
- **Posts** - Blog post management with categories
- **Pages** - Static page management
- **Categories** - Hierarchical category system
- **Media Library** - File upload and management
- **SEO Optimization** - Meta tags and descriptions

### 📈 Analytics & Reports
- **Analytics Dashboard** - Real-time metrics
- **User Activity Reports** - User behavior analysis
- **Content Performance** - Content engagement metrics
- **Traffic Analysis** - Visitor statistics
- **System Reports** - Server performance monitoring

### ⚙️ Settings
- **Site Configuration** - General site settings
- **Email Settings** - SMTP configuration
- **Security Settings** - Security preferences
- **Backup Management** - System backup options

## 🔧 API Endpoints

### Authentication
- `POST /api/login` - User login
- `GET /api/user` - Get current user

### Users
- `GET /api/users` - List users
- `POST /api/users` - Create user
- `GET /api/users/{id}` - Get user
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user

### Roles
- `GET /api/roles` - List roles
- `POST /api/roles` - Create role
- `GET /api/roles/{id}` - Get role
- `PUT /api/roles/{id}` - Update role
- `DELETE /api/roles/{id}` - Delete role

### Content
- `GET /api/posts` - List posts
- `POST /api/posts` - Create post
- `GET /api/pages` - List pages
- `POST /api/pages` - Create page
- `GET /api/categories` - List categories
- `POST /api/categories` - Create category

### Media
- `GET /api/media` - List media files
- `POST /api/media` - Upload media
- `PUT /api/media/{id}` - Update media
- `DELETE /api/media/{id}` - Delete media

### Analytics & Reports
- `GET /api/analytics` - Get analytics data
- `GET /api/reports` - Generate reports

## 🎨 Frontend Components

### Vue.js Components
- `AdminLayout.vue` - Main layout component
- `Dashboard.vue` - Dashboard overview
- `Users.vue` - User management
- `Roles.vue` - Role management
- `Posts.vue` - Post management
- `Pages.vue` - Page management
- `Categories.vue` - Category management
- `Media.vue` - Media management
- `Analytics.vue` - Analytics dashboard
- `Reports.vue` - Reporting system
- `Settings.vue` - Settings management

## 🛠️ Development

### Backend Development
```bash
# Run Laravel development server
php artisan serve

# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Frontend Development
```bash
# Install dependencies
npm install

# Development build
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch
```

### Database Management
```bash
# Create migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Refresh migrations
php artisan migrate:refresh --seed
```

## 🔒 Security Features

- **Laravel Sanctum** - API authentication
- **CSRF Protection** - Cross-site request forgery protection
- **Input Validation** - Comprehensive form validation
- **SQL Injection Protection** - Eloquent ORM protection
- **XSS Protection** - Output sanitization
- **Role-based Access** - Granular permission system

## 📊 Performance Optimization

- **Laravel Caching** - Application caching
- **Database Indexing** - Optimized queries
- **Asset Minification** - Compressed assets
- **Lazy Loading** - Efficient data loading
- **Pagination** - Large dataset handling

## 🧪 Testing

```bash
# Run PHP tests
php artisan test

# Run frontend tests
npm run test

# Run all tests
npm run test:all
```

## 📦 Deployment

### Production Setup
1. Set environment to production
2. Optimize Laravel for production
3. Build frontend assets
4. Configure web server
5. Set up SSL certificate

```bash
# Production optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

SANCTUM_STATEFUL_DOMAINS=yourdomain.com
SESSION_DOMAIN=yourdomain.com
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Coding Standards
- Follow PSR-12 for PHP code
- Use ESLint for JavaScript
- Write comprehensive tests
- Document your code
- Follow Vue.js style guide


## 🙏 Acknowledgments

- **Laravel Team** - For the amazing PHP framework
- **Vue.js Team** - For the progressive JavaScript framework
- **Tailwind CSS** - For the utility-first CSS framework
- **Community Contributors** - For valuable feedback and contributions


## 🔄 Changelog

### v1.0.0 (2024-01-15)
- ✨ Initial release
- 🎯 Complete CMS functionality
- 📊 Modern dashboard design
- 🔐 Role-based access control
- 📈 Analytics and reporting
- 📱 Responsive design
- 🚀 Performance optimizations

---

**Made with ❤️ by Waqas*

*This CMS provides a solid foundation for building modern web applications with Laravel and Vue.js.*



<!-- Last auto-update: 2025-08-06 08:38:09 -->

<!-- Last auto-update: 2025-08-07 15:12:17 -->

<!-- Last auto-update: 2025-08-08 07:32:54 -->

<!-- Last auto-update: 2025-12-30 10:38:24 -->

<!-- Last auto-update: 2025-12-31 06:49:13 -->

<!-- Auto-update: 2025-01-01 17:14:25 - 6970 -->

<!-- Auto-update: 2025-01-01 12:11:07 - 8345 -->

<!-- Auto-update: 2025-01-01 09:37:30 - 4435 -->

<!-- Auto-update: 2025-01-01 13:14:58 - 5746 -->

<!-- Auto-update: 2025-01-01 17:40:01 - 1232 -->

<!-- Auto-update: 2025-01-02 13:19:44 - 9966 -->

<!-- Auto-update: 2025-01-02 12:42:21 - 4285 -->

<!-- Auto-update: 2025-01-02 10:31:46 - 9471 -->

<!-- Auto-update: 2025-01-02 15:10:27 - 5275 -->

<!-- Auto-update: 2025-01-02 15:39:53 - 3848 -->

<!-- Auto-update: 2025-01-03 13:46:43 - 2806 -->

<!-- Auto-update: 2025-01-03 14:37:12 - 3344 -->

<!-- Auto-update: 2025-01-03 10:19:59 - 3310 -->

<!-- Auto-update: 2025-01-06 13:57:53 - 3275 -->

<!-- Auto-update: 2025-01-06 09:09:52 - 8584 -->

<!-- Auto-update: 2025-01-06 11:38:27 - 9726 -->

<!-- Auto-update: 2025-01-07 12:20:56 - 3850 -->

<!-- Auto-update: 2025-01-07 17:45:52 - 4980 -->

<!-- Auto-update: 2025-01-07 11:47:53 - 9043 -->

<!-- Auto-update: 2025-01-08 13:44:43 - 1671 -->

<!-- Auto-update: 2025-01-08 09:24:31 - 2841 -->

<!-- Auto-update: 2025-01-08 15:21:57 - 7223 -->

<!-- Auto-update: 2025-01-08 11:16:12 - 5934 -->

<!-- Auto-update: 2025-01-08 11:53:04 - 4499 -->

<!-- Auto-update: 2025-01-09 11:29:37 - 7443 -->

<!-- Auto-update: 2025-01-09 17:02:00 - 5649 -->

<!-- Auto-update: 2025-01-09 15:06:48 - 5827 -->

<!-- Auto-update: 2025-01-10 12:31:37 - 3248 -->

<!-- Auto-update: 2025-01-10 16:56:54 - 5030 -->

<!-- Auto-update: 2025-01-10 09:57:28 - 3340 -->

<!-- Auto-update: 2025-01-10 15:59:25 - 4725 -->

<!-- Auto-update: 2025-01-10 13:34:54 - 4288 -->

<!-- Auto-update: 2025-01-12 17:49:21 - 9977 -->

<!-- Auto-update: 2025-01-13 12:13:06 - 1931 -->

<!-- Auto-update: 2025-01-13 12:08:38 - 2209 -->

<!-- Auto-update: 2025-01-14 12:19:55 - 7170 -->

<!-- Auto-update: 2025-01-14 15:48:16 - 3057 -->

<!-- Auto-update: 2025-01-15 11:53:06 - 6132 -->

<!-- Auto-update: 2025-01-16 14:24:36 - 8754 -->

<!-- Auto-update: 2025-01-16 15:36:43 - 3972 -->

<!-- Auto-update: 2025-01-17 12:22:43 - 5902 -->

<!-- Auto-update: 2025-01-18 16:31:43 - 1139 -->

<!-- Auto-update: 2025-01-20 13:16:39 - 7800 -->

<!-- Auto-update: 2025-01-20 12:58:21 - 6329 -->

<!-- Auto-update: 2025-01-20 09:56:12 - 8345 -->

<!-- Auto-update: 2025-01-20 13:16:52 - 9652 -->

<!-- Auto-update: 2025-01-21 15:41:13 - 5797 -->

<!-- Auto-update: 2025-01-21 16:15:47 - 4352 -->

<!-- Auto-update: 2025-01-21 15:58:44 - 7691 -->

<!-- Auto-update: 2025-01-21 16:11:20 - 7764 -->

<!-- Auto-update: 2025-01-22 13:32:22 - 9749 -->

<!-- Auto-update: 2025-01-22 09:40:29 - 8183 -->

<!-- Auto-update: 2025-01-22 14:24:02 - 6608 -->

<!-- Auto-update: 2025-01-23 15:44:55 - 4430 -->

<!-- Auto-update: 2025-01-23 10:30:43 - 2379 -->

<!-- Auto-update: 2025-01-23 15:11:32 - 4356 -->

<!-- Auto-update: 2025-01-24 15:04:53 - 8750 -->

<!-- Auto-update: 2025-01-24 14:49:52 - 9200 -->

<!-- Auto-update: 2025-01-24 11:48:35 - 2918 -->

<!-- Auto-update: 2025-01-25 15:07:07 - 9728 -->

<!-- Auto-update: 2025-01-26 15:27:08 - 7276 -->

<!-- Auto-update: 2025-01-27 09:16:25 - 1856 -->

<!-- Auto-update: 2025-01-27 15:54:58 - 7304 -->

<!-- Auto-update: 2025-01-27 17:30:43 - 1574 -->

<!-- Auto-update: 2025-01-27 11:28:09 - 7659 -->

<!-- Auto-update: 2025-01-27 17:10:27 - 6818 -->

<!-- Auto-update: 2025-01-28 14:06:04 - 8119 -->

<!-- Auto-update: 2025-01-28 17:27:27 - 3224 -->

<!-- Auto-update: 2025-01-28 17:25:45 - 5282 -->

<!-- Auto-update: 2025-01-28 12:49:49 - 7176 -->

<!-- Auto-update: 2025-01-29 12:46:25 - 6111 -->

<!-- Auto-update: 2025-01-29 16:33:05 - 2755 -->

<!-- Auto-update: 2025-01-29 13:04:23 - 1706 -->

<!-- Auto-update: 2025-01-30 16:18:04 - 9963 -->

<!-- Auto-update: 2025-01-31 10:08:06 - 2556 -->

<!-- Auto-update: 2025-01-31 09:21:48 - 6144 -->

<!-- Auto-update: 2025-01-31 14:40:37 - 2227 -->

<!-- Auto-update: 2025-01-31 17:39:04 - 3947 -->

<!-- Auto-update: 2025-02-01 16:57:00 - 5962 -->

<!-- Auto-update: 2025-02-03 11:03:02 - 8177 -->

<!-- Auto-update: 2025-02-03 14:22:31 - 8392 -->

<!-- Auto-update: 2025-02-03 09:05:11 - 1686 -->

<!-- Auto-update: 2025-02-04 10:24:53 - 9701 -->

<!-- Auto-update: 2025-02-04 12:37:26 - 3475 -->

<!-- Auto-update: 2025-02-04 15:36:56 - 1573 -->

<!-- Auto-update: 2025-02-04 10:52:58 - 5887 -->

<!-- Auto-update: 2025-02-04 17:23:57 - 9195 -->

<!-- Auto-update: 2025-02-05 15:26:22 - 4937 -->

<!-- Auto-update: 2025-02-06 16:56:03 - 8832 -->

<!-- Auto-update: 2025-02-06 13:40:38 - 3880 -->

<!-- Auto-update: 2025-02-07 09:35:47 - 7082 -->

<!-- Auto-update: 2025-02-09 10:17:14 - 3160 -->

<!-- Auto-update: 2025-02-10 09:33:35 - 2326 -->

<!-- Auto-update: 2025-02-10 15:40:09 - 7601 -->

<!-- Auto-update: 2025-02-10 17:45:15 - 2587 -->

<!-- Auto-update: 2025-02-10 15:48:44 - 4668 -->

<!-- Auto-update: 2025-02-11 15:22:10 - 4309 -->

<!-- Auto-update: 2025-02-11 09:53:57 - 8979 -->

<!-- Auto-update: 2025-02-11 12:07:39 - 4981 -->

<!-- Auto-update: 2025-02-12 17:58:16 - 4529 -->

<!-- Auto-update: 2025-02-12 15:53:15 - 5328 -->

<!-- Auto-update: 2025-02-13 15:58:54 - 3111 -->

<!-- Auto-update: 2025-02-13 16:06:10 - 9920 -->

<!-- Auto-update: 2025-02-13 13:36:02 - 9612 -->

<!-- Auto-update: 2025-02-13 12:08:41 - 2942 -->

<!-- Auto-update: 2025-02-14 12:48:02 - 7310 -->

<!-- Auto-update: 2025-02-14 12:28:15 - 8089 -->

<!-- Auto-update: 2025-02-14 15:27:40 - 5451 -->

<!-- Auto-update: 2025-02-15 10:26:30 - 4205 -->

<!-- Auto-update: 2025-02-16 17:41:33 - 5401 -->

<!-- Auto-update: 2025-02-17 14:39:31 - 3810 -->

<!-- Auto-update: 2025-02-17 13:35:12 - 9275 -->

<!-- Auto-update: 2025-02-17 10:28:56 - 3736 -->

<!-- Auto-update: 2025-02-17 13:25:42 - 7353 -->

<!-- Auto-update: 2025-02-18 09:49:41 - 1284 -->

<!-- Auto-update: 2025-02-18 13:56:24 - 9647 -->

<!-- Auto-update: 2025-02-18 16:59:48 - 9301 -->

<!-- Auto-update: 2025-02-19 13:35:46 - 8300 -->

<!-- Auto-update: 2025-02-20 14:42:29 - 7105 -->

<!-- Auto-update: 2025-02-20 11:30:38 - 8036 -->

<!-- Auto-update: 2025-02-20 13:55:41 - 8883 -->

<!-- Auto-update: 2025-02-20 09:50:56 - 5969 -->

<!-- Auto-update: 2025-02-20 13:18:26 - 3861 -->

<!-- Auto-update: 2025-02-21 14:54:29 - 5922 -->

<!-- Auto-update: 2025-02-24 12:43:06 - 7369 -->

<!-- Auto-update: 2025-02-24 14:01:43 - 6329 -->

<!-- Auto-update: 2025-02-24 11:19:10 - 7707 -->

<!-- Auto-update: 2025-02-24 13:44:39 - 8225 -->

<!-- Auto-update: 2025-02-24 15:31:30 - 4360 -->

<!-- Auto-update: 2025-02-25 16:23:52 - 3518 -->

<!-- Auto-update: 2025-02-25 12:01:32 - 3380 -->

<!-- Auto-update: 2025-02-25 11:42:29 - 1777 -->

<!-- Auto-update: 2025-02-25 14:18:14 - 3565 -->

<!-- Auto-update: 2025-02-26 13:45:41 - 1244 -->

<!-- Auto-update: 2025-02-27 11:24:24 - 8568 -->

<!-- Auto-update: 2025-02-27 11:38:24 - 2249 -->

<!-- Auto-update: 2025-02-27 12:53:12 - 5322 -->

<!-- Auto-update: 2025-02-27 17:56:43 - 9701 -->

<!-- Auto-update: 2025-02-27 13:23:04 - 1027 -->

<!-- Auto-update: 2025-02-28 14:23:50 - 4130 -->

<!-- Auto-update: 2025-02-28 11:51:18 - 1494 -->

<!-- Auto-update: 2025-03-03 12:30:37 - 1245 -->

<!-- Auto-update: 2025-03-04 16:00:19 - 6386 -->

<!-- Auto-update: 2025-03-04 16:39:50 - 3618 -->

<!-- Auto-update: 2025-03-04 09:37:03 - 6289 -->

<!-- Auto-update: 2025-03-04 16:53:30 - 5702 -->

<!-- Auto-update: 2025-03-05 11:40:39 - 9539 -->

<!-- Auto-update: 2025-03-06 09:06:01 - 3750 -->

<!-- Auto-update: 2025-03-06 09:27:24 - 4599 -->

<!-- Auto-update: 2025-03-06 13:33:15 - 1998 -->

<!-- Auto-update: 2025-03-07 13:10:43 - 3925 -->

<!-- Auto-update: 2025-03-07 14:11:38 - 1638 -->

<!-- Auto-update: 2025-03-07 10:04:03 - 8242 -->

<!-- Auto-update: 2025-03-07 16:25:42 - 6421 -->

<!-- Auto-update: 2025-03-07 10:55:24 - 5257 -->

<!-- Auto-update: 2025-03-08 15:30:45 - 8444 -->

<!-- Auto-update: 2025-03-09 16:45:19 - 4636 -->

<!-- Auto-update: 2025-03-10 14:56:16 - 7949 -->

<!-- Auto-update: 2025-03-10 16:54:11 - 8418 -->

<!-- Auto-update: 2025-03-10 15:44:51 - 4956 -->

<!-- Auto-update: 2025-03-11 17:37:02 - 6994 -->

<!-- Auto-update: 2025-03-12 10:53:34 - 4749 -->

<!-- Auto-update: 2025-03-12 09:58:39 - 8562 -->

<!-- Auto-update: 2025-03-12 12:57:35 - 8305 -->

<!-- Auto-update: 2025-03-12 12:46:55 - 7454 -->

<!-- Auto-update: 2025-03-13 14:03:23 - 6760 -->

<!-- Auto-update: 2025-03-13 11:27:18 - 1042 -->

<!-- Auto-update: 2025-03-13 15:40:57 - 9453 -->

<!-- Auto-update: 2025-03-13 11:06:53 - 8009 -->

<!-- Auto-update: 2025-03-14 11:35:57 - 5242 -->

<!-- Auto-update: 2025-03-14 16:31:11 - 1943 -->

<!-- Auto-update: 2025-03-17 17:57:53 - 2288 -->

<!-- Auto-update: 2025-03-17 13:38:20 - 2865 -->

<!-- Auto-update: 2025-03-17 14:35:32 - 1733 -->

<!-- Auto-update: 2025-03-17 15:42:54 - 2126 -->

<!-- Auto-update: 2025-03-17 11:43:47 - 8528 -->

<!-- Auto-update: 2025-03-18 16:18:42 - 6478 -->

<!-- Auto-update: 2025-03-18 11:31:28 - 4524 -->

<!-- Auto-update: 2025-03-19 17:26:04 - 8878 -->

<!-- Auto-update: 2025-03-19 17:50:57 - 1694 -->

<!-- Auto-update: 2025-03-19 17:52:34 - 8338 -->

<!-- Auto-update: 2025-03-19 13:15:44 - 9877 -->

<!-- Auto-update: 2025-03-19 11:35:05 - 9020 -->

<!-- Auto-update: 2025-03-20 13:42:08 - 9958 -->

<!-- Auto-update: 2025-03-21 17:06:38 - 7222 -->

<!-- Auto-update: 2025-03-21 17:07:18 - 6079 -->

<!-- Auto-update: 2025-03-21 11:02:35 - 9730 -->

<!-- Auto-update: 2025-03-21 16:09:28 - 1802 -->

<!-- Auto-update: 2025-03-21 13:23:44 - 9281 -->

<!-- Auto-update: 2025-03-23 10:10:16 - 9574 -->

<!-- Auto-update: 2025-03-24 12:06:08 - 1366 -->

<!-- Auto-update: 2025-03-24 09:15:22 - 8839 -->

<!-- Auto-update: 2025-03-25 14:18:45 - 1024 -->

<!-- Auto-update: 2025-03-26 12:05:34 - 7321 -->

<!-- Auto-update: 2025-03-26 14:38:00 - 5753 -->

<!-- Auto-update: 2025-03-26 17:18:12 - 5391 -->

<!-- Auto-update: 2025-03-26 14:02:40 - 6733 -->

<!-- Auto-update: 2025-03-26 15:38:38 - 1636 -->

<!-- Auto-update: 2025-03-27 12:54:51 - 7600 -->

<!-- Auto-update: 2025-03-27 15:22:03 - 9455 -->

<!-- Auto-update: 2025-03-27 10:40:32 - 6598 -->

<!-- Auto-update: 2025-03-28 14:13:48 - 9513 -->

<!-- Auto-update: 2025-03-31 09:08:19 - 6384 -->

<!-- Auto-update: 2025-03-31 17:50:05 - 3567 -->

<!-- Auto-update: 2025-03-31 11:45:58 - 7970 -->

<!-- Auto-update: 2025-03-31 10:48:53 - 7255 -->

<!-- Auto-update: 2025-04-01 15:44:29 - 6252 -->

<!-- Auto-update: 2025-04-01 15:17:22 - 6959 -->

<!-- Auto-update: 2025-04-01 11:27:54 - 9981 -->

<!-- Auto-update: 2025-04-01 10:42:29 - 4790 -->

<!-- Auto-update: 2025-04-01 15:13:25 - 1442 -->

<!-- Auto-update: 2025-04-02 10:00:11 - 4479 -->

<!-- Auto-update: 2025-04-02 15:56:00 - 2934 -->

<!-- Auto-update: 2025-04-02 15:08:26 - 6348 -->

<!-- Auto-update: 2025-04-02 09:45:35 - 3115 -->

<!-- Auto-update: 2025-04-02 13:17:52 - 1952 -->

<!-- Auto-update: 2025-04-03 09:07:14 - 7916 -->

<!-- Auto-update: 2025-04-03 16:35:07 - 3713 -->

<!-- Auto-update: 2025-04-03 15:36:29 - 7265 -->

<!-- Auto-update: 2025-04-03 15:09:09 - 5957 -->

<!-- Auto-update: 2025-04-04 17:12:57 - 9051 -->

<!-- Auto-update: 2025-04-04 15:31:46 - 5229 -->

<!-- Auto-update: 2025-04-04 13:10:53 - 2254 -->

<!-- Auto-update: 2025-04-04 15:59:06 - 1302 -->

<!-- Auto-update: 2025-04-04 09:15:27 - 9156 -->

<!-- Auto-update: 2025-04-05 10:13:59 - 7873 -->

<!-- Auto-update: 2025-04-07 12:31:12 - 4339 -->

<!-- Auto-update: 2025-04-07 11:41:41 - 1582 -->

<!-- Auto-update: 2025-04-07 09:18:34 - 2348 -->

<!-- Auto-update: 2025-04-07 15:54:01 - 9819 -->

<!-- Auto-update: 2025-04-07 15:13:03 - 9289 -->

<!-- Auto-update: 2025-04-08 10:13:43 - 9627 -->

<!-- Auto-update: 2025-04-08 09:34:38 - 1340 -->

<!-- Auto-update: 2025-04-08 12:35:13 - 6961 -->

<!-- Auto-update: 2025-04-08 17:32:34 - 3632 -->

<!-- Auto-update: 2025-04-09 12:04:44 - 2621 -->

<!-- Auto-update: 2025-04-09 09:28:05 - 7900 -->

<!-- Auto-update: 2025-04-09 13:37:54 - 2336 -->

<!-- Auto-update: 2025-04-10 14:56:53 - 2072 -->

<!-- Auto-update: 2025-04-10 15:56:08 - 1696 -->

<!-- Auto-update: 2025-04-11 10:33:34 - 1934 -->

<!-- Auto-update: 2025-04-13 09:59:13 - 9174 -->

<!-- Auto-update: 2025-04-14 10:17:32 - 3983 -->

<!-- Auto-update: 2025-04-14 14:01:53 - 3447 -->

<!-- Auto-update: 2025-04-14 13:09:11 - 1777 -->

<!-- Auto-update: 2025-04-15 12:27:44 - 7384 -->

<!-- Auto-update: 2025-04-15 12:29:56 - 8636 -->

<!-- Auto-update: 2025-04-15 13:29:12 - 8169 -->

<!-- Auto-update: 2025-04-15 11:22:50 - 2672 -->

<!-- Auto-update: 2025-04-15 16:49:35 - 6558 -->

<!-- Auto-update: 2025-04-16 15:21:49 - 7614 -->

<!-- Auto-update: 2025-04-16 12:00:07 - 5308 -->

<!-- Auto-update: 2025-04-16 10:32:36 - 2700 -->

<!-- Auto-update: 2025-04-16 17:32:27 - 7089 -->

<!-- Auto-update: 2025-04-16 12:36:28 - 9206 -->

<!-- Auto-update: 2025-04-17 17:40:56 - 6583 -->

<!-- Auto-update: 2025-04-17 15:36:46 - 1998 -->

<!-- Auto-update: 2025-04-17 13:05:32 - 6297 -->

<!-- Auto-update: 2025-04-18 14:54:36 - 7445 -->

<!-- Auto-update: 2025-04-18 16:01:11 - 7401 -->

<!-- Auto-update: 2025-04-21 12:31:18 - 3355 -->

<!-- Auto-update: 2025-04-21 13:45:48 - 1167 -->

<!-- Auto-update: 2025-04-21 09:54:44 - 7265 -->

<!-- Auto-update: 2025-04-22 10:26:32 - 5825 -->

<!-- Auto-update: 2025-04-22 16:29:09 - 6756 -->

<!-- Auto-update: 2025-04-22 10:35:58 - 1031 -->

<!-- Auto-update: 2025-04-22 11:51:07 - 9381 -->

<!-- Auto-update: 2025-04-23 12:16:20 - 4133 -->

<!-- Auto-update: 2025-04-23 11:49:36 - 3491 -->

<!-- Auto-update: 2025-04-23 13:09:04 - 3745 -->

<!-- Auto-update: 2025-04-23 13:49:58 - 6814 -->

<!-- Auto-update: 2025-04-24 10:34:38 - 7371 -->

<!-- Auto-update: 2025-04-24 17:49:21 - 8117 -->

<!-- Auto-update: 2025-04-24 17:49:15 - 2769 -->

<!-- Auto-update: 2025-04-25 12:12:54 - 7728 -->

<!-- Auto-update: 2025-04-27 16:10:51 - 5122 -->

<!-- Auto-update: 2025-04-28 11:25:44 - 8571 -->

<!-- Auto-update: 2025-04-28 17:58:30 - 6278 -->

<!-- Auto-update: 2025-04-28 12:26:39 - 7793 -->

<!-- Auto-update: 2025-04-28 11:44:55 - 8117 -->

<!-- Auto-update: 2025-04-29 15:45:50 - 1844 -->

<!-- Auto-update: 2025-04-29 12:48:58 - 2388 -->

<!-- Auto-update: 2025-04-29 09:49:43 - 8901 -->

<!-- Auto-update: 2025-04-30 17:22:44 - 8605 -->

<!-- Auto-update: 2025-04-30 17:25:21 - 2745 -->

<!-- Auto-update: 2025-04-30 13:39:55 - 5224 -->

<!-- Auto-update: 2025-04-30 15:34:56 - 9174 -->

<!-- Auto-update: 2025-04-30 12:37:44 - 7040 -->

<!-- Auto-update: 2025-05-01 11:54:45 - 3132 -->

<!-- Auto-update: 2025-05-01 17:37:28 - 1762 -->

<!-- Auto-update: 2025-05-01 12:11:23 - 2503 -->

<!-- Auto-update: 2025-05-01 09:31:33 - 2365 -->

<!-- Auto-update: 2025-05-02 11:38:35 - 9486 -->

<!-- Auto-update: 2025-05-02 09:02:17 - 6887 -->

<!-- Auto-update: 2025-05-02 10:56:38 - 7631 -->

<!-- Auto-update: 2025-05-03 17:18:57 - 1675 -->

<!-- Auto-update: 2025-05-04 10:51:43 - 4657 -->

<!-- Auto-update: 2025-05-05 12:07:46 - 9742 -->

<!-- Auto-update: 2025-05-05 15:45:27 - 6349 -->

<!-- Auto-update: 2025-05-05 10:42:28 - 8094 -->

<!-- Auto-update: 2025-05-06 16:06:37 - 6771 -->

<!-- Auto-update: 2025-05-07 17:38:38 - 6822 -->

<!-- Auto-update: 2025-05-07 12:06:12 - 6220 -->

<!-- Auto-update: 2025-05-07 15:49:35 - 6972 -->

<!-- Auto-update: 2025-05-08 16:14:21 - 2278 -->

<!-- Auto-update: 2025-05-09 11:45:55 - 8697 -->

<!-- Auto-update: 2025-05-09 15:01:20 - 9047 -->

<!-- Auto-update: 2025-05-09 12:43:26 - 6306 -->

<!-- Auto-update: 2025-05-09 13:59:58 - 9493 -->

<!-- Auto-update: 2025-05-11 10:18:34 - 7803 -->

<!-- Auto-update: 2025-05-12 09:44:00 - 8178 -->

<!-- Auto-update: 2025-05-12 17:11:28 - 4551 -->

<!-- Auto-update: 2025-05-12 13:39:47 - 8520 -->

<!-- Auto-update: 2025-05-13 15:33:29 - 1812 -->

<!-- Auto-update: 2025-05-13 17:57:02 - 8020 -->

<!-- Auto-update: 2025-05-13 15:47:43 - 7686 -->

<!-- Auto-update: 2025-05-14 13:07:31 - 7391 -->

<!-- Auto-update: 2025-05-15 16:27:08 - 3528 -->

<!-- Auto-update: 2025-05-15 12:35:13 - 8253 -->

<!-- Auto-update: 2025-05-15 15:48:01 - 4853 -->

<!-- Auto-update: 2025-05-15 16:38:36 - 7595 -->

<!-- Auto-update: 2025-05-15 09:20:41 - 7482 -->

<!-- Auto-update: 2025-05-16 16:40:32 - 9526 -->

<!-- Auto-update: 2025-05-16 11:44:21 - 5852 -->

<!-- Auto-update: 2025-05-16 09:47:13 - 7879 -->

<!-- Auto-update: 2025-05-16 12:14:39 - 4657 -->

<!-- Auto-update: 2025-05-19 12:17:35 - 2279 -->

<!-- Auto-update: 2025-05-19 13:30:57 - 7741 -->

<!-- Auto-update: 2025-05-20 17:09:55 - 7654 -->

<!-- Auto-update: 2025-05-20 13:05:03 - 4217 -->

<!-- Auto-update: 2025-05-20 15:11:13 - 2098 -->

<!-- Auto-update: 2025-05-21 16:25:01 - 6515 -->