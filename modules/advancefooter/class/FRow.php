<?php
if (!defined('_PS_VERSION_'))
	exit;
class FRow extends ObjectModel{
	/** @var integer id_row */
	public $id_row;
    /** @var String custom class  */
    public $rclass;
    /** @var integer position  */
    public $position;
    /** @var Boolean active */
	public $active;

    public static $definition = array(
		'table' => 'advance_footer_row',
		'primary' => 'id_row',
		'fields' => array(
            'active' =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'position'      =>  array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'rclass'         =>	array('type' => self::TYPE_STRING, 'validate' => 'isMessage')
		)
	);
}