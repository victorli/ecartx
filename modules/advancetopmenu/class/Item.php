<?php if (!defined('_PS_VERSION_')) exit;
class Item extends ObjectModel
{
    /**
     *  *  * @var integer id_item */
    public $id_item;
    /**
     *  *  * @var String id_block */
    public $id_block;
    /**
     *  *  * @var String link type */
    public $type;
    /**
     *  *  * @var String title */
    public $title;
    /**
     *  *  * @var String link*/
    public $link;
    /**
     *  *  * @var String icon*/
    public $icon;
    /**
     *  *  * @var integer position  */
    public $position;
    /**
     *  *  * @var String custom class  */
    public $class;
    /**
     *  *  * @var Text custom text  */
    public $text;
    /**
     *  *  * @var int active status */
    public $active;
    public static $definition = array(
        'table' => 'advance_topmenu_items',
        'primary' => 'id_item',
        'multilang' => true,
        'fields' => array(
            'id_block' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true),
            'type' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isMessage',
                'required' => true),
            'link' => array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
            'icon' => array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
            'class' => array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
            'position' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'active' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool',
                'required' => true),
            // Lang fields
            'title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isMessage',
                'required' => true),
            'text' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isString')));
    public function checkAvaiable()
    {
        $result = Db::getInstance()->executeS('
            SELECT ti.id_item FROM `' . _DB_PREFIX_ . 'advance_topmenu_items` ti
            LEFT JOIN `' . _DB_PREFIX_ .
            'advance_topmenu_items_lang` til ON (ti.`id_item` = til.`id_item`)
            WHERE (id_block) = ' . (int)$this->id_block . ' AND
            UPPER(ti.link) = \'' . strtoupper($this->link) . '\'');
        if (count($result) > 0) return true;
        else  return false;
    }
    public function add($autodate = true, $nullValues = false)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        if ($this->position <= 0) $this->position = Item::getHigherPosition($this->id_block) + 1;
        $res = parent::add($autodate, $nullValues);
        if ($this->id_block == 0) $res &= Db::getInstance()->execute('
    			INSERT INTO `' . _DB_PREFIX_ . 'advance_topmenu_main_shop` (`id_shop`, `id_item`)
    			VALUES(' . (int)$id_shop . ', ' . (int)$this->id . ')');
        return $res;
    }
    public function delete()
    {
        $res = true;
        if ($this->type == 'img')
        {
            $image = $this->icon;
            if ($image && file_exists(dirname(__file__) . '/img/' . $image)) $res &= @unlink(dirname(__file__) .
                    '/img/' . $image);
        }
        $res &= Db::getInstance()->execute('
			DELETE FROM `' . _DB_PREFIX_ . 'advance_topmenu_main_shop`
			WHERE `id_item` = ' . (int)$this->id);
        $res &= parent::delete();
        return $res;
    }
    public static function getHigherPosition($id_block = 0)
    {
        $sql = 'SELECT MAX(`position`)
				FROM `' . _DB_PREFIX_ . 'advance_topmenu_items`
                WHERE id_block = ' . (int)$id_block;
        $position = DB::getInstance()->getValue($sql);
        return (is_numeric($position)) ? $position : -1;
    }
}
