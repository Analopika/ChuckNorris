
#  <font color="white">Chuck Norris</font> 
<br>

> ## <font color="white">Tech stack</font>: 
>
> ### <ins><font color="white">Frameworks</font></ins>:
> - [Slim Framework v4](https://www.slimframework.com/docs/v4/)
> - [Bootstrap v5 ](https://getbootstrap.com/docs/5.0/getting-started/introduction/)
> - [JQuery v3.7.1](https://api.jquery.com/)
>
> ### <ins> <font color="white">Languages</font></ins>: 
> - [PHP 8.2](#)
> - [JavaScript](#)
> - [HMTL5](#)
> - [CSS3](#)
>
>

<br>

> ## <font color="white">Project Structure</font>
```
├── /app
│   ├── /Config           # Configuration files (e.g., database)
│   ├── /Middleware        # Middleware for request filtering and processing
│   ├── /Repositories             # Views (Twig)
│   ├── /Routes            # Route definitions
│   └── /Config            # Configuration files (e.g., database, app settings)
│
├── /public                # Publicly accessible files
│   ├── /css               # CSS files
│   ├── /js                # JavaScript files
│   ├── /.htaccess
│   └── /index.php       # Entry point for the application
│
├── /vendor                # Composer dependencies
│
├── .env                   # Environment configuration file (never commit this)
├── .gitignore             # Files and directories to ignore in git
├── .htaccess
├── composer.json          # Composer dependencies
├── composer.lock          # Locked versions of dependencies
├── docker-compose.yaml
├── Dockerfile
├── Makefile               # Makefile
├── README.md              # Project documentation
└── schema.sql              # Database Schema
```