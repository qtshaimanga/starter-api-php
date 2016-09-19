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

## Build and Production
````
  - [server] add /var/www/api/projectName
```
```
  - After git add . / commit / push
  - cap production deploy (automatic install)
  - cap production apache:conf
````

### App crash > rollbask to the previous release
````
  - cap production deploy:rollback
````



<!-- api.air-edf.io/projectName
projectName.air-edf.io
cf.apache.rake et apache-conf.erb
-->
