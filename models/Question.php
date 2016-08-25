<?php

namespace app\models;

use Yii;
use app\components\ActiveRecord;

/**
 * This is the model class for table "question".
 *
 * @property integer $id
 * @property integer $question_type_id
 * @property string $text
 * @property string $data
 * @property string $hint
 * @property string $comment
 * @property integer $cost
 *
 * @property Answer[] $answers
 * @property ChallengeHasQuestion[] $challengeHasQuestions
 * @property Challenge[] $challenges
 * @property QuestionType $questionType
 * @property QuestionHasChallengeType[] $questionHasChallengeTypes
 * @property ChallengeType[] $challengeTypes
 * @property QuestionHasCourse[] $questionHasCourses
 * @property Course[] $courses
 * @property QuestionHasSubject[] $questionHasSubjects
 * @property Subject[] $subjects
 */
class Question extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question';
    }

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
        return [
            [['question_type_id'], 'required'],
            [['question_type_id', 'cost'], 'integer'],
            [['text', 'data', 'hint', 'comment'], 'string'],
            [['question_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuestionType::className(), 'targetAttribute' => ['question_type_id' => 'id']],

            [['courses_ids', 'subjects_ids', 'challengeTypes_ids'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('question', 'ID'),
            'question_type_id' => Yii::t('question', 'Question Type'),
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
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['question_id' => 'id'])->inverseOf('question');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeHasQuestions()
    {
        return $this->hasMany(ChallengeHasQuestion::className(), ['question_id' => 'id'])->inverseOf('question');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenges()
    {
        return $this->hasMany(Challenge::className(), ['id' => 'challenge_id'])->viaTable('challenge_has_question', ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionType()
    {
        return $this->hasOne(QuestionType::className(), ['id' => 'question_type_id'])->inverseOf('questions');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionHasChallengeTypes()
    {
        return $this->hasMany(QuestionHasChallengeType::className(), ['question_id' => 'id'])->inverseOf('question');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeTypes()
    {
        return $this->hasMany(ChallengeType::className(), ['id' => 'challenge_type_id'])->viaTable('question_has_challenge_type', ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionHasCourses()
    {
        return $this->hasMany(QuestionHasCourse::className(), ['question_id' => 'id'])->inverseOf('question');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourses()
    {
        return $this->hasMany(Course::className(), ['id' => 'course_id'])->viaTable('question_has_course', ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionHasSubjects()
    {
        return $this->hasMany(QuestionHasSubject::className(), ['question_id' => 'id'])->inverseOf('question');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(Subject::className(), ['id' => 'subject_id'])->viaTable('question_has_subject', ['question_id' => 'id']);
    }
}
