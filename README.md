project/
│
├── app/
│   ├── Models/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   └── LogoutController.php
│   │   │   │
│   │   │   ├── Client/
│   │   │   │   ├── ClientDashboardController.php
│   │   │   │   ├── ClientLoanController.php
│   │   │   │   └── ClientPaymentController.php
│   │   │   │
│   │   │   ├── Admin/
│   │   │   │   ├── AdminDashboardController.php
│   │   │   │   ├── AdminLoanController.php
│   │   │   │   ├── AdminLoanRequestController.php
│   │   │   │   ├── AdminPaymentController.php
│   │   │   │   └── AdminUserController.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── RoleMiddleware.php
│   │   │   └── EnsureClient.php
│   │   │   └── EnsureAdmin.php
│   │   │
│   │   └── Requests/
│   │
│   └── Services/
│       ├── LoanService.php
│       ├── PaymentService.php
│       └── LoanRequestService.php
│
├── routes/
│   ├── web.php
│   ├── auth.php
│   ├── client.php        // Routes espace client
│   └── admin.php         // Routes espace admin
│
├── resources/
│   ├── views/
│   │   ├── client/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── loans/
│   │   │   ├── payments/
│   │   │   └── profile/
│   │   │
│   │   ├── admin/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── loans/
│   │   │   ├── requests/
│   │   │   ├── users/
│   │   │   └── payments/
│   │   │
│   │   └── auth/
│   │       ├── login.blade.php
│   │       └── register.blade.php
│
└── database/
