<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\Course;
use app\models\LoginForm;
use Yii;
use yii\web\Controller;

/**
 * Class SiteController
 * @package app\controllers
 */
class HomeController extends Controller
{
    public $layout = 'home';

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // Authorized users only
            return !\Yii::$app->user->isGuest;
        }

        return false;
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $challenges = [];

        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $course) {
            $challenges = array_merge($challenges, $course->getNewChallenges(Yii::$app->user->id)->all());
        }

        return $this->render('index', [
            'challenges' => $challenges
        ]);
    }

}
