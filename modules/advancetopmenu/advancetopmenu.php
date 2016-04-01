<?php if (!defined('_PS_VERSION_')) exit;
include_once (dirname(__file__) . '/class/Item.php');
include_once (dirname(__file__) . '/class/Block.php');
include_once (dirname(__file__) . '/class/Submenu.php');
class AdvanceTopMenu extends Module
{
    public $absoluteUrl;
    private $absolutePath;
    private $admin_tpl_path;
    public function __construct()
    {
        $this->name = 'advancetopmenu';
        $this->tab = 'front_office_features';
        $this->version = '2.4';
        $this->author = 'OvicSoft';
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Supershop - Advanced Top Menu');
        $this->description = $this->l('Advanced Top Menu.');
        $this->secure_key = Tools::encrypt($this->name);
        //$this->absoluteUrl = $this->is_https() ? 'https://' : 'http://' .Tools::getShopDomainSsl().__PS_BASE_URI__.'modules/' . $this->name . '/';
        if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
			$this->absoluteUrl = _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/'.$this->name.'/'; 
		else
			$this->absoluteUrl = _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/';
        $this->absolutePath = _PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $this->name .DIRECTORY_SEPARATOR;
        $this->admin_tpl_path = _PS_MODULE_DIR_ . $this->name . '/views/templates/admin/';
    }
    // this also works, and is more future-proof
    public function install()
    {
        if (!parent::install() || !$this->registerHook('header') || !$this->registerHook('displayBackOfficeHeader') ||
         !$this->registerHook('displayHomeTopMenu') || !$this->installDB()) return false;
        $this->installSampleData();
        return true;
    }
    public function installDb()
    {
        return (Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advance_topmenu_items` (
			`id_item` int(6) NOT NULL AUTO_INCREMENT,
            `id_block` int(6) NOT NULL,
            `position` int(3),
            `type` varchar(30) NOT NULL,
            `icon` varchar(50),
            `link` varchar(255),
            `target` varchar(30),
            `class` varchar(200),
            `active` TINYINT(1) unsigned DEFAULT 1,
			PRIMARY KEY(`id_item`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8') && Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advance_topmenu_items_lang` (
			`id_item` int(6) NOT NULL,
            `id_lang` int(6) unsigned NOT NULL,
			`title` varchar(255) ,
            `text` text ,
			PRIMARY KEY(`id_item`,`id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8') && Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advance_topmenu_blocks` (
			`id_block` int(6) NOT NULL AUTO_INCREMENT,
            `position` int(3),
            `id_sub` int(6) NOT NULL,
            `width` int(6),
            `class` varchar(200),
			PRIMARY KEY(`id_block`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8') && Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advance_topmenu_main_shop` (
            `id_item` int(10) unsigned NOT NULL,
            `id_shop` int(10) unsigned NOT NULL,
            PRIMARY KEY(`id_item`,`id_shop`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8') && Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'advance_topmenu_sub` (
			`id_sub` int(6) NOT NULL AUTO_INCREMENT,
            `id_parent` int(6) NOT NULL,
            `width` int(6),
			`class` varchar(200) ,
            `active` TINYINT(1) unsigned DEFAULT 1,
			PRIMARY KEY(`id_sub`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8'));
    }
    private function installSampleData()
    {
        $sql = "INSERT INTO `" . _DB_PREFIX_ .
            "advance_topmenu_blocks` (`id_block`, `position`, `id_sub`, `width`, `class`) VALUES
                (1, 0, 1, 12, 'list'),
                (2, 0, 2, 3, 'list'),
                (3, 0, 2, 3, 'list'),
                (4, 0, 2, 6, ''),
                (5, 0, 3, 3, 'list'),
                (6, 0, 3, 3, 'list'),
                (7, 0, 3, 3, 'list'),
                (8, 0, 3, 3, 'list'),
                (9, 0, 4, 6, ''),
                (10, 0, 4, 3, 'list'),
                (11, 0, 4, 3, ''),
                (12, 0, 5, 3, 'list'),
                (13, 0, 5, 3, 'list'),
                (14, 0, 5, 3, 'list'),
                (15, 0, 5, 3, 'list'),
                (16, 0, 6, 12, '');";
        $result = Db::getInstance()->execute($sql);
        $sql = "INSERT INTO `" . _DB_PREFIX_ .
            "advance_topmenu_items` (`id_item`, `id_block`, `position`, `type`, `icon`, `link`, `target`, `class`, `active`) VALUES
                (1, 0, 1, 'link', '', 'CAT12', NULL, 'mega_menu_item icon_fashion', 1),
                (2, 0, 2, 'link', '', 'CAT13', NULL, 'mega_menu_item icon_furniture', 1),
                (3, 0, 3, 'link', '', 'CAT14', NULL, 'mega_menu_item icon_food', 1),
                (4, 0, 4, 'link', '', 'CAT15', NULL, 'list-dropdown mega_menu_item icon_electronics', 1),
                (5, 0, 5, 'link', '', 'CAT16', NULL, 'mega_menu_item icon_sports', 1),
                (6, 1, 5, 'link', '', '#', NULL, '', 1),
                (7, 1, 1, 'link', '', 'CAT58', NULL, '', 1),
                (8, 1, 2, 'link', '', 'CAT32', NULL, '', 1),
                (9, 1, 4, 'link', '', '#', NULL, '', 1),
                (10, 1, 3, 'link', '', 'CAT33', NULL, '', 1),
                (11, 1, 0, 'link', '', 'CAT31', NULL, '', 1),
                (12, 2, 0, 'link', '', 'CAT13', NULL, 'group_header', 1),
                (13, 2, 6, 'link', '', 'CAT55', NULL, '', 1),
                (14, 2, 4, 'link', '', 'CAT25', NULL, '', 1),
                (15, 2, 2, 'link', '', 'CAT22', NULL, '', 1),
                (16, 2, 3, 'link', '', 'CAT24', NULL, '', 1),
                (17, 2, 1, 'link', '', 'CAT23', NULL, '', 1),
                (18, 3, 0, 'link', '', 'PAGmanufacturer', NULL, 'group_header', 1),
                (19, 4, 0, 'html', '', '', NULL, 'clearfix', 1),
                (20, 3, 1, 'link', '', 'MAN33', NULL, '', 1),
                (21, 3, 2, 'link', '', '#', NULL, '', 1),
                (22, 3, 3, 'link', '', 'MAN34', NULL, '', 1),
                (23, 3, 4, 'link', '', '#', NULL, '', 1),
                (24, 3, 5, 'link', '', '#', NULL, '', 1),
                (25, 5, 0, 'link', '', '#', NULL, 'group_header', 1),
                (26, 5, 6, 'link', '', '#', NULL, '', 1),
                (27, 5, 4, 'link', '', '#', NULL, '', 1),
                (28, 5, 2, 'link', '', 'CAT28', NULL, '', 1),
                (29, 5, 5, 'link', '', '#', NULL, '', 1),
                (30, 5, 1, 'link', '', '#', NULL, '', 1),
                (31, 5, 3, 'link', '', '#', NULL, '', 1),
                (32, 6, 0, 'link', '', 'PAGmanufacturer', NULL, 'group_header', 1),
                (33, 6, 1, 'link', '', '#', NULL, '', 1),
                (34, 6, 2, 'link', '', '#', NULL, '', 1),
                (35, 6, 3, 'link', '', '#', NULL, '', 1),
                (36, 6, 4, 'link', '', 'CAT27', NULL, '', 1),
                (37, 6, 5, 'link', '', 'CAT29', NULL, '', 1),
                (38, 6, 6, 'link', '', 'CAT30', NULL, '', 1),
                (39, 7, 0, 'link', '', 'CAT28', NULL, 'group_header', 1),
                (40, 7, 1, 'link', '', '#', NULL, '', 1),
                (41, 7, 2, 'link', '', 'CAT27', NULL, '', 1),
                (42, 7, 3, 'link', '', 'CAT28', NULL, '', 1),
                (43, 7, 4, 'link', '', '#', NULL, '', 1),
                (44, 7, 5, 'link', '', '#', NULL, '', 1),
                (45, 7, 6, 'link', '', '#', NULL, '', 1),
                (46, 8, 1, 'link', '', '#', NULL, '', 1),
                (47, 8, 2, 'link', '', '#', NULL, '', 1),
                (48, 8, 0, 'link', '', 'CAT30', NULL, 'group_header', 1),
                (49, 8, 3, 'link', '', '#', NULL, '', 1),
                (50, 8, 4, 'link', '', '#', NULL, '', 1),
                (51, 8, 5, 'link', '', '#', NULL, '', 1),
                (52, 8, 6, 'link', '', '#', NULL, '', 1),
                (53, 9, 0, 'img', '1421811666sport.png', 'CAT16', NULL, '', 1),
                (54, 10, 0, 'link', '', 'CAT35', NULL, 'group_header', 1),
                (55, 10, 1, 'link', '', 'CAT36', NULL, '', 1),
                (56, 10, 2, 'link', '', 'CAT37', NULL, '', 1),
                (57, 10, 3, 'link', '', 'CAT38', NULL, '', 1),
                (58, 10, 4, 'link', '', 'CAT60', NULL, '', 1),
                (59, 10, 5, 'link', '', 'CAT61', NULL, '', 1),
                (61, 11, 0, 'link', '', 'PAGmanufacturer', NULL, 'group_header', 1),
                (62, 11, 1, 'img', '1421988831chaneee.png', 'MAN11', NULL, '', 1),
                (63, 11, 2, 'img', '1421988948ckoo.png', '#', NULL, '', 1),
                (64, 11, 3, 'img', '1421988983loreee.png', 'MAN9', NULL, '', 1),
                (65, 12, 1, 'img', '1421206957men.png', 'CAT20', NULL, '', 1),
                (66, 12, 0, 'link', 'icon-men', 'CAT20', NULL, 'group_header', 1),
                (67, 12, 5, 'link', '', '#', NULL, '', 1),
                (68, 12, 4, 'link', '', '#', NULL, '', 1),
                (69, 12, 2, 'link', '', '#', NULL, '', 1),
                (70, 12, 6, 'link', '', '#', NULL, '', 1),
                (71, 12, 3, 'link', '', 'CAT49', NULL, '', 1),
                (72, 13, 1, 'img', '1421206996women.png', 'CAT3', NULL, '', 1),
                (73, 13, 0, 'link', 'icon-women', 'CAT3', NULL, 'group_header', 1),
                (74, 13, 5, 'link', '', 'CAT47', NULL, '', 1),
                (75, 13, 4, 'link', '', 'CAT4', NULL, '', 1),
                (76, 13, 2, 'link', '', 'CAT4', NULL, '', 1),
                (77, 13, 6, 'link', '', 'CAT46', NULL, '', 1),
                (78, 13, 3, 'link', '', '#', NULL, '', 1),
                (79, 14, 1, 'img', '1421207007kid.png', 'CAT44', NULL, '', 1),
                (80, 14, 0, 'link', 'icon-kid', 'CAT44', NULL, 'group_header', 1),
                (81, 14, 5, 'link', '', '#', NULL, '', 1),
                (82, 14, 4, 'link', '', '#', NULL, '', 1),
                (83, 14, 2, 'link', '', '#', NULL, '', 1),
                (84, 14, 6, 'link', '', '#', NULL, '', 1),
                (85, 14, 3, 'link', '', '#', NULL, '', 1),
                (86, 15, 1, 'img', '1421207015trending.png', 'CAT43', NULL, '', 1),
                (87, 15, 0, 'link', 'icon-trending', 'CAT43', NULL, 'group_header', 1),
                (88, 15, 5, 'link', '', 'CAT21', NULL, '', 1),
                (89, 15, 4, 'link', '', 'CAT3', NULL, '', 1),
                (90, 15, 2, 'link', '', 'CAT20', NULL, '', 1),
                (92, 15, 3, 'link', '', 'CAT44', NULL, '', 1),
                (95, 2, 5, 'link', '', 'CAT26', NULL, '', 1),
                (96, 3, 6, 'link', '', '#', NULL, '', 1),
                (98, 0, 7, 'link', '', 'PAGindex', NULL, '', 1),
                (99, 0, 8, 'link', '', '#', NULL, 'list-dropdown', 1),
                (100, 0, 9, 'link', '', '#', NULL, '', 1),
                (101, 16, 0, 'link', '', '#', NULL, '', 1),
                (102, 16, 1, 'link', '', '#', NULL, '', 1),
                (103, 16, 2, 'link', '', '#', NULL, '', 1),
                (104, 16, 3, 'link', '', '#', NULL, '', 1),
                (105, 16, 4, 'link', '', '#', NULL, '', 1),
                (106, 16, 5, 'link', '', '#', NULL, '', 1),
                (107, 0, 7, 'link', '', '', NULL, 'mega_menu_item icon_blog', 1),
                (108, 0, 0, 'link', '', '', NULL, '', 0),
                (109, 0, 6, 'link', '', 'CAT17', NULL, 'mega_menu_item icon_jewelry', 1),
                (110, 1, 6, 'link', '', 'CAT34', NULL, '', 1);";
        $result &= Db::getInstance()->execute($sql);
        $sql = "INSERT INTO `" . _DB_PREFIX_ .
            "advance_topmenu_items_lang` (`id_item`, `id_lang`, `title`, `text`) VALUES
                (1, 1, 'Fashion', ''),
                (1, 2, 'Mode', ''),
                (2, 1, 'Furniture', ''),
                (2, 2, 'Meubles', ''),
                (3, 1, 'Food', ''),
                (3, 2, 'Nourriture', ''),
                (4, 1, 'Electronics', ''),
                (4, 2, 'Électronique', ''),
                (5, 1, 'Sports', ''),
                (5, 2, 'Sport', ''),
                (6, 1, 'Memory Cards', ''),
                (6, 2, 'Cartes mémoire', ''),
                (7, 1, 'Tablets', ''),
                (7, 2, 'Comprimés', ''),
                (8, 1, 'Laptop', ''),
                (8, 2, 'Laptop', ''),
                (9, 1, 'Webcam', ''),
                (9, 2, 'Webcam', ''),
                (10, 1, 'Camera', ''),
                (10, 2, 'Caméra', ''),
                (11, 1, 'Mobile', ''),
                (11, 2, 'Mobile', ''),
                (12, 1, 'Categories', ''),
                (12, 2, 'Catégories', ''),
                (13, 1, 'Living Room', ''),
                (13, 2, 'Salon', ''),
                (14, 1, 'Step Stools', ''),
                (14, 2, 'Escabeaux', ''),
                (15, 1, 'Bathtime Goods', ''),
                (15, 2, 'Les marchandises Bathtime', ''),
                (16, 1, 'Blankets', ''),
                (16, 2, 'Couvertures', ''),
                (17, 1, 'Shower Curtains', ''),
                (17, 2, 'Rideaux de douche', ''),
                (18, 1, 'Brands', ''),
                (18, 2, 'Marques', ''),
                (19, 1, 'Collection 2014', '<div class=\"col-sm-7 col\">\r\n<h2>Collection 2014</h2>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>\r\n<img alt=\"\" src=\"http://kutethemes.com/demo/fashion/london-stars1/img/cms/top_manu_banner1.png\" width=\"200\" height=\"91\" class=\"img-responsive\" /></div>\r\n<div class=\"col-sm-5 col\"><img alt=\"\" src=\"http://kutethemes.com/demo/fashion/london-stars1/img/cms/top_manu_banner.png\" class=\"img-responsive\" width=\"190\" height=\"282\" /></div>'),
                (19, 2, 'Collection 2014', '<div class=\"col-sm-7 col\">\r\n<h2>Collection 2014</h2>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>\r\n<img alt=\"\" src=\"http://kutethemes.com/demo/fashion/london-stars1/img/cms/top_manu_banner1.png\" width=\"200\" height=\"\91\" class=\"img-responsive\" /></div>\r\n<div class=\"col-sm-5 col\"><img alt=\"\" src=\"http://kutethemes.com/demo/fashion/london-stars1/img/cms/top_manu_banner.png\" class=\"img-responsive\" width=\"190\" height=\"282\" /></div>'),
                (20, 1, 'IKEA', ''),
                (20, 2, 'IKEA', ''),
                (21, 1, 'F & D', ''),
                (21, 2, 'F & D', ''),
                (22, 1, ' 	Palliser', ''),
                (22, 2, ' 	Palliser', ''),
                (23, 1, 'Fendi Casa ', ''),
                (23, 2, 'Fendi Casa ', ''),
                (24, 1, 'Edra ', ''),
                (24, 2, 'Edra ', ''),
                (25, 1, 'Asian Food', ''),
                (25, 2, 'Asain Alimentaire', ''),
                (26, 1, 'Desserts', ''),
                (26, 2, 'Desserts', ''),
                (27, 1, 'Sausages', ''),
                (27, 2, 'Saucisses', ''),
                (28, 1, 'Noodles', ''),
                (28, 2, 'Nouilles', ''),
                (29, 1, 'Meat Dishes', ''),
                (29, 2, 'Viandes', ''),
                (30, 1, 'Vietnamese Pho', ''),
                (30, 2, 'Pho vietnamien', ''),
                (31, 1, 'Seafood Dishes', ''),
                (31, 2, 'Plats de fruits de mer', ''),
                (32, 1, 'European Food', ''),
                (32, 2, 'Alimentaire Européenne', ''),
                (33, 1, 'Greek Potatoes', ''),
                (33, 2, 'Pommes de terre grecques', ''),
                (34, 1, 'Famous Spaghetti', ''),
                (34, 2, 'Spaghetti célèbres', ''),
                (35, 1, 'Chicken Parmesan', ''),
                (35, 2, 'poulet au parmesan', ''),
                (36, 1, 'Italian Pizza', ''),
                (36, 2, 'Pizza italienne', ''),
                (37, 1, 'French Cakes', ''),
                (37, 2, 'Gâteaux français', ''),
                (38, 1, 'Drink', ''),
                (38, 2, 'Boisson', ''),
                (39, 1, 'Fast Food', ''),
                (39, 2, 'Fast Food', ''),
                (40, 1, 'Hamberger', ''),
                (40, 2, 'Hamberger', ''),
                (41, 1, 'Pizza', ''),
                (41, 2, 'Pizza', ''),
                (42, 1, 'Noodles', ''),
                (42, 2, 'Nouilles', ''),
                (43, 1, 'Sandwich', ''),
                (43, 2, 'Sandwich', ''),
                (44, 1, 'Salad', ''),
                (44, 2, 'Salade', ''),
                (45, 1, 'Paste', ''),
                (45, 2, 'Pâte', ''),
                (46, 1, 'Cocktail Drinks', ''),
                (46, 2, 'Cocktail Drinks', ''),
                (47, 1, 'Coca Cola', ''),
                (47, 2, 'Coca Cola', ''),
                (48, 1, 'Drink', ''),
                (48, 2, 'Boisson', ''),
                (49, 1, 'Pepsi', ''),
                (49, 2, 'Pepsi', ''),
                (50, 1, 'Heineken', ''),
                (50, 2, 'Heineken', ''),
                (51, 1, 'Wine', ''),
                (51, 2, 'Du Vin', ''),
                (52, 1, 'Alcohol', ''),
                (52, 2, 'alcool', ''),
                (53, 1, 'Collection 2014', ''),
                (53, 2, '', ''),
                (54, 1, 'Boxing', ''),
                (54, 2, 'Boxe', ''),
                (55, 1, 'Basketball', ''),
                (55, 2, 'Basketball', ''),
                (56, 1, 'Racing', ''),
                (56, 2, 'courses', ''),
                (57, 1, 'Foodball', ''),
                (57, 2, 'Foodball', ''),
                (58, 1, 'Tenis', ''),
                (58, 2, 'Tenis', ''),
                (59, 1, 'Accessories', ''),
                (59, 2, 'Accessories', ''),
                (61, 1, 'View All Brands', ''),
                (61, 2, 'Toutes les Marques', ''),
                (62, 1, 'Dolce Gabbana', ''),
                (62, 2, '', ''),
                (63, 1, 'Pandora', ''),
                (63, 2, '', ''),
                (64, 1, 'Gucci', ''),
                (64, 2, '', ''),
                (65, 1, 'Categories Image', ''),
                (65, 2, 'Categories Image', ''),
                (66, 1, 'Men''s', ''),
                (66, 2, 'Pour des Hommes', ''),
                (67, 1, 'Scarves', ''),
                (67, 2, 'Foulards', ''),
                (68, 1, 'Tops', ''),
                (68, 2, 'Haut', ''),
                (69, 1, 'Skirts', ''),
                (69, 2, 'Jupes', ''),
                (70, 1, 'Pants', ''),
                (70, 2, 'Pantalon', ''),
                (71, 1, 'Jackets', ''),
                (71, 2, 'Vestes', ''),
                (72, 1, 'Categories Image', ''),
                (72, 2, 'Categories Image', ''),
                (73, 1, 'Women''s', ''),
                (73, 2, 'Aux Femmes', ''),
                (74, 1, 'Scarves', ''),
                (74, 2, 'Foulards', ''),
                (75, 1, 'Tops', ''),
                (75, 2, 'Haut', ''),
                (76, 1, 'Skirts', ''),
                (76, 2, 'Jupes', ''),
                (77, 1, 'Pants', ''),
                (77, 2, 'Pantalon', ''),
                (78, 1, 'Jackets', ''),
                (78, 2, 'Vestes', ''),
                (79, 1, 'Categories Image', ''),
                (79, 2, 'Categories Image', ''),
                (80, 1, 'Kid''s', ''),
                (80, 2, 'Enfants', ''),
                (81, 1, 'Scarves', ''),
                (81, 2, 'Foulards', ''),
                (82, 1, 'Tops', ''),
                (82, 2, 'Haut', ''),
                (83, 1, 'Shoes', ''),
                (83, 2, 'Chaussures', ''),
                (84, 1, 'Accessories', ''),
                (84, 2, 'Accessoires', ''),
                (85, 1, 'Clothing', ''),
                (85, 2, 'Vêtements', ''),
                (86, 1, 'Categories Image', ''),
                (86, 2, 'Categories Image', ''),
                (87, 1, 'Trending', ''),
                (87, 2, 'Tendances', ''),
                (88, 1, 'Accessories', ''),
                (88, 2, 'Accessories', ''),
                (89, 1, 'Women''s Clothing', ''),
                (89, 2, 'Vêtements pour Femmes', ''),
                (90, 1, 'Men''s Clothing', ''),
                (90, 2, 'Vêtements pour Hommes', ''),
                (92, 1, 'Kid''s Clothing', ''),
                (92, 2, 'Vêtements pour Enfants', ''),
                (95, 1, 'Towels & Rugs', ''),
                (95, 2, 'Serviettes de bain et tapis', ''),
                (96, 1, 'Henkel-Harris ', ''),
                (96, 2, 'Henkel-Harris ', ''),
                (98, 1, 'Home', ''),
                (98, 2, '', ''),
                (99, 1, 'Women', ''),
                (99, 2, '', ''),
                (100, 1, 'Shop 1', ''),
                (100, 2, '', ''),
                (101, 1, 'Dresses', ''),
                (101, 2, '', ''),
                (102, 1, 'Pants', ''),
                (102, 2, '', ''),
                (103, 1, 'Scarves', ''),
                (103, 2, '', ''),
                (104, 1, 'Skirts', ''),
                (104, 2, '', ''),
                (105, 1, 'Jackets', ''),
                (105, 2, '', ''),
                (106, 1, 'Scarves', ''),
                (106, 2, '', ''),
                (107, 1, 'Blog', ''),
                (107, 2, 'Blog', ''),
                (108, 1, 'Home', ''),
                (108, 2, 'Maison', ''),
                (109, 1, 'Jewelry', ''),
                (109, 2, 'Bijoux', ''),
                (110, 1, 'Accessories', ''),
                (110, 2, 'Accessories', '');";
        $result = Db::getInstance()->execute($sql);
        $sql = "INSERT INTO `" . _DB_PREFIX_ . "advance_topmenu_main_shop` (`id_item`, `id_shop`) VALUES
                (1, 1),
                (2, 1),
                (3, 1),
                (4, 1),
                (5, 1),
                (98, 2),
                (99, 2),
                (100, 2),
                (107, 1),
                (108, 1),
                (109, 1);";
        $result = Db::getInstance()->execute($sql);
        $sql = "INSERT INTO `" . _DB_PREFIX_ .
            "advance_topmenu_sub` (`id_sub`, `id_parent`, `width`, `class`, `active`) VALUES
                (1, 4, 200, '', 1),
                (2, 2, 830, 'mega_dropdown', 1),
                (3, 3, 830, 'mega_dropdown  men', 1),
                (4, 5, 830, 'mega_dropdown gift', 1),
                (5, 1, 830, 'mega_dropdown', 1),
                (6, 99, 200, 'list', 1);";
        $result = Db::getInstance()->execute($sql);
        return $result;
    }
    public function uninstall()
    {
        Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('topmenu.tpl'));
        if (!parent::uninstall() || !$this->uninstallDB()) return false;
        return true;
    }
    private function uninstallDb()
    {
        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'advance_topmenu_blocks`');
        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'advance_topmenu_sub`');
        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'advance_topmenu_items`');
        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'advance_topmenu_items_lang`');
        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'advance_topmenu_main_shop`');
        return true;
    }
    public function getContent()
    {
        $output = '';
        $errors = array();
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        $languages = Language::getLanguages(false);
        if (Tools::getValue('confirm_msg'))
        {
            $this->context->smarty->assign('confirmation', Tools::getValue('confirm_msg'));
        }
        if (Tools::isSubmit('submitnewItem'))
        {
            $id_item = (int)Tools::getValue('item_id');
            if ($id_item && Validate::isUnsignedId($id_item))
            {
                $new_item = new Item($id_item);
            }
            else
            {
                $new_item = new Item();
            }
            $new_item->id_block = Tools::getValue('block_id');
            $new_item->type = Tools::getValue('linktype');
            $new_item->active = (int)Tools::getValue('active');
            $itemtitle_set = false;
            foreach ($languages as $language)
            {
                $item_title = Tools::getValue('item_title_' . $language['id_lang']);
                if (strlen($item_title) > 0)
                {
                    $itemtitle_set = true;
                }
                $new_item->title[$language['id_lang']] = $item_title;
            }
            if (!$itemtitle_set)
            {
                $lang_title = Language::getLanguage($this->context->language->id);
                if ($new_item->type == 'img') $errors[] = 'This Alt text field is required at least in ' . $lang_title['name'];
                else  $errors[] = 'This item title field is required at least in ' . $lang_title['name'];
            }
            $new_item->class = Tools::getValue('custom_class');
            if ($new_item->type == 'link')
            {
                $new_item->icon = Tools::getValue('item_icon');
                $new_item->link = Tools::getValue('link_value');
            }
            elseif ($new_item->type == 'img')
            {
                if (isset($_FILES['item_img']) && strlen($_FILES['item_img']['name']) > 0)
                {
                    if (!$img_file = $this->moveUploadedImage($_FILES['item_img']))
                    {
                        $errors[] = 'An error occurred during the image upload.';
                    }
                    else
                    {
                        $new_item->icon = $img_file;
                        if (Tools::getValue('old_img') != '')
                        {
                            $filename = Tools::getValue('old_img');
                            if (file_exists(dirname(__file__) . '/img/' . $filename))
                            {
                                @unlink(dirname(__file__) . '/img/' . $filename);
                            }
                        }
                    }
                }
                else
                {
                    $new_item->icon = Tools::getValue('old_img');
                }
                $new_item->link = Tools::getValue('link_value');
            }
            elseif ($new_item->type == 'html')
            {
                foreach ($languages as $language) $new_item->text[$language['id_lang']] = Tools::getValue('item_html_' .
                        $language['id_lang']);
            }
            if (!count($errors))
            {
                if ($id_item && Validate::isUnsignedId($id_item))
                {
                    if (!$new_item->update())
                    {
                        $errors[] = 'An error occurred while update data.';
                    }
                }
                else
                {
                    if (!$new_item->add())
                    {
                        $errors[] = 'An error occurred while saving data.';
                    }
                }
                if (!count($errors))
                {
                    if ($id_item && Validate::isUnsignedId($id_item))
                    {
                        $this->context->smarty->assign('confirmation', $this->l('Item successfully updated.'));
                    }
                    else
                    {
                        $confirm_msg = $this->l('New item successfully added.');
                        Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('topmenu.tpl'));
                        Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' .
                            Tools::getAdminTokenLite('AdminModules') . '&confirm_msg=' . $confirm_msg);
                    }
                }
            }
        }
        elseif (Tools::isSubmit('submit_del_item'))
        {
            $item_id = Tools::getValue('item_id');
            if ($item_id && Validate::isUnsignedId($item_id))
            {
                $subs = $this->getSupMenu($item_id);
                $del = true;
                if ($subs && count($subs) > 0)
                {
                }
                foreach ($subs as $sub)
                {
                    $del &= $this->deleteSub($sub['id_sub']);
                }
                $item = new Item($item_id);
                if (!$item->delete() || !$del)
                {
                    $errors[] = 'An error occurred while delete item.';
                }
                else
                {
                    Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('topmenu.tpl'));
                    $this->context->smarty->assign('confirmation', $this->l('Delete successful.'));
                }
            }
        }
        elseif (Tools::isSubmit('submitnewsub'))
        {
            $id_sub = Tools::getValue('id_sub');
            if ($id_sub && Validate::isUnsignedId($id_sub))
            {
                $sub = new Submenu($id_sub);
            }
            else
            {
                $sub = new Submenu();
            }
            $sub->id_parent = Tools::getValue('id_parent');
            $sub->width = Tools::getValue('subwidth');
            $sub->class = Tools::getValue('sub_class');
            $sub->active = Tools::getValue('active');
            if ($id_sub && Validate::isUnsignedId($id_sub))
            {
                if (!$sub->update())
                {
                    $errors[] = 'An error occurred while update data.';
                }
            }
            else
            {
                if (!$sub->checkAvaiable())
                {
                    if (!$sub->add())
                    {
                        $errors[] = 'An error occurred while saving data.';
                    }
                }
                else
                {
                    $parent_item = new Item($sub->id_parent);
                    $errors[] = $parent_item->title[$this->context->language->id] . ' already have a sub.';
                }
            }
            if (!count($errors))
            {
                if ($id_sub && Validate::isUnsignedId($id_sub))
                {
                    $this->context->smarty->assign('confirmation', $this->l('Submenu successfully updated.'));
                }
                else
                {
                    $confirm_msg = $this->l('New sub successfully added.');
                    Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('topmenu.tpl'));
                    Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' .
                        Tools::getAdminTokenLite('AdminModules') . '&confirm_msg=' . $confirm_msg);
                }
            }
        }
        elseif (Tools::isSubmit('submit_del_sub'))
        {
            $id_sub = (int)Tools::getValue('id_sub');
            if ($id_sub && Validate::isUnsignedId($id_sub))
            {
                if (!$this->deleteSub($id_sub))
                {
                    $errors[] = 'An error occurred while delete sub menu.';
                }
                else
                {
                    Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('topmenu.tpl'));
                    $this->context->smarty->assign('confirmation', $this->l('Delete successful.'));
                }
            }
        }
        elseif (Tools::isSubmit('submitnewblock'))
        {
            $id_block = Tools::getValue('id_block');
            if ($id_block && Validate::isUnsignedId($id_block))
            {
                $block = new Block($id_block);
            }
            else
            {
                $block = new Block();
            }
            $block->id_sub = Tools::getValue('id_sub');
            $block->width = Tools::getValue('block_widh');
            $block->class = Tools::getValue('block_class');
            if ($id_block && Validate::isUnsignedId($id_block))
            {
                if (!$block->update())
                {
                    $errors[] = 'An error occurred while update block.';
                }
            }
            else
            {
                if (!$block->add())
                {
                    $errors[] = 'An error occurred while saving data.';
                }
            }
            if (!count($errors))
            {
                if ($id_block && Validate::isUnsignedId($id_block))
                {
                    $this->context->smarty->assign('confirmation', $this->l('Block successfully updated.'));
                }
                else
                {
                    $confirm_msg = $this->l('New block successfully added.');
                    Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('topmenu.tpl'));
                    Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' .
                        Tools::getAdminTokenLite('AdminModules') . '&confirm_msg=' . $confirm_msg);
                }
            }
        }
        elseif (Tools::isSubmit('submit_del_block'))
        {
            $id_block = Tools::getValue('id_block');
            if ($id_block && Validate::isUnsignedId($id_block))
            {
                if (!$this->deleteBlock($id_block))
                {
                    $errors[] = 'An error occurred while delete block.';
                }
                else
                {
                    Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('topmenu.tpl'));
                    $this->context->smarty->assign('confirmation', $this->l('Delete successful.'));
                }
            }
        }
        elseif (Tools::isSubmit('changeactive'))
        {
            $id_item = (int)Tools::getValue('item_id');
            if ($id_item && Validate::isUnsignedId($id_item))
            {
                $item = new Item($id_item);
                $item->active = !$item->active;
                if (!$item->update())
                {
                    $errors[] = $this->displayError('Could not change');
                }
                else
                {
                    $confirm_msg = $this->l('Successfully updated.');
                    Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('topmenu.tpl'));
                    Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' .
                        Tools::getAdminTokenLite('AdminModules') . '&confirm_msg=' . $confirm_msg);
                }
            }
        }
        elseif (Tools::isSubmit('changestatus'))
        {
            $id_sub = (int)Tools::getValue('id_sub');
            if ($id_sub && Validate::isUnsignedId($id_sub))
            {
                $sub_menu = new Submenu($id_sub);
                $sub_menu->active = !$sub_menu->active;
                if (!$sub_menu->update())
                {
                    $errors[] = $this->displayError('Could not change');
                }
                else
                {
                    $confirm_msg = $this->l('Submenu successfully updated.');
                    Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('topmenu.tpl'));
                    Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' .
                        Tools::getAdminTokenLite('AdminModules') . '&confirm_msg=' . $confirm_msg);
                }
            }
        }
        $this->context->smarty->assign(array(
            'admin_tpl_path' => $this->admin_tpl_path,
            'postAction' => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite
                ('AdminModules'),
            ));
        if (count($errors) > 0)
        {
            if (isset($errors) && count($errors)) $output .= $this->displayError(implode('<br />', $errors));
        }
        if (Tools::isSubmit('submit_edit_item') || (Tools::isSubmit('submitnewItem') && count($errors) > 0))
        {
            $output .= $this->displayItemForm();
        }
        elseif (Tools::isSubmit('submit_edit_sub'))
        {
            $output .= $this->displaySubForm();
        }
        elseif (Tools::isSubmit('submit_new_block'))
        {
            $output .= $this->displayBlockForm();
        }
        else
        {
            $output .= $this->displayForm();
        }
        return $output;
    }
    private function deleteSub($id_sub = null)
    {
        if (is_null($id_sub)) return false;
        $blocks = $this->getAllBlocks($id_sub);
        $del = true;
        if ($blocks && count($blocks) > 0)
            foreach ($blocks as $bl)
            {
                $del &= $this->deleteBlock($bl['id_block']);
            }
        if ($del)
        {
            $sub = new Submenu($id_sub);
            return $sub->delete();
        }
        return false;
    }
    private function deleteBlock($id_block = null)
    {
        if (is_null($id_block)) return false;
        $items = $this->getItemByBlock($id_block);
        $del = true;
        if ($items && count($items) > 0)
            foreach ($items as $it)
            {
                $item = new Item($it['id_item']);
                $del &= $item->delete();
            }
        if ($del)
        {
            $block = new Block($id_block);
            return $block->delete();
        }
        return false;
    }
    private function deleteItem($id_item = null)
    {
        if (is_null($id_item)) return false;
        $item = new Item($id_item);
        return $item->delete();
    }
    public function updateMenuPosition($order = null)
    {
        if (is_null($order)) return false;
        $position = explode('::', $order);
        $res = false;
        if (count($position) > 0)
            foreach ($position as $key => $id_item)
            {
                $res = Db::getInstance()->execute('
                    UPDATE `' . _DB_PREFIX_ . 'advance_topmenu_items`
                    SET `position` = ' . $key . '
                    WHERE `id_item` = ' . (int)$id_item);
                if (!$res) break;
            }
        Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('topmenu.tpl'));
        return $res;
    }
    public function updateBlockPosition($order = null)
    {
        if (is_null($order)) return false;
        $position = explode('::', $order);
        $res = false;
        if (count($position) > 0)
            foreach ($position as $key => $id_block)
            {
                $res = Db::getInstance()->execute('
                    UPDATE `' . _DB_PREFIX_ . 'advance_topmenu_blocks`
                    SET `position` = ' . $key . '
                    WHERE `id_block` = ' . (int)$id_block);
                if (!$res) break;
            }
        Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('topmenu.tpl'));
        return $res;
    }
    private function displayBlockForm()
    {
        $id_sub = Tools::getValue('id_sub');
        if (!strlen($id_sub) > 0)
        {
            return;
        }
        $id_block = Tools::getValue('id_block');
        if (strlen($id_block) > 0)
        {
            $block = new Block($id_block);
        }
        else
        {
            $block = new Block($id_block);
        }
        $block->id_sub = (int)$id_sub;
        $this->context->smarty->assign(array(
            'form' => 'block',
            'block' => $block,
            ));
        return $this->display(__file__, 'views/templates/admin/block_form.tpl');
    }
    private function displaySubForm()
    {
        $id_sub = Tools::getValue('id_sub');
        if (strlen($id_sub) > 0)
        {
            $sub = new Submenu($id_sub);
        }
        else
        {
            $sub = new Submenu($id_sub);
        }
        $main_items = $this->getMainItem();
        $this->context->smarty->assign(array(
            'form' => 'sub',
            'submenu' => $sub,
            'main_items' => $main_items));
        return $this->display(__file__, 'views/templates/admin/sub_form.tpl');
    }
    private function displayForm()
    {
        $main_items = $this->getMainItem();
        $supmenu = $this->getSupMenu();
        foreach ($supmenu as &$sub)
        {
            $sub['blocks'] = $this->getAllBlocks($sub['id_sub']);
            if (count($sub['blocks']))
            {
                foreach ($sub['blocks'] as &$block)
                {
                    $block['items'] = $this->getItemByBlock($block['id_block']);
                }
            }
        }
        $this->context->smarty->assign(array(
            'form' => 'main',
            'imgpath' => $this->_path . 'img/',
            'supmenu' => $supmenu,
            'ajaxUrl' => $this->absoluteUrl . 'ajax.php?secure_key=' . $this->secure_key,
            'list_items' => $main_items));
        return $this->display(__file__, 'views/templates/admin/admin.tpl');
    }
    private function displayItemForm()
    {
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        $languages = Language::getLanguages();
        $id_lang = $this->context->language->id;
        $item_id = (int)Tools::getValue('item_id');
        $link_texts = array();
        if ($item_id && Validate::isUnsignedId($item_id))
        {
            $Item = new Item($item_id);
        }
        else
        {
            $Item = new Item();
            $block_id = (int)Tools::getValue('block');
            $Item->id_block = $block_id;
        }
        if (Tools::isSubmit('submitnewItem'))
        {
            $Item->id_block = Tools::getValue('block_id');
            $Item->type = Tools::getValue('linktype');
            $Item->active = (int)Tools::getValue('active');
            $Item->class = Tools::getValue('custom_class');
            if ($Item->type == 'link')
            {
                $Item->icon = Tools::getValue('item_icon');
                $Item->link = Tools::getValue('link_value');
            }
            elseif ($Item->type == 'img')
            {
                $item_img = Tools::getValue('old_img');
                if (strlen($item_img) > 0) $Item->icon = $item_img;
                $Item->link = Tools::getValue('link_value');
            }
            elseif ($Item->type == 'html')
            {
                foreach ($languages as $language) $Item->text[$language['id_lang']] = Tools::getValue('item_html_' .
                        $language['id_lang']);
            }
        }
        $lang_ul = '<ul class="dropdown-menu">';
        $default_link_option = array();
        foreach ($languages as $lg)
        {
            $link_text = $this->fomartLink((array )$Item, $lg['id_lang']);
            $link_texts[$lg['id_lang']] = $link_text['link'];
            $lang_ul .= '<li><a href="javascript:hideOtherLanguage(' . $lg['id_lang'] . ');" tabindex="-1">' . $lg['name'] .
                '</a></li>';
            $default_link_option[$lg['id_lang']] = $this->getAllDefaultLink($lg['id_lang']);
        }
        $lang_ul .= '</ul>';
        $this->context->smarty->assign(array(
            'form' => 'item',
            'item' => $Item,
            'link_text' => $link_texts,
            'absoluteUrl' => $this->absoluteUrl,
            'default_link_option' => $default_link_option,
            'lang_ul' => $lang_ul,
            'langguages' => array(
                'default_lang' => $id_lang_default,
                'all' => $languages,
                'lang_dir' => _THEME_LANG_DIR_)));
        $iso = Language::getIsoById((int)($id_lang));
        $isoTinyMCE = (file_exists(_PS_ROOT_DIR_ . '/js/tiny_mce/langs/' . $iso . '.js') ? $iso : 'en');
        $ad = dirname($_SERVER["PHP_SELF"]);
        $html = '<script type="text/javascript">
    			var iso = \'' . $isoTinyMCE . '\' ;
    			var pathCSS = \'' . _THEME_CSS_DIR_ . '\' ;
    			var ad = \'' . $ad . '\' ;
    			$(document).ready(function(){
    			tinySetup({
    				editor_selector :"rte",
            		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,fontselect,fontsizeselect",
            		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,codemagic,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
            		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
            		theme_advanced_toolbar_location : "top",
            		theme_advanced_toolbar_align : "left",
            		theme_advanced_statusbar_location : "bottom",
            		theme_advanced_resizing : false,
                    extended_valid_elements: \'pre[*],script[*],style[*]\',
                    valid_children: "+body[style|script],pre[script|div|p|br|span|img|style|h1|h2|h3|h4|h5],*[*]",
                    valid_elements : \'*[*]\',
                    force_p_newlines : false,
                    cleanup: false,
                    forced_root_block : false,
                    force_br_newlines : true
    				});
    			});</script>';
        return $html . $this->display(__file__, 'views/templates/admin/item_form.tpl');
    }
    private function getAllBlocks($id_sub = null)
    {
        if (is_null($id_sub)) return;
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'advance_topmenu_blocks`
                WHERE `id_sub` = ' . $id_sub . '
                ORDER BY  `position` ASC';
        $results = Db::getInstance()->executeS($sql);
        return $results;
    }
    private function getSupMenu($id_main = null, $active = false)
    {
        $id_lang = $this->context->language->id;
        $id_shop = (int)$this->context->shop->id;
        $sql = 'SELECT s.*, til.`title` FROM `' . _DB_PREFIX_ . 'advance_topmenu_sub` s

                LEFT JOIN `' . _DB_PREFIX_ .
            'advance_topmenu_items_lang` til ON (s.`id_parent` = til.`id_item`)

                LEFT JOIN `' . _DB_PREFIX_ .
            'advance_topmenu_main_shop` tms ON (s.`id_parent` = tms.`id_item`)

                WHERE til.`id_lang` = ' . (int)$id_lang . '

                 AND id_shop = ' . $id_shop . ($active ? ' AND active = ' . $active : '') . (is_null
            ($id_main) ? '' : ' AND s.`id_parent` = ' . (int)$id_main);
        $results = Db::getInstance()->executeS($sql);
        return $results;
    }
    private function getItemByBlock($id_block = null, $active = false)
    {
        if (is_null($id_block)) return false;
        $id_lang = (int)$this->context->language->id;
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'advance_topmenu_items` ti

                LEFT JOIN `' . _DB_PREFIX_ .
            'advance_topmenu_items_lang` til ON (ti.`id_item` = til.`id_item`)

                WHERE `id_block` = ' . (int)$id_block . ' AND

                id_lang = ' . $id_lang . ($active ? ' AND active = ' . $active : '') . '

                ORDER BY  ti.`position` ASC';
        $results = Db::getInstance()->executeS($sql);
        return $results;
    }
    private function getMainItem($active = false)
    {
        $id_lang = (int)$this->context->language->id;
        $id_shop = (int)$this->context->shop->id;
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'advance_topmenu_items` ti

                LEFT JOIN `' . _DB_PREFIX_ .
            'advance_topmenu_items_lang` til ON (ti.`id_item` = til.`id_item`)

                LEFT JOIN `' . _DB_PREFIX_ .
            'advance_topmenu_main_shop` tis ON (ti.`id_item` = tis.`id_item`)

                WHERE `id_block` = 0 AND

                tis.`id_shop` = ' . $id_shop . ' AND

                til.`id_lang` = ' . $id_lang . ($active ? ' AND active = ' . $active : '') . '

                ORDER BY  ti.`position` ASC';
        $results = Db::getInstance()->executeS($sql);
        return $results;
    }
    private function getCMSOptions($parent = 0, $depth = 0, $id_lang = false, $link = false)
    {
        $html = '';
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $categories = $this->getCMSCategories(false, (int)$parent, (int)$id_lang);
        $pages = $this->getCMSPages((int)$parent, false, (int)$id_lang);
        $spacer = str_repeat('&nbsp;', 3 * (int)$depth);
        foreach ($categories as $category)
        {
            //$html .= '<option value="CMS_CAT'.$category['id_cms_category'].'" style="font-weight: bold;">'.$spacer.$category['name'].'</option>';
            $html .= $this->getCMSOptions($category['id_cms_category'], (int)$depth + 1, (int)$id_lang, $link);
            //$spacer = str_repeat('&nbsp;', 3 * (int)$depth);
        }
        foreach ($pages as $page)
            if ($link) $html .= '<option value="' . $this->context->link->getCMSLink($page['id_cms']) . '">' . (isset
                    ($spacer) ? $spacer : '') . $page['meta_title'] . '</option>';
            else  $html .= '<option value="CMS' . $page['id_cms'] . '">' . $page['meta_title'] . '</option>';
        return $html;
    }
    private function getCMSCategories($recursive = false, $parent = 1, $id_lang = false)
    {
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        if ($recursive === false)
        {
            $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `' . _DB_PREFIX_ . 'cms_category` bcp
				INNER JOIN `' . _DB_PREFIX_ . 'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = ' . (int)$id_lang . '
				AND bcp.`id_parent` = ' . (int)$parent;
            return Db::getInstance()->executeS($sql);
        }
        else
        {
            $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `' . _DB_PREFIX_ . 'cms_category` bcp
				INNER JOIN `' . _DB_PREFIX_ . 'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = ' . (int)$id_lang . '
				AND bcp.`id_parent` = ' . (int)$parent;
            $results = Db::getInstance()->executeS($sql);
            foreach ($results as $result)
            {
                $sub_categories = $this->getCMSCategories(true, $result['id_cms_category'], (int)$id_lang);
                if ($sub_categories && count($sub_categories) > 0) $result['sub_categories'] = $sub_categories;
                $categories[] = $result;
            }
            return isset($categories) ? $categories : false;
        }
    }
    private function getCMSPages($id_cms_category, $id_shop = false, $id_lang = false)
    {
        $id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`
			FROM `' . _DB_PREFIX_ . 'cms` c
			INNER JOIN `' . _DB_PREFIX_ . 'cms_shop` cs
			ON (c.`id_cms` = cs.`id_cms`)
			INNER JOIN `' . _DB_PREFIX_ . 'cms_lang` cl
			ON (c.`id_cms` = cl.`id_cms`)
			WHERE c.`id_cms_category` = ' . (int)$id_cms_category . '
			AND cs.`id_shop` = ' . (int)$id_shop . '
			AND cl.`id_lang` = ' . (int)$id_lang . '
			AND c.`active` = 1
			ORDER BY `position`';
        return Db::getInstance()->executeS($sql);
    }
    private function getPagesOption($id_lang = null, $link = false)
    {
        if (is_null($id_lang)) $id_lang = (int)$this->context->cookie->id_lang;
        $files = Meta::getMetasByIdLang($id_lang);
        $html = '';
        foreach ($files as $file)
        {
            if ($link) $html .= '<option value="' . $this->context->link->getPageLink($file['page']) . '">' . (($file['title'] !=
                    '') ? $file['title'] : $file['page']) . '</option>';
            else  $html .= '<option value="PAG' . $file['page'] . '">' . (($file['title'] != '') ? $file['title'] :
                    $file['page']) . '</option>';
        }
        return $html;
    }
    private function getCategoryOption($id_category = 1, $id_lang = false, $id_shop = false, $recursive = true,
        $link = false)
    {
        $html = '';
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);
        if (is_null($category->id)) return;
        if ($recursive)
        {
            $children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
            $spacer = str_repeat('&nbsp;', 3 * (int)$category->level_depth);
        }
        $shop = (object)Shop::getShop((int)$category->getShopID());
        if (!in_array($category->id, array(Configuration::get('PS_HOME_CATEGORY'), Configuration::get('PS_ROOT_CATEGORY'))))
        {
            if ($link) $html .= '<option value="' . $this->context->link->getCategoryLink($category->id) . '">' . (isset
                    ($spacer) ? $spacer : '') . str_repeat('&nbsp;', 3 * (int)$category->level_depth) . $category->name .
                    '</option>';
            else  $html .= '<option value="CAT' . (int)$category->id . '">' . str_repeat('&nbsp;', 3 * (int)$category->level_depth) .
                    $category->name . '</option>';
        }
        elseif ($category->id != Configuration::get('PS_ROOT_CATEGORY'))
        {
            $html .= '<optgroup label="' . str_repeat('&nbsp;', 3 * (int)$category->level_depth) . $category->name .
                '">';
        }
        if (isset($children) && count($children))
            foreach ($children as $child)
            {
                $html .= $this->getCategoryOption((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop'],
                    $recursive, $link);
            }
        return $html;
    }
    private function getAllDefaultLink($id_lang = null, $link = false)
    {
        if (is_null($id_lang)) $id_lang = (int)$this->context->language->id;
        $html = '<optgroup label="' . $this->l('Category') . '">';
        $html .= $this->getCategoryOption(1, $id_lang, false, true, $link);
        $html .= '</optgroup>';
        //CMS option
        $html .= '<optgroup label="' . $this->l('Cms') . '">';
        $html .= $this->getCMSOptions(0, 0, $id_lang, $link);
        $html .= '</optgroup>';
        //Manufacturer option
        $html .= '<optgroup label="' . $this->l('Manufacturer') . '">';
        $manufacturers = Manufacturer::getManufacturers(false, $id_lang);
        foreach ($manufacturers as $manufacturer)
        {
            if ($link) $html .= '<option value="' . $this->context->link->getManufacturerLink($manufacturer['id_manufacturer']) .
                    '">' . $manufacturer['name'] . '</option>';
            else  $html .= '<option value="MAN' . (int)$manufacturer['id_manufacturer'] . '">' . $manufacturer['name'] .
                    '</option>';
        }
        $html .= '</optgroup>';
        //Supplier option
        $html .= '<optgroup label="' . $this->l('Supplier') . '">';
        $suppliers = Supplier::getSuppliers(false, $id_lang);
        foreach ($suppliers as $supplier)
        {
            if ($link) $html .= '<option value="' . $this->context->link->getSupplierLink($supplier['id_supplier']) .
                    '">' . $supplier['name'] . '</option>';
            else  $html .= '<option value="SUP' . (int)$supplier['id_supplier'] . '">' . $supplier['name'] .
                    '</option>';
        }
        $html .= '</optgroup>';
        //Page option
        $html .= '<optgroup label="' . $this->l('Page') . '">';
        $html .= $this->getPagesOption($id_lang, $link);
        $shoplink = Shop::getShops();
        if (count($shoplink) > 1)
        {
            $html .= '<optgroup label="' . $this->l('Shops') . '">';
            foreach ($shoplink as $sh)
            {
                $html .= '<option value="SHO' . (int)$sh['id_shop'] . '">' . $sh['name'] . '</option>';
            }
        }
        $html .= '</optgroup>';
        return $html;
    }
    /**
     * Move an uploaded image to the module img/ folder
     */
    private function moveUploadedImage($file)
    {
        $img_name = time() . $file['name'];
        $main_name = $this->absolutePath . 'img' . DIRECTORY_SEPARATOR . $img_name;
        if (!move_uploaded_file($file['tmp_name'], $main_name))
        {
            return false;
        }
        return $img_name;
    }
    private function is_https()
	{
		if(Configuration::get('PS_SSL_ENABLED')) return true;
		else return false;
        //return strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? true : false;
    }
    private function fomartLink($item = null, $id_lang = null)
    {
        if (is_null($item)) return;
        if (!empty($this->context->controller->php_self)) $page_name = $this->context->controller->php_self;
        else
        {
            $page_name = Dispatcher::getInstance()->getController();
            $page_name = (preg_match('/^[0-9]/', $page_name) ? 'page_' . $page_name : $page_name);
        }
        $html = '';
        $selected_item = false;
        if (is_null($id_lang)) $id_lang = (int)$this->context->language->id;
        $type = substr($item['link'], 0, 3);
        $key = substr($item['link'], 3, strlen($item['link']) - 3);
        switch ($type)
        {
            case 'CAT':
                if ($page_name == 'category' && (int)Tools::getValue('id_category') == (int)$key) $selected_item = true;
                $html = $this->context->link->getCategoryLink((int)$key, null, $id_lang);
                break;
            case 'CMS':
                if ($page_name == 'cms' && (int)Tools::getValue('id_cms') == (int)$key) $selected_item = true;
                $html = $this->context->link->getCMSLink((int)$key, null, $id_lang);
                break;
            case 'MAN':
                if ($page_name == 'manufacturer' && (int)Tools::getValue('id_manufacturer') == (int)$key) $selected_item = true;
                $man = new Manufacturer((int)$key, $id_lang);
                $html = $this->context->link->getManufacturerLink($man->id, $man->link_rewrite, $id_lang);
                break;
            case 'SUP':
                if ($page_name == 'supplier' && (int)Tools::getValue('id_supplier') == (int)$key) $selected_item = true;
                $sup = new Supplier((int)$key, $id_lang);
                $html = $this->context->link->getSupplierLink($sup->id, $sup->link_rewrite, $id_lang);
                break;
            case 'PAG':
                $pag = Meta::getMetaByPage($key, $id_lang);
                $html = $this->context->link->getPageLink($pag['page'], true, $id_lang);
                if ($page_name == $pag['page']) $selected_item = true;
                break;
            case 'SHO':
                $shop = new Shop((int)$key);
                $html = $shop->getBaseURL();
                break;
            default:
                $html = $item['link'];
                break;
        }
        return array('link' => $html, 'selected_item' => $selected_item);
    }
    private function preHook()
    {
        $results = $this->getMainItem(true);
        if (count($results) > 0)
            foreach ($results as &$result)
            {
                $submenu = $this->getSupMenu($result['id_item'], true);
                if (count($submenu) > 0)
                {
                    $submenu = $submenu[0];
                    $blocks = $this->getAllBlocks($submenu['id_sub']);
                    if (count($blocks) > 0)
                    {
                        foreach ($blocks as &$block)
                        {
                            $items = $this->getItemByBlock($block['id_block'], true);
                            if (count($items) > 0)
                                foreach ($items as &$item)
                                {
                                    $checklink = $this->fomartLink($item);
                                    $item['link'] = $checklink['link'];
                                    $item['active'] = $checklink['selected_item'];
                                }
                            $block['items'] = $items;
                        }
                        $submenu['blocks'] = $blocks;
                        $result['submenu'] = $submenu;
                    }
                }
                $mainlink = $this->fomartLink($result);
                $result['link'] = $mainlink['link'];
                $result['active'] = $mainlink['selected_item'];
            }

        return $results;
    }
    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') != $this->name) return;
        $iso = Language::getIsoById((int)($this->context->language->id));        
        $ad = dirname($_SERVER["PHP_SELF"]);
        $this->smarty->assign(array('ad' => $ad, 'iso'=>$iso));
        $this->context->controller->addCSS($this->_path . 'css/admin.css');
        $this->context->controller->addJquery();
        $this->context->controller->addJS(_PS_JS_DIR_ . 'tiny_mce/tiny_mce.js');
        $this->context->controller->addJS($this->_path . 'js/tinymce.inc.js');
        $this->context->controller->addJS($this->_path . 'js/jquery-ui.js');
        $this->context->controller->addJS($this->_path . 'js/topmenu_admin.js');
    }
	public function hookDisplayHomeTopMenu($params){
	    $page_name = Dispatcher::getInstance()->getController();
		$page_name = (preg_match('/^[0-9]/', $page_name) ? 'page_'.$page_name : $page_name);
        $this->context->smarty->assign('page_name', $page_name);
       
		$this->smarty->assign(array('MENU' => $this->preHook(), 'absoluteUrl' => $this->absoluteUrl));
  
        return $this->display(__file__, 'topmenu.tpl');
	}	
    public function hookDisplayTop($param)
    {
        $page_name = Dispatcher::getInstance()->getController();
		$page_name = (preg_match('/^[0-9]/', $page_name) ? 'page_'.$page_name : $page_name);
        $this->context->smarty->assign('page_name', $page_name);
        
        $this->smarty->assign(array('MENU' => $this->preHook(), 'absoluteUrl' => $this->absoluteUrl));
        
        return $this->display(__file__, 'topmenu.tpl');
    }
    public function hookDisplayHeader($params)
    {
        $this->hookHeader($params);
    }
    public function hookHeader($params)
    {
        //$this->context->controller->addCSS(($this->_path) . 'css/topmenu.css', 'all');
        $this->context->controller->addJS(($this->_path) . 'js/top_menu.js');
    }
}
