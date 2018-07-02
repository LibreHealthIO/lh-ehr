#! /bin/bash

# variables to hold user actions and take decisions
#NB variables must be close the equal to sign e.g test=0 and not test = 0 ;)

accept=0;
deny=1;

if [[ "$EUID" = 0 ]]; then

    echo "Already root"
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
             sudo apt-get install -y -q php7.0-curl php7.0-xml php7.0-mbstring;
             zenity --question --width=350 --height=100 --text="Successfully upgraded system for LibreEHR environment" --title="LibrehealthEHR Upgrade" 2> /dev/null
             exit 1
        fi

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
                     sudo apt-get install -y -q php7.0-curl php7.0-xml php7.0-mbstring;
                     zenity --question --width=350 --height=100 --text="Successfully upgraded system for LibreEHR environment" --title="LibrehealthEHR Upgrade" 2> /dev/null
                     exit 1
                fi

            else
            echo " wrong password more than (3) attempts"
            exit 1
             fi

fi