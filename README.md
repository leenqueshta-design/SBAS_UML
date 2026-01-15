Small Business Accounting System (SBAS) - Software Engineering Project

Student Information
Name: Leen Salah Queshta
Student ID: 220221762
Course: Software Engineering



Project Overview
This project represents the Implementation Phase of the SBAS system. While the conceptual design of the system is much larger, the current implementation focuses on the core functional requirements essential for business operations.

The system is built using Object-Oriented PHP and follows a modular architecture to separate business logic from the user interface.



Implemented Use Cases
The following two primary functionalities have been fully implemented:

1. User Authentication (Login System)
Logic: Handles secure user entry by validating credentials against a data store.
Session Management: Uses PHP Sessions to maintain user state across the system.
Files: `LoginForm.php`, `AuthManager.php`, `UserDB.php`.

2. Invoice Creation & Management
Logic: Allows users to create invoices, select customers, and add products with automated calculations.
Tax Engine: A dedicated system to calculate taxes based on different rules (Standard/Reduced).
Files: `InvoiceForm.php`, `InvoiceManager.php`, `TaxSystem.php`, `InvoiceDB.php`.

---

System Architecture
The project is organized into a Layered Architecture to ensure clean code and maintainability:

User Interface Layer: Contains PHP pages and Form classes that render the HTML/CSS for the user (`login.php`, `invoice.php`).
App Logic Layer: Contains the "Managers" that handle the business rules and decision-making (`AuthManager.php`, `InvoiceManager.php`).
Database Layer: Simulates data persistence and retrieval logic (`UserDB.php`, `InvoiceDB.php`).



Technical Highlights
Class Autoloading: Implemented a dynamic `spl_autoload_register` in `config.php` to manage class dependencies automatically across directories.
Routing: Uses an `.htaccess` configuration to handle clean URL routing through `index.php`.
State Persistence: Invoices created during a session are stored and displayed in a "Recent Invoices" dashboard.


Developed as part of the academic requirements for the Software Engineering course.