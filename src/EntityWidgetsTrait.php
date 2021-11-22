<?php

namespace Drupal\multistep_form_wizard;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides helper methods to interact with form widgets.
 */
trait EntityWidgetsTrait {

  use DependencySerializationTrait;

  /**
   * Array of widgets.
   *
   * @var \Drupal\Core\Field\WidgetInterface[]
   */
  protected $widgets = [];

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Get widget for the field.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $field
   *   The field from which build widget.
   * @param string $form_view_mode
   *   Form view mode.
   *
   * @return \Drupal\Core\Field\WidgetInterface
   *   Widget.
   */
  protected function getWidget(FieldItemListInterface $field, string $form_view_mode = 'default') {
    $entity = $field->getEntity();
    $entity_type = $entity->getEntityTypeId();
    $entity_bundle = $entity->bundle();
    $definition = $field->getFieldDefinition();
    $identifier = $entity_type . $entity_bundle . $definition->getName();
    if (isset($this->widgets[$identifier])) {
      return $this->widgets[$identifier];
    }

    $displays = $this->getEntityTypeManager()
      ->getStorage('entity_form_display')
      ->loadByProperties([
        'bundle' => $entity_bundle,
        'mode' => $form_view_mode,
        'targetEntityType' => $entity_type,
      ]);

    /** @var \Drupal\Core\Entity\Entity\EntityFormDisplay $display */
    $display = current($displays);

    $this->widgets[$identifier] = $display->getRenderer($definition->getName());

    return $this->widgets[$identifier];
  }

  /**
   * Gets form for the widget.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $field
   *   Field.
   * @param array $form
   *   Form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   * @param string $form_view_mode
   *   Form operation.
   *
   * @return array
   *   Widget's form.
   */
  protected function getWidgetForm(FieldItemListInterface $field, array &$form, FormStateInterface $form_state, string $form_view_mode = 'default'): array {
    $form += ['#parents' => []];

    $widget = $this->getWidget($field, $form_view_mode);
    return $widget->form($field, $form, $form_state);
  }

  /**
   * Extract form values.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $field
   *   Field.
   * @param array $form
   *   Form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   * @param string $form_view_mode
   *   Form operation.
   */
  protected function extractFormValues(FieldItemListInterface $field, array $form, FormStateInterface $form_state, string $form_view_mode = 'default') {
    $widget = $this->getWidget($field, $form_view_mode);
    $widget->extractFormValues($field, $form, $form_state);
  }

  /**
   * Entity type manager.
   *
   * @return \Drupal\Core\Entity\EntityTypeManager
   *   Entity type manager.
   */
  public function getEntityTypeManager(): EntityTypeManager {
    if (isset($this->entityTypeManager)) {
      return $this->entityTypeManager;
    }

    $this->entityTypeManager = \Drupal::entityTypeManager();

    return $this->entityTypeManager;
  }

  /**
   * Set entity type manager.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   *   Config factory.
   */
  public function setEntityTypeManager(EntityTypeManager $entity_type_manager): void {
    $this->entityTypeManager = $entity_type_manager;
  }

}
