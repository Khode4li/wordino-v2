<?php
require_once "vendor/autoload.php";
require_once "system/config.php";

/*
INSERT INTO `user` (`id`, `username`, `password`, `role_id`) VALUES
(4, 'mamad', 'b0b50c14e7d65c9560cfe4061506d019', '2')
 */

echo "enter owner username: ";
$user = fgets(STDIN);
$user = trim($user);

echo "enter owner password: ";
$pass = fgets(STDIN);
$pass = trim($pass);


$conn = \system\registry\registry::get('dbConn');

$conn->query("
SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
START TRANSACTION;
SET time_zone = \"+00:00\";

CREATE TABLE `groups` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `permission` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `permission` (`id`, `name`) VALUES
(4, 'createGroup'),
(5, 'deleteGroup'),
(6, 'addWordlistToGroup'),
(7, 'removeWordlistFromGroup'),
(8, 'seeAllGroups'),
(9, 'seeWordlistsOfAGroup'),
(10, 'seeWordsOfAGroup'),
(11, 'seeGroupsWordsCount'),
(12, 'seeAllRoles'),
(13, 'seeAllPerms'),
(14, 'createNewRole'),
(15, 'editRolePermissions'),
(16, 'deleteRolePermissions'),
(17, 'addNewUser'),
(18, 'seeAllUsers'),
(19, 'changeOtherUsersRole'),
(20, 'seeOtherUsersPermissions'),
(21, 'deleteOtherUsersAccount'),
(22, 'addNewWordToWordlists'),
(23, 'createWordlist'),
(24, 'deleteWordlist'),
(25, 'seeWordlistWords'),
(26, 'seeAllWordlists'),
(27, 'seeWordlistsWordsCount'),
(28, 'addMultipleWordToWordlists'),
(29, 'addWordsToWordlistFromUrl');


CREATE TABLE `role` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


INSERT INTO `role` (`id`, `name`) VALUES
(1, 'owner');

CREATE TABLE `roleHasPerm` (
  `id` int NOT NULL,
  `perm_id` int NOT NULL,
  `role_id` int NOT NULL,
  `has_access` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `wlistGroup` (
  `id` int NOT NULL,
  `wlid` int NOT NULL,
  `gid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `wlistWords` (
  `id` int NOT NULL,
  `wlid` int NOT NULL,
  `wid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `wordlist` (
  `id` int NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `words` (
  `id` int NOT NULL,
  `word` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roleHasPerm`
--
ALTER TABLE `roleHasPerm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wlistGroup`
--
ALTER TABLE `wlistGroup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wlistWords`
--
ALTER TABLE `wlistWords`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wlid_wid` (`wlid`,`wid`);

--
-- Indexes for table `wordlist`
--
ALTER TABLE `wordlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `words`
--
ALTER TABLE `words`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_word` (`word`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `roleHasPerm`
--
ALTER TABLE `roleHasPerm`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wlistGroup`
--
ALTER TABLE `wlistGroup`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wlistWords`
--
ALTER TABLE `wlistWords`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131087;

--
-- AUTO_INCREMENT for table `wordlist`
--
ALTER TABLE `wordlist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `words`
--
ALTER TABLE `words`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119626;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
");

$conn->query("INSERT INTO `user` (`id`, `username`, `password`, `role_id`) VALUES
(4, :username, :password, '1')", [':username' => $user, ':password' => md5($pass.\system\registry\registry::get('salt'))]);