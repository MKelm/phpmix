CREATE TABLE IF NOT EXISTS `pc_hits` (
  `uid` varchar(32) NOT NULL,
  `time` double NOT NULL,
  `host` varchar(64) NOT NULL,
  `uri` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pc_visits` (
  `uid` varchar(32) NOT NULL,
  `country` varchar(4) NOT NULL,
  `time` double NOT NULL,
  `host` varchar(64) NOT NULL,
  `uri` varchar(64) NOT NULL,
  `browser` varchar(20) NOT NULL,
  `version` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pc_hits`
 ADD PRIMARY KEY (`uid`,`time`), ADD KEY `host` (`host`), ADD KEY `uri` (`uri`);

ALTER TABLE `pc_visits`
 ADD PRIMARY KEY (`uid`,`time`), ADD KEY `country` (`country`), ADD KEY `host` (`host`), ADD KEY `uri` (`uri`), ADD KEY `browser` (`browser`);
