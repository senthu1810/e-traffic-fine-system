echo "# E-Traffic Fine System

# 🚦 E-Traffic Fine System

The E-Traffic Fine System is a full-stack web application designed to digitize traffic fine management. It allows police officers to issue fines, users to view and pay them, and admins to manage everything efficiently.

## 📌 Features

### 👤 User Dashboard
- View pending and paid fines
- Pay fines online
- Submit complaints with evidence
- Track complaint status

### 👮 Police Dashboard
- Issue new fines to vehicle owners

### 🛠️ Admin Dashboard
- Manage users, officers, and violations
- Assign vehicles to users
- Track fines and payments
- View complaints and mark as resolved

## 📂 Tech Stack

- **Frontend**: HTML, CSS, Bootstrap 5, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Server**: XAMPP (Apache, MySQL, PHP)

## 🗃️ Database Tables

- `users`
- `police_officers`
- `vehicles`
- `fines`
- `payments`
- `complaints`
- `violations`

## 🔐 Authentication

- User and police login with secure password (hashed)
- Admin protected panel
- Session-based login with role checks

## ⚠️ Important Notes

- File uploads (images/videos) stored in `/uploads/`
## 🛠️ Setup Instructions

1. Clone the repo:
   ```bash
   git clone https://github.com/YOUR_USERNAME/e-traffic-fine-system.git
2.	Move the folder to your htdocs in XAMPP:
3.	C:\xampp\htdocs\e-traffic-fine-system
4.	Import the MySQL database (database.sql) using phpMyAdmin
5.	Update php/config.php with your local DB credentials.
6.	Start Apache and MySQL from XAMPP and go to:
7.	http://localhost/e-traffic-fine-system/

🧑💻 Author
•	Developed by Rasaiah Senthuran (HND- IT )  SLIATE

📃 License
This project is for educational purposes. No license applied.

echo "# E-Traffic Fine System
