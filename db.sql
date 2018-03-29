CREATE TABLE `ideas` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `content` varchar(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `votes` (
    `idea` int(11) NOT NULL,
    `user` varchar(255)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `votes`
ADD PRIMARY KEY (`idea`,`user`);

ALTER TABLE `votes`
ADD CONSTRAINT `votes_fk_idea` FOREIGN KEY (`idea`) REFERENCES `ideas` (`id`);