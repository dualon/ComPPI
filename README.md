README
======

What is ComPPI?
---------------

Comppi is a Compartmentalized Protein-Protein Interaction database

Requirements
------------

The ComPPI software is currently built on the top of Symfony2.
Please refer to "[Symfony2][1]" requirements and supported databases.
To install Symfony2 dependecies git is recommended,but you can also download it manually.

Installation
------------

Get the ComPPI code:

    $ git clone git@github.com:erenon/ComPPI.git
    
Locate the configuration parameters file and adjust the settings related to database connection to your environment.

    $ mv ./app/config/parameters.ini.dist ./app/config/parameters.ini
    $ vi ./app/config/parameters.ini

Install symfony dependencies:

    $ ./bin/vendors install
    
Build the database
------------------

The plaintext database files are located under `./src/Comppi/LoaderBundle/Resources/databases`. To load them into the configured database, issue the following command:

    $ ./bin/build_database.sh
    
Visit demo page
---------------

Assuming you put the project under your HTTP servers document root into the directory named `comppi`, you can access to the demo page via the following url:

    http://localhost/comppi/web/app_dev.php
    
If everything is went fine (including you have already loaded the plaintext files into your database), you have to see the listing of the available localizations. Clicking on each localization will show every interaction in that area in C'elegans. Generating these reports may take a moment for the first time.

[1]: http://symfony.com/
