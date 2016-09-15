# API with SILEX

## USE Doctrine, Sqlite, Security Service Provider, Capistrano...
##### by q.tshaimanga@gmail.com

## Install localy
  - curl -s http://getcomposer.org/installer | php
  - mv composer.phar /usr/local/bin/composer
  - composer install

#### Run dev serve
  - php -S localhost:8000 -t serve serve/index.php

#### Prod
  - cap production deploy
  - cap production apache:conf
