
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- members
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `members`;

CREATE TABLE `members`
(
    `id` INTEGER,
    `firstname` VARCHAR(50),
    `surname` VARCHAR(50),
    `email` VARCHAR(50),
    `gender` VARCHAR(50),
    `joined_date` DATE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
