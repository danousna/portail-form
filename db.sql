CREATE TABLE `ideas` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `content` varchar(255),
    `user` varchar(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `votes` (
    `idea` int(11) NOT NULL,
    `user` varchar(255)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `images` (
    `image` varchar(255) NOT NULL,
    `idea` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `votes`
ADD PRIMARY KEY (`idea`,`user`);

ALTER TABLE `votes`
ADD CONSTRAINT `votes_fk_idea` FOREIGN KEY (`idea`) REFERENCES `ideas` (`id`);

ALTER TABLE `images`
ADD PRIMARY KEY (`image`);

ALTER TABLE `images`
ADD CONSTRAINT `images_fk_idea` FOREIGN KEY (`idea`) REFERENCES `ideas` (`id`);