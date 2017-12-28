# Installation Instructions
Last Updated: December 26th, 2017

### Table of Contents

**[1. Overview of Directories](#overview-of-directories)**

**[2A. Windows Installation](#windows-installation)**

**[2B. Linux Installation](#linux-installation)**

**[3. Setup](#setup)**

**[4. Setting up Access Control](#setting-up-access-control)**

**[5. Upgrading](#upgrading)**

**[6. FAQ](#faq)**

## 1. Overview of Directories
<div id='overview-of-directories'/>

NOTE: The most recent documentations can be found on the [LibreHealth](http://librehealth.io/) website.

contrib: Contains many user-contributed encounter forms and utilities

custom: Contains scripts and other text files commonly customized

Documentation: Contains useful documentation

interface: Contains User Interface scripts and configuration

library: Contains scripts commonly included in other scripts

sql: Contains initial database images

gacl: Contains embedded php-GACL (access controls)

## 2A. Windows Installation
<div id='windows-installation'/>

The following instructions are for Windows systems only. For instructions on installing LibreEHR onto Linux systems, please refer to [Section 2B](#linux-installation)

To run LibreEHR on Windows, a compatible version of [XAMPP](https://www.apachefriends.org/index.html) or [WAMP](http://www.wampserver.com/en/) must be installed on your system. Compatible versions are 7.0 and 5.6 (5.6.30 is recommended). Versions 7.1x is not currently supported.

Clone the LibreEHR [repository](https://github.com/LibreHealthIO/LibreEHR) into your computer using a console (eg. [GitBash](https://git-for-windows.github.io/) or [Cmder](http://cmder.net/)).

The cloned repository should be moved into the root folder of the web server you are using. For WAMP, you should put the 'LibreEHR' folder into `\wamp\www\`. For XAMPP, you should put the `LibreEHR` folder into `\XAMPP\htdocs\`.

Point your web-browser to the LibreEHR Setup script. Do this by typing `localhost/libreEHR/setup.php` into the URL bar. A setup script will pop up.

On the first page, Leave the "Site ID:" as default and press continue.

![First Step](./Documentation/1_Installing/images/windows_installation/Step_1.png)

On the second page, make sure that there are no undefined index errors. If there are any, make sure that you have changed the php.ini file and have the correct version of XAMPP or WAMP installed. Press continue.

![Second Step](./Documentation/1_Installing/images/windows_installation/Step_2.png)

Follow the instructions in [Section 3. Setup](#setup).

## 2B. Linux Installation
<div id='linux-installation'/>

The following instructions are for installing LibreEHR onto Linux systems. 

###  Unpacking

The LibreHealthEHR release archive should be named as follows:

`librehealthehr-<version>-release.tar.gz  -or-  librehealthehr-<version>-release.zip`

To extract the archive, use either of the following commands from the command line:

`bash# tar -pxvzf librehealthehr-<version>-release.tar.gz`
`bash# unzip librehealthehr-<version>-release.tar.gz`

Be sure to use the `-p` flag when using tar, as certain permissions must be preserved.

LibreHealthEHR will be extracted into a directory named `librehealthehr`.

Alternatively, you can download the source code directly from the repository located at [librehealthehr](https://github.com/LibreHealthIO/librehealthehr) using

```
git clone https://github.com/LibreHealthIO/LibreEHR librehealthehr
```

###  Setting Up

To run LibreHealthEHR, MariaDB (preferred) or MySQL, and Apache or another PHP-capable web server must be configured.

If you don't already have it, download and install [Apache](www.apache.org); [MariaDB](https://mariadb.org) (prefered) or [MySQL](www.mysql.com); and [PHP.](www.php.net).

**Note:**

1. PHP versions 7.1+ are not supported.

2. MySQL versions 5.7+ have strict mode enabled by default and must be disabled. Instructions on how to disable it are given in the FAQs section.

3. LibreHealthEHR requires a number of web server and PHP features which may not be enabled on your system. These include:
  * PHP Index support (ensure that index.php is in your Index path in httpd.conf)
  * Session variables
  * PHP libcurl support (optional for operation, mandatory for billing)
  
4. Make sure these dependencies are met:
```
apache2
mysql-server (or if using mariadb, then use 'mariadb-server' instead)
libapache2-mod-php
libtiff-tools
php
php-mysql
php-cli
php-gd
php-gettext
php-xsl
php-curl
php-mcrypt
php-soap
php-json
imagemagick
php-mbstring
php-zip
php-ldap
php-xml
```

Copy the LibreHealthEHR folder into the root folder of the web server. On Mandrake Linux, for example, use the command:
  `bash# mv librehealthehr /var/www/html/`

Make sure the web server is running, and point a web-browser to the `setup.php` file, located within the librehealthehr web folder. If you installed LibreHealthEHR in the root web directory, the URL would read: `http://localhost/librehealthehr/setup.php`.

The setup script will step you through the configuration for LibreHealthEHR.
 
The first screen of the setup script will ensure that the web server user (in linux, often is `apache`, `www-data`, or `nobody`) has write privileges on certain files and directories. 

These can be set by using the `chmod a+w filename` command to grant global write permissions to the file. The directories include: 
```

librehealthehr/gacl/admin/templates_c
librehealthehr/sites/default/edi
librehealthehr/sites/default/era
librehealthehr/sites/default/documents
librehealthehr/sites/default/sqlconf.php
librehealthehr/interface/main/calendar/modules/PostCalendar/pntemplates/compiled
librehealthehr/interface/main/calendar/modules/PostCalendar/pntemplates/cache
librehealthehr/interface/modules/zend_modules/config/application.config.php 
```

**Note:** If the web server user name is `apache`, then the command `chown -R apache:apache directory_name` will grant global write permissions to the directories. We recommend making these changes permanent. Should the page display errors related to file or directory writing privileges you may click the 'Check Again' button to try again (after fixing permissions).

## 3. Setup
<div id='setup'/>

The setup.php script will run you through setting up LibreEHR.

#### Step 1
You need to tell setup whether it needs to create the database on its own, or if you have already created the database. MySQL root privileges will be required to create a database.

![Third Step](./Documentation/1_Installing/images/windows_installation/Step_3.png)

#### Step 2
You will be presented with a number of fields which specify the MySQL server details and the `librehealthehr` directory paths.

The `Server Host` field specifies the location of the MySQL server. If you run MySQL and Apache/PHP on the same server, then leave this as `localhost`. If MySQL and Apache/PHP are on separate servers, then enter the IP address (or host name) of the server running MySQL.

The `Server Port` field specifies the port to use when connecting to the MySQL server over TCP/IP. This should be left as `3306` unless you changed it in your MySQL configuration.

The `Database Name` field is the database where LibreHealthEHR will reside. If you selected to have the database created for you, this database will be created, along with the user specified in `Login Name`. If this database exists, setup will not be able to create it, and will return an error. If you selected that you have already created the database, then setup will use the information you provide to connect to the MySQL server. Note that setup will not accept a password that is not at least one (1) character in length.

The `Login Name` field is the MySQL user that will access the LibreHealthEHR database. If you selected to have the database created for you, this user will be created. If you selected that you have already created the database, then setup will use the information you provide to connect to the MySQL server.

The `Password` field is the password of the user entered into the above `Login Name` field. If you selected to have the database created for you, this user and password will be created. If you selected that you have already created the database, then setup will use the information you provide to connect to the MySQL server.

The `Name for Root Account` field will only appear if setup is creating the database. It is the name of the MySQL root account. For localhost, it is usually ok to leave it `root`.

The `Root Pass` field will likewise only appear if setup is creating the database. It is the password of your existing root user, and is used to acquire the privileges to create the new database and user.

The `User Hostname` field will also only appear if setup is creating the database. It is the hostname of the Apache/PHP server from which the user, i.e, `Login Name` is permitted to connect to the MySQL database. If you are setting up MySQL and Apache/PHP on the same computer, then you can use `localhost`. 

The `UTF-8 Collation` field is the collation setting for MySQL. If the language you are planning to use in LibreHealthEHR is in the menu, then you can select it. Otherwise, just select `General`. Choosing `None` is not recommended and will force latin1 encoding.

The `Initial User` is the username of the first user, which is what they will use to login. Limit this to one word only.

The `Initial User Password` is the password of the user entered into the above `Initial User` field.

The `Initial User's First Name` is the value to be used as their first name. This information may be changed in the user administration page.

The `Initial User's Last Name` is the value to be used as their last name. This information may be changed in the user administration page.

The `Initial Group` is the first group, basically name of the practice, that will be created. A user may belong to multiple groups, which again, can be altered on the user administration page. It is suggested that no more than one group per office be used.

![Fourth Step](./Documentation/1_Installing/images/windows_installation/Step_4.png)

#### Step 3
This is where setup will configure LibreHealthEHR. It will first create the database and connect to it to create the initial tables. It will then write the MySQL database configuration to the `librehealthehr/sites/default/sqlconf.php` file. 

Should anything fail during Step 3, you may have to remove the existing database or tables before you can try again. If no errors occur, you will see a `Continue` button at the bottom.

![Sixth Step](./Documentation/1_Installing/images/windows_installation/Step_6.png)

#### Step 4
This step will install and configure the embedded phpGACL access controls. It will first write configuration settings to files. It will then configure the database. It will then give the `Initial User` administrator access. 

Should anything fail during Step 4, you may have to remove the existing database or tables before you can try again. If no errors occur, you will see a `Continue` button at the bottom.

![Seventh Step](./Documentation/1_Installing/images/windows_installation/Step_7.png)

#### Step 5
You will be given instructions on configuring the PHP. We suggest you print these instructions for future reference. Instructions are given to edit the `php.ini` configuration file. If possible, the location of your `php.ini` file will be displayed in green. 

If your `php.ini` file location is not displayed, then you will need to search for it. The location of the `php.ini` file is dependent on the operating system. In linux, `php.ini` is generally found inside the `/etc/php/7.0` directory. In Windows, the `XAMPP` package places the `php.ini` file in the `xampp\apache\bin\` directory. The `WAMPP` package places the `php.ini` file in the `WAMP\BIN\PHP\` directory.

To ensure proper functioning of LibreHealthEHR you must make sure that the `php.ini` file includes the following settings:
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
file_uploads = On
``` 
Make sure that the MYSQL /etc/mysql/my.cnf file include the following settings: 
```
key_buffer_size set to 1024M
innodb_buffer_pool_size set to 70% of available RAM.
```
Make sure that you have disabled strict mode in Mysql . 

## How to disable Mysql strict mode?

Navigate to your my.ini/my.cnf file. You can find the file by looking at the following destinations:

If you are using WAMP: C:\WAMP\BIN\MYSQL\MySQL Server 5.6\my.ini OR left click on wampmanager icon -> MYSQL -> my.ini

In you are using XAMPP: C:\xampp\mysql\bin\my.ini 

In Linux it is typically located in /etc/mysql

Make the following changes in your my.ini/my.cnf file :

1.  Look for the following line:
    sql-mode = STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION (sometimes it may be called sql_mode)

2.  Change it to:
    sql-mode = "" (Blank)

3. Restart the MySQL service.

4. Restart WAMPP/XAMPP Server.

**Note:** If you are using XAMPP and cannot find this parameter in the my.ini file, you should run the server, open http://localhost/phpmyadmin/, click on the "variables" tab, search for "sql mode", and then set it to: ""

![Eigth Step](./Documentation/1_Installing/images/windows_installation/Step_8.png)


#### Step 6
You will be given instructions on configuring the Apache web server. We suggest you print these instructions for future reference. Instructions are given to secure the`librehealthehrwebroot/sites/*/documents`, `librehealthehrwebroot/sites/*/edi` and `librehealthehrwebroot/sites/*/era` directories, which contains patient information. This can be done by either placing pertinent `.htaccess` files in these directories or by editing the apache configuration file. 

The location of the apache configuration file is dependent on the operating system. In linux, you can type `httpd -V` or `apache2ctle  -V` on the commandline; the location to the configuration file will be the `HTTPD_ROOT` variable plus the `SERVER_CONFIG_FILE` variable. In Windows, the` XAMPP` package places the configuration file at `xampp\apache\conf\original\httpd.conf`. The `WAMPP` package places the configuration file at `\wamp\bin\Apache#.#.#\conf\httpd.conf`. 

To secure the `/documents`, `/edi` and `/era` directories you can paste the following code to the end of the apache configuration file (ensure you the put full path to directories in place of asterisks (*)):

```
<Directory "librehealthehrwebroot">
AllowOverride FileInfo
</Directory>
<Directory "librehealthehrwebroot/sites">
AllowOverride None
</Directory>
<Directory "librehealthehrwebroot/sites/*/documents">
order deny,allow
Deny from all
</Directory>
<Directory "librehealthehrwebroot/sites/*/edi">
order deny,allow
Deny from all
</Directory>
<Directory "librehealthehrwebroot/sites/*/era">
order deny,allow
Deny from all
</Directory>
```

For proper access to all pages of the website, enable the `mod_rewrite` module by issuing `a2enmod rewrite` on a terminal.

The final screen includes some additional instructions and important information. We suggest you print these instructions for future reference.
 
Once the system has been configured properly, you may login. Connect to the web server where the files are stored with your web browser. Login to the system using the username that you picked (default is `admin`), and the password. From there, select the `Administration` option, and customize the system to your needs. Add users and groups as is needed. For information on using LibreHealthEHR, consult the User Documentation in the `Documentation` folder, or the documentation on the [LibreHealth](http://librehealth.io/) website.

Reading `librehealthehr/sites/default/config.php` is a good idea.

To create custom encounter forms please consult the online documentation at [LibreHealth](http://librehealth.io/). Many forms exist in `interface/forms` and may be used as examples.

General-purpose fax support requires customization within LibreHealthEHR at Administration->Globals and custom/faxcover.txt; it also requires the following utilities:

* faxstat and sendfax from the HylaFAX client package
* mogrify from the ImageMagick package
* tiff2pdf, tiffcp and tiffsplit from the libtiff-tools package
* enscript

![Ninth Step](./Documentation/1_Installing/images/windows_installation/Step_9.png)


## 4. Setting Up Access Control
<div id='setting-up-access-control'/>

phpGACL access controls are installed and configured automatically during the setup for LibreHealthEHR. It can be administered within LibreHealthEHR in the admin->acl menu. This is a very powerful access control software. 

Learn more about phpGACL [here](http://phpgacl.sourceforge.net/). We recommend that you read the phpGACL manual, the `/librehealthehr/Documentation/README.phpgacl.md` file, and the online documentation on the [LibreHealth](http://librehealth.io/) website. We also recommend that you read the comments in `/librehealthehr/library/acl.inc`.

##  5. Upgrading
<div id='upgrading'/>

Be sure to backup your LibreHealthEHR installation and database before upgrading!

Upgrading LibreHealthEHR is currently done by replacing the old `librehealthehr` directory with a newer version. Ensure that you copy your settings from the following old `librehealthehr` files to the new configuration files (we do not recommend simply copying the entire files):

```
librehealthehr/sites/default/sqlconf.php
```

In this `sqlconf.php` file, set  `$config = 1;` (found near the bottom of the file within a bunch of slashes)

The following directories should be copied from the old version to the new version as well:
```
librehealthehr/sites/default/documents
librehealthehr/sites/default/era
librehealthehr/sites/default/edi
librehealthehr/sites/default/letter_templates
```

If there are other files that you have customized, then you will also need to treat those carefully.

## 6. FAQ
<div id='faq'/>

**Q. How can I install Apache, MySQL, and PHP on Windows?**

An easy way would be to install the [XAMPP Package](https://www.apachefriends.org/index.html). Make sure to copy the LibreHealthEHR files to the `htdocs` folder.


**Q. How can I install Apache, MySQL, and PHP on Linux?**

Follow the instructions [here](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-16-04).


**Q. I'm getting `table doesn't exist` error!**

This is because MySQL versions 5.7 and above have `strict mode` on default which needs to be disabled. This can be done by editing the MySQL configuration file and appending `sql_mode = ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION` at the end (if it is already present, modify it).

In Linux, this file is located in `/etc/mysql/mysql.conf.d/mysqld.cnf`. In Windows, it is usually located in `C:\ProgramData\MySQL\MySQL Server 5.7\my.ini`


**Q. How do I restart the apache service?**

Restart the apache service by using `sudo apache2ctl restart` on a terminal for Linux.
For Windows, restart the apache service by using the XAMPP/WAMP control interface, located in the system tray (if running) or from `xampp-control.exe` in `C:\xampp`. You can also restart apache by navigating to `C:\xampp\apache\bin` using CMD and executing `httpd -k restart`.

**Q. I need help! How do I reach you?**

Feel free to drop by [LibreHealth Chat](https://chat.librehealth.io) or the [LibreHealth Support Forum](https://forums.librehealth.io/c/7-support)!
