<?php

namespace PerrysTags;

use PerrysLambda\Converter\ListConverter;
use PerrysLambda\Converter\ItemConverter;

class TagConverter extends ListConverter
{

    protected static $serializerinstance;

    public function __construct()
    {
        if(is_null(self::$serializerinstance))
        {
            self::$serializerinstance = new TagSerializer();
        }

        parent::__construct();
        $ic = new ItemConverter();
        $ic->setSerializer(self::$serializerinstance);
        $this->setItemConverter($ic);
    }

}
