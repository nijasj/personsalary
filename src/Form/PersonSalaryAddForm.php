<?php

namespace Drupal\personsalary\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class PersonSalaryAddForm extends FormBase
{
    public function getFormId()
    {
        return 'personsalary_add_form';
    }
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        if (\Drupal::currentUser()->hasPermission('add salary entry')) 
        {
            $form['pid'] = [
                '#type' => 'textfield',
                '#title' => $this->t('pid'),
                '#required' => TRUE,
            ];
            $form['month'] = [
                '#type' => 'textfield',
                '#title' => $this->t('month'),
                '#required' => TRUE,
            ];
            $form['year'] = [
                '#type' => 'textfield',
                '#title' => $this->t('year'),
                '#required' => TRUE,
            ];
            $form['salary'] = [
                '#type' => 'textfield',
                '#title' => $this->t('salary'),
                '#required' => TRUE,
    
            ];
            $form['submit'] = [
                '#type' => 'submit',
                '#value' => $this->t('Add salary'),
            ];    
        } 
        else 
        {
            \Drupal::messenger()->addError($this->t('You do not have permission to add salary entries.'));
        }
        return $form;

    }
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $year = $form_state->getValue('year');

        // Check if the year is numeric and within a specific range (e.g., 1900 to current year)
        if (!is_numeric($year) || $year < 1900 || $year > date('Y')) {
          $form_state->setErrorByName('year', $this->t('Please enter a valid year between 1900 and the current year.'));
        }  
    }
    public function submitForm(array &$form , FormStateInterface $form_state)
    {
        $values = $form_state->getValues();

        $connection = \Drupal::database();
        $connection->insert('salary_entry')
        ->fields([
            'pid' => $values['pid'],
            'month' => $values['month'],
            'year' => $values['year'],
            'salary' => $values['salary'],
        ])->execute();
        \Drupal::messenger()->addMessage(t("salary added successfully"));
    }
}