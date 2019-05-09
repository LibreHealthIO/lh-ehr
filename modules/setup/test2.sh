#! /bin/bash

# variables to hold user actions and take decisions
#NB variables must be close the equal to sign e.g test=0 and not test = 0 ;)

accept=0;
deny=1;
rootSite="LibreEHR";

zenity  --notification  --window-icon=/var/www/html/$rootSite/modules/setup/libs/images/favicon.ico  --text "Saving lives with code :)"

if [[ "$EUID" = 0 ]]; then

    echo "Already root"
        zenity --question --width=350 --height=100 --text="Do you want to install this package?" --title="LibrehealthEHR Upgrade" 2> /dev/null
        test=$? #get the response from thr dialog box

        #comparing the value obtained to see if user accepted or not
        if [[ $test -eq $deny ]]; then
            echo "$(tput setaf 1)Upgrade stoped. User ended action"
            exit;
          else
            echo "$(tput setaf 2)Librehealth preparing to install packages... $(tput setaf 7)"
        fi

        ans=$(zenity --title="LibrehealthEHR Upgrade" --list --width=600 --height=300  --text "The following packages will be installed" --checklist  --column "Pick" --column "options" TRUE "git" TRUE "php-soap" TRUE "php-gd" TRUE "php-gettext" TRUE "php-zip" TRUE "php-json" TRUE "php-curl" TRUE "php-xml" TRUE "php-soap" TRUE "php-mbstring" TRUE "mysql-server" TRUE "apache2" TRUE "imagemagick" TRUE "libtiff-tools" TRUE "php(7.0.*)" TRUE "libapache2-mod-php" TRUE "composer(dependency manager)" --separator=":" 2> /dev/null);
        response=$? #get response from above dialog box
         #comparing the value obtained to see if user accepted or not
        if [[ $response -eq $deny ]]; then
            echo "$(tput setaf 1)Upgrade stoped. User ended action$(tput setaf 7)"
            exit;
          else
          	#installing the various php packages
             sudo yum install -y  php7.0-curl php7.0-xml php7.0-mbstring php7.0-mysql php7.0-cli php7.0-gd php7.0-gettext php7.0-xsl php7.0-mcrypt php7.0-soap php7.0-zip  php7.0-json php7.0-ldap  php7.0-xml  imagick;
             	#installing composer
             sudo curl -sS https://getcomposer.org/installer | php;
	         sudo mv composer.phar /usr/local/bin/composer;
	     	
	     	#restarting the apache2 server to load successfull installed modules and enable mpm_prefork
	     echo "$(tput setaf 2)restarting apache2 server and configuring modules...$(tput setaf 7)"
	     sudo service apache2 stop; sudo a2enmod mpm_prefork; sudo service apache2 restart;
	     ans=$?
	     	if [[ $ans -eq $deny ]]; then
	     		echo "$(tput setaf 1)Failed to start apache2 server $(tput setaf 7)"
	     	else
	     		echo "Successfully started apache2 server"
	     	fi	
	     	
	     	 zenity  --notification  --window-icon=/var/www/html/$rootSite/modules/setup/libs/images/favicon.ico  --text "Successfully installed packages :)"
	     	#opening browser to step two of the installation
	     	
		if which xdg-open > /dev/null
		then
		  xdg-open http://localhost/$rootSite/modules/setup/step2.php?t=2 & kill $PPID
		  
		elif which gnome-open > /dev/null
		then
		  gnome-open http://localhost/$rootSite/modules/setup/step2.php?t=2 & kill $PPID
		fi
	        
        fi  #end if of root

       else

            sudo -k # make sure to ask for password on next sudo
            if sudo true; then
                echo "(2) correct password"
                   zenity --question --width=350 --height=100 --text="Do you want to install this package?" --title="LibrehealthEHR Upgrade" 2> /dev/null
                   test=$? #get the response from thr dialog box

                    #comparing the value obtained to see if user accepted or not
                    if [[ $test -eq $deny ]]; then
                        echo "upgrade stoped. User ended action"
                        exit;
                      else
                        echo "use accepted"
                    fi

                ans=$(zenity --title="LibrehealthEHR Upgrade" --list --width=600 --height=300  --text "The following packages will be installed (you can decide to uncheck ones already installed.)" --checklist  --column "Pick" --column "options" TRUE "php7.0-curl" TRUE "php7.0-xml" TRUE "php7.0-soap" TRUE "php7.0-mbstring" --separator=":" 2> /dev/null);
                response=$? #get response from above dialog box
                 #comparing the value obtained to see if user accepted or not
                if [[ $response -eq $deny ]]; then
                    echo "upgrade stoped. User ended action"
                    exit;
                  else
                       #installing the various php packages
                         sudo yum install -y -q php7.0-curl php7.0-xml php7.0-mbstring php7.0-mysql php7.0-cli php7.0-gd php7.0-gettext php7.0-xsl php7.0-mcrypt php7.0-soap php7.0-zip  php7.0-json php7.0-ldap  php7.0-xml  imagick;
                            #installing composer
                         sudo curl -sS https://getcomposer.org/installer | php;
                         sudo mv composer.phar /usr/local/bin/composer;

                        #restarting the apache2 server to load successfull installed modules and enable mpm_prefork
                     echo "$(tput setaf 2)restarting apache2 server and configuring modules...$(tput setaf 7)"
                     sudo service apache2 stop; sudo a2enmod mpm_prefork; sudo service apache2 restart;
                     ans=$?
                        if [[ $ans -eq $deny ]]; then
                            echo "$(tput setaf 1)Failed to start apache2 server $(tput setaf 7)"
                        else
                            echo "Successfully started apache2 server"
                        fi

                         zenity  --notification  --window-icon=/var/www/html/$rootSite/modules/setup/libs/images/favicon.ico  --text "Successfully installed packages :)"
                        #opening browser to step two of the installation

                    if which xdg-open > /dev/null
                    then
                      xdg-open http://localhost/$rootSite/modules/setup/step2.php?t=2 & kill $PPID

                    elif which gnome-open > /dev/null
                    then
                      gnome-open http://localhost/$rootSite/modules/setup/step2.php?t=2 & kill $PPID
                    fi
                fi

            else
            echo " wrong password more than (3) attempts"
            exit 1
             fi

fi
