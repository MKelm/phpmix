CREATE TABLE `bg_galleries` (
`id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bg_images` (
`id` int(10) unsigned NOT NULL,
  `gallery_id` int(10) unsigned NOT NULL,
  `name` varchar(64) NOT NULL,
  `ext` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bg_tags` (
  `gallery_id` int(10) unsigned NOT NULL,
  `image_id` int(10) unsigned NOT NULL,
  `tag` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bg_users` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `bg_galleries`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);

ALTER TABLE `bg_images`
 ADD PRIMARY KEY (`id`), ADD KEY `gallery_id` (`gallery_id`), ADD KEY `name_ext` (`name`,`ext`);

ALTER TABLE `bg_tags`
 ADD KEY `gallery_id` (`gallery_id`), ADD KEY `tag` (`tag`), ADD KEY `image_id` (`image_id`);

ALTER TABLE `bg_users`
 ADD PRIMARY KEY (`id`), ADD KEY `name` (`name`), ADD KEY `email` (`email`), ADD KEY `password` (`password`);

ALTER TABLE `bg_galleries`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `bg_images`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `bg_users`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
