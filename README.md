# Symbiosis API with SILEX
## Install
    - composer.phar install

#### Run serve
    - php -S localhost:8080 -t serve serve/index.php

#### BDD SQLite:
  - GRAINE [ latitude/longitude/nom/date/parent ]
    > one to many User.parent
  - USER  [ nom/prenom/email/parent ]
    > one to many GRAINE.parent
    > one to many POLLEN.user
  - POLLEN [ user/latitude/longitude ]

## Roadmap
#### GET :
  - /users/
    > return all
  - /graines/
    > return all
  - /pollens/
    > return all
  - /graines/latitude=x&longitude=y&perimeter=d
    > return distance between one pts (x,y) and all seeds(x,y) in perimeter (d)
  - /graines/id=x
    > return childs and parent of a seed
  - /users/id=x
    > return all childs foreach colonies for an user

#### POST :
  - /users/
  - /graines/
  - /pollens/

</br>
###### nb: /etc/php.ini -> timezone('UTC')
