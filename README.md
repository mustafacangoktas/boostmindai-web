# BoostMindAI - AI-Powered Motivational Chat

[![MVP Stage](https://img.shields.io/badge/status-MVP--Stage-blueviolet)]()
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.0-8892BF.svg)](https://php.net/)
[![MySQL](https://img.shields.io/badge/mysql-%3E%3D5.7-4479A1.svg)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/bootstrap-v5.3-7952B3.svg)](https://getbootstrap.com/)
[![JavaScript](https://img.shields.io/badge/javascript-ES6-F7DF1E.svg)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=flat&logo=html5&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/HTML5)
[![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=flat&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)

BoostMindAI is an AI-powered web application designed to provide motivational support and engage users in empathetic
conversations. This project was developed as a final project for the BTK Academy Web Programming Workshop, utilizing
core web technologies like HTML, CSS, JavaScript, pure PHP, Bootstrap, and MySQL.

## About The Project

The primary goal of BoostMindAI is to offer a platform where users can receive uplifting messages and interact with an
AI assistant that understands and responds to their emotional state. The application leverages the Groq API for its AI
chat capabilities, providing humorous, sassy, and emotionally intelligent responses to cheer users up. This project is
currently in the MVP stage, focusing on core functionalities and user experience.

This project demonstrates proficiency in:

* Backend development with pure PHP.
* Database management with MySQL.
* Frontend design with HTML, CSS, and Bootstrap.
* Client-side interactivity with JavaScript.
* API integration (Groq for AI, Google reCAPTCHA for security).
* Implementing core web application features such as user authentication, session management, internationalization (
  i18n), and CRUD operations.

This project was a part of the **BTK Academy Web Programming Workshop**, where the fundamentals of web development were
taught, starting from HTML, CSS, and JS, and progressing to PHP and MySQL.

## Live Demo & Screenshots

* **Live Demo:** [https://boostmindai.mustafacan.dev]
* **Screenshots:**
    * [Add a brief description of screenshot 1 here and link to the image file or embed it]
    * `![Screenshot 1 Alt Text](path/to/your/screenshot1.png)`
    * [Add a brief description of screenshot 2 here and link to the image file or embed it]
    * `![Screenshot 2 Alt Text](path/to/your/screenshot2.png)`
    * (Add more as needed)

## Features

* **User Authentication:** Secure registration and login system with password hashing and "Remember Me" functionality.
* **AI Chat Interface:** Real-time chat with an AI assistant powered by the Groq API.
* **Motivational Support:** AI responses are tailored to be uplifting, humorous, and empathetic, based on custom
  prompts.
* **Chat History:** Users can view their past conversations, organized by date.
* **Message Management:**
    * Delete entire chat conversations for a specific date.
    * Regenerate AI responses for specific messages.
* **Favorite Messages:** Users can mark messages as favorites (up to 30).
* **Internationalization (i18n):** Supports multiple languages (English, Turkish, German, French) with dynamic content
  loading.
* **User Settings:** Users can update their name and password.
* **Timezone Detection:** Automatically detects and sets the user's timezone.
* **Responsive Design:** User interface built with Bootstrap for compatibility across various devices.
* **Security:** Implements Google reCAPTCHA for form protection.
* **Legal Pages:** Includes Terms of Service, Privacy Policy, and Cookie Policy pages, available in all supported
  languages.

## Built With

* **Backend:** PHP (Pure)
* **Frontend:** HTML5, CSS3, JavaScript (ES6)
* **Framework/Library:** Bootstrap 5.3
* **Database:** MySQL
* **AI API:** Groq API
* **Security:** Google reCAPTCHA v2
* **Icons:** Feather Icons

## Project Structure

```
.
├── assets/              # Static assets (CSS, JS, images)
├── config.php           # Application configuration
├── core/                # Core application files
│   ├── controllers/     # Controller classes
│   ├── Database.php     # Database connection manager
│   ├── Router.php       # URL routing system
│   └── services/        # Service classes
├── includes/            # Reusable UI components
├── index.php            # Application entry point
├── lang/                # Language translation files
├── pages/               # Page templates and API endpoints
│   ├── api/             # API endpoints
│   └── legal_contents/  # Legal pages in different languages
├── prompts/             # AI system prompts
└── routes.php           # Route definitions
```

## Getting Started

To get a local copy up and running, follow these simple steps.

### Prerequisites

* PHP >= 8.0
* MySQL >= 5.7
* A web server (e.g., Apache, Nginx)
* Composer (optional, but recommended for managing dependencies if you extend the project)
* Access to Groq API and Google reCAPTCHA keys.

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/mustafacangoktas/boostmindai-web.git
   cd boostmindai-web
   ```

2. **Database Setup:**
    * Create a MySQL database for the project.
    * Import the database schema. The schema is implicitly defined by the table creation queries in `core/Database.php`
      within the `initialize()` method. You will need to run these queries manually or adapt the `initialize()` method
      to run only once if the tables don't exist. The tables created are: `users`, `user_tokens`, `chat_messages`,
      `favorite_messages`.

3. **Configure the application:**
    * Rename `config.php.template` (or create `config.php` based on the template below) to `config.php`.
    * Update `config.php` with your database credentials, Google reCAPTCHA keys, and Groq API keys.

4. **Set up your web server:**
    * Point your web server's document root to the project's public directory (which is the root directory of this
      project, as it uses a front controller pattern with `index.php`).
    * Ensure URL rewriting is enabled if you are using Apache (an `.htaccess` file might be needed for clean URLs,
      though the current router seems to handle paths directly).

5. **Permissions:**
    * Ensure your web server has write permissions for session storage if applicable, and any directories where files
      might be uploaded or logs written (though this project doesn't seem to have explicit file uploads beyond session
      data).

## Configuration

The main configuration file is `config.php`. You need to create this file in the root directory of the project.

**`config.php` Template:**

```php
<?php
return [
    // MySQL database connection settings
    'DB_HOST' => 'localhost',         // Database host (e.g., localhost)
    'DB_NAME' => 'your_db_name',      // Database name
    'DB_USER' => 'your_db_user',      // Database username
    'DB_PASS' => 'your_db_password',  // Database password

    // Google reCAPTCHA keys (get from https://www.google.com/recaptcha/admin)
    'RECAPTCHA_SITE_KEY' => 'your_recaptcha_site_key',
    'RECAPTCHA_SECRET_KEY' => 'your_recaptcha_secret_key',

    // Groq API keys
    'GROQ_API_KEYS' => [
        'your_groq_api_key_1',
        'your_groq_api_key_2', // You can add multiple keys
        // Add more Groq API keys here
    ]
];
```

**Key Configuration Options:**

* `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`: Your MySQL database connection details.
* `RECAPTCHA_SITE_KEY`, `RECAPTCHA_SECRET_KEY`: Your Google reCAPTCHA v2 API keys.
* `GROQ_API_KEYS`: An array of your Groq API keys. The application will randomly select one for each request.

## Usage

Once the application is set up and configured:

1. Navigate to the application URL in your web browser.
2. You can explore the homepage, or register for a new account / log in if you have one.
3. After logging in, you will be redirected to the chat interface.
4. Start typing your messages to interact with BoostMindAI.
5. Use the sidebar to navigate through chat history, view favorites, or access settings.

The AI's personality and response style are defined in `prompts/responder_prompts.txt`.

## Contributing

Contributions are what make the open-source community such an amazing place to learn, inspire, and create. Any
contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also
simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

Distributed under the MIT License. See `LICENSE` file for more information.

## Contact

Mustafa Can - [mustafacan.dev](https://mustafacan.dev) - [contact@mustafacan.dev](mailto:contact@mustafacan.dev)

Project Link: [https://github.com/mustafacangoktas/boostmindai-web](https://github.com/mustafacangoktas/boostmindai-web)