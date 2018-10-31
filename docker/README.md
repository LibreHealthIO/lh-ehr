# `docker` support for LibreHealth EHR

To start a simple `lh-ehr` instance, follow the steps:

## Getting started

### 1. Get the source code (if not already)

    $ git clone https://github.com/LibreHealthIO/lh-ehr.git

### 2. Start LibreHealth EHR
We currently have an image pushed for PHP 7.2 and Apache. We do not support nginx currently, or any other web server.

#### Development
```bash
$ cp .env.mysql.example .env.mysql ; cp .env.ehr.example .env.ehr # then edit accordingly
$ docker-compose -p librehealth up -d
```

#### Production

```bash
$ cp .env.mysql.example .env.mysql ; cp .env.ehr.example .env.ehr # then edit accordingly
$ docker-compose -f docker-compose.prod.yml up -d
```
You might need to tweak all files for this case. For example if you are deploying this for your practice, you will not want to load demo data, so commenting that line out.

**The initial container creation may take awhile. It is loading a database with sample data. If you do not wish to use this, comment out the `../sql/nhanes:/docker-entrypoint-initdb.d/`  Line in the docker-compose file.**

Go to [http://localhost:8000](https://localhost:8000) and you're ready to go -- otherwise go to [http://localhost:8000/setup.php](http://localhost:8000/setup.php).

At this point the setup wizard will guide you through the installation steps.

### 4. Installation

**Optional Site ID Selection**

Site ID: `default`

-> Continue

**LibreHealth EHR Setup**

-> Continue

**LibreHealth EHR Setup - Step 1**

Choose: _I have already created the database_

-> Continue

**LibreHealth EHR Setup - Step 2**

MYSQL SERVER:
Server Host: `db`
Server Port: `3306`
Database Name: `libreehr`
Login Name:	`libreehr`
Password: `s3cret`

LibreHealth EHR USER:
Initial User: `<your admin user>`
Initial User Password: `<your admin password>`
Initial User's First Name: `<John>`
Initial User's Last Name: `<Doe>`
Initial Group: `Default`

**LibreHealth EHR Setup - Step 3**

-> Continue

**LibreHealth EHR Setup - Step 4**

-> Continue

**LibreHealth EHR Setup - Step 5**

-> Continue

**LibreHealth EHR Setup - Step 6**

-> Continue

**LibreHealth EHR Setup**

Follow link [Click here to start using LibreHealth EHR](http://localhost:8000/?site=default).

### 5. Use

### If you did not have the initial dataset loaded

Login with credentials provided above:
* Initial User: `<your admin user>`
* Initial User Password: `<your admin password>`

###  With the initial database with sample data

* Initial User: `admin`
* Initial User Password: `password`

## Cleanup

    $ docker-compose -p librehealth down -v

## Backup (make use of volumes)

The volumes that needs to be backed up: `librehealth_db_data` and `librehealth_sites`

    $ docker volume ls | grep librehealth
    local               librehealth_db_data
    local               librehealth_sites
