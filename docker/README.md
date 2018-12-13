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

The env.ehr contains the information for the EHR database itself.

The env.mysql contains the mysql root password and the initial database information for setup 

#### Development Start Up
The developer install uses the default 'docker-compose.yml' and will automatically import a demo database which is located in 
```bash
$ cp .env.mysql.example .env.mysql ; cp .env.ehr.example .env.ehr # then edit accordingly
$ cd .. # backup to root level
$ docker-compose -p librehealth_ehr up -d
```

The above will create the database with user and password as shown in the .env.ehr file.

If you do not wish to use this, comment out the `../sql/nhanes:/docker-entrypoint-initdb.d/`  Line in the docker-compose file.**

#### Production Start Up

If you are setting up with an existing production database you will need to edit both the .env files to contain the correct information for your existing database.

```bash
$ cp .env.mysql.example .env.mysql ; cp .env.ehr.example .env.ehr # then edit accordingly
$ cd .. # back up to root level
$ docker-compose -p librehealth_ehr -f docker-compose.prod.yml up -d
```

**The initial container creation may take awhile.  After the container has been created give it a few more minutess to allow the services to start and then proceed as below.

Now go to [http://localhost:8000](https://localhost:8000) and proceed to step 5.

If you created a new database then the setup wizard will guide you through the installation steps.

### 4. EHR Installation and setup 

* BELOW DOES NOT MAKE ANY SENSE

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

## Cleanup (Shutdown?)

    $ docker-compose -p librehealth_ehr down -v

## Backup (make use of volumes)

The volumes that need to be backed up from you hosting server are: 
* /var/lib/docker/volumes/lh-ehr_db_data
* /var/lib/docker/volumes/lh-ehr_sites

```
$ docker volume ls | grep librehealth
   local     librehealth_ehr_db_data
   local     librehealth_ehr_sites
```
