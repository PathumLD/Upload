CREATE TABLE `storage` (
  `file_name` varchar(255) NOT NULL,
  `file_mime` varchar(255) NOT NULL,
  `file_data` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `storage`
  ADD PRIMARY KEY (`file_name`);