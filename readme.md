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