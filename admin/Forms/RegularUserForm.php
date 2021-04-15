<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;

class RegularUserForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => 'required|min:3'
        ]);
        $this->add('email', 'email', [
            'rules' => ''
        ]);
        /*
        $this->add('display_name', 'text', [
            'rules' => ''
        ]);
        */
        $this->add('phone', 'text', [
            'rules' => 'required'
        ]);
        /*$this->add('is_admin', 'checkbox', [
            'rules' => ''
        ]);
        $this->add('is_banned', 'checkbox', [
            'rules' => ''
        ]);

        $this->add('verified', 'checkbox', [
            'rules' => ''
        ]);

        $this->add('password_new', 'password', [
            'rules' => ''
        ]);

        $this->add('password_confirmation', 'password', [
            'rules' => ''
        ]);
        */
        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
