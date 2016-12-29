<?php

namespace app\models;

use app\helpers\QuestionChecker;
use kartik\markdown\Markdown;
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

            'courses_ids' => Yii::t('question', 'Courses'),
            'subjects_ids' => Yii::t('question', 'Subjects'),
            'challengeTypes_ids' => Yii::t('question', 'Challenge Types')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionType()
    {
        return $this->hasOne(QuestionType::className(), ['id' => 'question_type_id'])->inverseOf('questions');
    }

    /**
     * Check if answer is correct
     * @param $answer
     * @return bool
     */
    public function check($answer)
    {
        return QuestionChecker::check($this, $answer);
    }

    /**
     * @return array
     */
    public function getData() {
        return Json::decode($this->data);
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if( parent::beforeDelete() ) {
            $cond = ['question_id' => $this->id];

            ChallengeHasQuestion::deleteAll($cond);

            QuestionHasCourse::deleteAll($cond);
            QuestionHasChallengeType::deleteAll($cond);
            QuestionHasSubject::deleteAll($cond);

            Answer::deleteAll($cond);

            return true;
        }

        return false;
    }

    /**
     * @param bool $html
     * @return string
     */
    public function getComment($html = false) {
        return $html ? nl2br(rtrim(Markdown::convert($this->comment), "\r\n")) : $this->comment;
    }

    /**
     * @param bool $html
     * @return string
     */
    public function getText($html = false) {
        return $html ? nl2br(rtrim(Markdown::convert($this->text), "\r\n")) : $this->text;
    }

    /**
     * @param bool $html
     * @return string
     */
    public function getHint($html = false) {
        return $html ? nl2br(rtrim(Markdown::convert($this->hint), "\r\n")) : $this->hint;
    }

}
