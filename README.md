# Transportation Management System (TMS)

A complete, production-ready Laravel 11 monolith for managing transportation logistics with real-time driver tracking, load management, and role-based access control.

## 🚀 Features

### MVP Capabilities
- **Authentication & RBAC**: Laravel Breeze + Spatie Permissions (admin/dispatcher/driver roles)
- **Load Management**: Create, assign, track shipment loads with status updates
- **Real-time Driver Tracking**: Browser geolocation API with breadcrumb trail history
- **Document Management**: Secure upload/storage of POD, BOL, photos per load
- **Interactive Maps**: Leaflet + OpenStreetMap (no API keys required)
- **Role-based Dashboards**: Customized views for each user role
- **Security**: CSRF protection, input validation, file upload restrictions, rate limiting

### Performance & Scalability
- Redis caching for driver locations
- Database indexes on critical queries
- Scheduled breadcrumb pruning (automated cleanup)
- Queue-ready architecture for heavy tasks

---

## 📋 Tech Stack

| Component | Technology |
|-----------|-----------|
| **Backend** | PHP 8.3, Laravel 11 |
| **Database** | MySQL 8 |
| **Cache/Queue** | Redis |
| **Frontend** | Blade templates, Alpine.js, Tailwind CSS |
| **Maps** | Leaflet.js, OpenStreetMap |
| **Auth** | Laravel Breeze (Blade) |
| **RBAC** | Spatie Laravel Permission |
| **Deployment** | Docker Compose |

---

## 🏗 Architecture

### Directory Structure
```
TMS/
├── app/
│   ├── Console/Commands/           # Artisan commands (breadcrumb pruning)
│   ├── Enums/                      # LoadStatus, DocumentType
│   ├── Http/
│   │   ├── Controllers/            # Web & API controllers
│   │   ├── Requests/               # Form validation classes
│   │   └── Kernel.php              # Middleware configuration
│   ├── Models/                     # Eloquent models
│   ├── Policies/                   # Authorization policies
│   └── Services/                   # Business logic layer
├── config/                         # Configuration files
├── database/
│   ├── factories/                  # Model factories for testing
│   ├── migrations/                 # Database migrations
│   └── seeders/                    # Seed data (roles, users, sample loads)
├── docker/                         # Docker configuration
├── public/                         # Public assets
├── resources/
│   ├── css/                        # Tailwind CSS
│   ├── js/                         # Alpine.js, Leaflet
│   └── views/                      # Blade templates
├── routes/                         # Web & API routes
├── storage/                        # Uploaded documents, logs
├── tests/Feature/                  # Feature tests
├── docker-compose.yml              # Docker orchestration
├── Dockerfile                      # PHP-FPM container
└── README.md                       # This file
```

---

## 🗄 Database Schema

### Core Tables

**users**
- Standard Laravel users table with Spatie roles/permissions

**loads**
- `id`, `reference_no` (unique), `pickup_address`, `delivery_address`
- `pickup_at`, `delivery_at`, `status` (enum), `assigned_driver_id`
- `notes`, `timestamps`
- **Indexes**: `status`, `assigned_driver_id`, `pickup_at`

**load_documents**
- `id`, `load_id`, `type` (POD/BOL/PHOTO/OTHER)
- `path`, `original_name`, `mime_type`, `size`, `uploaded_by`
- **Indexes**: `load_id`, `type`

**driver_locations** (latest position)
- `id`, `user_id` (unique), `latitude`, `longitude`
- `heading`, `speed`, `accuracy`, `captured_at`
- **Indexes**: `user_id`, `captured_at`

**driver_breadcrumbs** (historical trail)
- `id`, `load_id`, `user_id`, `latitude`, `longitude`, `captured_at`
- **Indexes**: `load_id`, `user_id + captured_at`, `captured_at`

---

## 🚢 Setup & Deployment

### Prerequisites
- **Docker Desktop** (Windows/Mac/Linux)
- **Git** (optional, for cloning)

### Quick Start (Docker)

1. **Navigate to project directory**:
```powershell
cd C:\Users\danii\Desktop\TMS
```

2. **Build and start containers**:
```powershell
docker compose up -d --build
```

3. **Install PHP dependencies**:
```powershell
docker compose exec app composer install
```

4. **Install NPM dependencies & build assets**:
```powershell
docker compose exec app npm install
docker compose exec app npm run build
```

5. **Copy environment file**:
```powershell
docker compose exec app cp .env.example .env
```

6. **Generate application key**:
```powershell
docker compose exec app php artisan key:generate
```

7. **Run migrations & seed database**:
```powershell
docker compose exec app php artisan migrate:fresh --seed
```

8. **Create storage link**:
```powershell
docker compose exec app php artisan storage:link
```

9. **Access the application**:
Open browser to `http://localhost:8080`

---

## 👥 Test Accounts

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@example.com | password |
| **Dispatcher** | dispatcher@example.com | password |
| **Driver** | driver@example.com | password |

---

## 🌐 Endpoints

### Web Routes (Browser)
- `GET /login` - Login page
- `GET /dashboard` - Role-based dashboard
- `GET /loads` - List all loads
- `GET /loads/create` - Create new load form
- `GET /loads/{id}` - Load details with map & documents
- `POST /loads/{id}/assign-driver` - Assign driver to load
- `POST /loads/{id}/update-status` - Update load status
- `GET /drivers/map` - Dispatcher map (all active drivers)
- `GET /driver` - Driver interface with tracking controls

### API Routes (JSON)
- `POST /api/driver/location` - Update driver GPS location (rate-limited: 60/min)
  - **Auth**: Sanctum token, role:driver required
  - **Body**: `{latitude, longitude, heading, speed, accuracy, load_id?}`
  
- `POST /api/loads/{id}/documents` - Upload document to load
  - **Auth**: Sanctum token required
  - **Body**: `multipart/form-data` with `document` file & `type` (pod/bol/photo/other)

---

## 📡 API Examples

### Driver Location Update (cURL)
```bash
curl -X POST http://localhost:8080/api/driver/location \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -d '{
    "latitude": 34.0522,
    "longitude": -118.2437,
    "heading": 180,
    "speed": 65.5,
    "accuracy": 10.0,
    "load_id": 1
  }'
```

**Response**:
```json
{
  "success": true,
  "message": "Location updated successfully",
  "data": {
    "latitude": "34.0522000",
    "longitude": "-118.2437000",
    "captured_at": "2024-01-15T10:30:00Z"
  }
}
```

### Document Upload (cURL)
```bash
curl -X POST http://localhost:8080/api/loads/1/documents \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -F "document=@/path/to/pod.pdf" \
  -F "type=pod"
```

**Response**:
```json
{
  "success": true,
  "message": "Document uploaded successfully",
  "data": {
    "id": 5,
    "type": "Proof of Delivery",
    "original_name": "pod.pdf",
    "size_mb": "1.25",
    "uploaded_at": "2024-01-15T10:35:00Z"
  }
}
```

---

## 🧪 Testing

### Run All Tests
```powershell
docker compose exec app php artisan test
```

### Run Specific Test Suite
```powershell
# Feature tests only
docker compose exec app php artisan test --testsuite=Feature

# Specific test file
docker compose exec app php artisan test tests/Feature/LoadManagementTest.php
```

### Test Coverage
- ✅ Authentication (login, logout, validation)
- ✅ Load management (CRUD, permissions)
- ✅ Driver location tracking (API, validation, rate limiting)
- ✅ Document uploads (file validation, storage)

---

## 🔒 Security Features

### Implemented
- **CSRF Protection**: All forms protected via Laravel middleware
- **Input Validation**: FormRequest classes for all user input
- **File Upload Security**:
  - Mime type validation (PDF, JPG, PNG, WebP only)
  - Size limits (10MB max)
  - Storage outside public directory
  - Sanitized filenames
- **Rate Limiting**: 60 requests/min on location endpoint
- **Authentication**: Laravel Sanctum for API routes
- **Authorization**: Policies + Spatie Permissions for role-based access
- **SQL Injection**: Eloquent ORM with parameterized queries
- **Password Hashing**: Bcrypt (Laravel default)

### Production Recommendations
- Enable HTTPS (required for geolocation API in production)
- Configure CORS for API endpoints
- Set up SSL certificates in nginx
- Use environment-specific `.env` files
- Enable Laravel error logging to external service

---

## 🛠 Maintenance Commands

### Prune Old Breadcrumbs
```powershell
# Manual execution
docker compose exec app php artisan breadcrumbs:prune

# Custom retention period (default: 90 days)
docker compose exec app php artisan breadcrumbs:prune --days=30
```

### Clear Cache
```powershell
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
```

### Queue Worker (if using queues)
```powershell
docker compose exec app php artisan queue:work --tries=3
```

---

## 📱 Driver Tracking Workflow

### For Drivers (Mobile-friendly)
1. Login at `/login` with driver credentials
2. Navigate to **My Loads** (`/driver`)
3. Click **Start Tracking** on active load
4. Browser requests geolocation permission (allow)
5. Location updates automatically every 10 seconds
6. Update load status using dropdown (e.g., "En Route", "Delivered")
7. Upload POD photo via file input
8. Click **Stop Tracking** when load complete

### For Dispatchers/Admins
1. View **Driver Map** (`/drivers/map`) to see all active drivers
2. Open specific load (`/loads/{id}`) to see:
   - Load details
   - Breadcrumb route history (polyline on map)
   - Uploaded documents
   - Current status
3. Assign/reassign drivers via dropdown
4. Update load status manually if needed

---

## 🐛 Troubleshooting

### Docker containers won't start
```powershell
docker compose down
docker compose up -d --build --force-recreate
```

### Permission errors inside container
```powershell
docker compose exec app chown -R tmsuser:tmsuser /var/www
```

### Database migration errors
```powershell
docker compose exec app php artisan migrate:fresh --seed
```

### Assets not loading
```powershell
docker compose exec app npm run build
```

### Geolocation not working
- Ensure using HTTPS in production (localhost is exempt)
- Check browser permissions for location access
- Verify browser console for JavaScript errors

---

## 📚 Additional Information

### Enums Reference

**LoadStatus**:
- `pending` → Pending
- `assigned` → Assigned
- `en_route` → En Route to Pickup
- `arrived_pickup` → Arrived at Pickup
- `loaded` → Loaded
- `in_transit` → In Transit
- `delivered` → Delivered
- `cancelled` → Cancelled

**DocumentType**:
- `pod` → Proof of Delivery
- `bol` → Bill of Lading
- `photo` → Photo
- `other` → Other

### Scheduled Tasks
- **Breadcrumb Pruning**: Daily at 2:00 AM (removes breadcrumbs > 90 days old)

### Default Configuration
- **Session Driver**: Redis
- **Cache Driver**: Redis
- **Queue Driver**: Redis (can use sync for simple setups)
- **Max Upload Size**: 10MB
- **Location Update Interval**: 10 seconds (configurable in driver view)

---

## 📄 License

This project is open-sourced software licensed under the MIT license.

---

## 🤝 Support

For issues or questions:
1. Check logs: `docker compose logs app`
2. Verify environment: `docker compose exec app php artisan about`
3. Run tests: `docker compose exec app php artisan test`

---

**Built with Laravel 11 • Ready for Production**
