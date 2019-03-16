# LibreHealth EHR Setup for Windows

Last Updated: November 19th, 2018

**Note: Read the instructions carefully, as failure to follow the steps may require the entire setup process to be restarted. If you are having trouble, see the [FAQ](#faq) section for ways to contact us and common setup errors.**

## Setup

#### Unpacking

- Fork the [repository](https://github.com/LibreHealthIO/lh-ehr).

- Clone the fork to your local machine with git bash.

```
git clone https://github.com/YOURUSERNAME/lh-ehr.git
```

LibreHealth EHR will be cloned into a directory named `lh-ehr`. Note this location for further use.

#### Requirements

[XAMPP](https://sourceforge.net/projects/xampp/files/)

- Downloaded the latest version (recommended) from the library hyperlinked above.

- Includes the following required applications: Apache Web Server, PHP, MySQL.

- Make sure it is downloaded to the `C:\xampp\` directory to give XAMPP write permissions.

#### Steps

Proceed after you have [unpacked](#unpacking) and met the [requirements](#requirements).

1. Move the `lh-ehr` project folder to `C:\xampp\htdocs\`.

2. Navigate to `C:\xampp\php\`. Open the `Configuration Settings` file called `php` with a text editor or notepad.

3. Change the following in the file using `Ctrl + F` to locate the variables. Some of the variables shown appear multiple times. Do not change any values above `Line 170` (blank line).

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

4. Save the file and close it.

5. Launch XAMPP Control Panel. You can find it in `C:\xampp\` or with Windows Search.

6. Click the `Start` button next to `Apache` and `MySQL`. Make sure that `Apache` is linked to `Ports 80,443` and that `MySQL` is linked to `Port 3306`.

7. Use your browser to navigate to `localhost/phpmyadmin`. Click on the `Variables tab` and set `sql mode` to "" (blank).

8. Restart Apache Web Server by clicking `Stop` and then clicking `Start` again on the XAMPP Control Panel.

9. Use your browser to navigate to `localhost/lh-ehr/setup.php`. Make sure there are no undefined index errors. If there are, check that you have changed the `php` settings file correctly and are using a supported PHP version of XAMPP.

10. Leave `default` as the `SITE ID:` and click `continue`.

11. Make sure no more undefined index errors show up and that all statuses are labeled `ready`. Click `continue`.

12. Select `Have setup create the database` and click `continue`.

13. Enter a `MySQL Server Password`. Record this information. Setup an `Initial User Password`, which you will need to access EHR. Record this information.

![Create Credentials](./Documentation/1_Installing/images/windows_installation/Step_4.png)

14. If the above steps have been followed with clarity, click continue through the rest of setup and eventually the EHR login page will appear.

![Setup Complete](./Documentation/1_Installing/images/windows_installation/Step_10.png)

15. Congratulations! You have successfully setup the LibreHealth EHR Developer Environment. It is recommended to bookmark the Login Page for easy access. Login with the username `admin` and password that you set up to access EHR.

### Additional Information

#### Setting up Access Control

phpGACL access controls are installed and configured automatically during LibreHealth EHR setup. It can be administered within LibreHealth EHR in the `admin` -> `acl` menu. This is a very powerful access control software.

Learn more about phpGACL [here](http://phpgacl.sourceforge.net/). It is recommended to read the phpGACL manual in the `/lh-ehr/Documentation/README.phpgacl.md` file, the online documentation at [LibreHealth](http://librehealth.io/), and the comments in the `/lh-ehr/library/acl.inc` file.

#### Patient Documents Capability

In order to take full advantage of the patient documents capability you must make sure that the configuration settings in `php` file include `file_uploads = On` and that `upload_max_filesize` is appropriate for your use and that `upload_tmp_dir` is set to the correct value that will work on your system.

#### Updating the Project

Be sure to back up your LibreHealthEHR installation and database before updating!

Login to EHR. There should be an orange button at the bottom right which you can hover over to reveal two options. Click the `</>` button that appears. Follow the instructions to update EHR.

### FAQ

**Q. I am getting a `table doesn't exist` error!**

This is because MySQL versions 5.7 and above have `strict mode` on default which needs to be disabled. This can be resolved by making sure Step #7 is fufilled.

**Q. I am getting an `Internal Server Error` when trying to run portal dashboard!**

Open the Apache configuration file and uncomment this line by removing the `#`:

```
#LoadModule rewrite_module modules/mod_rewrite.so
```

Search for

```
AllowOverride None
```

and change it to

```
AllowOverride All
```

**Q. I am getting a `Check that mysqld is running` error when opening up EHR!**

mysqld is a daemon that runs silently and can be invoked to start a mySQL server on your computer. If the credentials that lh-ehr uses to access the mySQL server are incorrect, then mysqld will not initiate the server, resulting in this error. This may have occured if you tried to sync your fork to the upstream repository. It will have reverted the database password in `sqlconf.php` in `C:\xampp\htdocs\lh-ehr\library\`. 

Change the following line to include your own password inside the single quotes.

```
$pass    = 'libreehr';
```

Restart Apache Web Server and MySQL.

**Q. How do I get back to the Login Page once I close out?**

You can go to `localhost/lh-ehr` and it will redirect you to the login page. 

**Q. I need help! How do I contact someone?**

Feel free to drop by the [LibreHealth Online Chat](https://chat.librehealth.io) or the [LibreHealth Support Forum](https://forums.librehealth.io/c/7-support)!