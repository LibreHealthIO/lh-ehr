# Welcome to LibreHealth EHR!!!

LibreHealth EHR is a free and open-source electronic health records and
medical practice management application. 

The mission of LibreHealth is to help provide high quality medical care to all people, regardless of race, socioeconomic status or geographic location, by giving medical practices and clinics across the globe access to medical software, free of charge.

That same software is designed to save clinics both time and money, which gives practitioners more time to spend with individual patients, thereby affording patients with higher quality care.

We are current and former contributors to OpenEMR and thank that community for years of hard work. We intend to honor that legacy by allowing this new community to leverage the good things in OpenEMR, share what we create and not be afraid to break backward compatibility in the name of forward progress and modern development models.

We are collaborating closely with the LibreHealth Project [LibreHealth.io](http://LibreHealth.io), an umbrella organization for health IT projects with similar goals.

Our project is primarily licensed under Mozilla Public License Version 2.

Code inherited from OpenEMR is licensed under GPL 2 or higher.

The project is part of the Software Freedom Conservancy family [sfconservancy.org](http://sfconservancy.org)
 
***Thank you for your support!***


# Contributing code
Code contributions are very welcome! Browse the [Issue tracker](https://github.com/LibreHealthIO/LibreEHR/issues) for issues that need code and/or come up with your own ideas & code. Please open a [Pull Request](https://github.com/LibreHealthIO/LibreEHR/pulls) to contribute your own code.

## Local Development

For detailed and step-by-step Installation Instructions refer [Installation Instructions](/INSTALL.md)

## Windows :: 

Firstly make sure that you have [WAMP](http://www.wampserver.com/en/) or [XAMPP](https://www.apachefriends.org/index.html) server installed and the time zone is set correctly.

Make the following changes in `php.ini` file. You can find the `php.ini` file by looking at the following destination :
* In case of WAMP :
`C:/WAMP/BIN/PHP/php.ini` OR (left click )  wampmanager icon -> PHP -> php.ini
* In  case of XAMPP:
`C:\xampp\php\php.ini.`.
In Linux it located in
`/etc/php/7.0/php.ini` or similar

Make the following changes in your php.ini file :
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

Make sure you have disabled strict mode in Mysql . 

## How to disable Mysql strict mode?

Make the following changes in the `my.ini/my.cnf`:
Find it here `C:\WAMP\BIN\MYSQL\MySQL Server 5.6\my.ini` OR `C:\xampp\mysql\bin\my.ini` 
OR (left click ) wampmanager icon -> MYSQL -> my.ini
In Linux it's typically located in /etc/mysql

    1.  Look for the following line:
        sql-mode = STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION
        or sometimes it maybe  sql_mode

    2.  Change it to:
        sql-mode="" (Blank)

    3. Restart the MySQL service.
    

Restart WAMPP/XAMPP Server.

You can fork & clone the repository for local development. To get started you need to:
 - Clone the repository
 - Run index.php file which then redirects to setup page! Follow the instructions and you are done!!
 
Sometimes , installation may take more time than usual on some systems. In that case, you would need to increase `max_execution_time` in your php.ini file and then restart your server.




