<?php

namespace Drupal\personsalary\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Render\Element\Table;

class PersonSalarySearchForm extends FormBase
 {

  public function getFormId() 
  {
    return 'personsalary_search_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) 
  {
    $form['search'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search by pid'),
      '#default_value' => $form_state->getValue('search'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) 
  {
    $search = $form_state->getValue('search');
    $form_state->setRedirect('personsalary.search_controller', ['search' => $search]);
  }



}