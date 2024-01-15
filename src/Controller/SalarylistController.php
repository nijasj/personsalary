<?php

namespace Drupal\personsalary\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Controller for displaying salary entries.
 */
class SalaryListController extends ControllerBase {

  protected $database;
  protected $entityTypeManager;
  protected $currentUser;

  public function __construct(Connection $database, EntityTypeManagerInterface $entityTypeManager, AccountProxyInterface $currentUser) {
    $this->database = $database;
    $this->entityTypeManager = $entityTypeManager;
    $this->currentUser = $currentUser;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }

  public function content() {
    // Fetch data from the database
    $header = [
      'id' => $this->t('ID'),
      'name' => $this->t('Name'),
      'month' => $this->t('Month'),
      'year' => $this->t('Year'),
      'salary' => $this->t('Salary'),
    ];

    $query = $this->database->select('salary_entry', 'se')
      ->fields('se')
      
      ->orderByHeader($header)
      ->limit(50); // Adjust the limit as needed.

    $query->join('persons_module', 'pm', 'pm.id = se.pid'); // Adjust the join condition based on your database structure.

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