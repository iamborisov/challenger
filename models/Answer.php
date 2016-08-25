<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "answer".
 *
 * @property integer $id
 * @property integer $attempt_id
 * @property integer $question_id
 * @property string $data
 * @property integer $correct
 *
 * @property Attempt $attempt
 * @property Question $question
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attempt_id', 'question_id'], 'required'],
            [['attempt_id', 'question_id', 'correct'], 'integer'],
            [['data'], 'string'],
            [['attempt_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attempt::className(), 'targetAttribute' => ['attempt_id' => 'id']],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::className(), 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'attempt_id' => Yii::t('app', 'Attempt ID'),
            'question_id' => Yii::t('app', 'Question ID'),
            'data' => Yii::t('app', 'Data'),
            'correct' => Yii::t('app', 'Correct'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttempt()
    {
        return $this->hasOne(Attempt::className(), ['id' => 'attempt_id'])->inverseOf('answers');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id'])->inverseOf('answers');
    }
}
