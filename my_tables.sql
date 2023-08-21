DROP TABLE IF EXISTS `player`;
CREATE TABLE if not EXISTS `player`(
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `lastname` varchar (150) NOT NULL,
    `firstname` varchar (150) NOT NULL,
    `nickname` varchar (150) NOT NULL,
    PRIMARY KEY (`id`))

DROP TABLE IF EXISTS `group`;
CREATE TABLE if not EXISTS `group`; (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `label` varchar (150) NOT NULL,
    `player1_id` varchar (150) NOT NULL,
    `player2_id` varchar (150) NOT NULL,
    `player3_id` varchar (150) NOT NULL,
    `player4_id` varchar (150) NOT NULL,
    PRIMARY KEY (`id`))