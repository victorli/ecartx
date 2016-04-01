DROP TABLE IF EXISTS `PREFIX_ovic_custom_banner_lang`;
CREATE TABLE `PREFIX_ovic_custom_banner_lang` (
  `bannerId` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `id_shop` int(10) unsigned NOT NULL,
  `banner_image` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `banner_link` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `banner_title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`bannerId`,`id_lang`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `PREFIX_ovic_custom_banner_lang` (`bannerId`, `id_lang`, `id_shop`, `banner_image`, `banner_link`, `banner_title`) VALUES
(2,	1,	1,	'2-1-1-banner.png',	'#',	'Banner 1 en'),
(2,	2,	1,	'2-2-1-banner.png',	'Banner fr',	'Banner fr'),
(3,	1,	1,	'3-1-1-banner.png',	'#',	'Banner 2 en'),
(3,	2,	1,	'3-2-1-banner.png',	'Banner 2',	'Banner 2'),
(4,	1,	1,	'4-1-1-banner.png',	'#',	'Banner 3'),
(4,	2,	1,	'4-2-1-banner.png',	'#',	'Banner 3'),
(5,	1,	1,	'5-1-1-banner.png',	'#',	'Banner 4'),
(5,	2,	1,	'5-2-1-banner.png',	'#',	'Banner 4'),
(6,	1,	1,	'6-1-1-banner.png',	'#',	'Banner 5'),
(6,	2,	1,	'6-2-1-banner.png',	'#',	'Banner 5'),
(7,	1,	1,	'7-1-1-banner.png',	'Banner 6',	'Banner 6'),
(7,	2,	1,	'7-2-1-banner.png',	'Banner 6',	'Banner 6'),
(8,	1,	1,	'8-1-1-banner.jpg',	'#',	''),
(8,	2,	1,	'',	'#',	''),
(9,	1,	1,	'9-1-1-banner.jpg',	'',	''),
(9,	2,	1,	'',	'',	''),
(10,	1,	1,	'10-1-1-banner.jpg',	'#',	''),
(10,	2,	1,	'10-1-1-banner.jpg',	'#',	'');

DROP TABLE IF EXISTS `PREFIX_ovic_custom_banners`;
CREATE TABLE `PREFIX_ovic_custom_banners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned NOT NULL,
  `position` int(10) unsigned NOT NULL,
  `position_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `ordering` int(10) unsigned NOT NULL,
  `params` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `PREFIX_ovic_custom_banners` (`id`, `id_shop`, `position`, `position_name`, `status`, `ordering`, `params`) VALUES
(2,	0,	167,	'displayGroupFashions',	1,	1,	'{\"layout\":\"default\",\"width\":\"col-sm-6\",\"className\":\"\"}'),
(3,	0,	167,	'displayGroupFashions',	1,	2,	'{\"layout\":\"default\",\"width\":\"col-sm-6\",\"className\":\"\"}'),
(4,	0,	168,	'displayGroupFoods',	1,	3,	'{\"layout\":\"default\",\"width\":\"col-sm-6\",\"className\":\"\"}'),
(5,	0,	168,	'displayGroupFoods',	1,	4,	'{\"layout\":\"default\",\"width\":\"col-sm-6\",\"className\":\"\"}'),
(6,	0,	169,	'displayGroupSports',	1,	5,	'{\"layout\":\"default\",\"width\":\"col-sm-6\",\"className\":\"\"}'),
(7,	0,	169,	'displayGroupSports',	1,	6,	'{\"layout\":\"default\",\"width\":\"col-sm-6\",\"className\":\"\"}'),
(8,	0,	205,	'displayCustomBanner2',	1,	7,	'{\"layout\":\"default\",\"width\":\"col-sm-6\",\"className\":\"test-class-name\"}'),
(9,	0,	204,	'displayCustomBanner1',	1,	8,	'{\"layout\":\"default\",\"width\":\"col-sm-6\",\"className\":\"\"}'),
(10,	0,	206,	'displayCustomBanner3',	1,	1,	'{\"layout\":\"default\",\"width\":\"col-sm-3\",\"className\":\"\"}');