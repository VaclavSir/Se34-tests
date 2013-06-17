CREATE TABLE `guestbook` (
	`id` INT PRIMARY KEY auto_increment,
	`name` VARCHAR(255) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`message` TEXT,
	`submission_time` DATETIME NOT NULL,
	`ip_address` VARCHAR(255) NOT NULL
);
