DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`(
    `id` INT AUTO_INCREMENT,

    `full_name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,

    `username` VARCHAR(127) NOT NULL,
    `password` VARCHAR(127) NOT NULL,

    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`(
    `id` INT AUTO_INCREMENT,

    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,

    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    `updated_by` INT NOT NULL ,
    `created_by` INT NOT NULL ,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
);

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`(
    `id` INT AUTO_INCREMENT,
    ``

    `ean` CHAR(13) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `image` CHAR(32) NOT NULL,
    `price` DECIMAL(19, 4) NOT NULL,
    `description` TEXT,

    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    `updated_by` INT NOT NULL ,
    `created_by` INT NOT NULL ,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
);

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts`(
    `id` INT AUTO_INCREMENT,

    `description` TEXT NOT NULL,

    `owner_type` VARCHAR(64) NOT NULL,
    `owner_id` INT NOT NULL,

    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    `updated_by` INT NOT NULL ,
    `created_by` INT NOT NULL ,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
);

DROP TABLE IF EXISTS `account_operations`;
CREATE TABLE `account_operations`(
    `id` INT AUTO_INCREMENT,
    `account_id` INT NOT NULL,

    `amount` DECIMAL(19, 4) NOT NULL,
    `description` TEXT NOT NULL,

    `debited_from` INT,

    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT NOT NULL ,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`),
    FOREIGN KEY (`debit_from`) REFERENCES `accounts`(`id`)
);

DROP TABLE IF EXISTS `bierclub_members`;
CREATE TABLE `bierclub_members`(
    `id` INT AUTO_INCREMENT,

    `full_name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `image` VARCHAR(40) NOT NULL,
    `document` VARCHAR(40) NOT NULL,
    `card` VARCHAR(127) NOT NULL,

    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    `updated_by` INT NOT NULL,
    `created_by` INT NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
);


DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales`(
    `id` INT AUTO_INCREMENT,

    `observations` TEXT,
    `total` DECIMAL(19, 4) NOT NULL,

    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT NOT NULL ,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
);


DROP TABLE IF EXISTS `sale_items`;
CREATE TABLE `sale_items`(
    `id` INT AUTO_INCREMENT,
    `product_id` INT NOT NULL,
    `sale_id` INT NOT NULL,

    `quantity` SMALLINT,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
    FOREIGN KEY (`sale_id`) REFERENCES `sales`(`id`)
);
