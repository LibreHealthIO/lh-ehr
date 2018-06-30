# DevDocs

## ISO Creation

To create an ISO containing the installation of Ubuntu along with Senaite and Senaite's JSON API, you can follow the following steps:

## Windows Users

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