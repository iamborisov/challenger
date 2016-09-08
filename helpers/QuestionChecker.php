<?php

namespace app\helpers;

use app\models\Question;
use yii\helpers\Json;

class QuestionChecker {

    public static function check(Question $question, $answer) {
        $data = $question->getData();
        $answer = is_string($answer) ? Json::decode($answer) : $answer;

        switch ($question->getQuestionType()->one()->sysname) {
            case 'select_one':
                return self::checkSelectOne($data['answers'], $answer);

            case 'select_multiple':
                return self::checkSelectMany($data['answers'], $answer);

            case 'text_short':
                return self::checkTextShort($data['answer'], $answer);

            case 'text_long':
                return false;

            case 'dictation':
                return false;

            case 'assoc':
                return self::checkAssoc($answer);

            default:
                return false;
        }
    }

    /**
     * @param $correct
     * @param $answer
     * @return bool
     */
    protected static function checkSelectOne($correct, $answer)
    {
        return count($correct) && count($answer) && end($correct) == reset($answer);
    }

    /**
     * @param $correct
     * @param $answer
     * @return bool
     */
    protected static function checkSelectMany($correct, $answer)
    {
        return (count($correct) == count($answer)) && count(array_diff($correct, $answer)) == 0;
    }

    /**
     * @param $correct
     * @param $answer
     * @return bool
     */
    protected static function checkTextShort($correct, $answer)
    {
        $answer = trim( $answer );
        return strcasecmp($correct, $answer) == 0;
    }

    /**
     * @param $correct
     * @param $answer
     * @return bool
     */
    protected static function checkAssoc($answer)
    {
        if ( !is_array($answer) || !count($answer) ) {
            return false;
        }

        foreach ( $answer as $i => $pair ) {
            if ( $pair[0] != $i || $pair[1] != $i ) {
                return false;
            }
        }

        return true;
    }
}