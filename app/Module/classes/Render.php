<?php

namespace App\Module\classes;

use App\Module\classes\SomeLib;
use App\Module\classes\TableBuilder;
use Kris\LaravelFormBuilder\FormBuilder;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Render
 *
 * @author JERRYS
 */
class Render {

    protected $model;
    protected $form;
    protected $table;    
    protected $layout;
    protected $lib;

    public function __construct($model, $layout = "jrs.layout") {
        $this->model = $model;
        $this->layout = $layout;
        $this->lib = new SomeLib;
    }

    public function formWidget($rules = []) {
        $form_method = (!array_key_exists('form-method', $rules)) ? NULL : $rules['form-method'];
        $form_url = (!array_key_exists('form-url', $rules)) ? NULL : $rules['form-url'];
        $form_model = (!array_key_exists('form-model', $rules)) ? NULL : $rules['form-model'];

        $this->form = \FormBuilder::plain([
                    'method' => $form_method,
                    'url' => $form_url,
                    'model' => $form_model,
                    'enctype' => "multipart/form-data",
                    'class' => ""
        ]);
        $this->lib->initiateForm($this->form, $this->model->attributes());
        $this->lib->clearOrModifiedForm($this->form, $rules);

        return $this;
    }

    public function newTableWidget($rules) {
        $this->table = $rules;

        $this->table['body'] = '';

        if (array_key_exists('paginate', $this->table)) {

            foreach ($this->table['query']->paginate($this->table['paginate']) as $key => $value) {

                $this->table['body'] .= '<tr>';

                foreach ($this->table['show'] as $key2 => $value2) {

                    $this->table['body'] .= '<td> <center> ';

                    if ($value2[0] == TRUE) {

                        if (is_object($value->$value2[1])) {

                            if (array_key_exists(3, $value2) && $value2[3] == TRUE) {

                                $this->table['body'] .= "<ul>";

                                foreach ($value->$value2[1] as $key3 => $value3) {

                                    if (!array_key_exists($value2[2], $this->table['image'])) {

                                        $this->table['body'] .= "<li>" . $value3->$value2[2] . "</li>";
                                    } else {

                                        $this->table['body'] .= "<li>"
                                                . "<img "
                                                . "src='" . asset($value3->$value2[2]) . "' "
                                                . "width='75px' "
                                                . "height='105px' />"
                                                . "</li>"
                                                . "<br/>";
                                    }
                                }

                                $this->table['body'] .= "</ul>";
                            } else {

                                if (!array_key_exists($value2[2], $this->table['image'])) {

                                    $this->table['body'] .= $value->$value2[1]->$value2[2];
                                } else {

                                    $this->table['body'] .= "<img "
                                            . "src='" . asset($value->$value2[1]->$value2[2]) . "' "
                                            . "width='75px' height='105px' />";
                                }
                            }
                        } else {

                            if (!array_key_exists($value2[1], $this->table['image'])) {

                                $this->table['body'] .= $value->$value2[1];
                            } else {

                                $this->table['body'] .= "<center>"
                                        . "<img "
                                        . "src='" . asset($value->$value2[1]) . "' "
                                        . "width='75px' height='105px' />"
                                        . "</center>";
                            }
                        }
                    } else {

                        $this->table['body'] .= $value2[1];
                    }

                    $this->table['body'] .= "</center></td>";
                }

                if (array_key_exists('update', $this->table)) {

                    $param = $this->table['update'][1];

                    $this->table['body'] .=
                            "<td><center>"
                            . "<a "
                            . "href='" . url($this->table['update'][0]) . "/" . $value->$param . "' "
                            . "class='btn btn-info btn-xs'>"
                            . "<i class='fa fa-pencil'></i></a>"
                            . "</center></td>";
                }

                if (array_key_exists('delete', $this->table)) {

                    $param = $this->table['delete'][1];

                    $this->table['body'] .=
                            "<td><center>"
                            . "<a "
                            . "href='" . url($this->table['delete'][0]) . "/" . $value->$param . "' "
                            . "class = 'btn btn-danger btn-xs' "
                            . "onclick = \"return confirm('Are you sure you want to continue')\">"
                            . "<i class='fa fa-remove'></i></a>"
                            . "</center></td>";
                }

                $this->table['body'] .= '</tr>';
            }
        }

        $this->table = (string) view('jrs.table.new-table', ['table' => $this->table]);

        return $this;
    }    

    public function compileWidget() {
        $form = $this->form;

        $table = $this->table;                

        return view($this->layout, compact(['form', 'table']));
    }

    /*
     * OLD
     */

    public function tableWidget($collections = [], $rules = []) {
        $head = [];
        $fields = [];
        $create = '';

        if (!empty($collections)) {
            foreach ($collections as $key => $value) {
                if (array_key_exists('update', $rules)) {
                    $url = (array_key_exists('url', $rules['update'])) ? $rules['update']['url'] : NULL;
                    $pk = (array_key_exists('pk', $rules['update'])) ? $rules['update']['pk'] : NULL;
                    $class = (array_key_exists('class', $rules['update'])) ? $rules['update']['class'] : NULL;
                    array_set($collections, $key . ".update", "<a href='" . url($url . '/' . $value[$pk]) . "' class='" . $class . "'>Update</a>");
                }

                if (array_key_exists('delete', $rules)) {
                    $url = (array_key_exists('url', $rules['delete'])) ? $rules['delete']['url'] : NULL;
                    $pk = (array_key_exists('pk', $rules['delete'])) ? $rules['delete']['pk'] : NULL;
                    $class = (array_key_exists('class', $rules['delete'])) ? $rules['delete']['class'] : NULL;
                    array_set($collections, $key . ".delete", "<a href='" . url($url . '/' . $value[$pk]) . "' class='" . $class . "'>Delete</a>");
                }
            }

            $fields = array_keys($collections[0]);
            $head = $this->lib->beautyWord($fields);
        }

        if (array_key_exists('create', $rules)) {
            $url = (array_key_exists('url', $rules['create'])) ? $rules['create']['url'] : NULL;
            $pk = (array_key_exists('pk', $rules['create'])) ? $rules['create']['pk'] : NULL;
            $class = (array_key_exists('class', $rules['create'])) ? $rules['create']['class'] : NULL;
            $create = "<a href='" . url($url) . "' class='" . $class . "'>Create</a>";
        }

        $this->table = [
            "create" => $create,
            "head" => $head,
            "body" => [$fields, $collections]
        ];

        return $this;
    }

}
