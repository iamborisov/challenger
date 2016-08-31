<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class Subject extends \app\models\ar\Subject
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'course_id' => Yii::t('course', 'Course ID'),
            'name' => Yii::t('subject', 'Name'),
            'description' => Yii::t('subject', 'Description'),
            'position' => Yii::t('subject', 'Position'),
        ];
    }
}
