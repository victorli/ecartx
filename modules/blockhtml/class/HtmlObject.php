<?php if (!defined('_PS_VERSION_')) exit;
class HtmlObject extends ObjectModel
{
    /**
     @var integer tab ID */
    public $id_htmlobject;
    /**
     @var int active status */
    public $active;
    /**
     @var int hook_postition */
    public $hook_postition;
    /**
     @var string title  */
    public $title;
    /**
     @var string content  */
    public $content;
    
    public static $definition = array(
        'table' => 'htmlobject',
        'primary' => 'id_htmlobject',
        'multilang' => true,
        'fields' => array(
            'hook_postition' =>  array('type' => self::TYPE_INT, 'validate' => 'isInt','required' => true),
            'active'         =>  array('type' => self::TYPE_BOOL,'validate' => 'isBool','required' => true),
            // Lang fields
            'title'          =>	array('type' => self::TYPE_STRING, 'lang' => true,'validate' => 'isString'),
            'content'        =>	array('type' => self::TYPE_HTML, 'lang' => true,'validate' => 'isString'))
        );
    public function add($autodate = true, $null_values = false)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $res = parent::add($autodate, $null_values);
        $res &= Db::getInstance()->execute('
			INSERT INTO `' . _DB_PREFIX_ . 'htmlobject_shop` (`id_shop`, `id_htmlobject`)
			VALUES(' . (int)$id_shop . ', ' . (int)$this->id . ')');
        return $res;
    }
    public function delete()
    {
        $res = true;
        $res &= Db::getInstance()->execute('
			DELETE FROM `' . _DB_PREFIX_ . 'home_adv_shop`
			WHERE `id_htmlobject` = ' . (int)$this->id);
        $res &= parent::delete();
        return $res;
    }
}
