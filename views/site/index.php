<?php

/* @var $this yii\web\View */

$this->title = \Yii::$app->name;

echo $this->render( Yii::$app->user->isGuest ? '_landing' : '_home' );
