<?php
    use yii\widgets\ActiveForm;
    use app\widgets\AnswerEditor;

    $currentQuestion = $session->getCurrentQuestionNumber();
    $totalQuestions = $challenge->getQuestionsCount();

    $question = $session->getCurrentQuestion();
?>
<h1><?= $challenge->name ?></h1>
<div class="panel panel-default">
    <div class="panel-heading">
        Задание <?= $currentQuestion + 1 ?> из <?= $totalQuestions ?>
        <div class="pull-right" style="width: 30%;">
            <div class="progress">
                <div
                    class="progress-bar progress-bar-info progress-bar-striped"
                    role="progressbar"
                    aria-valuenow="<?= $currentQuestion + 1 ?>"
                    aria-valuemin="0"
                    aria-valuemax="<?= $totalQuestions - 1 ?>"
                    style="width: <?= floor( $currentQuestion / $totalQuestions * 100) ?>%"
                ></div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?= $question->text ?>

        <?php $form = ActiveForm::begin([
            'action' => ['challenge/answer', 'id' => $challenge->id],
            'method' => 'post'
        ]); ?>

            <?php echo AnswerEditor::widget([
                'name' => 'answer',
                'question' => $question
            ]) ?>

            <input type="submit">

        <?php ActiveForm::end(); ?>

    </div>
</div>