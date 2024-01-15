<?php

namespace Drupal\personsalary\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class PersonSalaryDeleteForm extends FormBase
{
    public function getFormId()
    {
        return 'personsalary_delete_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        if (\Drupal::currentUser()->hasPermission('delete salary entry')) 
        {
            $form['pid_delete'] = [
                '#type' => 'textfield',
                '#title' => $this->t('Person ID'),
                '#required' => TRUE,
            ];
            $form['month_delete'] = [
                '#type' => 'textfield',
                '#title' => $this->t('Month'),
                '#required' => TRUE,
            ];
            $form['year_delete'] = [
                '#type' => 'textfield',
                '#title' => $this->t('Year'),
                '#required' => TRUE,
            ];

            $form['submit_delete'] = [
                '#type' => 'submit',
                '#value' => $this->t('Delete Salary'),
            ];    
        } 
        else 
        {
            \Drupal::messenger()->addError($this->t('You do not have permission to delete salary entries.'));
        }
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        // You can add validation logic here if needed
    }

    public function submitForm(array &$form , FormStateInterface $form_state)
    {
        $values = $form_state->getValues();

        $connection = \Drupal::database();
        $query = $connection->delete('salary_entry')
            ->condition('pid', $values['pid_delete'])
            ->condition('month', $values['month_delete'])
            ->condition('year', $values['year_delete'])
            ->execute();

        if ($query) {
            \Drupal::messenger()->addMessage(t("Salary entry deleted successfully."));
        } else {
            \Drupal::messenger()->addError(t("Unable to delete salary entry."));
        }
    }
}
