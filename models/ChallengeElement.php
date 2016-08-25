<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "challenge_element".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $position
 *
 * @property Challenge[] $challenges
 */
class ChallengeElement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'challenge_element';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'string'],
            [['position'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'position' => Yii::t('app', 'Position'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenges()
    {
        return $this->hasMany(Challenge::className(), ['challenge_element_id' => 'id'])->inverseOf('challengeElement');
    }
}
