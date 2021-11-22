<?php

namespace Drupal\multistep_form_wizard\Step;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;

/**
 * Step package wrapper.
 */
class StepPackage {

  use DependencySerializationTrait;

  /**
   * Step class.
   *
   * @var string
   */
  private $class;

  /**
   * Step id.
   *
   * @var string
   */
  private $id;

  /**
   * Constructs StepPackage.
   */
  public function __construct(string $class, string $id) {
    $this->class = $class;
    $this->id = $id;
  }

  /**
   * Create form definitions.
   */
  public static function createFromDefinition(array $definitions) {
    return new static($definitions['class'], $definitions['id']);
  }

  /**
   * Gets class.
   *
   * @return string
   *   Class.
   */
  public function getClass(): string {
    return $this->class;
  }

  /**
   * Sets class.
   *
   * @param string $class
   *   Class.
   */
  public function setClass(string $class): void {
    $this->class = $class;
  }

  /**
   * Gets id.
   *
   * @return string
   *   Get id.
   */
  public function getId(): string {
    return $this->id;
  }

  /**
   * Sets id.
   *
   * @param string $id
   *   Set id.
   */
  public function setId(string $id): void {
    $this->id = $id;
  }

}
