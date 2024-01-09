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
sudo apt install apache2 redis-server -y
sudo apt install php libapache2-mod-php php-mysql composer php-redis php-zip -y
```
2. Clone the repository
```bash
cd /var/www/html
git clone https://github.com/ds2600/arwt.git && cd arwt
```
3. Create a new virtual host configuration  
*Ensure you replace all instances of &lt;your-domain&gt; with your actual domain.*
```bash
sudo vi /etc/apache2/sites-available/<your-domain>.conf
```

4. Add the following to the &lt;your-domain&gt;.conf file
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

5. Enable the Virtual Host and mod_rewrite, disable the default Apache site
```bash
sudo a2ensite <your-domain>.conf
sudo a2enmod rewrite
sudo a2dissite 000-default.conf
```
6. Reload Apache
```bash
sudo systemctl reload apache2
```

7. Run the installation script, this will set file permissions and create a <code>config.php</code> and <code>.env</code> file.
```bash
sudo chmod +x install.sh
sudo ./install.sh
```

8. Modify the <code>arwt/config/config.php</code> file to reflect your configuration.
```bash
vi config/config.php
```
```php
// Site Configuration
return [
    'callsign' => '', // Your call sign
    'gmrs_callsign' => '', // Your GMRS call sign, if applicable. Optional.
    'base_url' => '', // Base URL of the web host, probably your domain
    'uls_search' => false, // Enable or disable FCC ULS search
    'uls_search_limit' => 5, // Limit the number of ULS searches per hour
    'redis_cache' => false, // Enable Redis Cache for FCC ULS searches
    'debug' => false, // Enable or disable debug mode
];
```

---  

**Steps 9-10 are only applicable if you are using the FCC ULS search, otherwise, skip to step 11.**  

--- 

9. Modify the <code>.env</code> file with your database information.
```bash
vi .env
```
```
DB_HOST=localhost
DB_NAME=ULSDATA
DB_USER=root
DB_PASS=password
```

10. In your web browser, navigate to <your-domain>/install.php, and follow the installation process. This process will download and install the latest FCC ULS database information.  
> The FCC usually updates it's weekly file on Saturdays.  
> To prevent missing data, it's recommended that you complete Step 10 on a Sunday after 12:00PM Eastern time. 


11. Once everything is successful, make sure to delete <code>public/install.php</code>.


