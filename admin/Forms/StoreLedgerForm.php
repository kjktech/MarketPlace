<?php

namespace Modules\Panel\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\StoreLedger;

class StoreLegderForm extends Form
{
    public function buildForm()
    {
      $this->add('owner-email', 'email', [
          'attr' => ['class' => 'search-owner form-control', 'disabled' => 'disabled'],
          'label' => 'Owner',
          'rules' => ''
      ]);

      $this->add('owner_id', 'hidden', [
          'attr' => ['id' => 'owner_id'],
          'rules' => ''
      ]);

      $this->add('officer-email', 'email', [
          'attr' => ['class' => 'search-officer form-control'],
          'label' => 'Admin Officer',
          'rules' => ''
      ]);

      $this->add('officer_id', 'hidden', [
          'attr' => ['id' => 'officer_id'],
          'rules' => ''
      ]);

      $this->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);
    }
}
