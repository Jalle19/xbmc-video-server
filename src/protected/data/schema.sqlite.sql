DROP TABLE IF EXISTS "backends";
CREATE TABLE "backend" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL, "name" VARCHAR NOT NULL, "hostname" VARCHAR NOT NULL, "port" INTEGER NOT NULL , "username" VARCHAR NOT NULL, "password" VARCHAR NOT NULL, "proxyLocation" VARCHAR, "default" INTEGER NOT NULL);

DROP TABLE IF EXISTS "user";
CREATE TABLE "user" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "role" VARCHAR NOT NULL  DEFAULT user, "username" VARCHAR NOT NULL  UNIQUE , "password" VARCHAR NOT NULL );

INSERT INTO "user" ('role','username','password') VALUES('admin','admin','admin');