<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\Loyalty;

class LoyaltyForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => 'required|min:3'
        ]);
        $this->add('value', 'text', [
            'rules' => 'required|min:3'
        ]);
        $this->add('operator', 'text', [
            'rules' => 'required|min:3'
        ]);

        $this->add('points', 'number', [
            'rules' => 'required|min:3'
        ]);

        $this->add('duration', 'datetime-local', [
           'attr' => [
           'class' => 'form-control datetimepicker',
           'data-format' => 'DD, dd MM yyyy - HH:iip',

         ],
            
        ]);

        $this->add('status', 'select', [
  			'label' => "Status",
  			'choices' => array(true => "Open", false => "Closed"),
  			'attr' => [
                  'class' => 'form-control',

              ],
              'rules' => '',
          ]);

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
