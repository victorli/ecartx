<?php
if (!defined('_PS_VERSION_'))
	exit;
class FItem extends ObjectModel{
    /** @var integer ID */
	public $id;
	/** @var integer id_block */
	public $id_block;
	   /** @var String block title */
	public $title;
    /** @var Boolean display title */
	public $display_title;
    /** @var String type of item */
    public $itemtype;
    /** @var String content key, link type, module id */
    public $content_key;
    /** @var integer position  */
    public $position;
    /** @var String target link, hook module */
    public $target;
    /** @var String content value, link value */
    public $content_value;
    /** @var String custom html value  */
    public $text;

    public static $definition = array(
		'table' => 'advance_footer_block_items',
		'primary' => 'id_item',
        'multilang' => true,
		'fields' => array(
			'id_block'     =>	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'display_title' =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'position'      =>  array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'itemtype'      =>	array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
            'content_key'   =>	array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
            'target'        =>	array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
            'content_value' =>	array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
            // Lang fields
			'title'         =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isMessage', 'required' => true),
            'text'          =>	array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
		)
	);
    public function checkAvaiable(){
        $result =  Db::getInstance()->executeS('
            SELECT bi.`id_item` FROM `' . _DB_PREFIX_ . 'advance_footer_block_items` bi
            LEFT JOIN `' . _DB_PREFIX_ . 'advance_footer_block_items_lang` bil ON (bi.`id_item` = bil.`id_item`)
            WHERE bi.`id_block` = '.(int)$this->id_block.' AND
            UPPER(bil.`title`) = \''.strtoupper($this->title).'\' AND
            bi.`display_title` = \''.$this->display_title.'\' AND
            UPPER(bi.`itemtype`) = \''.strtoupper($this->itemtype).'\' AND
            UPPER(bi.`content_key`) = \''.strtoupper($this->content_key).'\' AND
            UPPER(bi.`target`) = \''.strtoupper($this->target).'\' AND
            UPPER(bi.`content_value`) = \''.strtoupper($this->content_value).'\''
        );
        if (count($result) > 0)
            return true;
        else
            return false;
    }
}