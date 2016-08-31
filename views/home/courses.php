<?php
/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 *
 */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Доступные курсы
    </div>
    <div class="panel-body">
        <?php foreach( $dataProvider->getModels() as $course ): ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <p><?= $course->name ?></p>

                    <div class="pull-left">
                        <?= $course->description ?>
                    </div>

                    <div class="pull-right">
                        <a href="#" class="btn btn-default">Подписаться</a>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>