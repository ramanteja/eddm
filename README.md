# EDDM (Electronic Drug Dispensing Machine)

A Laravel + Filament-based web application for managing and dispensing medicines via Raspberry Pi-controlled vending machines.

---

## 🚀 Features

- Role-based access: `SuperAdmin`, `Admin`, `User`
- REST API support for Raspberry Pi:
  - `GET /api/next-order`
  - `POST /api/dispense`
- Real-time order and payment status tracking
- Stock-based validation with dispenser slot assignment
- Role-specific views and permissions (users can only see/edit their own orders)
- Filament-based admin dashboard

---

## 🛠️ Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/ramanteja/eddm.git
cd eddm
```

### 2. Install Dependencies

```bash
composer install
```

If you use frontend assets (like Tailwind/CSS via Filament):

```bash
npm install && npm run build
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Update the `.env` file with your database credentials and other required settings.

---

## 🧱 Database Migration

```bash
php artisan migrate
```

> Note: No seeders are included. Use Laravel Tinker to create the first SuperAdmin:

```bash
php artisan tinker
```

```php
$user = \App\Models\User::create([
    'name' => 'Super Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
]);

$user->assignRole('SuperAdmin');
```

Then log in as the SuperAdmin to create dispensers, medicines, users, and orders.

---

## 📡 API Endpoints for Raspberry Pi

> These endpoints use **Bearer Token Authentication**

### ➤ Get the next order

```http
GET /api/next-order
Authorization: Bearer <token>
```

### ➤ Mark order as dispensed

```http
POST /api/dispense
Content-Type: application/json
Authorization: Bearer <token>

{
  "order_id": 3
}
```

---

## 🧩 Role-Based Access

- **SuperAdmin**: Full access to everything
- **Admin**: Can manage dispensers, medicines, users, and orders
- **User**:
  - Can only view and manage their own orders (before payment)
  - Cannot edit status or payment after order is marked paid
  - Cannot delete orders

---

## 💡 Notes

- Users see only their orders in the dashboard
- Quantity is validated against stock both while creating and editing orders
- Clean error handling with Filament notifications
- Payment status and status fields are protected from modification by regular users

---

## 📌 To-Do / Ideas

- Add Webhook or WebSocket support for real-time sync with Raspberry Pi
- Enable QR code generation for order verification
- Optional Stripe or Razorpay integration for online payments
- Auto-dispenser assignment based on availability

---

## 👨‍💻 Developed By

**Raman Teja**  
[github.com/ramanteja](https://github.com/ramanteja)

Made with ❤️ using Laravel & Filament
