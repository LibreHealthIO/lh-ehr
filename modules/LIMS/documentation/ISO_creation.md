# DevDocs

## ISO Creation

To create an ISO containing the installation of Ubuntu along with Senaite and Senaite's JSON API, you can follow the following steps:

### Windows Users

You will have to create a VM containing a vanilla Ubuntu installation. You can find the ISO of Ubuntu 16.04 LTS [here](https://www.ubuntu.com/download/alternative-downloads). 

Pick the one that suits your OS.

(Instructions for Linux are the same from this point onwards) 

Once you've created a VM containing the OS, you will need to download Cubic using the following commands:

```
sudo apt-add-repository ppa:cubic-wizard/release

sudo apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 6494C6D6997C215E

sudo apt update

sudo apt install cubic 

```

Once cubic is installed, open the application. It requires you to have an ubuntu ISO within the VM to access, so download the same from the link previously given. 

Next, open the Cubic application. Select a folder where you want the ISO to be created in (project directory, can be anything you want). In the ISO selection screen, click "Select" in the "Original ISO" section and navigate to the Ubuntu ISO that you downloaded.
Click next, and give it some time to load the ISO in.

In the next few steps, you will come across a terminal. In that, follow the steps provided in the README file of senaite (installation steps for Senaite). Once done, proceed with the installation and the ISO file will be generated. Anyone who uses this ISO will have all the features that you put in through the terminal set up for them.


## Guzzle

[Guzzle](http://guzzlephp.org) is used as the cURL library in this application. Following are the base configurations that have been applied to the library to make it easy to use.

Base URL: This is retrieved from the database and is kept as a user-configurable URL which is prepended to every request that is sent to the API (eg. http://localhost:8080/senaite/@@API/senaite/v1/).

Sessions: Since multiple users need to be able to log in to the system, we cannot use Basic Authentication headers. So, we're using cookie jars provided by Guzzle. In particular, we're using a Session jar. This jar stores all the cookies that we need for the particular session and is then destroyed after the user logs out. Once the user logs in, the Senaite API sends a __ac cookie which we need to send with every request thereafter to show authentication.


## Pages, Routing and Templates

The application follows a fairly simple routing system.

The "action" GET variable decides the main page while the "sact" GET variable decides the sub-page.

For example, if you're looking to create an instrument, the url would be index.php?action=instrument&sact=createinstrument

The main pages are stored in the pages/ directory with the name of the PHP file being the name of the value in the "action" GET variable. The sub-pages are defined inside the PHP code.

The templates for each page can be found in the templates/site/ folder,with the names of the folders also being the same as the name of the page and the sub-actions having a separate PHP file for each of them.

## Senaite API

The Senaite API follows a simple route format. The base URL is: 
http://hostname/senaite/@@API/senaite/v1/

The default port the API runs on is 8080.

### GET requests

A simple GET request to retrieve data from the LIMS would work like the following:

If you're trying to retrieve the instruments currently saved, a GET request needs to be sent to the URL:
http://hostname/senaite/@@API/senaite/v1/instrument
and it'll return a JSON response with the list of instruments.

### POST requests

Creating or updating data requires a POST request. The request can be sent to the same URL as the GET request with data and it will create the relevant entry in the database.

Native documentation can also be found at http://plonejsonapiroutes.readthedocs.io/en/latest/crud.html

### Additional Notes

Some entries such as Instruments and Analysis Requests have prerequisites or dependencies on other data. For example, while creating an Instrument, you need to supply an Instrument Type, Manufacturer and Supplier. While trying to create data like this, instead of using the form_params (which translates to form-data) parameter in Guzzle, data needs to be sent as JSON with the "UID" of the prerequisite sent in the relevant field.

For example, while creating an instrument, the following fields would properly work for the prerequisite data: 
```
{
  'InstrumentType': 'UIDHere',
  'Supplier': 'UIDHere',
  'Manufacturer': 'UIDHere'
}
```