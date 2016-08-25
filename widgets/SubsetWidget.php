<?php

namespace app\widgets;

use yii\base\Exception;
use yii\widgets\InputWidget;

class SubsetWidget extends InputWidget {

    public $template = 'subset/default';

    public $header = 'subset/header';

    public $item = 'subset/item';

    public $form;

    public $child;

    public $add = true;

    public $fields = [];

    public function run()
    {
        $this->prepareFields();

        echo $this->render( $this->template, [
            'model' => new $this->child,
            'header' => $this->renderHeader(),
            'rows' => $this->renderRows(),
            'empty' => $this->render( $this->item, [
                'model' => new $this->child,
                'fields' => $this->fields,
                'form' => $this->form,
                'add' => $this->add,
            ] ),
            'add' => $this->add,
            'form' => $this->form
        ] );
    }

    private function prepareFields() {
        $attributes = (new $this->child)->attributeLabels();

        if ( !count( $this->fields ) ) {
            $this->fields = array_keys( $attributes );
        }

        $fields = [];
        foreach ( $this->fields as $name => $params ) {
            if ( is_numeric($name) && is_string($params) ) {
                $fields[$params] = [
                    'widget' => 'textInput',
                    'params' => [],
                    'title' => $attributes[$params]
                ];
            } elseif ( is_string($name) && is_string($params) ) {
                $fields[$name] = [
                    'widget' => $params,
                    'params' => [],
                    'title' => $attributes[$name]
                ];
            } elseif ( is_string($name) && is_array($params) && count($params) ) {
                $fields[$name] = [
                    'widget' => $params[0],
                    'params' => count($params) > 1 ? $params[1] : [],
                    'title' => $attributes[$name]
                ];
            } else {
                throw new Exception( 'Wrong fields config in SubsetWidget' );
            }
        }

        $this->fields = $fields;
    }

    private function renderRows() {
        $data = $this->model->{'get' . ucfirst($this->attribute)}()->all();

        $rows = [];
        foreach ( $data as $subitem ) {
            $rows[] = $this->render( $this->item, [
                'model' => $subitem,
                'fields' => $this->fields,
                'form' => $this->form,
                'add' => $this->add,
            ] );
        }

        return $rows;
    }

    private function renderHeader() {
        return $this->render( $this->header, [
            'model' => new $this->child,
            'fields' => $this->fields,
            'form' => $this->form,
            'add' => $this->add,
        ] );
    }

}