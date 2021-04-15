<?php

namespace App\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class MultiCheckboxType extends FormField {

    protected function getTemplate()
    {
        // At first it tries to load config variable,
        // and if fails falls back to loading view
        // resources/views/fields/mulitcheckbox.blade.php
        return 'fields.multicheckbox';
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        //$options['modeldata'] = 'This is some data for view';
        return parent::render($options, $showLabel, $showField, $showError);
    }
}
