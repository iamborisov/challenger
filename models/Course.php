<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class Course extends \app\models\ar\Course
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'position' => Yii::t('app', 'Position'),
        ];
    }
}
