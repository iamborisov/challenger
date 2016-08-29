<?php

namespace app\helpers;

use app\models\Challenge;
use app\models\Question;

/**
 * Challenge Session Manager
 * @package app\helpers
 */
class ChallengeSession
{

    /**
     * @var Challenge
     */
    protected $challenge;

    /**
     * User ID
     * @var int
     */
    protected $user;

//----------------------------------------------------------------------------------------------------------------------
// Public
//----------------------------------------------------------------------------------------------------------------------

    /**
     * ChallengeSession constructor.
     * @param Challenge $challenge
     * @param $user
     */
    public function __construct(Challenge $challenge, $user)
    {
        $this->challenge = $challenge;
        $this->user = $user;
    }

    /**
     * Can user start this challenge?
     * @return bool
     */
    public function canStart()
    {
        return true;
    }

    /**
     * Start challenge
     * @return bool
     */
    public function start()
    {
        if ($this->canStart()) {
            $this->openQueue();
            $this->setCurrentQuestionNumber(0);
            $this->setStartTime();

            return true;
        }

        return false;
    }

    /**
     * Finish challenge
     * Calling when the last answer submitted
     */
    public function finish()
    {
        $this->saveAnswers();
        $this->closeQueue();
        $this->setFinishTime();
    }

    /**
     * Submit answer and switch to next question.
     * If no questions left - finish challenge
     * @param $answer string
     */
    public function answer($answer)
    {
        if (!$this->isFinished()) {
            $this->storeAnswer($answer);
            $this->setCurrentQuestionNumber($this->getCurrentQuestionNumber() + 1);
        }

        if ($this->isFinished()) {
            $this->finish();
        }
    }

    /**
     * Is last question reached?
     * @return bool
     */
    public function isFinished()
    {
        return $this->getCurrentQuestionNumber() >= count($this->getQueue());
    }

    /**
     * @return Challenge
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

//----------------------------------------------------------------------------------------------------------------------
// Current question
//----------------------------------------------------------------------------------------------------------------------

    /**
     * @return int
     */
    public function getCurrentQuestionNumber()
    {
        return \Yii::$app->session->get($this->getSessionKey('question'), 0);
    }

    /**
     * @return Question
     */
    public function getCurrentQuestion()
    {
        $queue = $this->getQueue();
        $question = $this->getCurrentQuestionNumber();
        return Question::findOne($queue[$question]);
    }

    /**
     * @param $value int
     */
    protected function setCurrentQuestionNumber($value)
    {
        \Yii::$app->session->set($this->getSessionKey('question'), $value);
    }

//----------------------------------------------------------------------------------------------------------------------
// Answers
//----------------------------------------------------------------------------------------------------------------------

    /**
     * Temporary stores answer until challenge finished
     * @param $answer string
     */
    protected function storeAnswer($answer)
    {
        \Yii::$app->session->set($this->getSessionKey('answer-' . $this->getCurrentQuestionNumber()), $answer);
    }

    /**
     * Clear temporary answers storage and combine answers into QuestionID => Answer array
     */
    protected function saveAnswers()
    {
        $answers = [];

        foreach ($this->getQueue() as $i => $id) {
            $answers[$id] = \Yii::$app->session->get($this->getSessionKey('answer-' . $i), []);
            \Yii::$app->session->remove($this->getSessionKey('answer-' . $i));
        }

        \Yii::$app->session->set($this->getSessionKey('answers'), $answers);
    }

    /**
     * Get all answers (available after challenge finish)
     * @return array
     */
    public function getAnswers()
    {
        return \Yii::$app->session->get($this->getSessionKey('answers'), []);
    }

    /**
     * Get current question answer
     * @return string
     */
    public function getAnswer()
    {
        return \Yii::$app->session->get($this->getSessionKey('answer-' . $this->getCurrentQuestionNumber()), '');
    }

//----------------------------------------------------------------------------------------------------------------------
// Questions queue
//----------------------------------------------------------------------------------------------------------------------

    /**
     * Create questions queue
     */
    protected function openQueue()
    {
        \Yii::$app->session->set($this->getSessionKey('queue'), $this->generateQueue());
    }

    /**
     * Clear questions queue
     */
    protected function closeQueue()
    {
        \Yii::$app->session->remove($this->getSessionKey('queue'));
    }

    /**
     * Get questions ids for current challenge
     * @return int[]
     */
    protected function getQueue()
    {
        return \Yii::$app->session->get($this->getSessionKey('queue'));
    }

    /**
     * Generate questions queue using challenge settings
     * @return int[]
     */
    protected function generateQueue()
    {
        $queue = [];

        switch ($this->challenge->getMode()) {
            case Challenge::MODE_STATIC:
            case Challenge::MODE_DYNAMIC:
                foreach ($this->challenge->getQuestions()->all() as $question) {
                    $queue[] = $question->id;
                }
                break;

            case Challenge::MODE_RANDOM:
                $chooser = new QuestionChooser($this->challenge);
                $queue = $chooser->generate();
                break;

        }

        return $queue;
    }

//----------------------------------------------------------------------------------------------------------------------
// Time tracking
//----------------------------------------------------------------------------------------------------------------------

    protected function setStartTime()
    {
        \Yii::$app->session->set($this->getSessionKey('start'), time());
    }

    protected function setFinishTime()
    {
        \Yii::$app->session->set($this->getSessionKey('finish'), time());
    }

    public function getStartTime()
    {
        return \Yii::$app->session->get($this->getSessionKey('start'));
    }

    public function getFinishTime()
    {
        return \Yii::$app->session->get($this->getSessionKey('finish'));
    }

//----------------------------------------------------------------------------------------------------------------------
// Helpers
//----------------------------------------------------------------------------------------------------------------------

    /**
     * Generate session key
     * @param string $postfix
     * @return string
     */
    private function getSessionKey($postfix = '')
    {
        return implode('-', ['challenge', $this->challenge->id, $this->user, $postfix]);
    }
}