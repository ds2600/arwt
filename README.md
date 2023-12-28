# Amateur Radio Website Template

## Description

A dead simple web site template for hams to use. It's not special. It's not unique. It can be heavily customized, though.  
Right now, there's some simple page templates, with possibly a gallery and integration with various log books (Cloudlog, LOTW, etc) on my radar.

## FCC ULS Search
The functionality to search the FCC ULS database is built in, as long as you put in some extra work. There are existing scripts out there, I've [forked](https://www.github.com/ds2600/FCCULS-mysql2) one of them and am working on updating it to make it actually usable without wasting a bunch of time.  

## Demo
You can view a limited demo [here](http://arwt.ds2600.com). It doesn't currently have the FCC ULS search functional, but I'm working on that. 😁

## Requirements
- Ubuntu 22.04 LTS
- Domain Name
- For FCC ULS database search:
  - MySQL database with the following tables:
    - PUBACC_AM
    - PUBACC_EN
    - PUBACC_SF

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Installation

1. Install Apache, PHP and Extensions
```bash
sudo apt update
sudo apt install apache2 -y
sudo apt install php libapache2-mod-php php-mysql composer -y
```
2. Clone the repository
```bash
cd /var/www/html
git clone https://github.com/ds2600/arwt.git
```
3. Set Permissions
```bash
sudo chown -R www-data:www-data /var/www/html/arwt
sudo chmod -R 755 /var/www/html/arwt
```

4. Create a new virtual host configuration  
*Ensure you replace all instances of &lt;your-domain&gt; with your actual domain.*
```bash
sudo vi /etc/apache2/sites-available/<your-domain>.conf
```

5. Add the following to the &lt;your-domain&gt;.conf file
```apacheconf
<VirtualHost *:80>
    ServerAdmin admin@<your-domain>.com
    ServerName <your-domain>.com
    ServerAlias www.<your-domain>.com
    DocumentRoot /var/www/html/arwt/public

    <Directory /var/www/html/arwt/public>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```
6. Enable the Virtual Host and mod_rewrite, disable the default Apache site
```bash
sudo a2ensite <your-domain>.conf
sudo a2enmod rewrite
sudo a2dissite 000-default.conf
```
7. Reload Apache
```bash
sudo systemctl reload apache2
```

8. Create an ARWT configuration file
```bash
cd /var/www/html/arwt
cp config/config.example.php config/config.php
```

9. Using your favorite text editor, edit the newly created configuration file
```bash
vi config/config.php
```

```php
// Site Configuration
return [
	// Enter your call sign
	'callsign' => 'KVARWT', 
	// Enter your database information below, leave empty if not using the FCC ULS Search
	'db_host' => '', 
	'db_user' => '',
	'db_pass' => '',
	'db_name' => 'ULSDATA',
	// Enable or disable the FCC ULS search feature - leave as false unless you know otherwise
	'uls_search' => false,
	// Limit the number of ULS searches per hour - can be left as default
	'uls_search_limit' => 10,
	// Enable or disable debug mode - limited current use
	'debug' => false,
];
```

10. Navigate to the base directory and install dependencies
```bash
cd /var/www/html/arwt  
composer install
```

11. Navigate to *&lt;your-domain&gt;* for further instruction.


