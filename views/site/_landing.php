<?php /* @var $this yii\web\View */ ?>

<div class="site-index">

    <div class="jumbotron">
        <h1>Приветствие!</h1>

        <p class="lead">Тут отличное место для не очень длинного слогана.</p>

        <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['user/register']) ?>">Регистрация</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Зачем всё это надо?</h2>

                <p>Немного продающего текста уместно написать тут.</p>
                <p>Убедите пользователя, что он хочет потратить своё драгоценное время.</p>
            </div>
            <div class="col-lg-4">
                <h2>Почему именно мы?</h2>

                <p>Немного саморекламы уместно написать.</p>
                <p>Расскажите пользователю почему он должен воспользоваться именно данным сервисом.</p>
            </div>
            <div class="col-lg-4">
                <h2>А дайте пощупать?</h2>

                <p>Вы можете пройти бесплатное тестирование прямо сейчас!</p>
                <p>Дайте пользователю попробовать то, за что он заплатит.</p>

                <p><a class="btn btn-default" href="<?= \yii\helpers\Url::to(['test/free']) ?>">Бесплатное тестирование</a></p>
            </div>
        </div>

    </div>
</div>
