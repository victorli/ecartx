DROP TABLE IF EXISTS `PREFIX_groupcategory_group_lang`;
CREATE TABLE `PREFIX_groupcategory_group_lang` (
  `group_id` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `id_shop` int(10) unsigned NOT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `banner` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `banner_link` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `banner_size` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`group_id`,`id_lang`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `PREFIX_groupcategory_groups`;
CREATE TABLE `PREFIX_groupcategory_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop_a` int(6) NOT NULL,
  `position` int(10) unsigned NOT NULL,
  `position_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `id_shop` int(10) unsigned NOT NULL,
  `categoryId` int(10) unsigned NOT NULL,
  `cat_type` enum('auto','manual') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'auto',
  `order_by` enum('seller','price','discount','date_add','position','review','view') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'position',
  `order_way` enum('asc','desc') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'desc',
  `on_condition` enum('all','new','used','refurbished') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'all',
  `on_sale` tinyint(2) unsigned NOT NULL DEFAULT '2',
  `on_new` tinyint(2) unsigned NOT NULL DEFAULT '2',
  `on_discount` tinyint(2) unsigned NOT NULL DEFAULT '2',
  `max_item` tinyint(3) unsigned NOT NULL DEFAULT '12',
  `params` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `is_cache` tinyint(4) NOT NULL DEFAULT '1',
  `type_default` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `style_id` int(10) unsigned NOT NULL,
  `layout` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `manufactureConfig` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `itemConfig` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `types` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `ordering` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `note` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `PREFIX_groupcategory_item_lang`;
CREATE TABLE `PREFIX_groupcategory_item_lang` (
  `itemId` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `id_shop` int(10) unsigned NOT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `banner` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `banner_link` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `banner_size` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`itemId`,`id_lang`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `PREFIX_groupcategory_items`;
CREATE TABLE `PREFIX_groupcategory_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupId` int(10) unsigned NOT NULL,
  `categoryId` int(10) unsigned NOT NULL,
  `cat_type` enum('auto','manual') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'auto',
  `order_by` enum('seller','price','discount','date_add','position','review','view') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'position',
  `order_way` enum('asc','desc') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'desc',
  `on_condition` enum('all','new','used','refurbished') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'all',
  `on_sale` tinyint(2) unsigned NOT NULL DEFAULT '2',
  `on_new` tinyint(2) unsigned NOT NULL DEFAULT '2',
  `on_discount` tinyint(2) unsigned NOT NULL DEFAULT '2',
  `max_item` tinyint(3) unsigned NOT NULL DEFAULT '12',
  `params` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `is_cache` tinyint(4) NOT NULL DEFAULT '1',
  `maxItem` tinyint(3) unsigned NOT NULL,
  `ordering` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `PREFIX_groupcategory_product_view`;
CREATE TABLE `PREFIX_groupcategory_product_view` (
  `productId` int(10) unsigned NOT NULL,
  `total` int(10) unsigned NOT NULL,
  PRIMARY KEY (`productId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `PREFIX_groupcategory_styles`;
CREATE TABLE `PREFIX_groupcategory_styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `params` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `PREFIX_groupcategory_group_lang` (`group_id`, `id_lang`, `id_shop`, `name`, `banner`, `banner_link`, `banner_size`) VALUES
(2,	1,	1,	'Fashion',	'2-1-1-banner.png',	'',	'{\"width\":281,\"height\":397,\"w_per_h\":0.708}'),
(2,	2,	1,	'Mode',	'2-2-1-banner.png',	'',	'{\"width\":281,\"height\":397,\"w_per_h\":0.708}'),
(5,	1,	1,	'Food',	'5-1-1-banner.png',	'#',	'{\"width\":281,\"height\":397,\"w_per_h\":0.708}'),
(5,	2,	1,	'Nourriture',	'5-2-1-banner.png',	'',	'{\"width\":281,\"height\":397,\"w_per_h\":0.708}'),
(6,	1,	1,	'Furniture',	'6-1-1-banner.png',	'',	'{\"width\":281,\"height\":397,\"w_per_h\":0.708}'),
(6,	2,	1,	'Meubles',	'6-2-1-banner.png',	'',	'{\"width\":281,\"height\":397,\"w_per_h\":0.708}'),
(7,	1,	1,	'Electronics',	'7-1-1-banner.png',	'',	'{\"width\":281,\"height\":397,\"w_per_h\":0.708}'),
(7,	2,	1,	'Electronics',	'7-2-1-banner.png',	'',	'{\"width\":281,\"height\":397,\"w_per_h\":0.708}'),
(8,	1,	1,	'Sports',	'8-1-1-banner.png',	'',	'{\"width\":281,\"height\":397,\"w_per_h\":0.708}'),
(8,	2,	1,	'Sports',	'8-2-1-banner.png',	'',	'{\"width\":281,\"height\":397,\"w_per_h\":0.708}'),
(9,	1,	1,	'Jewelry',	'9-1-1-banner.png',	'',	'{\"width\":281,\"height\":397,\"w_per_h\":0.708}'),
(9,	2,	1,	'Bijoux',	'9-2-1-banner.png',	'',	'{\"width\":281,\"height\":397,\"w_per_h\":0.708}');
INSERT INTO `PREFIX_groupcategory_groups` (`id`, `id_shop_a`, `position`, `position_name`, `id_shop`, `categoryId`, `cat_type`, `order_by`, `order_way`, `on_condition`, `on_sale`, `on_new`, `on_discount`, `max_item`, `params`, `is_cache`, `type_default`, `style_id`, `layout`, `manufactureConfig`, `itemConfig`, `types`, `icon`, `ordering`, `status`, `note`) VALUES
(2,	0,	167,	'displayGroupFashions',	1,	12,	'auto',	'position',	'desc',	'all',	2,	2,	2,	24,	'{\"features\":[\"seller\",\"special\"],\"manufacturers\":[\"1\"],\"products\":[]}',	1,	'arrival',	2,	'default',	'{\"ids\":[\"15\",\"20\",\"21\",\"22\"],\"imageWidth\":136,\"imageHeight\":68}',	'{\"itemWidth\":\"230\",\"itemHeight\":\"276\",\"itemMinWidth\":200,\"countItem\":24}',	'[\"saller\",\"special\"]',	'-icon.png',	7,	1,	''),
(5,	0,	168,	'displayGroupFoods',	1,	14,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"features\":[\"special\",\"arrival\"],\"manufacturers\":[\"1\"],\"products\":[]}',	1,	'arrival',	1,	'default',	'{\"ids\":[\"23\",\"24\",\"25\"],\"imageWidth\":136,\"imageHeight\":68}',	'{\"itemWidth\":\"230\",\"itemHeight\":\"276\",\"itemMinWidth\":200,\"countItem\":12}',	'[\"special\",\"arrival\"]',	'5-icon.png',	9,	1,	''),
(6,	0,	167,	'displayGroupFashions',	1,	13,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"features\":[\"seller\",\"view\"],\"manufacturers\":[\"1\"],\"products\":[]}',	1,	'view',	3,	'default',	'{\"ids\":[\"26\",\"33\",\"34\"],\"imageWidth\":136,\"imageHeight\":68}',	'{\"itemWidth\":\"230\",\"itemHeight\":\"276\",\"itemMinWidth\":200,\"countItem\":12}',	'[\"saller\",\"view\"]',	'6-icon.png',	8,	1,	''),
(7,	0,	168,	'displayGroupFoods',	1,	15,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"features\":[\"view\",\"special\"],\"manufacturers\":[\"1\"],\"products\":[]}',	1,	'view',	4,	'default',	'{\"ids\":[\"14\",\"16\",\"17\",\"18\",\"19\"],\"imageWidth\":136,\"imageHeight\":68}',	'{\"itemWidth\":\"230\",\"itemHeight\":\"276\",\"itemMinWidth\":200,\"countItem\":12}',	'[\"view\",\"special\"]',	'7-icon.png',	10,	1,	''),
(8,	0,	169,	'displayGroupSports',	1,	16,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"features\":[\"seller\",\"view\"],\"manufacturers\":[\"1\"],\"products\":[]}',	1,	'arrival',	6,	'default',	'{\"ids\":[\"30\",\"31\",\"32\"],\"imageWidth\":136,\"imageHeight\":68}',	'{\"itemWidth\":\"230\",\"itemHeight\":\"276\",\"itemMinWidth\":200,\"countItem\":12}',	'[\"saller\",\"view\"]',	'8-icon.png',	11,	1,	''),
(9,	0,	169,	'displayGroupSports',	1,	17,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"features\":[\"seller\",\"view\"],\"manufacturers\":[\"1\"],\"products\":[]}',	1,	'view',	5,	'default',	'{\"ids\":[\"27\",\"28\",\"29\"],\"imageWidth\":136,\"imageHeight\":68}',	'{\"itemWidth\":\"230\",\"itemHeight\":\"276\",\"itemMinWidth\":200,\"countItem\":12}',	'[\"saller\",\"view\"]',	'9-icon.png',	12,	1,	'');
INSERT INTO `PREFIX_groupcategory_item_lang` (`itemId`, `id_lang`, `id_shop`, `name`, `banner`, `banner_link`, `banner_size`) VALUES
(3,	1,	1,	'Street Style - edit',	'',	'',	''),
(3,	2,	1,	'Street style',	'',	'',	''),
(4,	1,	1,	'Designer',	'',	'',	''),
(4,	2,	1,	'Designer',	'',	'',	''),
(5,	1,	1,	'Dressess',	'',	'',	''),
(5,	2,	1,	'Dressess',	'',	'',	''),
(6,	1,	1,	'Accessories',	'',	'',	''),
(6,	2,	1,	'Accessories',	'',	'',	''),
(16,	1,	1,	'Pizza',	'16-5-1-1-banner.png',	'',	''),
(16,	2,	1,	'Pizza',	'16-5-2-1-banner.png',	'',	''),
(17,	1,	1,	'Noodle',	'17-5-1-1-banner.png',	'',	''),
(17,	2,	1,	'Nouille',	'17-5-2-1-banner.png',	'',	''),
(18,	1,	1,	'Cake',	'18-5-1-1-banner.png',	'',	''),
(18,	2,	1,	'G',	'18-5-2-1-banner.png',	'',	''),
(19,	1,	1,	'Drink',	'19-5-1-1-banner.png',	'',	''),
(19,	2,	1,	'Boisson',	'19-5-2-1-banner.png',	'',	''),
(20,	1,	1,	'Towels & Rugs',	'',	'',	''),
(20,	2,	1,	'Serviettes et tapis',	'',	'',	''),
(21,	1,	1,	'Step Stools',	'',	'',	''),
(21,	2,	1,	'Outils ',	'',	'',	''),
(22,	1,	1,	'Blankets',	'',	'',	''),
(22,	2,	1,	'Couvertures',	'',	'',	''),
(23,	1,	1,	'Shower Curtains',	'23-6-1-1-banner.png',	'#',	''),
(23,	2,	1,	'Rideaux de douche',	'23-6-2-1-banner.png',	'',	''),
(24,	1,	1,	'Bathtime Goods',	'24-6-1-1-banner.png',	'',	''),
(24,	2,	1,	'Biens de Bathtime',	'24-6-2-1-banner.png',	'',	''),
(25,	1,	1,	'Accessories',	'',	'',	''),
(25,	2,	1,	'Accessories',	'',	'',	''),
(26,	1,	1,	'Camera',	'',	'',	''),
(26,	2,	1,	'Cam',	'',	'',	''),
(27,	1,	1,	'Laptop',	'',	'',	''),
(27,	2,	1,	'Portatif',	'',	'',	''),
(28,	1,	1,	'Mobile',	'',	'',	''),
(28,	2,	1,	'Mobile',	'',	'',	''),
(29,	1,	1,	'Football',	'',	'',	''),
(29,	2,	1,	'Football',	'',	'',	''),
(30,	1,	1,	'Racing',	'',	'',	''),
(30,	2,	1,	'Courses',	'',	'',	''),
(31,	1,	1,	'Basketball',	'',	'',	''),
(31,	2,	1,	'Basketball',	'',	'',	''),
(32,	1,	1,	'Boxing',	'',	'',	''),
(32,	2,	1,	'Boxe',	'',	'',	''),
(34,	1,	1,	'Rings',	'',	'',	''),
(34,	2,	1,	'Anneaux',	'',	'',	''),
(35,	1,	1,	'Earrings',	'',	'',	''),
(35,	2,	1,	'Boucles doreilles',	'',	'',	''),
(36,	1,	1,	'Necklaces & Pendants',	'',	'',	''),
(36,	2,	1,	'Colliers et Pendentifs',	'',	'',	''),
(38,	1,	1,	'Bracelets',	'',	'',	''),
(38,	2,	1,	'Bracelets',	'38-9-2-1-banner.png',	'',	'');
INSERT INTO `PREFIX_groupcategory_items` (`id`, `groupId`, `categoryId`, `cat_type`, `order_by`, `order_way`, `on_condition`, `on_sale`, `on_new`, `on_discount`, `max_item`, `params`, `is_cache`, `maxItem`, `ordering`, `status`) VALUES
(3,	2,	18,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	1,	1),
(4,	2,	19,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	2,	1),
(5,	2,	3,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	3,	1),
(6,	2,	21,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	4,	1),
(16,	5,	27,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	2,	1),
(17,	5,	28,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	3,	1),
(18,	5,	29,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	4,	1),
(19,	5,	30,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	5,	1),
(20,	6,	26,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	9,	1),
(21,	6,	22,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	8,	1),
(22,	6,	24,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	7,	1),
(23,	6,	23,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	5,	1),
(24,	6,	22,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	6,	1),
(25,	7,	34,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	4,	1),
(26,	7,	33,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	3,	1),
(27,	7,	32,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	2,	1),
(28,	7,	31,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	1,	1),
(29,	8,	38,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	4,	1),
(30,	8,	37,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	3,	1),
(31,	8,	36,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	2,	1),
(32,	8,	35,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	1,	1),
(34,	9,	41,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	2,	1),
(35,	9,	40,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	3,	1),
(36,	9,	39,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	1,	1),
(38,	9,	42,	'auto',	'position',	'desc',	'all',	2,	2,	2,	12,	'{\"products\":[]}',	1,	12,	4,	1);
INSERT INTO `PREFIX_groupcategory_styles` (`id`, `id_shop`, `name`, `params`) VALUES
(1,	1,	'Food',	'{\"backgroundColorHeader\":\"#c75347\",\"colorBackgroundType\":\"#d85c50\",\"colorList\":\"#c75347\",\"bannerColorFrom\":\"#ffffff\",\"bannerColorTo\":\"#ffffff\"}'),
(2,	1,	'Fashion',	'{\"backgroundColorHeader\":\"#a6cada\",\"colorBackgroundType\":\"#b2d7e8\",\"colorList\":\"#a6cada\",\"bannerColorFrom\":\"#ffffff\",\"bannerColorTo\":\"#ffffff\"}'),
(3,	1,	'Furniture',	'{\"backgroundColorHeader\":\"#ffd549\",\"colorBackgroundType\":\"#eed472\",\"colorList\":\"#ffd549\",\"bannerColorFrom\":\"#ffffff\",\"bannerColorTo\":\"#ffffff\"}'),
(4,	1,	'Electronics',	'{\"backgroundColorHeader\":\"#82a3cc\",\"colorBackgroundType\":\"#99b9d8\",\"colorList\":\"#82a3cc\",\"bannerColorFrom\":\"#ffffff\",\"bannerColorTo\":\"#ffffff\"}'),
(5,	1,	'Jewelry',	'{\"backgroundColorHeader\":\"#f59fba\",\"colorBackgroundType\":\"#fab8cd\",\"colorList\":\"#f59fba\",\"bannerColorFrom\":\"#ffffff\",\"bannerColorTo\":\"#ffffff\"}'),
(6,	1,	'Sports',	'{\"backgroundColorHeader\":\"#59c6bb\",\"colorBackgroundType\":\"#65d9cd\",\"colorList\":\"#59c6bb\",\"bannerColorFrom\":\"#ffffff\",\"bannerColorTo\":\"#ffffff\"}');
