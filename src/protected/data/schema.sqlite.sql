DROP TABLE IF EXISTS "backend";
CREATE TABLE "backend" (
	"id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL, 
	"name" VARCHAR NOT NULL, 
	"hostname" VARCHAR NOT NULL, 
	"port" INTEGER NOT NULL , 
	"username" VARCHAR NOT NULL, 
	"password" VARCHAR NOT NULL, 
	"proxyLocation" VARCHAR, 
	"default" INTEGER NOT NULL, 
	"macAddress" VARCHAR, 
	"subnetMask" VARCHAR);

DROP TABLE IF EXISTS "user";
CREATE TABLE "user" (
	"id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , 
	"role" VARCHAR NOT NULL  DEFAULT user, 
	"username" VARCHAR NOT NULL  UNIQUE , 
	"password" VARCHAR NOT NULL, 
	"language" VARCHAR
);

INSERT INTO "user" ('role','username','password','language') VALUES('admin','admin','admin',NULL);

DROP TABLE IF EXISTS "settings";
CREATE TABLE "settings" (
	"name" VARCHAR PRIMARY KEY NOT NULL , 
	"value" VARCHAR
);

INSERT INTO "settings" ('name','value') VALUES ('applicationName','XBMC Video Server');
INSERT INTO "settings" ('name','value') VALUES ('singleFilePlaylist','0');
INSERT INTO "settings" ('name','value') VALUES ('showHelpBlocks','1');
INSERT INTO "settings" ('name','value') VALUES ('cacheApiCalls','0');
INSERT INTO "settings" ('name','value') VALUES ('allowUserPowerOff','');
INSERT INTO "settings" ('name','value') VALUES ('pagesize','60');
INSERT INTO "settings" ('name','value') VALUES ('useHttpsForVfsUrls','0');
INSERT INTO "settings" ('name','value') VALUES ('whitelist','');
INSERT INTO "settings" ('name','value') VALUES ('ignoreArticle','0');
INSERT INTO "settings" ('name','value') VALUES ('language','en');
INSERT INTO "settings" ('name','value') VALUES ('playlistFormat','m3u');
INSERT INTO "settings" ('name','value') VALUES ('applicationSubtitle','Free your library');
INSERT INTO "settings" ('name','value') VALUES ('requestTimeout','30');

DROP TABLE IF EXISTS "display_mode";
CREATE TABLE "display_mode" (
	"id" integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	"user_id" integer NOT NULL REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
	"context" varchar(255) NOT NULL,
	"mode" varchar(255) NOT NULL
);