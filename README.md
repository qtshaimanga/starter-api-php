# Symbiosis API with SILEX
## Install
    - composer.phar install

#### Run serve
    - php -S localhost:8080 -t serve serve/index.php

#### BDD SQLite:
  - GRAINE [ latitude/longitude/nom/date/parent ]
    > one to many User.parent

  - USER  [ pseudo/date/email/parent/mdp ]
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

  - /users/id={id}&param={param}
    > return user with param : all or pseudo/date/email/parent/mdp

  - /graines/latitude=x&longitude=y&perimeter=d
    > return distance between one pts (x,y) and all seeds(x,y) in perimeter (d)

  - /graines/id=x
    > return childs and parent of a seed

  - /user/parents/id=x
    > return all childs foreach colonies for an user

#### POST :
  - /user/
  - /graine/
  - /pollen/

#### PUT :
  - /users/id={id}
    > update an user

</br>
###### nb: /etc/php.ini -> timezone('UTC')
