<?php

namespace app\models;

use Yii;
use app\components\ActiveRecord;

/**
 * This is the model class for table "question_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $sysname
 *
 * @property ChallengeGeneration[] $challengeGenerations
 * @property Question[] $questions
 */
class QuestionType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['sysname'], 'string', 'max' => 32],
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
            'sysname' => Yii::t('app', 'Sysname'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeGenerations()
    {
        return $this->hasMany(ChallengeGeneration::className(), ['question_type_id' => 'id'])->inverseOf('questionType');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['question_type_id' => 'id'])->inverseOf('questionType');
    }

}
