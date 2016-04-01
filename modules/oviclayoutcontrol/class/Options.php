<?php if (!defined('_PS_VERSION_')) exit;
class Options extends ObjectModel
{
    /**
     *  *  * @var integer id_item */
    public $id_option;
    /**
     *  *  * @var String name */
    public $name;
    /**
     *  *  * @var String image name*/
    public $image;
    /**
     *  *  * @var String column selected  */
    public $column;
    /**
     *  *  * @var int active status */
    public $active;
    public static $definition = array(
        'table' => 'ovic_options',
        'primary' => 'id_option',
        'multilang' => true,
        'fields' => array(
            'image' => array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
            'column' => array('type' => self::TYPE_STRING, 'validate' => 'isMessage','required' => true),
            'active' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool',
                'required' => true),
            // Lang fields
            'name' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isMessage',
                'required' => true)));

    public function delete()
    {
        $context = Context::getContext();
        $res = true;
        $image = $this->image;
        if ($image && file_exists(dirname(__file__) . '/../thumbnails/' . $image)) $res &= @unlink(dirname(__file__) .
                '/../thumbnails/' . $image);
        $where = "`id_option` = ".(int)$this->id_option. " AND `id_shop` = ".(int)$context->shop->id;
        $res &= Db::getInstance()->delete('ovic_options_hook_module', $where);
        $res &= parent::delete();
        return $res;
    }
}
