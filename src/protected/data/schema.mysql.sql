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
	PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
	`id` INT PRIMARY KEY AUTO_INCREMENT NOT NULL, 
	`role` VARCHAR(255) NOT NULL DEFAULT "user", 
	`username` VARCHAR(255) NOT NULL, 
	`password` VARCHAR(255) NOT NULL
);

INSERT INTO `user` (`role`,`username`,`password`) VALUES('admin','admin','admin');