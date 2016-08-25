<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $profile
 */

$this->title = Yii::t('home', 'Home');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('@dektrium/user/views/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('@dektrium/user/views/settings/_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="panel-body">
                <?php for( $i =0 ; $i < 3; $i++ ): ?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p>Какой-то тест</p>

                            <div class="progress">
                                <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                    <span class="sr-only">20% Complete</span>
                                </div>
                            </div>

                            <div class="pull-left">
                                Какая-то информация
                            </div>

                            <div class="pull-right">
                                <a href="#" class="btn btn-default">Повторить</a>
                            </div>
                        </div>
                    </div>


                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>
