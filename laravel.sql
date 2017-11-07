--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `firstname` varchar(255) NOT NULL,
    `lastname` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `remember_token` varchar(100) DEFAULT NULL,
    `is_admin` tinyint(1) NOT NULL DEFAULT 0,
    `new_comments` tinyint(4) NOT NULL DEFAULT 0,
    `avatar_path` varchar(100) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB;

--
-- Table structure for table `albums`
--

DROP TABLE IF EXISTS `albums`;
CREATE TABLE `albums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `albums_users_id_fk` (`user_id`),
  CONSTRAINT `albums_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `path` text NOT NULL,
    `path_thumb` varchar(191) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `album_image`;
CREATE TABLE `album_image` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `album_id` int(10) unsigned NOT NULL,
    `image_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `album_image_albums_id_fk` (`album_id`),
    KEY `album_image_images_id_fk` (`image_id`),
    CONSTRAINT `album_image_albums_id_fk` FOREIGN KEY (`album_id`) REFERENCES `albums` (`id`) ON DELETE CASCADE,
    CONSTRAINT `album_image_images_id_fk` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `text` text NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `image_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `image_owner_id` int(10) unsigned NOT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_users_id_fk` (`user_id`),
  KEY `comments_images_id_fk` (`image_id`),
  KEY `comments_image_owner_users_id_fk` (`image_owner_id`),
  CONSTRAINT `comments_image_owner_users_id_fk` FOREIGN KEY (`image_owner_id`)
    REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_images_id_fk` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`),
  CONSTRAINT `comments_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;


--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2017_10_15_200007_create_album_table',2),(5,'2017_10_17_204413_update_users_table',3),(7,'2017_10_20_205344_create_image_table',5),(8,'2017_10_20_212107_create_image_album_table',6),(18,'2014_10_12_000000_create_users_table',7),(19,'2014_10_12_100000_create_password_resets_table',7),(20,'2017_10_15_104211_create_items_table',7),(21,'2017_10_17_204528_update_users_table',7),(24,'2017_10_20_212250_create_albums_table',8),(25,'2017_10_20_212504_create_images_table',8),(26,'2017_10_20_212632_create_image_album_table',8),(31,'2017_06_26_095610_CreateCommentsTable',9),(32,'2017_06_26_194528_ChangeCommentsTable',9),(33,'2017_10_24_203512_add_thumb_path',10),(34,'2017_10_28_185322_create_comments_table',11),(36,'2017_10_29_195957_add_new_comments_to_user',12),(37,'2017_11_05_120137_add_avatar_user',13);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB ;
