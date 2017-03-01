**Welcome to LibreHealth EHR!!!**

LibreHealth EHR is a free and open-source electronic health records and
medical practice management application. 

The mission of LibreHealth is to help provide high quality medical care to all people, regardless of race, socioeconomic status or geographic location, by giving medical practices and clinics across the globe access to medical software, free of charge.

That same software is designed to save clinics both time and money, which gives practitioners more time to spend with individual patients, thereby affording patients with higher quality care.

We are current and former contributors to OpenEMR and thank that community for years of hard work. We intend to honor that legacy by allowing this new community to leverage the good things in OpenEMR, share what we create and not be afraid to break backward compatibility in the name of forward progress and modern development models.

We are collaborating closely with the LibreHealth Project [LibreHealth.io](http://LibreHealth.io), an umbrella organization for health IT projects with similar goals.   

Our project is primarily licensed under Mozilla Public License Version 2.

Code inherited from OpenEMR is licensed under GPL 2 or higher.

The project is part of the Software Freedom Conservancy family [sfconservancy.org](http://sfconservancy.org)  

Tax deductible Donations can be made to the project through the SFC. 
 
***Thank you for your support!***

# Contributing code
Code contributions are very welcome! Browse the [Issue tracker](https://github.com/LibreHealthIO/LibreEHR/issues) for issues that need code and/or come up with your own ideas & code. Please open a Pull Request to contribute your own code.

## Local Development

Windows :: 

You can fork & clone the repository for local development. To get started you need to:
 - Clone the repository
 - Make sure you have disabled strict mode in Mysql . For doing that, you can look for instructions below. 
 
 Sometimes , installation may take more time than usual on some systems. In that case, you would need to increase "max_execution_time" in your php.ini file .

## How to disable Mysql strict mode?

Make the following changes in the "my.ini/my.cnf":

    1.  Look for the following line:
        sql-mode = "STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"

    2.  Change it to:
        sql-mode="" (Blank)

    3. Restart the MySQL service.


