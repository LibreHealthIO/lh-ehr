# SENAITE LIMS Installation Guide

### Supported Operating Systems

* Microsoft Windows
* LINUX distributions
* OS X (Instructions will be added soon)

## Microsoft Windows

To install SENAITE on Windows, you will need the following resources:

* [VirtualBox](https://www.virtualbox.org/)
* Ubuntu Image File ([64-bit](http://releases.ubuntu.com/16.04/ubuntu-16.04.4-desktop-amd64.iso.torrent) , [32-bit](http://releases.ubuntu.com/16.04/ubuntu-16.04.4-desktop-i386.iso.torrent))

Start by opening VirtualBox and select "New" to create a new Virtual Machine. Choose Linux and Ubuntu in the OS selection step of the wizard.

![Step 1](https://i.imgur.com/mHt3RGN.png)

Everything else can be left as default as you progress through the wizard. Around 512mb-1GB of RAM should be allocated to the system along with 5-8GB+ of hard disk space.

Once the VM has been created, double click on the name in the left column to start the VM. You will get a prompt to browse for the ISO file. Select the Ubuntu ISO file that you had downloaded previously.

![Step 2](https://i.imgur.com/HfUkCBX.png)

Click on the "Install Ubuntu" option.

![Step 3](https://i.imgur.com/6FYa1wH.png)

Check the "Download updates while installing Ubuntu" option.

In the next step, check "Erase and install Ubuntu" and proceed.

Pick your time zone and keyboard layouts in the next step.

Then, enter the username and password you want for your OS account.

The installation takes some time.

Once the OS has finished installation, open the terminal ( Ctrl + Alt + T ).

( From this point onwards, the instructions are similar for the Linux distributions )

In the terminal, run the following scripts:

```
sudo apt install -y python-setuptools libxml2-dev libxslt1-dev libbz2-dev libjpeg62-dev libz-dev
sudo apt install -y libreadline-dev wv poppler-utils git
sudo apt install -y build-essential gcc python-dev git-core libffi-dev
sudo apt install -y libpcre3 libpcre3-dev autoconf libtool pkg-config
sudo apt install -y zlib1g-dev libssl-dev libexpat1-dev libxslt1.1
sudo apt install -y gnuplot libcairo2 libpango1.0-0 libgdk-pixbuf2.0-0
```

Once the above libraries are installed, go to the following website using Firefox in the VM https://launchpad.net/plone/4.3/4.3.17/+download/Plone-4.3.17-UnifiedInstaller.tgz

This will download the Plone CMS Installer. Open the ZIP file and extract it to your desktop.

Now, go back to the terminal and run this command

`cd ~/Desktop/Plone-4.3.1-UnifiedInstaller`

Install Plone using this command:
`./install.sh standalone`

Wait till the installation finishes.
Once the download finishes, it will show you your administrator username and password in the terminal. Note those down.

Now we need to install Senaite's JSON API onto Plone.

Navigate to the directory where plone is installed using
`cd ~/Plone/zinstance` in the terminal.

Open the buildout.cfg file for editing using
`gedit buildout.cfg`

Scroll down to the "eggs" section and add "senaite.jsonapi" at the end.

![Step 4](https://i.imgur.com/eWhIpnq.png)

Now, in the terminal, run:

`bin/buildout`

This will install the Senaite JSON API.
You might see some warnings in the terminal, you can ignore those.

Now, to start the Plone server, execute the following command:

`bin/plonectl start`

In the VM, using the browser, visit http://localhost:8080 and click "Install SENAITE core". It will ask for the username and password that you noted down earlier. Input those, click Install in the next step and let the setup run.

Once the setup finishes, it will open your LIMS dashboard. The installation is finished.

At this point, your Senaite API is only available for usage from within inside the VM. To let LibreEHR use the Senaite API you will need to forward the port 8080 ( or something else of your choosing ) in the Virtual Machine configuration. To do that, close the machine ( you can save the state ).

Right click on the machine in the left column in VirtualBox and click Settings. Open the "Network" section and click on "Advanced". Open the "Port Forwarding" popup and click the small "+" on the right side to create a new Port Forwarding rule. When the rule is created, enter "8080" in both the Host Port and the Guest Port. Once that is done, click OK and you can start the VM again.

Next, you need to configure LibreEHR to make sure it can communicate with the LIMS. Log into an administrator account in LibreEHR, go to Administrator->Globals->LIMS. Enable the LIMS, pick senaite as the LIMS software to use, add the URL where the VM is residing ( if it is on your system, add http://localhost:8080), and enter the authentication username and password you noted down earlier.

Everything should work as intended from this point onwards, you can access the LIMS in LibreEHR.

## Linux Distributions

To install Plone and SENAITE in Linux distributions, you can follow the steps in the Windows tutorial after the installation of the VM.
