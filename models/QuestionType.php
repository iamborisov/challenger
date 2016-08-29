<?php

namespace app\models;

use Yii;
use app\components\ActiveRecord;
use yii\helpers\Json;

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

    public function check( $data, $answer ) {
        $answer = is_string($answer) ? Json::decode($answer) : $answer;

        switch ( $this->sysname ) {
            case 'select_one':
                return $this->checkSelectOne( $data['answers'], $answer );
            case 'select_multiple':
                return $this->checkSelectMany( $data['answers'], $answer );
            case 'text_short':
                return $this->checkTextShort( $data['answer'], $answer );
            case 'text_long':
                return false;
            case 'dictation':
                return false;
            default:
                return false;
        }
    }

    protected function checkSelectOne( $correct, $answer ) {
        return count($correct) && count($answer) && end($correct) == reset($answer);
    }

    protected function checkSelectMany( $correct, $answer ) {
        return count(array_diff($correct, $answer)) == 0;
    }

    protected function checkTextShort( $correct, $answer ) {
        return $correct == $answer;
    }

}
