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
            'name' => Yii::t('course', 'Name'),
            'description' => Yii::t('course', 'Description'),
            'position' => Yii::t('course', 'Position'),
        ];
    }
}
