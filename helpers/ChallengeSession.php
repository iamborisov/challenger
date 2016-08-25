<?php

namespace app\helpers;

use \app\models\Challenge;
use app\models\Question;

class ChallengeSession {

    protected $challenge;

    protected $user;

//----------------------------------------------------------------------------------------------------------------------
// Public
//----------------------------------------------------------------------------------------------------------------------

    public function __construct( Challenge $challenge, $user )
    {
        $this->challenge = $challenge;
        $this->user = $user;
    }

    public function canStart() {
        return true;
    }

    public function start() {
        if ( $this->canStart() ) {
            $this->openQueue();
            $this->setCurrentQuestionNumber( 0 );

            return true;
        }

        return false;
    }

    public function finish() {
        $this->saveAnswers();
        $this->closeQueue();
    }

    public function answer( $answer ) {
        if ( !$this->isFinished() ) {
            $this->storeAnswer( $answer );
            $this->setCurrentQuestionNumber( $this->getCurrentQuestionNumber() + 1 );
        }

        if ( $this->isFinished() ) {
            $this->finish();
        }
    }

    public function isFinished() {
        return $this->getCurrentQuestionNumber() >= count( $this->getQueue() );
    }

//----------------------------------------------------------------------------------------------------------------------
// Current question
//----------------------------------------------------------------------------------------------------------------------

    public function getCurrentQuestionNumber() {
        return \Yii::$app->session->get( $this->getSessionKey( 'question' ), 0 );
    }

    public function getCurrentQuestion() {
        $queue = $this->getQueue();
        $question = $this->getCurrentQuestionNumber();
        return Question::findOne( $queue[$question] );
    }

    protected function setCurrentQuestionNumber( $value ) {
        return \Yii::$app->session->set( $this->getSessionKey( 'question' ), $value );
    }

//----------------------------------------------------------------------------------------------------------------------
// Answers
//----------------------------------------------------------------------------------------------------------------------

    protected function storeAnswer( $answer ) {
        \Yii::$app->session->set( $this->getSessionKey( 'answer-' . $this->getCurrentQuestionNumber() ), $answer );
    }

    protected function saveAnswers() {
        $answers = [];

        foreach ( $this->getQueue() as $i => $id ) {
            $answers[$id] = \Yii::$app->session->get( $this->getSessionKey( 'answer-' . $i ), [] );
            \Yii::$app->session->remove( $this->getSessionKey( 'answer-' . $i ) );
        }

        \Yii::$app->session->set( $this->getSessionKey( 'answers' ), $answers );
    }

    public function getAnswers() {
        return \Yii::$app->session->get( $this->getSessionKey( 'answers' ), [] );
    }

//----------------------------------------------------------------------------------------------------------------------
// Questions queue
//----------------------------------------------------------------------------------------------------------------------

    protected function openQueue() {
        \Yii::$app->session->set( $this->getSessionKey( 'queue' ), $this->generateQueue() );
    }

    protected function closeQueue() {
        \Yii::$app->session->remove( $this->getSessionKey( 'queue' ) );
    }

    protected function getQueue() {
        return \Yii::$app->session->get( $this->getSessionKey( 'queue' ) );
    }

    protected function generateQueue() {
        $queue = [];

        switch( $this->challenge->getMode() ) {
            case Challenge::MODE_STATIC:
            case Challenge::MODE_DYNAMIC:
                foreach ( $this->challenge->getQuestions()->all() as $question ) {
                    $queue[] = $question->id;
                }
                break;

            case Challenge::MODE_RANDOM:
                $chooser = new QuestionChooser( $this->challenge );
                $queue = $chooser->generate();
                break;

        }

        return $queue;
    }

//----------------------------------------------------------------------------------------------------------------------
// Helpers
//----------------------------------------------------------------------------------------------------------------------

    private function getSessionKey( $postfix = '' ) {
        return implode('-', [ 'challenge', $this->challenge->id, $this->user, $postfix ]);
    }
}