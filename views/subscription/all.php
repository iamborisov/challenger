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
        <?php if( !$dataProvider->getCount() ): ?>
            <p class="text-muted text-center">
                Для Вас нет ничего нового, заходите в следующий раз!
            </p>
            <p class="text-muted text-center">
                Или Вы можете <a href="<?= \yii\helpers\Url::to(['subscription/index']) ?>">перейти к уже имеющимся подпискам</a>.
            </p>
        <?php endif; ?>

        <?php foreach( $dataProvider->getModels() as $course ): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= $course->name ?>
                </div>
                <div class="panel-body">
                    <p><?= $course->description ?></p>

                    <div class="pull-left">
                        <a href="<?= \yii\helpers\Url::to(['subscription/view', 'id' => $course->id]) ?>" class="btn btn-primary">Перейти к курсу</a>
                    </div>

                    <div class="pull-right">
                        <a href="<?= \yii\helpers\Url::to(['subscription/subscribe', 'id' => $course->id]) ?>" class="btn btn-success">Подписаться</a>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>