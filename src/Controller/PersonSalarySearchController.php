<?php

/**
 * @file
 * Contains \Drupal\personsalary\Controller\PersonSalarySearchController
 */

namespace Drupal\personsalary\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

class PersonSalarySearchController extends ControllerBase 
{
  
  public function searchResults(Request $request, $search)
  {
    $connection = \Drupal::database();

    $query = $connection->select('personsalary', 'ps');
    $query->fields('ps');
    $query->condition('name', '%' . $connection->escapeLike($search) . '%', 'LIKE');
    $results = $query->execute()->fetchAll();

    if (!empty($results)) 
    {
      $header = [
        'id' => $this->t('id'),
        'name' => $this->t('Name'),
        'month' => $this->t('month'),
        'year' => $this->t('year'),
        'salary' => $this->t('salary'),
      ];

      $rows = [];

      foreach ($results as $result) 
      {
        $rows[] = [
          'name' => $result->name,
          'email' => $result->email,
          'phone' => $result->phone,
          'dob' => $result->dob,
        ];
      }

      $build['search_results']['table'] = [
        '#theme' => 'table',
        '#header' => $header,
        '#rows' => $rows,
      ];
    } 
    else 
    {
      $build['search_results']['no_results'] = [
        '#markup' => $this->t('No matching person found.'),
      ];
    }

    return $build;
  }
}