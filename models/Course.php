<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class Course extends \app\models\ar\Course
{
    static public function findSubscribed($user) {
        $subscriptions = CourseSubscription::find()
            ->select('course_id')
            ->where(['user_id' => is_object($user) ? $user->id : $user])
            ->column();

        return self::find()->where(['id' => $subscriptions]);
    }

    static public function findAvailable($user) {
        $subscriptions = CourseSubscription::find()
            ->select('course_id')
            ->where(['user_id' => is_object($user) ? $user->id : $user])
            ->column();

        return self::find()->where(['not in', 'id', $subscriptions]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'position' => Yii::t('app', 'Position'),
            'subjects' => Yii::t('subject', 'Subjects'),
        ];
    }
}
