<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class ChallengeType extends \app\models\ar\ChallengeType
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('challengeType', 'Name'),
            'description' => Yii::t('challengeType', 'Description'),
            'position' => Yii::t('challengeType', 'Position'),
        ];
    }

}
