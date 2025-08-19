
#  <font color="white">staging.humeint.africa  *v1.5*</font> 
<br>

> ## <font color="white">Tech stack</font>: 
>
> ### <ins><font color="white">Frameworks</font></ins>:
> - [Slim Framework v4](https://www.slimframework.com/docs/v4/)
> - [Bootstrap v5 ](https://getbootstrap.com/docs/5.0/getting-started/introduction/)
> - [JQuery v3.7.1](https://api.jquery.com/)
>
> ### <ins> <font color="white">Languages</font></ins>: 
> - [Bash](#)
> - [PHP 8.2](#)
> - [JavaScript](#)
> - [HMTL5](#)
> - [CSS3](#)
>
> ### <ins> <font color="white">Infra</font></ins>: 
> - [Linux Debian 11 “Bullseye”](https://www.debian.org/releases/bullseye/)
> - [Mysql 8.0](https://dev.mysql.com/doc/refman/8.0/en/)
> - [SQLite](https://www.sqlite.org/docs.html)
> - [BitBucket pipelines](https://bitbucket.org/)
> - [DigitalOcean (CDN, S3)](https://docs.digitalocean.com/)
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