DROP TABLE IF EXISTS "config";
CREATE TABLE "config" ("property" VARCHAR PRIMARY KEY  NOT NULL ,"value" VARCHAR NOT NULL );
INSERT INTO "config" VALUES('hostname','localhost');
INSERT INTO "config" VALUES('port','8080');
INSERT INTO "config" VALUES('username','xbmc');
INSERT INTO "config" VALUES('password','xbmc');
INSERT INTO "config" VALUES('proxyLocation','');
INSERT INTO "config" VALUES('isConfigured','0');

DROP TABLE IF EXISTS "user";
CREATE TABLE "user" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "role" VARCHAR NOT NULL  DEFAULT user, "username" VARCHAR NOT NULL  UNIQUE , "password" VARCHAR NOT NULL );

INSERT INTO "user" ('role','username','password') VALUES('admin','admin','admin');
