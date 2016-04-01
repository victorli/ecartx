<?php

if (!defined('_PS_VERSION_'))
    exit;
class Submenu extends ObjectModel
{
    /** @var integer id_sub */
	public $id_sub;
    /** @var integer id_parent */
	public $id_parent;
    /** @var integer width */
    public $width;
    /** @var String custom class  */
    public $class;
    /** @var int active status */
    public $active;
    public static $definition = array(
		'table' => 'advance_topmenu_sub',
		'primary' => 'id_sub',
		'fields' => array(
            'id_parent'     =>	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'width'         => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'class'         =>	array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
            'active'        =>  array('type' => self::TYPE_BOOL,'validate' => 'isBool','required' => true)
		)
	);
    public function checkAvaiable(){
        $result =  Db::getInstance()->executeS('
            SELECT id_sub FROM `' . _DB_PREFIX_ . 'advance_topmenu_sub`
            WHERE id_parent = '.(int)$this->id_parent
        );
        if (count($result) > 0)
            return true;
        else
            return false;
    }
}