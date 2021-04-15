<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;

class UserForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => 'required|min:3'
        ]);
        $this->add('email', 'email', [
            'rules' => ''
        ]);
        $this->add('display_name', 'text', [
            'rules' => ''
        ]);
        /*$this->add('is_admin', 'checkbox', [
            'rules' => ''
        ]);*/
        $this->add('is_banned', 'checkbox', [
            'rules' => ''
        ]);

        $this->add('verified', 'checkbox', [
            'rules' => ''
        ]);

        if(auth()->check() && auth()->user()->hasRole('super-admin')) {
           //dd($this->model->getRoleNames()->last());
            $this->add('role', 'select', [
                'choices' => [null => 'Unassigned', 'super-admin' => 'Super Admin', 'admin' => 'Admin', 'moderator' => 'Moderator', 'editor' => 'Editor', 'member' => 'Member'],
                'selected' => function ($data) {
                    return $this->model->getRoleNames()->last();
                },
                'help_block' => [
                    'text' => "Note: Moderators can edit/disable listings & ban members, Editors can edit/publish listings",
                    'attr' => ['class' => 'help-block text-muted']
                ],
            ]);
        }

        $this->add('password_new', 'password', [
            'rules' => ''
        ]);

        $this->add('password_confirmation', 'password', [
            'rules' => ''
        ]);

        $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
