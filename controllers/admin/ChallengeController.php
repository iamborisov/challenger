<?php

namespace app\controllers\admin;

use app\helpers\QuestionChooser;
use app\helpers\Subset;
use app\models\ChallengeGeneration;
use app\models\ChallengeHasQuestion;
use app\models\ChallengeMark;
use app\models\ChallengeSettings;
use app\models\Question;
use Yii;
use app\models\Challenge;
use app\models\search\ChallengeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChallengeController implements the CRUD actions for Challenge model.
 */
class ChallengeController extends Controller
{
    public function beforeAction($action)
    {
        if ( parent::beforeAction($action) ) {
            if ( \Yii::$app->user->can('admin') ) {
                return true;
            } else {
                throw new ForbiddenHttpException('Access denied');
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Challenge models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ChallengeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Challenge model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Challenge model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Challenge();
        $modelSettings = $model->challengeSettings ? $model->challengeSettings : new ChallengeSettings();

        // ajax search
        if ( Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) ) {
            return $this->render('create', [
                'model' => $model
            ]);
        }

        // model saving
        if ($model->load(Yii::$app->request->post()) && $model->save() ) {
            $modelSettings->load(Yii::$app->request->post());
            $modelSettings->challenge_id = $model->id;
            $modelSettings->save();

            Subset::save(
                ChallengeMark::className(),
                Yii::$app->request->post(),
                ['challenge_id' => $model->id]
            );

            $model->setMode( Yii::$app->request->post('mode'), Yii::$app->request->post() );

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'modelSettings' => $modelSettings,
            ]);
        }
    }

    /**
     * Updates an existing Challenge model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelSettings = $model->challengeSettings ? $model->challengeSettings : new ChallengeSettings();

        // ajax search
        if ( Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) ) {
            return $this->render('update', [
                'model' => $model
            ]);
        }

        // model saving
        if ($model->load(Yii::$app->request->post()) && $model->save() ) {
            $modelSettings->load(Yii::$app->request->post());
            $modelSettings->challenge_id = $model->id;
            $modelSettings->save();

            Subset::save(
                ChallengeMark::className(),
                Yii::$app->request->post(),
                ['challenge_id' => $model->id]
            );

            $model->setMode( Yii::$app->request->post('mode'), Yii::$app->request->post() );

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'modelSettings' => $modelSettings,
            ]);
        }
    }

    /**
     * Deletes an existing Challenge model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionGenerate() {
        $generator = new QuestionChooser();

        foreach ( Yii::$app->request->post('rules') as $rule ) {
            $generator->addRule( $rule['type'], $rule['count'] );
        }

        $questions = Question::find()->where(['id' => $generator->generate()])->all();

        $result = [];
        foreach ( $questions as $question ) {
            $result[ $question->id ] = $question->text;
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $result;
    }

    /**
     * Finds the Challenge model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Challenge the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Challenge::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
