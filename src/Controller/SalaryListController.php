<?php

namespace Drupal\personsalary\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Controller for displaying the salary list.
 */
class SalaryListController extends ControllerBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new SalaryListController object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   */
  public function __construct(Connection $database, EntityTypeManagerInterface $entityTypeManager) {
    $this->database = $database;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Display the salary list.
   */
  public function salaryList() {
    $header = [
      'id' => $this->t('ID'),
      'name' => $this->t('Name'),
      'month' => $this->t('Month'),
      'year' => $this->t('Year'),
      'salary' => $this->t('Salary'),
    ];

    $query = $this->database->select('person_salary', 'ps')
      ->fields('ps')
      ->extend('TableSort')
      ->orderByHeader($header)
      ->limit(50); // Adjust the limit as needed.

    $query->join('person_field_data', 'p', 'p.id = ps.id'); // Adjust the join condition based on your database structure.

    $result = $query->execute();

    foreach ($result as $row) {
      $person = $this->entityTypeManager->getStorage('person')->load($row->id);
      $rows[] = [
        'id' => $row->id,
        'name' => $person->label(), // Adjust this based on the actual field name in the Person entity.
        'month' => $row->Month,
        'year' => $row->Year,
        'salary' => $row->Salary,
      ];
    }

    $build = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No salary entries found'),
    ];

    return $build;
  }

}
