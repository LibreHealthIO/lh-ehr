SETTING UP GIT/GITHUB

How to setup git/github and your local system to get started with contributing to librehealthehr

Note if on Windows download and install Git Bash and use it to run the commands below

	1)create a github account https://github.com if you don't have, if you have log in to you account
	
	2)navigate to this url https://github.com/LibreHealthIO/lh-ehr and fork the repository

	3)follow the instructions on this url https://help.github.com/articles/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent/
	  until you have created and setup your SSH PUBLIC KEY

	4)Clone your github repository:
	   git clone git@github.com:yourGithubUserName/lh-ehr.git lh-ehr
	  (now your github repository is called ‘origin’)

	5) copy the lh-ehr folder from the directory you cloned it to, to your server

	6)Move into the lh-ehr folder on your server from the terminal:
	  cd lh-ehr

	7) Set up a connection to the main LibreEHR github respository:
	   git remote add upstream git://github.com/LibreHealthIO/lh-ehr.git
	   (now the main LibreHealthEHR github repository is called ‘upstream’)

	8) All the pieces are now set up. You have your local repository that is connected to your public github repository(origin) and is connected to the
	   official Libreehr github repository(upstream)
 	
//CONTRIBUTING CODE
Make sure your master is up to date

° git checkout master
° git pull upstream master

Create a working branch

- git checkout -b workbranchname  (use a good branch name, with ticket#ID if possible)

- ... make your changes to the code
- ... test your changes to make sure they work and are on track

° git add ... 'add' ONLY the files that need to be delivered to the (this is called staging)
° git commit -m "Good short comment about the fix including the issue#" 
° git push origin <workbranchname> 

NOTE before creating any new branch to work on run the commands below on your terminal so as to get latest changes
° git checkout master
° git pull upstream master

