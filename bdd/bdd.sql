DROP TABLE IF EXISTS "USER";
CREATE TABLE "USER" ("nom" VARCHAR,"prenom" VARCHAR,"email" VARCHAR,"entreprise" VARCHAR,"mdp" VARCHAR,"role" VARCHAR DEFAULT (null) , "salt" VARCHAR);
INSERT INTO "USER" VALUES('quentin','tshaimanga','yolo@gmail.com','edf','Dgac9OBUal1luy5H2TTahka5w38TMlMfYrhX+pmST+isuSE09tOVv0qjx0Msx4XXxehHr9NS8tjeWrx8lcL2/A==','ROLE_USER','cocacola');

-- salt + password
-- O017j3WNRjeJSCZitaCJ7o484oVYF+9AP/Wne9fpgCv7ILBduQlz2yJ1+46MkMzbDovhpodzLBdKL7L2A/ZtzQ==
-- salt + foo
-- Dgac9OBUal1luy5H2TTahka5w38TMlMfYrhX+pmST+isuSE09tOVv0qjx0Msx4XXxehHr9NS8tjeWrx8lcL2/A==
