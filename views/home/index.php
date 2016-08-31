<?php
    use yii\helpers\Html;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        Моя страница
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