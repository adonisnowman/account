<?php


class Users extends BaseModel
{

	public static $tableName = __CLASS__;
	public function initialize()
	{		
        $this->setSource(self::$tableName);
	}

	public function beforeValidationOnCreate()
	{
		$this->register_time = Tools::getDateTime();
		$this->updated_time = Tools::getDateTime();
		$this->REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
		$this->HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
	}

	public function beforeValidationOnUpdate()
	{
		$this->updated_time = Tools::getDateTime();
		$this->REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
		$this->HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
	}

	public function beforeSave()
	{
		if (!empty($this->password)) {
			$this->password = Tools::Crypt($this->password);
		}
        if (!empty($this->mobile)) {
			$this->mobile = Tools::Crypt($this->mobile);
		}
	}

	public function afterFetch()
	{
		if (!empty($this->password)) {
			$this->password = Tools::Crypt($this->password,true);
		}
        if (!empty($this->mobile)) {
			$this->mobile = Tools::Crypt($this->mobile,true);
		}
		return $this;
	}

	public function afterSave()
	{
	}
	public static function getObjectById($Item)
    {
        $keys = ["UniqueID"];
        $Object = self::$tableName::findFirst([
            'conditions' => Models::Conditions($keys),
            'bind'       => Tools::fix_element_Key($Item, $keys),
            'for_update' => true,
        ]);
        return $Object;
    }
    public static function getOneById($Item)
    {

        $keys = ["UniqueID"];
        $Item = self::$tableName::findFirst([
            'conditions' => Models::Conditions($keys),
            'bind'       => Tools::fix_element_Key($Item, $keys),
            'for_update' => true,
        ]);

        return (empty($Item)) ? [] : $Item->toArray();
    }
    public static function getObjectByItem($Item)
    {
        $keys = array_keys($Item);
		$Object = self::$tableName::findFirst([
            'conditions' => Models::Conditions($keys),
            'bind'       => Tools::fix_element_Key($Item, $keys),
            'for_update' => true,
        ]);
        return $Object;
    }
    public static function getOneByItem($Item)
    {

        $keys = array_keys($Item);
        $Item = self::$tableName::findFirst([
            'conditions' => Models::Conditions($keys),
            'bind'       => Tools::fix_element_Key($Item, $keys),
            'for_update' => true,
        ]);

        return (empty($Item->UniqueID)) ? [] : $Item->toArray();
    }

    public static function getListByItem($Item)
    {

        $keys = array_keys($Item);
        $List = self::$tableName::find([
            'conditions' => Models::Conditions($keys),
            'bind'       => Tools::fix_element_Key($Item, $keys),
            'for_update' => true,
        ]);

        $List = (empty($List)) ? [] : $List->toArray();

        return $List;
    }
	
}