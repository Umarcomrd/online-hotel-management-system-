# BlueWave Hotel Management System

A web-based hotel management system built with PHP and MySQL, allowing users to browse rooms, make bookings, and submit complaints — with a dedicated admin panel for managing the hotel's operations.

## Features

- **User Registration & Login** — secure account creation and authentication for guests
- **Admin Panel** — separate authenticated area for hotel staff to manage the system
- **Room Management** — add, edit, and display rooms with image uploads
- **Booking System** — combined date and guest selection form for reserving rooms
- **Complaint Submission** — guests can submit complaints/feedback directly through the platform

## Tech Stack

- **Backend:** PHP (PDO for database access)
- **Database:** MySQL
- **Server:** Apache via XAMPP
- **Frontend:** HTML, CSS (custom navy & gold design system)
- **Fonts:** Playfair Display, Jost

## Getting Started

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) installed (Apache + MySQL + PHP)

### Installation

1. Clone the repository into your XAMPP `htdocs` folder:
   ```bash
   git clone https://github.com/Umarcomrd/online-hotel-management-system-.git
   ```

2. Move/rename the project into your XAMPP web root if needed:
   ```
   /opt/lampp/htdocs/hotel/
   ```

3. Start Apache and MySQL from the XAMPP control panel.

4. Create a database named `hotel` in phpMyAdmin and import the provided SQL file (if included).

5. Update database credentials in the config file:
   ```
   confiq.php
   ```

6. Visit the project in your browser:
   ```
   http://localhost/hotel/
   ```

## Admin Access

Admin registration requires a secret access code to prevent unauthorized signups. Contact the project maintainer for the current code.

## Project Structure

```
hotel/
├── admin/          # Admin panel (protected by auth.php session guard)
├── uploads/         # Room images
├── booking_form.php # Combined date/guest booking form
├── confiq.php        # Database configuration
└── ...
```

## License

This project is for educational purposes.

## Author

Umar
