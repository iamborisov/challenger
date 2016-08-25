<div class="jumbotron">
    <h1><?= $challenge->name ?></h1>

    <p class="lead"><?= $challenge->description ?></p>

    <p>Курс: <?= $challenge->course->name ?></p>
    <p>Тема: <?= $challenge->subject->name ?></p>
    <?php if( $challenge->challengeSettings->limit_time ): ?>
        <p>Ограничение времени: <?= round($challenge->challengeSettings->limit_time / 60) ?> мин</p>
    <?php endif; ?>
    <p>Количество заданий: <?= $challenge->getQuestionsCount() ?></p>

    <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['start', 'id' => $challenge->id, 'confirm' => true]) ?>">Начать тестирование</a></p>

    <?php var_dump( $session->getAnswers() ) ?>
</div>