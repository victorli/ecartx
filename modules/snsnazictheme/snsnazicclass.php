<?php
/**
* 2015 SNSTheme
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
*  @author    SNSTheme <contact@snstheme.com>
*  @copyright 2015 SNSTheme
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of SNSTheme
*/

if (!defined('_PS_VERSION_')) exit;

class SNSNazicClass {
	public function getThemeFields ($tab = false) {
		$theme_fields = array();
		$snsp_general = array(
			'SNS_NAZ_HOMEPAGE'			=> '1',
			'SNS_NAZ_THEMECOLOR'		=> '#f23f3f',
			'SNS_NAZ_THEMECOLORRAND'	=> '0',
			'SNS_NAZ_THEMECOLORRANDIN'	=> '#58dd90,#0095b8,#d4006f',
			'SNS_NAZ_LAYOUTTYPE'		=> '1',
			'SNS_NAZ_FONTFAMILY'		=> 'Arial',
			'SNS_NAZ_FONTSIZE'			=> '13px',
			'SNS_NAZ_BODYCOLOR'			=> '#f23f3f',
			'SNS_NAZ_BODYIMG'			=> '0_nopattern.png',
			'SNS_NAZ_CUSTOMBG'          => '1',
			'SNS_NAZ_BGBODY'            => array(
											'default' => '__SNSPS_BASE_URI__themes/sns_nazic/images/pattern_152.jpg',
											'lang' => true,
											
				                      	),

			'SNS_NAZ_GOOGLEFONT'		=> 'Open+Sans:300,400,600,700|Open Sans',
			'SNS_NAZ_GOOGLETARGETS'		=> 'body',
			'SNS_NAZ_GALLERYIMG'		=> '1'
		);
		$snsp_header = array(
			'SNS_NAZ_WELCOMEMESS'		=> array(
											'default' => 'Welcome to our online store !',
											'lang' => true
										),

			'SNS_NAZ_CUSTOMLOGO'   		=> '1',

			'SNS_NAZ_CUSTOMLOGO_URL'	=> array(
											'default' => '__SNSPS_BASE_URI__themes/sns_nazic/images/logo.png',
											'lang' => true
										),

			'SNS_NAZ_STICKYMENU'		=> '1',
			
		);
		$snsp_slideshow = array(
			'SNS_NAZ_SLSTATUS'			=> 1,
			'SNS_NAZ_SLAUTO'			=> 5000,
			'SNS_NAZ_SLANIMATEIN'		=> 'fadeIn',
			'SNS_NAZ_ANIMATEOUT'		=> 'fadeOut',
			'SNS_NAZ_SLIMGS'			=> array(
											'default' => 'a:3:{s:31:"_142985166337705585130283657737";a:3:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:3:"img";s:59:"__SNSPS_BASE_URI__themes/sns_nazic/images/slideshow/001.jpg";}s:31:"_142985170122705341411315046591";a:3:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:3:"img";s:59:"__SNSPS_BASE_URI__themes/sns_nazic/images/slideshow/002.jpg";}s:29:"_1429851701752061893280331333";a:3:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:3:"img";s:59:"__SNSPS_BASE_URI__themes/sns_nazic/images/slideshow/003.jpg";}}',
											'lang' => true,
											'type' => 'additem'
				                      	)
		);
		$snsp_footer = array(
			
			'SNS_NAZ_FMIDDLE1'			=> array(
											'default' => '<h3>Our Offers</h3>
															<ul>
															<li><a href="#">Delivery</a> </li>
															<li><a href="#">Service</a> </li>
															<li><a href="#">Gift Cards</a> </li>
															<li><a href="#">Mobile</a> </li>
															<li><a href="#">Manufacturers</a> </li>
															</ul>',
											'lang' => true
										),
			'SNS_NAZ_FMIDDLE2'			=> array(
											'default' => '<h3>Shipping Info</h3>
															<ul>
															<li><a href="#">New products</a> </li>
															<li><a href="#">Top sellers</a> </li>
															<li><a href="#">Manufactirers</a> </li>
															<li><a href="#">Suppliers</a> </li>
															<li><a href="#">Specials</a> </li>
															</ul>',
											'lang' => true
										),
			'SNS_NAZ_FMIDDLE3'			=> array(
											'default' => '<h3>Our Account</h3>
																<ul>
																<li><a href="#">My Account</a> </li>
																<li><a href="#">Orders and Returns</a> </li>
																<li><a href="#">Site Map</a> </li>
																<li><a href="#">Search Terms</a> </li>
																<li><a href="#">Advanced Search</a> </li>
																</ul>',
											'lang' => true
										),

			'SNS_NAZ_FMIDDLE4'			=> array(
											'default' => '<h3>Informations</h3>
															<ul>
															<li><a href="#">About Us</a> </li>
															<li><a href="#">Contact Us</a> </li>
															<li><a href="#">Customer Service</a> </li>
															<li><a href="#">Privacy Policy</a> </li>
															<li><a href="#">Blogs</a> </li>
															</ul>',
											'lang' => true
										),


			'SNS_NAZ_FMIDDLE5'			=> array(
											'default' => '<h3>Customer Services</h3>
															<ul>
															<li><a href="#">Check Order </a> </li>
															<li><a href="#">Shipping </a> </li>
															<li><a href="#">Exchanges</a> </li>
															<li><a href="#">Recall</a> </li>
															<li><a href="#">Live chat support</a> </li>
															</ul>',
											'lang' => true
										),

			'SNS_NAZ_COPYRIGHT'			=> array(
											'default' => '&copy; 2015 <a target="_blank" class="_blank" href="http://www.prestashop.com">Ecommerce software by PrestaShop&trade; </a>',
											'lang' => true
										),
 
			'SNS_NAZ_FMORELINKS'   => array(
											'default' => '<ul>
															<li><a href="#">About Us</a>  </li>
															<li><a href="#">Contact Us</a>  </li>
															<li><a href="#">Customer Service</a> </li>
															</ul>',
											'lang' => true
										),
			
		);
		$snsp_prdpage = array(
			
			'SNS_NAZ_ADDTHISBTN'		=> '1',
			'SNS_NAZ_CUSTOMTAB'			=> '1',
			'SNS_NAZ_CUSTOMTAB_TITLE'	=> array(
											'default' => 'Custom tab',
											'lang' => true
										),
			'SNS_NAZ_CUSTOMTAB_CONTENT'	=> array(
											'default' => 'This is custom tab.',
											'lang' => true
										),


			'SNS_ALLOW_BANNER_LEFT_PRD' => '1',

			'SNS_NAZ_BANNER_PRD_LEFT'	=> array(
											'default' => '__SNSPS_BASE_URI__themes/sns_nazic/images/banner-prd.jpg', 
											'lang' => true
										)

		);
		$snsp_bannerhome = array(
			'SNS_ALLOW_BANNER'       =>     '1',
			'SNS_NAZ_BANNER_HOME_1'  => array(
											    'default' => '__SNSPS_BASE_URI__themes/sns_nazic/images/001.jpg',
											    'lang' => true
											),
			'SNS_NAZ_BANNER_HOME_2'  => array(
											    'default' => '__SNSPS_BASE_URI__themes/sns_nazic/images/002.jpg',
											    'lang' => true
											),
			
		
		
			'SNS_ALLOW_BANNER_LEFT'       	=>     '1',
			'SNS_NAZ_BANNER_HOME_LEFT' 	    => array(
											    'default' => '__SNSPS_BASE_URI__themes/sns_nazic/images/003.jpg',
											    'lang' => true
											),

			'SNS_NAZ_BANNER_GVL_LEFT' 		=> array(
											    'default' => '__SNSPS_BASE_URI__themes/sns_nazic/images/005.jpg',
											    'lang' => true
											)
		);

		$snsp_staticblock = array(
			'SNS_NAZ_STATICBLOCK'   => array(
											    'default' => '<p><a class="banner" href="#"> <img src="__SNSPS_BASE_URI__themes/sns_nazic/images/004.jpg" alt="" /> </a></p>',
											    'lang' => true
											)
		);




		$snsp_contact = array(
			'SNS_NAZ_CONTACT_STATUS'	=>  '1',
		
			'SNS_NAZ_STORE_ADDRESS' => array(
										
										'default' => '42 avenue des Champs Elysées 75000 Paris France',
										'lang' => true
									),

			// Làm đến đây

			

			'SNS_NAZ_STORE_PHONE' => array(
										
										'default' => '00-123-456-789',
										'lang' => true
									),

			

			'SNS_NAZ_STORE_EMAIL' => array(
										
										'default' => 'contact@nazic.com',
										'lang' => true
									),
			'SNS_NAZ_MAP_ZOOM' => array(
										
										'default' => '12',
										'lang' => true
									),


			'SNS_NAZ_SOCIAL'	=> array(
									'default' => 'a:5:{s:32:"_1431588892845021767865748658333";a:4:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:4:"icon";s:13:"fa-envelope-o";s:6:"target";s:5:"_self";}s:31:"_143158889339705654897910988157";a:4:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:4:"icon";s:13:"fa fa-twitter";s:6:"target";s:5:"_self";}s:31:"_143158889399603499492786055327";a:4:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:4:"icon";s:13:"fa fa-youtube";s:6:"target";s:5:"_self";}s:32:"_1432005165015021328130679661794";a:4:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:4:"icon";s:13:"fa fa-dropbox";s:6:"target";s:5:"_self";}s:31:"_143200516550507428287505566549";a:4:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:4:"icon";s:12:"fa fa-github";s:6:"target";s:5:"_self";}}',
									'lang' => true,
									'type' => 'additem'
								),

			'SNS_NAZ_PAYMENTLOGO'		=> array(
											'default' => '__SNSPS_BASE_URI__themes/sns_nazic/images/payment-logo.png',
											'lang' => true
										)

		);
		$snsp_ourbrand = array(
			'SNS_NAZ_OURBRAND_TITLE'	=> array(
											'default' => 'Ourbrands',
											'lang' => true
										),
			'SNS_NAZ_OURBRAND_STATUS'	=> '1',
			'SNS_NAZ_OURBRANDS'			=> array(
											'default' => 'a:7:{s:32:"_1431586629357046939323648567266";a:4:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:6:"target";s:5:"_self";s:4:"logo";s:56:"__SNSPS_BASE_URI__themes/sns_nazic/images/brands/001.png";}s:32:"_1431586630301012699397414363323";a:4:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:6:"target";s:5:"_self";s:4:"logo";s:56:"__SNSPS_BASE_URI__themes/sns_nazic/images/brands/002.png";}s:31:"_143158663091508732472021187857";a:4:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:6:"target";s:5:"_self";s:4:"logo";s:56:"__SNSPS_BASE_URI__themes/sns_nazic/images/brands/003.png";}s:31:"_143158800220702144336326212628";a:4:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:6:"target";s:5:"_self";s:4:"logo";s:56:"__SNSPS_BASE_URI__themes/sns_nazic/images/brands/004.png";}s:31:"_143158800279006656016011715556";a:4:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:6:"target";s:5:"_self";s:4:"logo";s:56:"__SNSPS_BASE_URI__themes/sns_nazic/images/brands/005.png";}s:30:"_14315880032860579013849167114";a:4:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:6:"target";s:5:"_self";s:4:"logo";s:56:"__SNSPS_BASE_URI__themes/sns_nazic/images/brands/006.png";}s:31:"_143158800382202874894120041387";a:4:{s:5:"title";s:9:"SNS Theme";s:4:"link";s:1:"#";s:6:"target";s:5:"_self";s:4:"logo";s:56:"__SNSPS_BASE_URI__themes/sns_nazic/images/brands/001.png";}}',
											'lang' => true,
											'type' => 'additem'
										)
		);
		$snsp_catslide = array(
			'SNS_NAZ_CATSLIDE_TITLE'  	=> array(
											'default' => 'Categories slider',
											'lang' => true
				                      	),
			'SNS_NAZ_CATSLIDE_STATUS' 	=> '1',
			'SNS_NAZ_CATSLIDE_BGIMG'		=> array(
											'default' => '__SNSPS_BASE_URI__themes/sns_nazic/images/cat/bg-category.png',
											'lang' => true
										),
			'SNS_NAZ_CATSLIDE'       	=> array(
											'default' => 'a:7:{s:32:"_1421913444066028279407223315123";a:3:{s:4:"name";s:5:"Women";s:4:"link";s:1:"#";s:5:"image";s:52:"__SNSPS_BASE_URI__themes/sns_nazic/images/cat/1.jpg";}s:31:"_142191344501806730973064508767";a:3:{s:4:"name";s:3:"Men";s:4:"link";s:1:"#";s:5:"image";s:52:"__SNSPS_BASE_URI__themes/sns_nazic/images/cat/2.jpg";}s:31:"_142191344557108508482213254064";a:3:{s:4:"name";s:4:"Bags";s:4:"link";s:1:"#";s:5:"image";s:52:"__SNSPS_BASE_URI__themes/sns_nazic/images/cat/3.jpg";}s:31:"_142191356920708599297553803743";a:3:{s:4:"name";s:5:"Sport";s:4:"link";s:1:"#";s:5:"image";s:52:"__SNSPS_BASE_URI__themes/sns_nazic/images/cat/4.jpg";}s:33:"_14219136921860022802073933801292";a:3:{s:4:"name";s:5:"Women";s:4:"link";s:1:"#";s:5:"image";s:52:"__SNSPS_BASE_URI__themes/sns_nazic/images/cat/1.jpg";}s:32:"_1421913692850009143237036452179";a:3:{s:4:"name";s:3:"Men";s:4:"link";s:1:"#";s:5:"image";s:52:"__SNSPS_BASE_URI__themes/sns_nazic/images/cat/2.jpg";}s:32:"_1421913693453035748231399998365";a:3:{s:4:"name";s:4:"Bags";s:4:"link";s:1:"#";s:5:"image";s:52:"__SNSPS_BASE_URI__themes/sns_nazic/images/cat/3.jpg";}}',
											'lang' => true,
											'type' => 'additem'
				                      	)
		);
		$snsp_advance = array(
			'SNS_NAZ_SCSSCOMPILE'		=> '1',
			'SNS_NAZ_SCSSFORMAT'		=> 'scss_formatter_compressed',
			'SNS_NAZ_SHOWCPANEL'		=> '1',
			'SNS_NAZ_SHOWTOOLTIP'		=> '1'
		);
		$snsp_customcssjs = array(
			'SNS_NAZ_CUSTOMCSS'			=> '',
			'SNS_NAZ_CUSTOMJS'			=> ''
		);
		
		$theme_fields = array_merge($theme_fields, $this->addFieldProperties($snsp_general));
		$theme_fields = array_merge($theme_fields, $this->addFieldProperties($snsp_header));
		$theme_fields = array_merge($theme_fields, $this->addFieldProperties($snsp_slideshow));
		$theme_fields = array_merge($theme_fields, $this->addFieldProperties($snsp_footer));
		$theme_fields = array_merge($theme_fields, $this->addFieldProperties($snsp_prdpage));
		$theme_fields = array_merge($theme_fields, $this->addFieldProperties($snsp_bannerhome));
		$theme_fields = array_merge($theme_fields, $this->addFieldProperties($snsp_staticblock));
	
		
		$theme_fields = array_merge($theme_fields, $this->addFieldProperties($snsp_contact));
		$theme_fields = array_merge($theme_fields, $this->addFieldProperties($snsp_ourbrand));
		$theme_fields = array_merge($theme_fields, $this->addFieldProperties($snsp_catslide));
		$theme_fields = array_merge($theme_fields, $this->addFieldProperties($snsp_advance));
		$theme_fields = array_merge($theme_fields, $this->addFieldProperties($snsp_customcssjs));
		if($tab) {
			return $$tab;
		} else {
			return $theme_fields;
		}
	}
	public function addFieldProperties($fields) {
		foreach($fields as &$field) {
			!is_array($field) && settype($field, 'array');
			if(isset($field[0])) {
				$field['default'] = $field[0];
				unset($field[0]);
			}
			$field['type'] = (isset($field['type'])) ? $field['type'] : false;
			$field['lang'] = (isset($field['lang'])) ? $field['lang'] : false;
		}
		return $fields;
	}
	public function delField() {
//		$curr = array();
//		$using = array();
//		$sql = 'SELECT name FROM '._DB_PREFIX_.'configuration WHERE name like "SNS_NAZ_%"';
//		$results = Db::getInstance()->ExecuteS($sql);
//		$fields = $this->SNSClass->getThemeFields();
//		foreach($results as $field) $curr[] = $field['name'];
//		foreach($fields as $field => $info) $using[] = $field;
//		$diff = array_diff($curr,$using);
//		foreach($diff as $field) Configuration::deleteByName($field);
	}
	public function getBaseUrl(){
		if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']))
			return 'https://'.Tools::getShopDomain();
		else
			return 'http://'.Tools::getShopDomain();
	}
	public function replaceLinkContent($string, $out = false) {
		if($out) {
			return str_replace('__SNSPS_BASE_URI__', $this->getBaseUrl().__PS_BASE_URI__, $string);
		} else { 
			$return = str_replace(_PS_BASE_URL_SSL_.__PS_BASE_URI__, '__SNSPS_BASE_URI__', $string); 
			return str_replace(_PS_BASE_URL_.__PS_BASE_URI__, '__SNSPS_BASE_URI__', $return); 
		}
	}
}
?>