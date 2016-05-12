# Symbiosis API with SILEX
### Install
    - composer.phar install

#### Run serve
    - php -S localhost:8080 -t serve serve/index.php

### BDD SQLite:
    - GRAINE [ latitude/longitude/nom/date/parent ]
    one to many User.parent
    - USER  [ nom/prenom/email/parent ]
    one to many GRAINE.parent
    one to many POLLEN.user
    - POLLEN [ user/latitude/longitude ]

### Roadmap
  #### GET :
    - /users/
    RETURN all
    - /graines/
    RETURN all
    - /pollens/
    RETURN all
    - /graines/latitude=x&longitude=y&perimeter=d
    RETURN distance between one pts (x,y) and all seeds(x,y) in perimeter (d)
    - /graines/id=x
    RETURN childs and parent of a seed
    - /users/id=x
    RETURN all childs foreach colonies for an user


  #### POST :
    - /users/
    - /graines/
    - /pollens/

</br>
###### nb: /etc/php.ini -> timezone('UTC')
