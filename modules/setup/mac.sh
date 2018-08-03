#! /bin/bash

# variables to hold user actions and take decisions
#NB variables must be close the equal to sign e.g test=0 and not test = 0 ;)

accept=0;
deny=1;
rootSite="LibreEHR";

s=(osascript -e 'tell app "System Events" to display dialog "LibreHealth EHR" ')
echo $s