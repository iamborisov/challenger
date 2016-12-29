<?php
/**
 * @var \app\helpers\ChallengeSummarizer $summary
 */

    $questions = $summary->getQuestions();
    $results = $summary->getCorrectness();
    $hints = $summary->getHints();
    $points = $summary->getPoints();
?>

<div class="panel panel-default">
    <div class="panel-heading" role="tab">
        <h4 class="panel-title">
            <?= $challenge->name ?>
        </h4>
    </div>
    <div role="tabpanel">
        <div class="panel-body">
            <p class="lead"><?= $challenge->description ?></p>

            <p>Оценка: <?= $summary->getMark() ? $summary->getMark() : 'не доступно' ?></p>
            <p>Время: <?= round($summary->getTime() / 60) ?> мин.</p>

            <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['start', 'id' => $challenge->id, 'confirm' => true]) ?>">Повторить тестирование</a></p>

        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="summaryHead">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" href="#summary" aria-expanded="true" aria-controls="summary">
                Подробные результаты
            </a>
        </h4>
    </div>
    <div id="summary" class="panel-collapse" role="tabpanel" aria-labelledby="summaryHead">
        <div class="panel-body">
            <table class="table table-condensed table-hover">
                <tr>
                    <th class="col-md-1">#</th>
                    <th class="col-md-7">Задание</th>
                    <th class="col-md-1 text-center">Подсказка</th>
                    <th class="col-md-2 text-center">Ответ</th>
                    <th class="col-md-1 text-center">Балл</th>
                </tr>
                <?php foreach( $summary->getQuestions() as $i => $question ): ?>
                    <tr>
                        <td class="text-left"><?= $i + 1 ?></td>
                        <td class="text-left"><?= $question->text ?></td>
                        <td class="<?= !$hints[$question->id] ? 'success' : 'danger' ?>"><?= !$hints[$question->id] ? 'Нет' : 'Да' ?></td>
                        <td class="<?= $results[$question->id] ? 'success' : 'danger' ?>"><?= $results[$question->id] ? 'Верно' : 'Ошибка' ?></td>
                        <td class=""><?= $points[$question->id] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>