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

            <div class="hint-content alert alert-info" role="alert" style="display: none;">
                <strong>Подсказка:</strong> <?= $question->hint ?>
            </div>

        <div class="row">
            <div class="col-xs-6 col-md-6 text-left">
                <input type="submit" class="btn btn-success" value="Ответить">
            </div>
            <div class="col-xs-6 col-md-6 text-right">
                <a href="#" class="btn btn-primary hint-button">Подсказать</a>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<script>
    $(function(){
        $('.hint-button').click( function() {
            $('.hint-content').show();
            $(this).addClass('disabled');
            return false;
        } );
    });
</script>