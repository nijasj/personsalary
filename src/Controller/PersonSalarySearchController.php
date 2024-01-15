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

    $query = $connection->select('salary_entry', 'se');
    $query->fields('se');
    $query->condition('pid', '%' . $connection->escapeLike($search) . '%', 'LIKE');
    $results = $query->execute()->fetchAll();

    if (!empty($results)) 
    {
      $header = [
        'pid' => $this->t('pid'),
        'month' => $this->t('month'),
        'year' => $this->t('year'),
        'salary' => $this->t('salary'),
      ];

      $rows = [];

      foreach ($results as $result) 
      {
        $rows[] = [
          'pid' => $result->pid,
          'month' => $result->month,
          'year' => $result->year,
          'salary' => $result->salary,
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
        '#markup' => $this->t('No matching salary found.'),
      ];
    }

    return $build;
  }
}