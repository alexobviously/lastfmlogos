## Installation
1. Download the [source files](https://github.com/alexobviously/lastfmlogos)
2. Download the [latest logos]([will provide link later])
3. You need a installation of Apache/PHP5/MySQL/gd2
4. Change the settings in */classes/bd/DBConfig.class.php* and */classes/Config.class.php*
5. Run the SQL script */install.sql*
6. Extract all logos to */logos/*, go to admin.php in your browser and run the Fill DB command
7. Make sure the /cache folder has read and write permissions

## Usage
* To add new logos, run Fill DB in admin again
* To update users' banners, delete 

## Adding a new layout
Look at the *Layout* interface for detail guidelines.

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
