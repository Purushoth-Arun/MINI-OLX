# Mini OLX - Buy and Sell Platform

A simple buy and sell platform built with PHP, MySQL, HTML, and JavaScript.

## Features

- User registration and authentication
- Product listing and management
- Product search and filtering
- Wishlist functionality
- Purchase system
- Messaging between buyers and sellers
- Responsive design
- Modern UI with Bootstrap 5

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- mod_rewrite enabled (for Apache)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/mini-olx.git
cd mini-olx
```

2. Create a MySQL database and import the schema:
```bash
mysql -u your_username -p your_database_name < database/schema.sql
```

3. Configure the database connection:
   - Open `config/database.php`
   - Update the database credentials:
     ```php
     $host = 'localhost';
     $dbname = 'your_database_name';
     $username = 'your_username';
     $password = 'your_password';
     ```

4. Set up the web server:
   - Point your web server's document root to the project directory
   - Ensure the `uploads` directory is writable:
     ```bash
     chmod 777 uploads
     ```

5. Access the application through your web browser:
```
http://localhost/mini-olx
```

## Project Structure

```
mini-olx/
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── main.js
├── config/
│   └── database.php
├── database/
│   └── schema.sql
├── uploads/
├── add_product.php
├── add_wishlist.php
├── dashboard.php
├── index.php
├── login.php
├── logout.php
├── product.php
├── products.php
├── register.php
├── remove_wishlist.php
└── README.md
```

## Usage

1. Register a new account or login with existing credentials
2. Browse products on the home page or products page
3. Use filters to find specific products
4. View product details and contact sellers
5. Add products to your wishlist
6. Purchase products
7. Manage your products and wishlist from the dashboard

## Security Features

- Password hashing
- SQL injection prevention using prepared statements
- XSS prevention using htmlspecialchars
- Session management
- File upload validation
- Input validation

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- Bootstrap 5 for the UI framework
- Font Awesome for icons
- PHP PDO for database operations 