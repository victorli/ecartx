<?php
if (!defined('_PS_VERSION_'))
	exit;

class FBlock extends ObjectModel{
    /** @var integer ID */
	public $id;
	/** @var integer row display block */
	public $id_row;
	   /** @var String block title */
	public $title;
    /** @var String custom class  */
    public $bclass;
    /** @var integer position  */
    public $position;
    /** @var Boolean display title */
	public $display_title;
    /** @var integer width */
    public $width;

    public static $definition = array(
		'table' => 'advance_footer_blocks',
		'primary' => 'id_block',
        'multilang' => true,
		'fields' => array(
			'id_row'            =>	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'display_title'  =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'position'      =>  array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'bclass'         =>	array('type' => self::TYPE_STRING, 'validate' => 'isMessage'),
            'width'          =>	array('type' => self::TYPE_INT,  'validate' => 'isUnsignedId'),
            // Lang fields
			'title'          =>	array('type' => self::TYPE_STRING , 'lang' => true, 'validate' => 'isMessage', 'required' => true),

		)
	);

    public function add($autodate = true, $null_values = false)
	{
		$context = Context::getContext();
		$id_shop = $context->shop->id;

		$res = parent::add($autodate, $null_values);
		$res &= Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'advance_footer_shop` (`id_shop`, `id_block`)
			VALUES('.(int)$id_shop.', '.(int)$this->id.')'
		);
		return $res;
	}

    public function delete(){
        $res = Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'advance_footer_shop`
			WHERE `id_block` = '.(int)$this->id
		);

		$res &= parent::delete();
		return $res;
    }
    public function checkAvaiable(){
        $result =  Db::getInstance()->executeS('
            SELECT b.`id_block` FROM `' . _DB_PREFIX_ . 'advance_footer_blocks` b
            LEFT JOIN `'._DB_PREFIX_.'advance_footer_blocks_lang` bl ON (b.`id_block` = bl.`id_block`)
            WHERE `id_row` = '.(int)$this->id_row.' AND
            UPPER(bl.`title`) = \''.strtoupper($this->title).'\' AND
            `display_title` = \''.$this->display_title.'\' AND
            `width` = '.(int)$this->width
        );
        if (count($result) > 0)
            return true;
        else
            return false;
    }
    public function getItems(){
        $context = Context::getContext();
        $id_lang = $context->language->id;
        $result =Db::getInstance()->executeS('
            Select bi.*, bil.* from `' . _DB_PREFIX_ . 'advance_footer_block_items` bi
            LEFT JOIN `' . _DB_PREFIX_ . 'advance_footer_block_items_lang` bil ON (bi.`id_item` = bil.`id_item`)
            WHERE
            bi.`id_block` = '.$this->id.' AND
            bil.`id_lang` = '.(int)$id_lang .'
            ORDER BY  bi.`position` ASC');
        return $result;
    }
}