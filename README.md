# TRADICARE - SALES MANAGEMENT AND APPOINTMENT BOOKING SYSTEM

=======================================================================
                        TRADICARE SYSTEM
=======================================================================

Project Name: Tradicare
Student Name: [KHAIRUL ANWAR BIN AHMAD SAYUTHI]
Matric No: [DI220146]
Course: [BIT]
Submission Date: [15-06-2025]

-----------------------------------------------------------------------
                        TABLE OF CONTENTS
-----------------------------------------------------------------------
1. INTRODUCTION
2. SYSTEM REQUIREMENTS
3. INSTALLATION GUIDE
4. SYSTEM FEATURES
5. USER GUIDE
   5.1 Customer Features
   5.2 Admin Features
6. DATABASE STRUCTURE
7. TECHNICAL IMPLEMENTATION
8. TESTING CREDENTIALS
9. KNOWN ISSUES
10. FUTURE ENHANCEMENTS

-----------------------------------------------------------------------
                        1. INTRODUCTION
-----------------------------------------------------------------------
Tradicare is a comprehensive web application for traditional medicine and wellness services. The system allows customers to book appointments for various services, purchase traditional medicine products, and manage their orders and appointments. The admin side provides tools for managing products, services, appointments, and customer data.

-----------------------------------------------------------------------
                        2. SYSTEM REQUIREMENTS
-----------------------------------------------------------------------
- PHP 8.0 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer
- Web browser (Chrome, Firefox, Safari, Edge)
- XAMPP/WAMP/MAMP for local development

-----------------------------------------------------------------------
                        3. INSTALLATION GUIDE
-----------------------------------------------------------------------
1. Install XAMPP/WAMP/MAMP on your computer
2. Clone or extract the project files to the htdocs folder
3. Create a database named 'tradicare' in phpMyAdmin
4. Import the database file from the project folder (if provided)
5. Configure the .env file with your database credentials
6. Open command prompt/terminal in the project directory
7. Run the following commands:
   - composer install
   - php artisan key:generate
   - php artisan migrate --seed (if database not imported)
   - php artisan serve (optional, if not using XAMPP/WAMP/MAMP)
8. Access the application at https://tradicare.systems/ or http://127.0.0.1:8000

-----------------------------------------------------------------------
                        4. SYSTEM FEATURES
-----------------------------------------------------------------------
Customer Features:
- User registration and authentication
- Profile management
- Address management
- Product browsing and purchasing
- Service browsing and appointment booking
- Order tracking
- Appointment management (scheduling, rescheduling, cancellation)
- Payment processing

Admin Features:
- Dashboard with analytics
- Product management
- Service management
- Order management
- Appointment management
- Customer management
- Report generation
- Appointment time slot management

-----------------------------------------------------------------------
                        5. USER GUIDE
-----------------------------------------------------------------------
5.1 Customer Features

Registration and Login:
- Navigate to the homepage and click on 'Register'
- Fill in the required details and submit
- Login using your email and password

Browsing Products:
- Click on 'Shop' in the navigation menu
- Browse products by category or search
- Click on a product to view details
- Add products to cart

Making Purchases:
- View your cart by clicking the cart icon
- Adjust quantities or remove items as needed
- Proceed to checkout
- Select delivery address
- Complete payment

Booking Appointments:
- Click on 'Make Appointment' in the navigation menu
- Select a service
- Choose an available date and time slot
- Provide contact information
- Complete payment if required

Managing Profile:
- Click on your profile icon
- Update personal information
- Change password
- Add or edit addresses

Viewing Orders and Appointments:
- Navigate to 'My Orders' or 'My Appointments' in your profile
- View status of orders and appointments
- Cancel orders or appointments if needed

5.2 Admin Features

Accessing Admin Panel:
- Login with admin credentials
- You will be redirected to the admin dashboard

Managing Products:
- Navigate to 'Products' in the admin menu
- Add, edit, or delete products
- Update product status, price, and inventory

Managing Services:
- Navigate to 'Services' in the admin menu
- Add, edit, or delete services
- Update service status and pricing

Managing Orders:
- View all orders in the 'Orders' section
- Update order status (To Pay, To Ship, To Receive, Completed, Cancelled)
- View order details

Managing Appointments:
- View all appointments in the 'Appointments' section
- Update appointment status
- Manage available time slots

Generating Reports:
- Navigate to 'Reports' in the admin menu
- Select report type
- Set date range if applicable
- Generate and download reports

-----------------------------------------------------------------------
                        6. DATABASE STRUCTURE
-----------------------------------------------------------------------
The system uses a relational database with the following main tables:

- users: Stores user information and authentication details
- products: Contains product information
- services: Contains service information
- carts: Stores user shopping cart data
- cart_items: Contains items in user carts
- orders: Stores order information
- order_items: Contains items in orders
- appointments: Stores appointment information
- available_times: Contains available time slots for appointments
- unavailable_times: Contains blocked time slots
- payments: Stores payment information
- locations: Stores user address information
- tracking: Stores order tracking information

-----------------------------------------------------------------------
                        7. TECHNICAL IMPLEMENTATION
-----------------------------------------------------------------------
The Tradicare system is built using the following technologies:

- Laravel PHP Framework
- MySQL Database
- Bootstrap for frontend styling
- JavaScript/jQuery for client-side functionality
- AJAX for asynchronous requests
- Payment gateway integration

The application follows the MVC (Model-View-Controller) architecture:
- Models: Represent database tables and relationships
- Views: Blade templates for rendering HTML
- Controllers: Handle user requests and business logic

-----------------------------------------------------------------------
                        8. TESTING CREDENTIALS
-----------------------------------------------------------------------
Customer Account:
- Email: anwarsayuthi@gmail.com
- Password: 101010

Admin Account:
- Email: tradicare@gmail.com
- Password: 123456

-----------------------------------------------------------------------
                        9. KNOWN ISSUES
-----------------------------------------------------------------------
- [List any known issues or limitations of the system]

-----------------------------------------------------------------------
                        10. FUTURE ENHANCEMENTS
-----------------------------------------------------------------------
- Mobile application development
- Integration with more payment gateways
- Enhanced reporting and analytics
- Customer loyalty program
- Multi-language support
- Dark mode theme option

=======================================================================
                        END OF DOCUMENT
=======================================================================