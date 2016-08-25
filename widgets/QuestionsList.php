<?php

namespace app\widgets;

use yii;
use yii\web\AssetManager;
use yii\widgets\InputWidget;
use app\models\search\QuestionSearch;

class QuestionsList extends InputWidget {

    private $assetManager;

    public $id = false;

    public $name = false;

    public $modalSelector = '';

    public function run()
    {
        $questions = $this->model->{$this->attribute};

        $data = [];
        foreach ( $questions as $item ) {
            $data[$item->question->id] = $item->question->text;
        }

        // register assets
        $this->getView()->registerJsFile(
            $this->publishAsset('js/questions-list.js')
        );
        $this->getView()->registerCssFile(
            $this->publishAsset('css/questions-list.css')
        );

        // render
        echo $this->render( 'questionsList/default', [
            'id' => $this->id,
            'data' => $data,
            'name' => $this->name ? $this->name : \yii\helpers\Html::getInputName($this->model, $this->attribute),
            'modalSelector' => $this->modalSelector,
        ] );
    }

    public function getAssetsPath() {
        return $this->getViewPath() . DIRECTORY_SEPARATOR . 'questionsList' . DIRECTORY_SEPARATOR;
    }

    public function publishAsset( $src ) {
        $path = Yii::getAlias( $this->getAssetsPath() . $src );
        if ( ! $this->assetManager ) {
            $this->assetManager = new AssetManager();
        }
        $return = $this->assetManager->publish( $path );
        return $return[1];
    }

}