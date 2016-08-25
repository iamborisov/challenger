<?php

namespace app\helpers;

use app\models\Challenge;
use app\models\ChallengeGeneration;
use app\models\Question;

class QuestionChooser {

    protected $rules = [];

    protected $ignore = [];

    protected $rule = 0;

    protected $question = 0;

    protected $queue = [];

    public static function fromChallenge( Challenge $challange ) {
        $inst = new self;

        foreach ( $challange->challengeGenerations as $rule ) {
            $inst->addRule( $rule->question_type_id, $rule->question_count );
        }

        return $inst;
    }

    public function __construct()
    {
    }

    public function addRule( $type, $count ) {
        $this->rules[] = [$type, $count];
    }

    public function ignoreQuestion( Question $question ) {
        $this->ignore[] = $question->id;
    }

    public function generate() {
        $result = [];

        while ( $next = $this->next() ) {
            $result[] = $next;
        }

        return $result;
    }

    public function next() {
        if ( !isset( $this->queue[$this->question] ) ) {
            $this->nextRule();
        }

        if ( isset( $this->queue[$this->question] ) ) {
            return $this->nextQuestion();
        } else {
            return false;
        }
    }

    protected function nextRule() {
        if ( isset( $this->rules[$this->rule] ) ) {
            $type = $this->rules[$this->rule][0];
            $count = $this->rules[$this->rule][1];

            $query = Question::find()->where(['not in', 'id', $this->ignore])->andWhere(['question_type_id' => $type]);
            $realCount = $query->count();

            if ( $realCount <= $count ) {
                $questions = $query->select('id')->column();
            } else {
                $offsets = [];
                for ( $i = 0; $i < $count; $i++ ) {
                    while(in_array($num = mt_rand(0, $realCount - 1), $offsets)) {}
                    $offsets[] = $num;
                }
                foreach ( $offsets as $offset ) {
                    $questions[] = $query->offset($offset)->limit(1)->select('id')->scalar();
                }
            }

            foreach ( $questions as $id ) {
                $this->queue[] = $id;
                $this->ignore[] = $id;
            }

            $this->rule++;
        }
    }

    protected function nextQuestion() {
        $this->question++;
        return $this->queue[$this->question - 1];
    }
}