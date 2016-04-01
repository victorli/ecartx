<?php
if (!defined('_PS_VERSION_'))
	exit;
class Extratabs extends ObjectModel{
    /** @var integer tab ID */
	public $id_tab;
    /** @var integer position */
	public $position;
    /** @var String title */
	public $title;
    /** @var String ID category */
	public $category;
    /** @var String ID id  product */
	public $product;
    /** @var Boolean active */
    public $active;
    /** @var Text tab content  */
    public $content;
    
    public static $definition = array(
		'table' => 'extra_tabs',
		'primary' => 'id_tab',
        'multilang' => true,
		'fields' => array(
            'active' =>			array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' =>		array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
            'category'     =>	array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'required' => true),
            'product'      =>	array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'required' => true),   
            // Lang fields
			'title'        =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isMessage'),       		            
            'content'      => 	array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'))
	);
    public function add($autodate = true, $null_values = false)
	{
		$context = Context::getContext();
		$id_shop = $context->shop->id;

		$res = parent::add($autodate, $null_values);
		$res &= Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'extra_tabs_shop` (`id_shop`, `id_tab`)
			VALUES('.(int)$id_shop.', '.(int)$this->id.')'
		);
		return $res;
	}
    public function delete()
	{
		$res = true;

		$res &= $this->reOrderPositions();

		$res &= Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'extra_tabs_shop`
			WHERE `id_tab` = '.(int)$this->id
		);

		$res &= parent::delete();
		return $res;
	}

	public function reOrderPositions()
	{
		$id_slide = $this->id;
		$context = Context::getContext();
		$id_shop = $context->shop->id;

		$max = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT MAX(et.`position`) as position
			FROM `'._DB_PREFIX_.'extra_tabs` et, `'._DB_PREFIX_.'extra_tabs_shop` ets
			WHERE et.`id_tab` = ets.`id_tab` AND ets.`id_shop` = '.(int)$id_shop
		);

		if ((int)$max == (int)$id_slide)
			return true;

		$rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT et.`position` as position, et.`id_tab` as id_slide
			FROM `'._DB_PREFIX_.'extra_tabs` et
			LEFT JOIN `'._DB_PREFIX_.'extra_tabs_shop` ets ON (et.`id_tab` = ets.`id_tab`)
			WHERE ets.`id_shop` = '.(int)$id_shop.' AND et.`position` > '.(int)$this->position
		);

		foreach ($rows as $row)
		{
			$current_tab = new Extratabs($row['id_tab']);
			--$current_tab->position;
			$current_tab->update();
			unset($current_tab);
		}

		return true;
	}
}