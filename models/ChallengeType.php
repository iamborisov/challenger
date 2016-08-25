<?php

namespace app\models;

use Yii;
use app\components\ActiveRecord;

/**
 * This is the model class for table "challenge_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $position
 *
 * @property Challenge[] $challenges
 * @property QuestionHasChallengeType[] $questionHasChallengeTypes
 * @property Question[] $questions
 */
class ChallengeType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'challenge_type';
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
        return $this->hasMany(Challenge::className(), ['challenge_type_id' => 'id'])->inverseOf('challengeType');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionHasChallengeTypes()
    {
        return $this->hasMany(QuestionHasChallengeType::className(), ['challenge_type_id' => 'id'])->inverseOf('challengeType');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['id' => 'question_id'])->viaTable('question_has_challenge_type', ['challenge_type_id' => 'id']);
    }
}
