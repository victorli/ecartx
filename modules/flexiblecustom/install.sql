DROP TABLE IF EXISTS `PREFIX_flexiblecustom_module_group_lang`;
CREATE TABLE `PREFIX_flexiblecustom_module_group_lang` (
  `group_id` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `id_shop` int(10) unsigned NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`group_id`,`id_lang`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
DROP TABLE IF EXISTS `PREFIX_flexiblecustom_module_group_products`;
CREATE TABLE `PREFIX_flexiblecustom_module_group_products` (
  `module_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `ordering` int(10) unsigned NOT NULL,
  PRIMARY KEY (`module_id`,`group_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
DROP TABLE IF EXISTS `PREFIX_flexiblecustom_module_groups`;
CREATE TABLE `PREFIX_flexiblecustom_module_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` int(10) unsigned NOT NULL,
  `categoryId` int(10) unsigned NOT NULL,
  `productCount` int(10) unsigned NOT NULL,
  `maxItem` tinyint(3) unsigned NOT NULL,
  `productIds` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('auto','manual') COLLATE utf8_unicode_ci NOT NULL,
  `ordering` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `icon` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `iconActive` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `params` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
DROP TABLE IF EXISTS `PREFIX_flexiblecustom_modules`;
CREATE TABLE `PREFIX_flexiblecustom_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned NOT NULL,
  `params` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `position` int(10) unsigned NOT NULL,
  `position_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `ordering` int(10) unsigned NOT NULL,
  `note` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
DROP TABLE IF EXISTS `PREFIX_flexiblecustom_modules_lang`;
CREATE TABLE `PREFIX_flexiblecustom_modules_lang` (
  `module_id` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `id_shop` int(10) unsigned NOT NULL,
  `module_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `banners` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`module_id`,`id_lang`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
