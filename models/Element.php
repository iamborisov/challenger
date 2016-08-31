<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class Element extends \app\models\ar\Element
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('element', 'Name'),
            'description' => Yii::t('element', 'Description'),
            'position' => Yii::t('element', 'Position'),
        ];
    }
}
