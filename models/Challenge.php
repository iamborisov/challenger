<?php

namespace app\models;

use app\helpers\Subset;
use Yii;

/**
 * @inheritdoc
 */
class Challenge extends \app\models\ar\Challenge
{
    const MODE_STATIC = 'static';
    const MODE_DYNAMIC = 'dynamic';
    const MODE_RANDOM = 'random';

    /**
     * Get free chalanges
     * @return array|\yii\db\ActiveRecord[]
     */
    static public function findFree()
    {
        return self::find()->with([
            'challengeSettings' => function (\yii\db\ActiveQuery $query) {
                $query->andWhere([
                    'registration_required' => false,
                    'subscription_required' => false,
                ]);
            }
        ])->all();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'course_id' => Yii::t('course', 'Course'),
            'challenge_type_id' => Yii::t('challengeType', 'Challenge Type'),
            'element_id' => Yii::t('element', 'Element'),
            'subject_id' => Yii::t('subject', 'Subject'),
            'grade_number' => Yii::t('challenge', 'Grade Number'),
            'name' => Yii::t('challenge', 'Name'),
            'description' => Yii::t('challenge', 'Description'),
            'exercise_number' => Yii::t('challenge', 'Exercise Number'),
            'exercise_challenge_number' => Yii::t('challenge', 'Exercise Challenge Number'),
            'challengeHasQuestions' => Yii::t('challenge', 'Challenge Has Questions'),
            'challengeGenerations' => Yii::t('challenge', 'Challenge Generations'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeHasQuestions()
    {
        return parent::getChallengeHasQuestions()->orderBy(['position' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this
            ->hasMany(Question::className(), ['id' => 'question_id'])
            ->viaTable('challenge_has_question', ['challenge_id' => 'id'], function ($query) {
                $query->orderBy(['position' => SORT_ASC]);
            });
    }

    /**
     * Get question generation mode
     * @return string
     */
    public function getMode()
    {
        $questions = $this->getChallengeHasQuestions()->count();
        $rules = $this->getChallengeGenerations()->count();

        if ($questions && $rules) {
            return self::MODE_DYNAMIC;
        } elseif ($questions) {
            return self::MODE_STATIC;
        } elseif ($rules) {
            return self::MODE_RANDOM;
        } else {
            return self::MODE_STATIC;
        }
    }

    /**
     * Get modes list
     * @return array
     */
    public function getModes()
    {
        return [
            self::MODE_STATIC => 'Вручную, я сам выберу необходимые задания',
            self::MODE_DYNAMIC => 'Полуавтоматически, я сам выберу необходимые задания из случайно сгенерированного набора',
            self::MODE_RANDOM => 'Автоматически, при каждом прохождении будут выбраны случайные задания',
        ];
    }

    /**
     * Set question generation mode
     * @param $mode
     * @param array $data
     */
    public function setMode($mode, $data = null)
    {
        ChallengeGeneration::deleteAll(['challenge_id' => $this->id]);
        ChallengeHasQuestion::deleteAll(['challenge_id' => $this->id]);

        switch ($mode) {
            case self::MODE_STATIC:
                Subset::save(
                    ChallengeHasQuestion::className(),
                    $data,
                    ['challenge_id' => $this->id]
                );
                break;
            case self::MODE_DYNAMIC:
                Subset::save(
                    ChallengeHasQuestion::className(),
                    $data,
                    ['challenge_id' => $this->id]
                );
                Subset::save(
                    ChallengeGeneration::className(),
                    $data,
                    ['challenge_id' => $this->id]
                );
                break;
            case self::MODE_RANDOM:
                Subset::save(
                    ChallengeGeneration::className(),
                    $data,
                    ['challenge_id' => $this->id]
                );
                break;
        }
    }

    /**
     * Get questions count in this challenge
     * @return int
     */
    public function getQuestionsCount()
    {
        switch ($this->getMode()) {
            case self::MODE_STATIC:
            case self::MODE_DYNAMIC:
                return $this->getChallengeHasQuestions()->count();

            case self::MODE_RANDOM:
                $result = 0;
                foreach ($this->getChallengeGenerations() as $rule) {
                    $result += $rule->question_count;
                }
                return $result;

            default:
                return 0;
        }
    }
}
