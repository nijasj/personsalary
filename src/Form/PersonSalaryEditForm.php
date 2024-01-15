<?php

namespace Drupal\personsalary\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class PersonSalaryEditForm extends FormBase 
{
    public function getFormId() 
    {
        return 'personsalary_edit_form';
    }
    

    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL)
     {
        if (\Drupal::currentUser()->hasPermission('edit salary entry')) 
        {
            if ($id === NULL) 
            {
                $id = $this->getCurrentId();
            }

            $entry = $this->loadSalaryEntry($id);

            if (!$entry)
            {
                $this->messenger()->addError($this->t('salary not found.'));
                $form_state->setRedirect('personsalary.search_form');
                return $form;
            }

            $form['pid'] = [
            '#type' => 'textfield',
            '#title' => $this->t('pid'),
            '#required' => TRUE,
            '#default_value' => $entry['pid'],
            ];

            $form['month'] = [
            '#type' => 'textfield',
            '#title' => $this->t('month'),
            '#required' => TRUE,
            '#default_value' => $entry['month'],
            ];

            $form['year'] = [
            '#type' => 'textfield',
            '#title' => $this->t('year'),
            '#required' => TRUE,
            '#default_value' => $entry['year'],
            ];

            $form['salary'] = [
            '#type' => 'textfield',
            '#title' => $this->t('salary'),
            '#default_value' => $entry['salary'],
            ];

            $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Update Address'),
            ];
        } 
        else 
        {
            \Drupal::messenger()->addError($this->t('You do not have permission to edit salary entries.'));
        }
        

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        $year = $form_state->getValue('year');

        // Check if the year is numeric and within a specific range (e.g., 1900 to current year)
        if (!is_numeric($year) || $year < 1900 || $year > date('Y')) {
          $form_state->setErrorByName('year', $this->t('Please enter a valid year between 1900 and the current year.'));
        }   
    }


    public function submitForm(array &$form, FormStateInterface $form_state) 
    {
        $id = $this->getCurrentId();
        $this->updateSalaryEntry($id, $form_state->getValues());
        $this->messenger()->addMessage($this->t('salary updated successfully.'));
        $form_state->setRedirect('personsalary.search_form');
    }


    private function getCurrentId() {
        $id = \Drupal::routeMatch()->getParameter('id');
        return $id;
    }

    private function loadSalaryEntry($id) {
        $connection = \Drupal::database();
        $query = $connection->select('salary_entry', 'se')
        ->fields('se')
        ->condition('id', $id)
        ->execute();
        return $query->fetchAssoc();
    }

    private function updatesalaryEntry($id, $values) {
        $connection = \Drupal::database();
        $connection->update('salary_entry')
        ->fields([
            'pid' => $values['pid'],
            'month' => $values['month'],
            'year' => $values['year'],
            'salary' => $values['salary'],
        ])
        ->condition('id', $id)
        ->execute();
    }
  
}