<?php

namespace app\components;

use Yii;
use Yii\helpers\ArrayHelper;

class ActiveRecord extends \yii\db\ActiveRecord
{

    public function onUnsafeAttribute($name, $value)
    {
        if (YII_DEBUG) {
            Yii::trace("Failed to set unsafe attribute '$name' in '" . get_class($this) . "'.", __METHOD__);
        }
    }

//======================================================================================================================
// LISTBOX HELPER
//======================================================================================================================
    public static function getList($exclude = [], $field = 'name')
    {
        $ids = [];
        foreach ( $exclude as $item ) {
            $ids[] = is_object($item) ? $item->id : $item;
        }

        $condition = count($ids) ? ['not in', 'id', $ids] : '';

        return ArrayHelper::map(static::find()->where($condition)->all(), 'id', $field);
    }
}