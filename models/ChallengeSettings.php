<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "challenge_settings".
 *
 * @property integer $challenge_id
 * @property integer $immediate_result
 * @property integer $retries_enabled
 * @property integer $registration_required
 * @property integer $subscription_required
 * @property string $start_time
 * @property string $finish_time
 * @property integer $limit_time
 * @property integer $limit_stop
 * @property integer $autostart
 *
 * @property Challenge $challenge
 */
class ChallengeSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'challenge_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['challenge_id'], 'required'],
            [['challenge_id', 'immediate_result', 'retries_enabled', 'registration_required', 'subscription_required', 'limit_time', 'limit_stop', 'autostart'], 'integer'],
            [['start_time', 'finish_time'], 'safe'],
            [['challenge_id'], 'exist', 'skipOnError' => true, 'targetClass' => Challenge::className(), 'targetAttribute' => ['challenge_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'challenge_id' => Yii::t('challenge', 'Challenge ID'),
            'immediate_result' => Yii::t('challenge', 'Immediate Result'),
            'retries_enabled' => Yii::t('challenge', 'Retries Enabled'),
            'registration_required' => Yii::t('challenge', 'Registration Required'),
            'subscription_required' => Yii::t('challenge', 'Subscription Required'),
            'start_time' => Yii::t('challenge', 'Start Time'),
            'finish_time' => Yii::t('challenge', 'Finish Time'),
            'limit_time' => Yii::t('challenge', 'Limit Time'),
            'limit_stop' => Yii::t('challenge', 'Limit Stop'),
            'autostart' => Yii::t('challenge', 'Autostart'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenge()
    {
        return $this->hasOne(Challenge::className(), ['id' => 'challenge_id'])->inverseOf('challengeSettings');
    }
}
