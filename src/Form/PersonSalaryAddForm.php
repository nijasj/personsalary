<?php

namespace Drupal\personsalary\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form for inserting, updating, and deleting person salaries.
 */
class PersonSalaryForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'person_salary_form';
  }

  /**
   * {@inheritdoc}
   */
  
public function buildForm(array $form, FormStateInterface $form_state) {
   
    $form['name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name'),
        '#required' => TRUE,
        '#default_value' => $entry['name'],
    ];
    
    $form['Month'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Month'),
      '#required' => TRUE,
    ];
  
    $form['Year'] = [
      '#type' => 'number',
      '#title' => $this->t('Year'),
      '#required' => TRUE,
    ];
  
    $form['Salary'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Salary'),
      '#required' => TRUE,
    ];
  
    // Add more form elements as needed.
  
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
  
    return $form;
  }
  

 

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate form elements.
    // For example, check if the entered year is valid.
    $values = $form_state->getValues();
        
    $year = $form_state->getValue('Year');
    if (!is_numeric($year) || $year < 1900 || $year > date('Y')) {
      $form_state->setErrorByName('Year', $this->t('Please enter a valid year.'));
    }
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Check if the user has the 'administer salary' permission.
    if (\Drupal::currentUser()->hasPermission('administer salary')) {
      // Process the form submission for users with the appropriate permission.
      // Perform insert, update, or delete operations on the person_salary table.
      // Use $form_state->getValue('Month'), $form_state->getValue('Year'), etc., to get form values.
      // ...
    }
    else {
      // User doesn't have permission, display an error message.
      drupal_set_message($this->t('You do not have permission to administer person salaries.'), 'error');
    }
  }
}
