<?php
    use yii\widgets\ActiveForm;
    use app\widgets\AnswerEditor;
    use kartik\markdown\Markdown;

    $currentQuestion = $session->getCurrentQuestionNumber();
    $totalQuestions = $challenge->getQuestionsCount();

    $question = $session->getCurrentQuestion();

    $summary = \app\helpers\ChallengeSummarizer::fromSession( $session );

/**
 * @var \app\helpers\ChallengeSummarizer $summary
 * @var \app\models\Question $question
 * @var \app\models\Challenge $challenge
 * @var \app\helpers\ChallengeSession $session
 */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= $challenge->name ?>
        <div class="pull-right text-right" style="width: 30%;">
            Задание <?= $currentQuestion + 1 ?> из <?= $totalQuestions ?>
        </div>
        <div class="progress">
            <?php if( $challenge->settings->immediate_result ): ?>
                <?php $comments = $summary->getComments(); ?>
                <?php foreach( $summary->getCorrectness() as $id => $correctness ): ?>
                    <div
                        class="progress-bar progress-bar-<?= $correctness ? 'success' : 'danger' ?>"
                        style="width: <?= floor( 100 / $totalQuestions ) ?>%"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        data-html="true"
                        title="<?= htmlspecialchars( $comments[$id] ) ?>"
                    ></div>
                <?php endforeach;?>
            <?php else: ?>
                <div class="progress-bar progress-bar-info" style="width: <?= floor( $currentQuestion / $totalQuestions * 100) ?>%"></div>
            <?php endif;?>
        </div>
    </div>
    <div class="panel-body">
        <?= $question->getText() ?>

        <?php $form = ActiveForm::begin([
            'action' => ['challenge/answer', 'id' => $challenge->id],
            'method' => 'post'
        ]); ?>

            <?php echo AnswerEditor::widget([
                'name' => 'answer',
                'question' => $question
            ]) ?>

            <div class="hint-content alert alert-info" role="alert" style="display: none;">
                <strong>Подсказка:</strong><br /><span></span>
            </div>

        <div class="row question-buttons">
            <div class="col-xs-6 col-md-6 text-left">
                <input type="submit" class="btn btn-success" value="Ответить">
            </div>
            <div class="col-xs-6 col-md-6 text-right">
                <a href="#" class="btn btn-primary hint-button">Подсказать</a>
                <?php if( $session->getCurrentQuestionNumber() < $challenge->getQuestionsCount() - 1 ): ?>
                    <a href="<?= \yii\helpers\Url::toRoute(['challenge/skip', 'id' => $challenge->id]) ?>" class="btn btn-warning ">Пропустить</a>
                <?php endif; ?>
                <a href="<?= \yii\helpers\Url::toRoute(['challenge/finish', 'id' => $challenge->id]) ?>" class="btn btn-danger">Завершить</a>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<script>
    $(function() {

        function showHint(hint) {
            $('.hint-content span').html(hint);
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

        <?php if($challenge->settings->immediate_result ): ?>
        $('.progress-bar[data-toggle="tooltip"]').tooltip();
        <?php endif; ?>
    });
</script>