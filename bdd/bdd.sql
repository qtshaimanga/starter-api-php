DROP TABLE IF EXISTS "USER";
CREATE TABLE "USER" ("nom" VARCHAR,"prenom" VARCHAR,"email" VARCHAR,"entreprise" VARCHAR,"mdp" VARCHAR,"role" VARCHAR DEFAULT (null) , "salt" VARCHAR);
INSERT INTO "USER" VALUES('quentin','tshaimanga','yolo@gmail.com','edf','O017j3WNRjeJSCZitaCJ7o484oVYF+9AP/Wne9fpgCv7ILBduQlz2yJ1+46MkMzbDovhpodzLBdKL7L2A/ZtzQ==','ROLE_USER','cocacola');
