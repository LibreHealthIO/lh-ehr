# `docker` support for LibreHealth EHR

To start a simple `lh-ehr` instance, follow the steps:

## Getting started
### 1. Install Composer, Docker and docker-compose (for ubuntu in this example)

[Composer Getting Started](https://getcomposer.org/)

[Docker CE install Page](https://docs.docker.com/install/overview/)

[Docker Compose install Page](https://docs.docker.com/compose/install/#install-compose)

### 2. Get the source code (if not already)

    $ git clone https://github.com/LibreHealthIO/lh-ehr.git

### 3. Configure the docker resources for LibreHealth EHR

    $ cd lh-ehr/docker

The librehealth_ehr container is configured for PHP 7.2 and Apache2.

The `.env.ehr` contains the information for the EHR database itself. Database credentials should match those in `.env.mysql`.

The `.env.mysql` contains the mysql root password and the initial database information for setup. The database with the name specified by `MYSQL_DATABASE` will be created and access will be granted to the user specified by `MYSQL_USER`. A user will also be created with the username specified in `MYSQL_USER` and the password specified by `MYSQL_PASSWORD`.

:warning: **You should change these from the defaults if running in production!!** :warning:


### Ensure that your user is part of the `docker` user group

``` bash
  $ sudo usermod -aG docker your-user
```

:warning: You will need to logout and log back in again for this to take effect. No need to reboot, just logout and log back in.

#### Development Start Up

The developer install uses the default `docker-compose.yml` and will automatically import a demo database which is located in `sql/nhanes`
```bash
$ cp .env.mysql.example .env.mysql ; cp .env.ehr.example .env.ehr # then edit accordingly
$ cd .. # backup to root level
$ docker-compose -p librehealth_ehr up -d
```

The above will create the database with user and password as shown in the .env.ehr file.

If you do not wish to use this, comment out the `- ../sql/nhanes:/docker-entrypoint-initdb.d/`  Line in the `docker-compose.yml` file.

This will be ready to go if you used the initial database mentioned above. [Otherwise follow the setup instructions](#4-ehr-installation-and-setup).


#### Production Start Up

If you are setting up with an existing production database you will need to edit both the .env files to contain the correct information for your existing database.

```bash
$ cp .env.mysql.example .env.mysql ; cp .env.ehr.example .env.ehr # then edit accordingly
$ cd .. # back up to root level
$ docker-compose -p librehealth_ehr -f docker-compose.prod.yml up -d
```

**The initial container creation may take awhile.  After the container has been created give it a few more minutes to allow the services to start and then proceed as below.**

**Note**: replace `localhost:8000` with the IP of the server. Port might be different as well.

:warning: You should place this behind a reverse proxy with HTTPS if this is running remotely. This image does not include HTTPS support. :warning:

Now go to [http://localhost:8000](https://localhost:8000) and [proceed to step 5](#5-using-librehealth-ehr).

If you created a new database then the setup wizard will guide you through the installation steps. Instructions follow below for that.

### 4. EHR Installation and setup

#### Optional Site Selection

![Optional Site ID Selection](images/site_selection.png)

You should be able to leave this as `default` and press <kbd>Continue</kbd>

#### Permission Checks

Press <kbd>Continue</kbd> as everything is designed to be correct.


#### Step 1

![Step 1](images/step1.png)

Select "I have already created the database" as seen above and press  <kbd>Continue</kbd>

#### Step 2

![Step 2](images/step2.png)

Fields boxed in red are ones that should be filled out. The arrow to the Database name is due to the fact that it doesn't necessarily need to be changed.

##### MySQL database settings

The following are based off of `.env.mysql`
- MySQL Server Host shold be `db`
- Port remains unchanged
- Database name should be whatever you set `MYSQL_DATABASE` to in `.env.mysql`, default should be fine.
- Username should be whatever you set `MYSQL_USER` to in `.env.mysql`
- Password should be changed from the default and should match what you set `MYSQL_PASSWORD` to in `.env.mysql`

##### LibreHealth EHR Settings

The next section is for the initial administrative user for LibreHealth EHR. All fields should be changed according to the instructions.

When the above is completed, press <kbd>Continue</kbd>

#### Step 3

This step will take a bit to complete. When complete, press <kbd>Continue</kbd>.

#### Step 4

Just press <kbd>Continue</kbd>

#### Step 5

Just press <kbd>Continue</kbd>

#### Step 6

Just press <kbd>Continue</kbd>

#### Step 7

![](images/step7.png)

You should print this page and store it in a secure location.

Once done, follow the link which is boxed in red above.


### 5. Using LibreHealth EHR


### If you did not have the initial dataset loaded

Login with credentials provided above:
- Initial User: `<your admin user>`
- Initial User Password: `<your admin password>`


###  With the initial database with sample data

- Initial User: `admin`
- Initial User Password: `password`


## Stopping the container

### Development

``` bash
$ docker-compose -p librehealth_ehr down -v
```
### Production

``` bash
$ docker-compose -p librehealth_ehr -f docker-compose.prod.yml down -v
```

## Recreating the container

Since data is stored in volumes, the container can be re-created without issue.

### Development

``` bash
$ docker-compose -p librehealth_ehr up -d --force-recreate
```
### Production

``` bash
$ docker-compose -p librehealth_ehr -f docker-compose.prod.yml up -d --force-recreate
```


## Backup (make use of volumes)

The volumes that need to be backed up from you hosting server are:
- /var/lib/docker/volumes/lh-ehr_db_data
- /var/lib/docker/volumes/lh-ehr_sites

```
$ docker volume ls | grep librehealth
   local     librehealth_ehr_db_data
   local     librehealth_ehr_sites
```

## Updating LibreHealth EHR

This is only applicable to production.

``` bash
$ docker-compose -p librehealth_ehr -f docker-compose.prod.yml pull
$ docker-compose -p librehealth_ehr -f docker-compose.prod.yml up -d
```

Do not forget to run http://localhost:8000/sql_upgrade.php

**Replace `localhost:8000` with the port and, IP address or hostname of your server.**

## Accessing mysql via the container
If you need to enter the database container for any reason -- it is possible to do so.

``` bash
 $ docker-compose -p librehealth_ehr -f docker-compose.prod.yml exec db bash
```

Beyond this -- it is a full shell, you are logged in as root, so be careful. At this point, it is assumed you have basic knowledge around how to use MySQL.

When done, type `exit` at the shell prompt and you will fall back your host system.

## Accessing the Database using [adminer](https://www.adminer.org/)

You can access the database using adminer by going to http://localhost:8001

You'll need your database credentials. Use the hostname `db` for the database server.
