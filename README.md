# AstraVPN Enterprise

AstraVPN Enterprise is a modern VPN management platform built with PHP 8, MySQL, OpenVPN/WireGuard support, JWT authentication, and a responsive glassmorphism dashboard.

## Features

- User authentication and subscription management
- Multi-server OpenVPN and WireGuard support
- Admin dashboard with analytics, logs, and server control
- REST API with JWT auth and secure middleware
- Auto-config generation and QR codes
- Responsive dark/light UI with Tailwind-style styling
- Rate limiting, CSRF protection, and secure sessions

## Installation

1. Install XAMPP and start Apache + MySQL.
2. Copy this project into `htdocs/astravpn` or use `d:\vpn1` on your local environment.
3. Run `composer install` in the project root.
4. Copy `.env.example` to `.env` and update database credentials.
5. Import `database/schema.sql` into MySQL.
6. Configure Apache to point document root to `public/`.

## Composer

```bash
composer install
```

## Database

1. Create database `astravpn`.
2. Import `database/schema.sql`.

## Apache

- Enable `mod_rewrite`.
- Set document root to `public`.
- Ensure `.htaccess` is allowed.

## Running

```bash
php -S localhost:8000 -t public
```

## Notes

- Use strong `JWT_SECRET` in `.env`.
- Set `APP_DEBUG=false` in production.
- Place `openvpn` and `wireguard` configuration directories in the configured paths.
