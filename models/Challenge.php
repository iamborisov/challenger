<?php

namespace app\models;

use app\helpers\Subset;
use Yii;

/**
 * This is the model class for table "challenge".
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $challenge_type_id
 * @property integer $element_id
 * @property integer $subject_id
 * @property integer $grade_number
 * @property string $name
 * @property string $description
 * @property integer $exercise_number
 * @property integer $exercise_challenge_number
 *
 * @property Attempt[] $attempts
 * @property Element $element
 * @property Subject $subject
 * @property ChallengeType $challengeType
 * @property Course $course
 * @property ChallengeGeneration[] $challengeGenerations
 * @property ChallengeHasQuestion[] $challengeHasQuestions
 * @property Question[] $questions
 * @property ChallengeMark[] $challengeMarks
 * @property ChallengeSettings $challengeSettings
 */
class Challenge extends \yii\db\ActiveRecord
{

    const MODE_STATIC = 'static';
    const MODE_DYNAMIC = 'dynamic';
    const MODE_RANDOM = 'random';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'challenge';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'challenge_type_id', 'element_id', 'subject_id'], 'required'],
            [['course_id', 'challenge_type_id', 'element_id', 'subject_id', 'grade_number', 'exercise_number', 'exercise_challenge_number'], 'integer'],
            [['name', 'description'], 'string'],
            [['element_id'], 'exist', 'skipOnError' => true, 'targetClass' => Element::className(), 'targetAttribute' => ['element_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::className(), 'targetAttribute' => ['subject_id' => 'id']],
            [['challenge_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChallengeType::className(), 'targetAttribute' => ['challenge_type_id' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('challenge', 'ID'),
            'course_id' => Yii::t('challenge', 'Course ID'),
            'challenge_type_id' => Yii::t('challenge', 'Challenge Type ID'),
            'element_id' => Yii::t('challenge', 'Element ID'),
            'subject_id' => Yii::t('challenge', 'Subject ID'),
            'grade_number' => Yii::t('challenge', 'Grade Number'),
            'name' => Yii::t('challenge', 'Name'),
            'description' => Yii::t('challenge', 'Description'),
            'exercise_number' => Yii::t('challenge', 'Exercise Number'),
            'exercise_challenge_number' => Yii::t('challenge', 'Exercise Challenge Number'),
            'challengeHasQuestions' => Yii::t('challenge', 'Challenge Has Questions'),
            'challengeGenerations' => Yii::t('challenge', 'Challenge Generations'),
        ];
    }

    static public function findFree() {
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
     * @return \yii\db\ActiveQuery
     */
    public function getAttempts()
    {
        return $this->hasMany(Attempt::className(), ['challenge_id' => 'id'])->inverseOf('challenge');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement()
    {
        return $this->hasOne(Element::className(), ['id' => 'element_id'])->inverseOf('challenges');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subject::className(), ['id' => 'subject_id'])->inverseOf('challenges');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeType()
    {
        return $this->hasOne(ChallengeType::className(), ['id' => 'challenge_type_id'])->inverseOf('challenges');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id'])->inverseOf('challenges');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeGenerations()
    {
        return $this->hasMany(ChallengeGeneration::className(), ['challenge_id' => 'id'])->inverseOf('challenge');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeHasQuestions()
    {
        return $this->hasMany(ChallengeHasQuestion::className(), ['challenge_id' => 'id'])->inverseOf('challenge')->orderBy(['position' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this
            ->hasMany(Question::className(), ['id' => 'question_id'])
            ->viaTable('challenge_has_question', ['challenge_id' => 'id'], function($query) {
                $query->orderBy(['position' => SORT_ASC]);
            });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeMarks()
    {
        return $this->hasMany(ChallengeMark::className(), ['challenge_id' => 'id'])->inverseOf('challenge');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeSettings()
    {
        return $this->hasOne(ChallengeSettings::className(), ['challenge_id' => 'id'])->inverseOf('challenge');
    }

    public function getMode() {
        $questions = $this->getChallengeHasQuestions()->count();
        $rules = $this->getChallengeGenerations()->count();

        if ( $questions && $rules ) {
            return self::MODE_DYNAMIC;
        } elseif ( $questions ) {
            return self::MODE_STATIC;
        } elseif ( $rules )  {
            return self::MODE_RANDOM;
        } else {
            return self::MODE_STATIC;
        }
    }

    public function getModes() {
        return [
            self::MODE_STATIC => 'Вручную, я сам выберу необходимые задания',
            self::MODE_DYNAMIC => 'Полуавтоматически, я сам выберу необходимые задания из случайно сгенерированного набора',
            self::MODE_RANDOM => 'Автоматически, при каждом прохождении будут выбраны случайные задания',
        ];
    }

    public function setMode( $mode, $data = null ) {
        ChallengeGeneration::deleteAll(['challenge_id' => $this->id]);
        ChallengeHasQuestion::deleteAll(['challenge_id' => $this->id]);

        switch ( $mode ) {
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

    public function getQuestionsCount() {
        switch ( $this->getMode() ) {
            case self::MODE_STATIC:
            case self::MODE_DYNAMIC:
                return $this->getChallengeHasQuestions()->count();

            case self::MODE_RANDOM:
                $result = 0;
                foreach ( $this->getChallengeGenerations() as $rule ) {
                    $result += $rule->question_count;
                }
                return $result;

            default:
                return 0;
        }
    }
}
