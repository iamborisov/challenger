<?php

namespace app\widgets;

use yii;
use yii\web\AssetManager;
use yii\base\Widget;
use app\models\search\QuestionSearch;

class QuestionSelection extends Widget {

    public $id = false;

    public $pageSize = 10;

    public function run()
    {
        // questions
        $searchModel = new QuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = $this->pageSize;

        // render
        echo $this->render( 'questionSelection/default', [
            'id' => $this->id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ] );
    }

}