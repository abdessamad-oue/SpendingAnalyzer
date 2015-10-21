Spending Analyzer Web App
========================

Welcome to the Spending Analyzer  - 
I developed this web application to 
do a simple analysis for my personal bank account spending ..

I share this application with the community..

You just need a personal web server to host the application 

It is based on the Symfony Standard edition (2.3) 

You do not have to provide your bank access!
You can enter your transations in manually with forms or easily import all transactions via a file.

### What we can do with the App

 - You can see your banking by categories and by type (CREDIT  / DEBIT ) 
 - You will be able to import your transactions by uploading file ....
 - You can add transactions manually
 - You can add multiple account


### You will need

 * PHP 5.4+
 * Mysql5.5+  
 * Apache2.2 or Apache2.4
 * composer
 

### Initial setup and configuration
```sh
git clone https://github.com/abdessamad-oue/SpendingAnalyzer.git
cp app/config/parameters.yml.dist app/config/parameters.yml
curl -s http://getcomposer.org/installer | php --

```
### Setup filesystem permissions


```sh
cd /path/to/SpendingAnalyzer
sudo mkdir app/{cache,logs,sessions,files}
sudo chown -R `whoami`:www-data app/cache app/logs app/sessions app/files
sudo chmod -R 777 app/cache app/logs app/sessions
sudo chmod -R 775 app/files
sudo chown -R `whoami`:www-data web/
sudo chmod -R 775 web/
```

### Install vendors

```sh
  cd /path/to/SpendingAnalyzer
  php composer.phar update
```


Edit app/config/parameters.yml and change username and password to access to your Mysql SGBD



### DATABASE

Create database and tables :
 
```sh
php app/console doctrine:database:create --env=prod
php app/console doctrine:schema:create --env=prod
```

Save a initial data 
```sh
php app/console SpendingAnalyzer:initialdata --env=prod
```

### CREATE YOUR USER
```sh
php app/console SpendingAnalyzer:createuser --env=prod
```

### ASSETS
Install assets (CSS, JS)

```sh
php app/console assets:install --env=prod
```

### Access by web browser

Create an apache virtual host :

For Apache 2.2
```sh
<VirtualHost *:80>
        ServerName YouServerName.ext
        DocumentRoot /PATH/TO/SpendingAnalyzer/web

        <Directory /PATH/TO/SpendingAnalyzer/web/web>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride all
                Order allow,deny
                allow from all
        </Directory>

        CustomLog ${APACHE_LOG_DIR}/spending-analyzer_access.log combined
        ErrorLog ${APACHE_LOG_DIR}/spending-analyzer_error.log

</VirtualHost>
```

For Apache 2.4
```sh
<VirtualHost *:80>
        ServerName YouServerName.ext
        DocumentRoot /PATH/TO/SpendingAnalyzer/web

        <Directory /PATH/TO/SpendingAnalyzer/web>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride all
                Require all granted               
        </Directory>

        CustomLog ${APACHE_LOG_DIR}/spending-analyzer_access.log combined
        ErrorLog ${APACHE_LOG_DIR}/spending-analyzer_error.log

</VirtualHost>
```


Activate your vHost :

```sh
    sudo a2ensite <yourVhostName>
```
Restart Apache

```sh
    sudo /etc/init.d/apache2 restart
```

And that's it.  
