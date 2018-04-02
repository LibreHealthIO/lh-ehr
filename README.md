# LibreHealth EHR

LibreHealth EHR is a free and open-source electronic health records and medical practice management application.

The mission of LibreHealth is to help provide high quality medical care to all people, regardless of race, socioeconomic status, or geographic location, by providing medical practices and clinics across the globe access to free of charge medical software. That same software is designed to save clinics both time and money, which gives practitioners more time to spend with individual patients, thereby supplying patients with higher quality care.

We are current and former contributors to OpenEMR and thank that community for years of hard work. We intend to honor that legacy by allowing this new community to leverage the good things in OpenEMR, share what we create and not be afraid to break backward compatibility in the name of forward progress and modern development models.

We are collaborating closely with the [LibreHealth Project](http://LibreHealth.io), an umbrella organization for health IT projects with similar goals.

# Contributing code

Code contributions are very welcome! We encourage newcomers to browse the [issue tracker](https://github.com/LibreHealthIO/LibreEHR/issues) for open issues and/or if you have found a bug in LibreEHR, please [create a new issue](https://github.com/LibreHealthIO/LibreEHR/issues/new) for same. You may open a [pull request](https://github.com/LibreHealthIO/LibreEHR/pulls) to contribute your code to an issue, from your fork of the [LibreEHR repository](https://github.com/LibreHealthIO/LibreEHR).

# Installation

For detailed, step-by-step instructions, refer to [Installation Instructions](/INSTALL.md)

## On Windows:

1. First off, make sure that you have the [WAMP](http://www.wampserver.com/en/) or [XAMPP](https://www.apachefriends.org/index.html) server installed and that the time zone is set correctly.

2. Make the following changes in `php.ini` file. You can find the `php.ini` file by looking at the following destination:
..* In the case of WAMP :
`C:/WAMP/BIN/PHP/php.ini` OR (left click)  wampmanager icon -> PHP -> php.ini
..* In the case of XAMPP:
`C:\xampp\php\php.ini.`.
In Linux, it is located in:
`/etc/php/7.0/php.ini` or something similar.

Make the following changes in your php.ini file:
(Search for the following and make necessary changes)

```
max_execution_time = 600
max_input_time = 600
max_input_vars = 3000
memory_limit = 512M
post_max_size = 32M
upload_max_filesize = 32M
session.gc_maxlifetime = 14400
short_open_tag = On
display_errors = Off
upload_tmp_dir is set to a correct default value that will work on your system
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE
```

3. Make sure you have disabled strict mode in Mysql.

### How to disable Mysql strict mode?

Make the following changes in the `my.ini/my.cnf`:
Find it here `C:\WAMP\BIN\MYSQL\MySQL Server 5.6\my.ini` OR `C:\xampp\mysql\bin\my.ini`
OR (left click ) wampmanager icon -> MYSQL -> my.ini
In Linux it's typically located in /etc/mysql

    1.  Look for the following line:
        sql-mode = STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION
        or sometimes it maybe sql_mode

    2.  Change it to:
        sql-mode="" (Blank)

    3. Restart the MySQL service.


4. Restart WAMPP/XAMPP Server.

(XAMPP)
 If you don't find this parameter (sql-mode/sql_mode) in the my.ini file, you should run the server, open http://localhost/phpmyadmin/, click on the "variables" tab, search for "sql mode", and then set it to: ""

5. You can fork & clone the repository for local development. To get started you need to:
 - Fork the [LibreEHR repository](https://github.com/LibreHealthIO/LibreEHR).
 - Clone your fork of LibreEHR repository to your local machine. Your fork would be on, as an example, `https://github.com/your-github-username/LibreEHR`
 - Open LibreEHR directory and run index.php file, which will then redirect to the setup page! Follow the [instructions](/INSTALL.md/#windows-setup) and you are done!

Note: Sometimes , installation may take more time than usual on some systems. In that case, you would need to increase `max_execution_time` in your php.ini file and then restart your server.

# License

LibreHealth EHR is primarily licensed under Mozilla Public License Version 2. The code inherited from OpenEMR is licensed under GPL 2 or higher. This project is a part of the [Software Freedom Conservancy](http://sfconservancy.org) family.

***Thank you for your support!***
