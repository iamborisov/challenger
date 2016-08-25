<?php

/* @var $this yii\web\View */

$this->title = \Yii::$app->name;
?>
<div class="site-index">

    <div class="jumbotron">
        <p class="lead">[Overall statistics placeholder]</p>
    </div>

    <div class="body-content">
        <hr>
        <div class="row">
            <div class="col-lg-4">
                <h2>Статистика</h2>

                <p>Просмотр разной статистики</p>

                <p>
                    <a class="btn btn-default disabled" href="<?= yii\helpers\Url::toRoute('admin/course/index') ?>">???</a>
                </p>
            </div>
            <div class="col-lg-4">
                <h2>Задания</h2>

                <p>Управление базой заданий</p>

                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/question/index') ?>">Список</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/question/create') ?>">Создать</a>
                </p>
            </div>
            <div class="col-lg-4">
                <h2>Тесты</h2>

                <p>Настройка параметров генерации тестов на основе базы заданий</p>

                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/challenge/index') ?>">Список</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/challenge/create') ?>">Создать</a>
                </p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-4">
                <h2>Пользователи</h2>

                <p>Управление пользователями, блокировка, редактирование профиля и т.п.</p>

                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('user/admin/index') ?>">Список</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('user/admin/create') ?>">Создать</a>
                </p>
            </div>
            <div class="col-lg-4">
                <h2>Словари</h2>

                <p>Управление служебными списками, используемыми при формировании заданий и тестов</p>

                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/course/index') ?>">Курсы</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/challengetype/index') ?>">Типы тестов</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/element/index') ?>">Элементы</a>
                </p>
            </div>
            <div class="col-lg-4">
                <h2>Система</h2>

                <p>Системные настройки</p>

                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('rbac/role/index') ?>">Роли</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('rbac/permission/index') ?>">Разрешения</a>
                </p>
            </div>
        </div>

    </div>
</div>
