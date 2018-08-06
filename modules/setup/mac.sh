#! /bin/bash

# variables to hold user actions and take decisions
#NB variables must be close the equal to sign e.g test=0 and not test = 0 ;)

accept=0;
deny=1;
rootSite="LibreEHR";



#checking if the user is already root or not
if [[ "$EUID" = 0 ]]; then

    echo "Already root"
    s=(osascript -e 'tell app "System Events" to display dialog "LibreHealth EHR" ')
    response=$?

      #comparing the value obtained to see if user accepted or not
        if [[ $response -eq $deny ]]; then
            echo "$(tput setaf 1)Upgrade stoped. User ended action"
            exit 1
          else
            echo "$(tput setaf 2)Librehealth preparing to install packages... $(tput setaf 7)"
        fi

        # Check if  Homebrew is already installed,if not we install it
        if test ! $(which brew); then
            echo "Installing homebrew..."
            ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
        fi

        # Update homebrew (brew update && brew upgrade) ~ could go here
        brew update

        #get a wider range of possible repositories by tapping into them
        brew tap homebrew/dupes
        brew tap homebrew/versions
        brew tap homebrew/homebrew-php

        # Install the latest bash to get updated
        brew install bash

        #uninstalling previous php version
        brew unlink php56

        #any further packages could be added here
        PACKAGES=(
            php70
            imagemagick
            libapache2-mod-php
            )

        echo "Installing packages..."
        brew install ${PACKAGES[@]}

        echo "Cleaning up..."
        brew cleanup

        #incase of an upgrade shell
        brew install php@7.0

        #installation of various extensions
        brew install mcrypt php70-mcrypt
        brew install mysql php70-mysql
        brew install cli  php70-cli
        brew install gd php70-gd
        brew install gettext php70-gettext
        brew install xsl php70-xsl
        brew install curl php70-curl
        brew install soap php70-soap
        brew install php70-json
        brew install mbstring php70-mbstring
        brew install php70-zip
        brew install php70-ldap
        brew install php70-xml


        #open browser after installation
        open -a /Applications/Firefox.app http://localhost/$rootSite/modules/setup/step2.php?t=2

     # make sure to ask for password on next sudo (get user password 3 attempts)
    else
    sudo -k
    if sudo true; then
    s=(osascript -e 'tell app "System Events" to display dialog "LibreHealth EHR" ')
    response=$?

      #comparing the value obtained to see if user accepted or not
        if [[ $response -eq $deny ]]; then
            echo "$(tput setaf 1)Upgrade stoped. User ended action"
            exit 1
          else
            echo "$(tput setaf 2)Librehealth preparing to install packages... $(tput setaf 7)"
        fi

        # Check if  Homebrew is already installed,if not we install it
        if test ! $(which brew); then
            echo "Installing homebrew..."
            ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
        fi

        # Update homebrew (brew update && brew upgrade) ~ could go here
        brew update

        #get a wider range of possible repositories by tapping into them
        brew tap homebrew/dupes
        brew tap homebrew/versions
        brew tap homebrew/homebrew-php

        # Install the latest bash to get updated
        brew install bash

        #uninstalling previous php version
        brew unlink php56

        #any further packages could be added here
        PACKAGES=(
            php70
            imagemagick
            libapache2-mod-php
            )

        echo "Installing packages..."
        brew install ${PACKAGES[@]}

        echo "Cleaning up..."
        brew cleanup

        #incase of an upgrade shell
        brew install php@7.0

        #installation of various extensions
        brew install mcrypt php70-mcrypt
        brew install mysql php70-mysql
        brew install cli  php70-cli
        brew install gd php70-gd
        brew install gettext php70-gettext
        brew install xsl php70-xsl
        brew install curl php70-curl
        brew install soap php70-soap
        brew install php70-json
        brew install mbstring php70-mbstring
        brew install php70-zip
        brew install php70-ldap
        brew install php70-xml


        #open browser after installation
        open -a /Applications/Firefox.app http://localhost/$rootSite/modules/setup/step2.php?t=2



     #exits the terminal cause the user doesnt have the permission to run if not root
    else
     echo " wrong password more than (3) attempts"
     exit 1
      fi

fi







