<?php
    use yii\widgets\ActiveForm;
    use app\widgets\AnswerEditor;

    $currentQuestion = $session->getCurrentQuestionNumber();
    $totalQuestions = $challenge->getQuestionsCount();

    $question = $session->getCurrentQuestion();

/**
 * @var \app\models\Question $question
 * @var \app\models\Challenge $challenge
 * @var \app\helpers\ChallengeSession $session
 */
?>
<h1><?= $challenge->name ?></h1>
<div class="panel panel-default">
    <div class="panel-heading">
        Задание <?= $currentQuestion + 1 ?> из <?= $totalQuestions ?>
        <div class="pull-right" style="width: 30%;">
            <div class="progress">
                <?php if( $challenge->settings->immediate_result ): ?>
                    <?php foreach( \app\helpers\ChallengeSummarizer::fromSession( $session )->getCorrectness() as $correctness ): ?>
                        <div class="progress-bar progress-bar-<?= $correctness ? 'success' : 'danger' ?>" style="width: <?= floor( 100 / $totalQuestions ) ?>%"></div>
                    <?php endforeach;?>
                <?php else: ?>
                    <div class="progress-bar progress-bar-info" style="width: <?= floor( $currentQuestion / $totalQuestions * 100) ?>%"></div>
                <?php endif;?>
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
                <strong>Подсказка:</strong> <span></span>
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
    $(function() {

        function showHint(hint) {
            $('.hint-content span').text(hint);
            $('.hint-content').show();
            $('.hint-button').hide();
        }

        $('.hint-button').click( function() {
            $.get('<?= \yii\helpers\Url::to(['challenge/hint', 'id' => $challenge->id]) ?>', function(data) {
                showHint(data);
            });

            return false;
        } );

        <?php if( $session->isHintUsed() ): ?>
        showHint(<?= \yii\helpers\Json::encode( $session->hint() ) ?>);
        <?php endif;?>
    });
</script>