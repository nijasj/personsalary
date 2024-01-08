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
        if (\Drupal::currentUser()->hasPermission('edit personsalary entry')) 
        {
            if ($id === NULL) 
            {
                $id = $this->getCurrentId();
            }

            $entry = $this->loadPersonSalaryEntry($id);

            if (!$entry)
            {
                $this->messenger()->addError($this->t('person not found.'));
                $form_state->setRedirect('personsalary.search_form');
                return $form;
            }

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
            $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Update Address'),
            ];
        } 
        else 
        {
            \Drupal::messenger()->addError($this->t('You do not have permission to edit personsalary entries.'));
        }
        

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        $values = $form_state->getValues();
        
        $year = $form_state->getValue('Year');
    if (!is_numeric($year) || $year < 1900 || $year > date('Y')) {
      $form_state->setErrorByName('Year', $this->t('Please enter a valid year.'));
    }
    }


    public function submitForm(array &$form, FormStateInterface $form_state) 
    {
        $id = $this->getCurrentId();
        $this->updatePersonSalaryEntry($id, $form_state->getValues());
        $this->messenger()->addMessage($this->t('PersonSalary updated successfully.'));
        $form_state->setRedirect('personsalary.search_form');
    }


    private function getCurrentId() {
        $id = \Drupal::routeMatch()->getParameter('id');
        return $id;
    }

    private function loadPersonSalaryEntry($id) {
        $connection = \Drupal::database();
        $query = $connection->select('personsalary', 'ps')
        ->fields('ps')
        ->condition('id', $id)
        ->execute();
        return $query->fetchAssoc();
    }

    private function updatePersonSalaryEntry($id, $values) {
        $connection = \Drupal::database();
        $connection->update('personsalary')
        ->fields([
            'id' => $values['id'],
            'name' => $values['name'],
            'month' => $values['month'],
            'year' => $values['year'],
            'salary' => $values['salary'],
        ])
        ->condition('id', $id)
        ->execute();
    }
  
}