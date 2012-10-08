## Installation
1. Download the [source files](https://github.com/alexobviously/lastfmlogos)
2. Download the [latest logos]([will provide link later])
3. You need a installation of Apache/PHP5/MySQL/gd2
4. Change the settings in */classes/bd/DBConfig.class.php* and */classes/Config.class.php*
5. Run the SQL script */installation.sql*
6. Extract all logos to */logos/*
7. Make sure the */cache* folder has read and write permissions
8. Make sure */bans.php* has read and write permissions

## Usage
* To add new logos, simply put the image files in your logos folder. JPG, PNG and GIF are supported. Make sure the width of each logo is 150-160px and that they are black and white.
* To update users' banners, click Empty Cache in admin, or delete all of the images in the cache folder manually.
* Visit admin.php for more functions, including banning. There are instructions on the page.

## Adding a new layout
There are currently one column and two column layouts.
Look at their class files to figure out how to make your own.

## Security configuration

Authentication can be added to prevent unwanted calls to utility scripts.

**.htaccess**

Create a file with the following content.

    AuthName "Restricted Area" 
    AuthType Basic 
    AuthUserFile /some/path/.htpasswd 
    AuthGroupFile /dev/null 
    
    <Files admin*.php>
    require valid-user
    </Files>

**.htpasswd**

To create the password file use the following command.

    htpasswd -c .htpasswd adminuser

## Distributed Computing
This script now supports a distributed computing, implemented due to high load on the lastfmlogos.info server. If you have several servers that you want to distribute the load across then this is now possible with the master/slave system.
The settings for this are in classes/Slaves.php. On your master server (the one that will handle user requests), set SERVER_TYPE = 1, and 