<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'wxpay_unifiedorder` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
	`appid` char(32) not null,
	`mch_id` char(32) not null,
	`device_info` varchar(32),
	`nonce_str` varchar（32），
	`sign` varchar(32),
	`sign_type` varchar(32),
	`body` varchar(128) not null,
	`detail` text,
	`attach` varchar(127),
	`out_trade_no` varchar(32) not null,
	`fee_type`varchar(16) default `CNY`,sss
	`total_fee` int not null,
	`spbill_create_ip` char(16) not null,
	`time_start` varchar(14) not null,
	`time_expire` varchar(14) not null,
	`goods_tag` varchar(32),
	`notify_url` varchar(256) not null,
	`trade_type` varchar(16) not null,
	`product_id` varchar(32),
	`limit_pay` varchar(32),
	`openid` varchar(128),
	`return_code` varchar(16),
	`return_msg` varchar(128),
	`return_nonce_str varchar(32),
	`result_code` varchar(16),
	`prepay_id` varchar(64),
	`code_url` varchar(64),
	`err_code` varchar(32),
	`err_code_des` varchar(128),
	`created_at` datetimes,
    PRIMARY KEY  (`id`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
