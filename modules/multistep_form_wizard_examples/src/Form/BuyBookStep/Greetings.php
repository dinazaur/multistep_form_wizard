<?php

namespace Drupal\multistep_form_wizard_examples\Form\BuyBookStep;

use Drupal\Core\Form\FormStateInterface;

/**
 * Greetings book step.
 */
class Greetings extends BaseStep {

  /**
   * {@inheritDoc}
   */
  protected function getTitle(): string {
    return $this->t('What book do you want to share?');
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['field_type'] = $this->getWidgetForm($this->wizard->getBook()->get('field_type'), $form, $form_state);
    return $form;
  }

  /**
   * {@inheritDoc}
   *
   * You don't need to use submitForm all the time, each action has its
   * own handler.
   */
  public function nextAction(array &$form, FormStateInterface $form_state) {
    $this->extractFormValues($this->wizard->getBook()->get('field_type'), $form, $form_state);

    // Move to next step.
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
