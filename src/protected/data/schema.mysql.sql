DROP TABLE IF EXISTS `backend`;
CREATE TABLE `backend` (
	`id` INT NOT NULL AUTO_INCREMENT, 
	`name` VARCHAR(255) NOT NULL, 
	`hostname` VARCHAR(255) NOT NULL, 
	`port` INT NOT NULL, 
	`username` VARCHAR(255) NOT NULL, 
	`password` VARCHAR(255) NOT NULL, 
	`proxyLocation` VARCHAR(255), 
	`default` INT NOT NULL,
    `macAddress` VARCHAR(255), 
	`subnetMask` VARCHAR(255), 
	PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
	`id` INT PRIMARY KEY AUTO_INCREMENT NOT NULL, 
	`role` VARCHAR(255) NOT NULL DEFAULT "user", 
	`username` VARCHAR(255) NOT NULL, 
	`password` VARCHAR(255) NOT NULL,
	`language` VARCHAR(255)
);

INSERT INTO `user` (`role`,`username`,`password`,`language`) VALUES('admin','admin','admin',NULL);

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
	`name` VARCHAR(255) PRIMARY KEY NOT NULL,
	`value` VARCHAR(255) NULL DEFAULT NULL
);

INSERT INTO `settings` (`name`,`value`) VALUES ('applicationName','XBMC Video Server');
INSERT INTO `settings` (`name`,`value`) VALUES ('singleFilePlaylist','0');
INSERT INTO `settings` (`name`,`value`) VALUES ('showHelpBlocks','1');
INSERT INTO `settings` (`name`,`value`) VALUES ('cacheApiCalls','0');
INSERT INTO `settings` (`name`,`value`) VALUES ('pagesize','60');
INSERT INTO `settings` (`name`,`value`) VALUES ('disableFrodoWarning','0');
INSERT INTO `settings` (`name`,`value`) VALUES ('useHttpsForVfsUrls','0');
INSERT INTO `settings` (`name`,`value`) VALUES ('whitelist','');
INSERT INTO `settings` (`name`,`value`) VALUES ('ignoreArticle','0');
INSERT INTO `settings` (`name`,`value`) VALUES ('language','en');
INSERT INTO `settings` (`name`,`value`) VALUES ('playlistFormat','m3u');
INSERT INTO `settings` (`name`,`value`) VALUES ('applicationSubtitle','Free your library');

DROP TABLE IF EXISTS `display_mode`;
CREATE TABLE `display_mode` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`user_id` INT NOT NULL REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
	`context` VARCHAR(255) NOT NULL,
	`mode` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`)
);