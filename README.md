# API with SILEX Framework

## USE Doctrine, Sqlite, Security Service Provider, Capistrano...
##### q.tshaimanga@gmail.com

## Install localy
````
  - curl -s http://getcomposer.org/installer | php
  - mv composer.phar /usr/local/bin/composer
  - composer install
````

### Run dev serve
````
  - php -S localhost:8000 -t ./ ./index.php
````

## Build and Production in api.air-edf.io/project
````
  - [server] add /var/www/api/projectName
  - set deploy.rb
```
```
  - After git add . / commit / push
  - cap production deploy (set server info before automatic install)
````

## Apache configuration if deploy in other folder
````
  - cap production apache:conf  
````

### App crash > rollbask to the previous release
````
  - cap production deploy:rollback
````
