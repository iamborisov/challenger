<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\LoginForm;
use app\models\search\CourseSearch;
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
        $searchModel = new CourseSearch();
        $dataProvider = $searchModel->searchActive(
            Yii::$app->user->id,
            Yii::$app->request->queryParams
        );

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionSubscriptions()
    {
        $searchModel = new CourseSearch();
        $dataProvider = $searchModel->searchSubscribed(
            Yii::$app->user->id,
            Yii::$app->request->queryParams
        );

        return $this->render('subscriptions', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionCourses()
    {
        $searchModel = new CourseSearch();
        $dataProvider = $searchModel->searchAvailable(
            Yii::$app->user->id,
            Yii::$app->request->queryParams
        );

        return $this->render('courses', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
