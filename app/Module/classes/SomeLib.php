<?php

namespace App\Module\classes;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SomeLib {

    public function arrayModified($the_array, $rules = []) {
        
        if (!empty($rules)) {
            
            if (array_key_exists('except', $rules)) {
                
                $the_array = array_except($the_array, $rules['except']);
            }

            if (array_key_exists('only', $rules)) {
                
                $the_array = array_only($the_array, $rules['only']);
            }

            if (array_key_exists('merge', $rules)) {
                
                $the_array = array_merge($the_array, $rules['merge']);
            }

            if (array_key_exists('modify', $rules)) {
                
                foreach ($rules['modify'] as $key => $value) {
                    
                    $the_array[$key] = $value;
                }
            }
        }

        return $the_array;
    }

    public function clearOrModifiedForm($the_form, $rules = []) {
        
        if (array_key_exists('form-clear', $rules)) {
            
            foreach ($rules['form-clear'] as $key => $value) {
                
                $the_form->remove($value);
            }
        }

        if (array_key_exists('form-modify', $rules)) {
            
            foreach ($rules['form-modify'] as $key => $value) {
                
                if (!array_key_exists(2, $value)) {
                    
                    $value[2] = [];
                }

                if (!array_key_exists(3, $value)) {
                    
                    $value[3] = false;
                }

                $the_form->modify($value[0], $value[1], $value[2], $value[3]);
            }
        }
    }

    public function initiateForm($the_form, $attributes = []) {
        
        foreach ($attributes as $key => $value) {
            
            if (!array_key_exists(2, $value)) {
                
                $value[2] = [];
            }

            if (!array_key_exists(3, $value)) {
                
                $value[3] = false;
            }

            $the_form->add($value[0], $value[1], $value[2], $value[3]);
        }
    }

    public function beautyWord($words = []) {
        
        foreach ($words as $key => $value) {
            
            $words[$key] = trim(ucwords(str_replace(["_", "-", "."], " ", $value)));
        }
        
        return $words;
    }

    public function makeList($data) {
        
        $list = [];
        $data_display = $data['display'];

        foreach ($data['model'] as $key => $value) {
            
            $display = '';
            
            foreach ($data_display as $key1 => $value1) {
                
                if(!empty($value1)) {
                    
                    if ($value1[0] == '#') {
                        
                        $substr = substr($value1, 1);
                        $display .= $value->$substr;
                    } else {
                        
                        $display .= $value1;
                    }                    
                }else{
                    
                    $display .= $value1;
                }
            }

            $list[$value->$data['value']] = $display;
        }

        return $list;
    }

}
