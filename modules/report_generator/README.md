# lh-ehr-laravel-port
LibreHealth EHR - Porting main LibreEHR codebase to Laravel framework.

# Welcome to LibreHealth Laravel Port

LibreHealth EHR is a free and open-source electronic health records and medical practice management application.

The mission of LibreHealth is to help provide high quality medical care to all people, regardless of race, socioeconomic status, or geographic location, by providing medical practices and clinics across the globe access to free of charge medical software.

That same software is designed to save clinics both time and money, which gives practitioners more time to spend with individual patients, thereby supplying patients with higher quality care.

We are current and former contributors to OpenEMR and thank that community for years of hard work. We intend to honor that legacy by allowing this new community to leverage the good things in OpenEMR, share what we create and not be afraid to break backward compatibility in the name of forward progress and modern development models.

This repository carries the new code base for the EHR application. The new code base is based on the Laravel framework because it has standards that will enable contributors to easily understand code written by others. The modular approach is used in developing this new EHR application with Laravel's laravel-module package.

We are collaborating closely with the [LibreHealth Project](http://LibreHealth.io), an umbrella organization for health IT projects with similar goals.

# Contributing code

Code contributions are very welcome! We encourage newcomers to browse the [issue tracker](https://github.com/LibreHealthIO/lh-ehr-laravel-port/issues) for open issues and/or if you have found a bug in LibreEHR, please [create a new issue](https://github.com/LibreHealthIO/lh-ehr-laravel-port/issues/new) for same. You may open a [pull request](https://github.com/LibreHealthIO/lh-ehr-laravel-port/pulls) to contribute your code to an issue, from your fork of the [LibreEHR repository](https://github.com/LibreHealthIO/lh-ehr-laravel-port).

# Installation

##Laravel Installation :

For installation purpose, official docs at https://laravel.com/docs/5.4/installation followed.
Below are brief instructions to follow for unix based systems.

##Dependencies:

1. PHP 7.0+
2. OpenSSL PHP Extension
3. PDO PHP Extension
4. Mbstring PHP Extension
5. Composer

##Install Laravel:

Run `composer global require "laravel/installer"`

The command above will install laravel via Laravel Installer. In order to have global access, we need to export its path.
So for this, follow below instructions :

1. Open your shell. (In my case bash shell)
2. open .bashrc
3. Add **export PATH="$PATH:$HOME/.config/composer/vendor/bin"** at the end of file.
4. Run source ~/.bashrc


##Instructions:

1. Repository setup
    * Fork the Repository at https://github.com/LibreHealthIO/lh-ehr-laravel-port.git
    * Clone your fork of the Repository from your GitHub account at https://github.com/your_username/lh-ehr-laravel-port.git
        - Command to clone fork: `git clone https://github.com/your_username/lh-ehr-laravel-port.git`
    * Add your upstream and origin remotes
        - command to add upstream --> `git remote add upstream https://github.com/LibreHealthIO/lh-ehr-laravel-port.git`
        - command to add origin --> `git remote add origin https://github.com/your_username/lh-ehr-laravel-port.git`

2. Ensure to have the following dependencies
    * PHP 7.0 (7.0.* is required for LibreEHR)
    * OpenSSL PHP Extension
    * PDO PHP Extension
    * Mbstring PHP Extension
    * composer

3. Installing Composer
    * Run `sudo apt update` in the terminal.
    * Run `sudo apt install curl php7.0-cli php7.0-mbstring git unzip`
    * `cd~`
    * `curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer`
    * Test the installation by running `composer` in the terminal.
    * Ensure that your home/'username'/.config/ folder is writtable.

3. Run the command below to install Laravel globally via Laravel Installer
    `composer global require "laravel/installer"`

4. Now we have to add global access
    * Open .bashrc by running this command `sudo gedit .bashrc`. Feel free to use anything other than gedit. You'll be prompted to enter your password.
    * Add this line at the end of the file and save it
      **PATH="$PATH:$HOME/.config/composer/vendor/bin"**
    * Run this command after saving the .bashrc file
       `source ~/.bashrc`

5. Navigate to lh-ehr-laravel-port directory
    `cd lh-ehr-laravel-port`

6. Add your .env file by running this command: `cp .env.example .env`

7. Add your .env file to .gitignore file.  _It is already there, just check!_

8. Generate key for your application by running the command below
    `php artisan key:generate`
   Observe that the key is inserted in your .env file.

9. Edit your database name, username and password in your .env file
    DB_DATABASE = your_db_name
    DB_USERNAME = your_username
    DB_PASSWORD = your_password

    _For Report Generator's database_
    DB_REPORT_GENERATOR_DATABASE=homestead
    DB_REPORT_GENERATOR_USERNAME=homestead
    DB_REPORT_GENERATOR_PASSWORD=secret

    _For Main LibreEHR database_
    DB_LIBREEHR_DATABASE=homestead
    DB_LIBREEHR_USERNAME=homestead
    DB_LIBREEHR_PASSWORD=secret

10. Now, you can create the database by running
    `php artisan make:database`  _Subsequently, this will create both databases automatically._

    For now you have to create two databases manually. These databases should have the same
    names as those specified in **DB_DATABASE** and **DB_REPORT_GENERATOR_DATABASE**

11. Create tables by running
    `php artisan module:migrate ReportGenerator`

    In case you want to do a refresh(rollback) of the database, use
    `php artisan module:migrate-refresh ReportGenerator` OR
    `php artisan module:migrate-rollback ReportGenerator`

12. You can seed the database width
    `php artisan db:seed` _This shouldn't work for now._
    If seeding fails, then use the [librereportgenerator.sql](Documentation/librereportgenerator.sql) file in this directory to import sample data.

13. Start the application by running the command below in the terminal:
        `php artisan serve`

14. Head over to your browser and access [localhost:8000](http://localhost:8000) or 127.0.0.1:8000

15. You can also check your database at [phpMyAdmin](http://localhost/phpmyadmin/)


# License

LibreHealth EHR is primarily licensed under Mozilla Public License Version 2. The code inherited from OpenEMR is licensed under GPL 2 or higher. This project is a part of the [Software Freedom Conservancy](http://sfconservancy.org) family.

***Thank you for your support!***
