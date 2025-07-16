# 📰 Simple News Portal

![Project Status](https://img.shields.io/badge/Status-Completed%20(With%20Login)-brightgreen)
![HTML5](https://img.shields.io/badge/-HTML5-333333?style=flat&logo=HTML5)
![CSS3](https://img.shields.io/badge/-CSS-333333?style=flat&logo=CSS3&logoColor=1572B6)
![JavaScript](https://img.shields.io/badge/-JavaScript-333333?style=flat&logo=javascript)
![PHP](https://img.shields.io/badge/-PHP-333333?style=flat&logo=php&logoColor=777BB4)
![MySQL](https://img.shields.io/badge/-MySQL-333333?style=flat&logo=mysql&logoColor=4479A1)
![Git](https://img.shields.io/badge/-Git-333333?style=flat&logo=git&logoColor=F05032)
![GitHub](https://img.shields.io/badge/-GitHub-333333?style=flat&logo=github&logoColor=181717)

A news portal developed with PHP and MySQL, focused on demonstrating Fullstack web development skills. The project includes a public interface for viewing news and a **secure administrative panel (CRUD)** for content management.

---

## 🚀 Technologies Used

This project was built using the following technologies and tools:

* **Frontend:**
    * `HTML5`: Semantic structure for the pages.
    * `CSS3`: Styling and responsive layout.
    * `JavaScript`: (Can be added for future interactions, form validations, or dynamic functionalities.)
* **Backend:**
    * `PHP`: Programming language for server-side logic and database interaction.
    * `MySQL`: Relational database management system for storing news and administrative user data.
* **Version Control:**
    * `Git`: Distributed version control system.
    * `GitHub`: Code hosting platform for version control and collaboration.
* **Development Environment:**
    * `XAMPP`/`WAMP`/`MAMP`: For setting up the Apache server and MySQL locally.

---

## ✨ Features

The news portal offers the following functionalities:

### Public Area (Frontend)
* **News Listing:** Displays the latest news on the homepage, ordered by publication date.
* **News Detail Page:** Allows viewing the full content of a news article by clicking on its title or the "Read More" button.
* **Responsive Design:** Adaptive layout for different screen sizes (desktop, tablet, mobile).

### Administrative Area (Backend - CRUD)
* **Authentication System:**
    * **Login:** Secure access page with username and password for administrators.
    * **Logout:** Functionality to end the administrative session.
    * **Route Protection:** All pages within the administrative area are protected, requiring login for access.
* **News Management (CRUD):**
    * **View/List:** Displays all registered news articles in a table format.
    * **Add:** Form to create new news articles, including title, subtitle, content, author, and featured image upload.
    * **Edit:** Form to update information of existing news articles, with the option to change or remove the featured image.
    * **Delete:** Functionality to permanently remove news articles from the database and their respective image files.
* **Status Messages:** Visual feedback for successful operations (add, edit, delete) or errors.

---

## 🛠️ How to Run the Project Locally

Follow these steps to set up and run the project on your machine:

### Prerequisites
* A web server with PHP (preferably PHP 7.4+ or 8.x).
* MySQL Server.
* Tools like `XAMPP`, `WAMP`, or `MAMP` simplify the installation of Apache, PHP, and MySQL.

### Setup
1.  **Clone the Repository:**
    ```bash
    git clone [https://github.com/your-username/news-portal.git](https://github.com/your-username/news-portal.git)
    ```
    Navigate into the project directory:
    ```bash
    cd news-portal
    ```

2.  **Configure the Web Environment:**
    * Move the `news-portal` folder to your web server's document root directory (`htdocs` for XAMPP/MAMP, `www` for WAMP).

3.  **Database Configuration (MySQL):**
    * Open your MySQL manager (e.g., phpMyAdmin, MySQL Workbench, or terminal).
    * Create a new database. For example: `site_noticias_db`.
        ```sql
        CREATE DATABASE site_noticias_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        ```
    * Select the `site_noticias_db` database and execute the following SQL scripts to create the `noticias` and `usuarios` tables:
        ```sql
        -- News Table
        CREATE TABLE noticias (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255) NOT NULL,
            subtitulo VARCHAR(255),
            conteudo TEXT NOT NULL,
            imagem_destaque VARCHAR(255),
            data_publicacao DATETIME DEFAULT CURRENT_TIMESTAMP,
            autor VARCHAR(100),
            slug VARCHAR(255) UNIQUE NOT NULL
        );

        -- Users Table (for the administrative area)
        CREATE TABLE usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome_usuario VARCHAR(50) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            email VARCHAR(100),
            nivel_acesso ENUM('admin', 'editor') DEFAULT 'editor'
        );
        ```
    * **Insert an Admin User:**
        To test the login, you'll need a user. **Never insert the password in plain text directly into the database.** Use PHP to generate the password hash (e.g., `password_hash("yourpassword", PASSWORD_DEFAULT)`).
        Example of how to generate the hash (create a temporary PHP file and run it in your browser):
        ```php
        <?php echo password_hash("admin123", PASSWORD_DEFAULT); ?>
        ```
        With the generated hash, insert the user:
        ```sql
        INSERT INTO usuarios (nome_usuario, senha, email, nivel_acesso) VALUES
        ('admin', 'PASTE_YOUR_GENERATED_HASH_HERE', 'admin@example.com', 'admin');
        ```
    * (Optional) Insert some sample news articles:
        ```sql
        INSERT INTO noticias (titulo, subtitulo, conteudo, imagem_destaque, autor, slug) VALUES
        ('Brazilian Researchers Unravel Amazon Rainforest Secrets with AI', 'New technological approach promises to revolutionize environmental preservation and biodiversity studies.', 'A multidisciplinary team of Brazilian researchers...', 'public/uploads/amazon-ai.jpg', 'John Doe', 'brazilian-researchers-amazon-ai');
        ```

4.  **Configure the Database Connection File (`includes/database.php`):**
    * Open the `news-portal/includes/database.php` file.
    * Adjust database credentials if they differ from the default (`root`, empty password).
    * **ADD SESSION INITIALIZATION:** Ensure that `session_start()` is included securely, preferably within a check to prevent multiple session starts:
        ```php
        <?php
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', 'root'); // Your MySQL username
        define('DB_PASSWORD', '');     // Your MySQL password
        define('DB_NAME', 'site_noticias_db'); // The database name you created

        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        $conn->set_charset("utf8mb4");

        // Ensures the session is started only once
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // It's good practice to omit the closing tag ?> in files that only contain PHP code.
        ```

5.  **Create the Uploads Folder:**
    * Manually create a folder named `uploads` inside `news-portal/public/`.
    * Ensure this folder has write permissions for your web server (usually not an issue in local development environments).

6.  **Create Authentication Files (`admin/login.php`, `admin/logout.php`, `admin/verificar_login.php`):**
    * Make sure these files exist in your `admin/` folder with the code provided in the previous login implementation steps.

7.  **Protect Administrative Pages:**
    * In **all PHP files within the `admin/` folder and its subfolders** (e.g., `admin/index.php`, `admin/adicionar.php`, `admin/editar.php`, `admin/excluir.php`), add the following lines at the very top:
        ```php
        <?php
        require_once '../includes/database.php'; // Ensures connection and session
        require_once 'verificar_login.php';    // Redirects if not logged in
        // ... (rest of the page code) ...
        ```
        **Pay attention to `../` paths** if the PHP file is in a subfolder (e.g., `admin/products/index.php` would need `../../includes/database.php` and `../verificar_login.php`).

### Accessing the Project
* **Public Site:** Open your browser and go to `http://localhost/news-portal/`
* **Administrative Area (with Login):** Go to `http://localhost/news-portal/admin/`. You will be redirected to the login page. Use the username and password you inserted into the database.

---

## 🎯 Future Improvements (Roadmap)

This project serves as a solid foundation. Future enhancements could include:

* **Pagination:** Add pagination for news listings, both on the frontend and backend, to handle large volumes of data.
* **News Categories:** Implement functionality to categorize news articles and allow filtering by category.
* **Search System:** Add a search bar to find news by title or content.
* **UI/UX Enhancements:** Refine the portal's design (frontend and admin panel) using a CSS framework (like Bootstrap or Tailwind CSS) and add more JavaScript interactions.
* **Client-Side Form Validation:** Implement client-side form validation using JavaScript for a better user experience.
* **Commenting System:** Allow users to comment on news articles.
* **Access Levels:** Expand the `access_level` functionality to control permissions for different types of admin users (e.g., editor can only create/edit, but not delete).

---

## 👨‍💻 Author

* **Mika** - [LinkedIn](https://www.linkedin.com/in/gustavo-ev) | [Portfolio](https://gustavoevieira.github.io/portfolio/) | [GitHub](https://github.com/gustavoevieira)

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
