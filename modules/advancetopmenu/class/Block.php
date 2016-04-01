<?php

if (!defined('_PS_VERSION_'))
    exit;
class Block extends ObjectModel
{
    /** @var integer ID */
    public $id_block;
    /** @var integer id sub menu */
    public $id_sub;
    /** @var integer position */
    public $position;
     /** @var integer width */
    public $width;
    /** @var String custom class  */
    public $class;

    public static $definition = array(
        'table' => 'advance_topmenu_blocks',
        'primary' => 'id_block',
        'fields' => array(
            'id_sub'        =>	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'width'         => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'class'         =>	array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
            'position'      =>  array('type' => self::TYPE_STRING, 'validate' => 'isInt')
            ));
}
