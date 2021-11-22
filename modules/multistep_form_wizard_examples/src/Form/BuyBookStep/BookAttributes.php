<?php

namespace Drupal\multistep_form_wizard_examples\Form\BuyBookStep;

use Drupal\Core\Form\FormStateInterface;

/**
 * Book attributes step.
 */
class BookAttributes extends BaseStep {

  /**
   * {@inheritDoc}
   */
  protected function getTitle(): string {
    return $this->t('Add book attributes');
  }

  /**
   * Fields to render.
   *
   * I know that I can use the
   * \Drupal\Core\Entity\Display\EntityFormDisplayInterface::buildForm
   * and create separate form view modes for each step. But that's only for
   * demonstration purposes, and for this we have
   * https://www.drupal.org/project/forms_steps.
   * Instead, this module provides a more flexible way of controlling what
   * should we show and how should each step behaves.
   *
   * @return string[]
   *   Fields to render.
   */
  protected function getFieldsToRender() {
    return [
      'field_price',
      'field_image',
      'field_is_new',
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $book = $this->wizard->getBook();

    foreach ($this->getFieldsToRender() as $field_name) {
      $form[$field_name] = $this->getWidgetForm($book->get($field_name), $form, $form_state);
    }

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function nextAction(array &$form, FormStateInterface $form_state) {
    $book = $this->wizard->getBook();
    foreach ($this->getFieldsToRender() as $field_name) {
      $this->extractFormValues($book->get($field_name), $form, $form_state);
    }

    parent::nextAction($form, $form_state);
  }

  /**
   * You can change any predefined action button structure in getters.
   */
  protected function getNextButton(): array {
    $button = parent::getNextButton();
    $button['#value'] = $this->t("Let's Go!");
    return $button;
  }

}
