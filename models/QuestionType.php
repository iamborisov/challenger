<?php

namespace app\models;

use Yii;
use yii\helpers\Json;

/**
 * @inheritdoc
 */
class QuestionType extends \app\models\ar\QuestionType
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('questionType', 'Name'),
            'sysname' => Yii::t('questionType', 'Sysname'),
        ];
    }

    /**
     * Check if question answered correctly
     * @param $data Question settings
     * @param $answer Answer
     * @return bool
     */
    public function check($data, $answer)
    {
        $answer = is_string($answer) ? Json::decode($answer) : $answer;

        switch ($this->sysname) {
            case 'select_one':
                return $this->checkSelectOne($data['answers'], $answer);

            case 'select_multiple':
                return $this->checkSelectMany($data['answers'], $answer);

            case 'text_short':
                return $this->checkTextShort($data['answer'], $answer);

            case 'text_long':
                return false;

            case 'dictation':
                return false;

            default:
                return false;
        }
    }

    /**
     * @param $correct
     * @param $answer
     * @return bool
     */
    protected function checkSelectOne($correct, $answer)
    {
        return count($correct) && count($answer) && end($correct) == reset($answer);
    }

    /**
     * @param $correct
     * @param $answer
     * @return bool
     */
    protected function checkSelectMany($correct, $answer)
    {
        return (count($correct) == count($answer)) && count(array_diff($correct, $answer)) == 0;
    }

    /**
     * @param $correct
     * @param $answer
     * @return bool
     */
    protected function checkTextShort($correct, $answer)
    {
        return strcasecmp($correct, $answer) == 0;
    }

}
