<?php

namespace app\models;

use Yii;
use yii\helpers\Json;

/**
 * @inheritdoc
 *
 * @property int[] $courses_ids
 * @property int[] $subjects_ids
 * @property int[] $challengeTypes_ids
 */
class Question extends \app\models\ar\Question
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \voskobovich\behaviors\ManyToManyBehavior::className(),
                'relations' => [
                    'courses_ids' => 'courses',
                    'subjects_ids' => 'subjects',
                    'challengeTypes_ids' => 'challengeTypes',
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['courses_ids', 'subjects_ids', 'challengeTypes_ids'], 'each', 'rule' => ['integer']],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'question_type_id' => Yii::t('questionType', 'Question Type'),
            'text' => Yii::t('question', 'Text'),
            'data' => Yii::t('question', 'Data'),
            'hint' => Yii::t('question', 'Hint'),
            'comment' => Yii::t('question', 'Comment'),
            'cost' => Yii::t('question', 'Cost'),

            'courses_ids' => Yii::t('course', 'Courses'),
            'subjects_ids' => Yii::t('subject', 'Subjects'),
            'challengeTypes_ids' => Yii::t('challengeType', 'Challenge Types')
        ];
    }

    /**
     * Check if answer is correct
     * @param $answer
     * @return bool
     */
    public function check($answer)
    {
        $data = Json::decode($this->data);

        return $this->getQuestionType()->one()->check($data, $answer);
    }
}
