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
* The filename of the logo must be exactly the same as the band name is in the URL of their page on last.fm. For example, say you want to add the logo of Four Year Strong. Find them on last.fm and look at the url: http://www.last.fm/music/Four+Year+Strong, so the name of the logo must be Four+Year+Strong.png (or .jpg or .gif).
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
IF YOU DON'T KNOW WHAT THIS IS, DON'T USE IT!
The settings for this are in classes/Slaves.php. 
* On your master server (the one that will handle user requests), set SERVER_TYPE = 1, and ENABLE_SLAVES = true.
* On your slave server(s) set SERVER_TYPE = 2
* You need to add each of your slave servers to the slave array in your MASTER's Slaves.php. For example, if you have a slave with the script installed at http://www.example.com/bandlogos and one at http://www.somewhere.net you would write the following:

	$slave_array = array(
	array("url"=>"http://www.example.com/bandlogos","weight"=>1),
	array("url"=>"http://www.somewhere.net","weight"=>1)
	);

Notice that no trailing forward slash is added after the URL.
* Weight: this allows you to distribute the load unevenly across several servers. For example, say you have one slave and want your master server to handle three times as many requests as the slave, set MASTER_WEIGHT = 3 and the slave's weight to 1.