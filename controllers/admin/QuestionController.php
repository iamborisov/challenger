<?php

namespace app\controllers\admin;

use app\components\BaseAdminCrudController;
use app\models\Question;
use app\models\search\QuestionSearch;
use Yii;

/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends BaseAdminCrudController
{
    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return Question::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return QuestionSearch::className();
    }

}
