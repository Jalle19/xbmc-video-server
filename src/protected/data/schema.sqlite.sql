DROP TABLE IF EXISTS "backend";
CREATE TABLE "backend" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL, "name" VARCHAR NOT NULL, "hostname" VARCHAR NOT NULL, "port" INTEGER NOT NULL , "username" VARCHAR NOT NULL, "password" VARCHAR NOT NULL, "proxyLocation" VARCHAR, "default" INTEGER NOT NULL, "macAddress" VARCHAR, "subnetMask" VARCHAR);

DROP TABLE IF EXISTS "user";
CREATE TABLE "user" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "role" VARCHAR NOT NULL  DEFAULT user, "username" VARCHAR NOT NULL  UNIQUE , "password" VARCHAR NOT NULL, "language" VARCHAR );

INSERT INTO "user" ('role','username','password','language') VALUES('admin','admin','admin',NULL);

DROP TABLE IF EXISTS "settings";
CREATE TABLE "settings" ("name" VARCHAR PRIMARY KEY NOT NULL , "value" VARCHAR);

INSERT INTO "settings" ('name','value') VALUES ('applicationName','XBMC Video Server');
INSERT INTO "settings" ('name','value') VALUES ('singleFilePlaylist','0');
INSERT INTO "settings" ('name','value') VALUES ('showHelpBlocks','1');
INSERT INTO "settings" ('name','value') VALUES ('cacheApiCalls','0');
INSERT INTO "settings" ('name','value') VALUES ('pagesize','60');
INSERT INTO "settings" ('name','value') VALUES ('disableFrodoWarning','0');
INSERT INTO "settings" ('name','value') VALUES ('useHttpsForVfsUrls','0');
INSERT INTO "settings" ('name','value') VALUES ('whitelist','');
INSERT INTO "settings" ('name','value') VALUES ('ignoreArticle','0');
INSERT INTO "settings" ('name','value') VALUES ('language','en');
