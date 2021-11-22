<?php

namespace Drupal\multistep_form_wizard_examples\Form\BuyBookStep;

use Drupal\Core\Form\FormStateInterface;

/**
 * Congratulation book step.
 */
class Congratulation extends BaseStep {

  /**
   * {@inheritDoc}
   */
  protected function getTitle(): string {
    return $this->t('Congratulation!');
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['description'] = [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => $this->t('Go back to our site! Thank you.'),
    ];
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function getActions(array $form, FormStateInterface $form_state): array {
    $actions = parent::getActions($form, $form_state);
    // Remove back button.
    unset($actions[self::BACK]);
    return $actions;
  }

  /**
   * {@inheritDoc}
   */
  public function submitAction(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('<front>');
  }

  /**
   * {@inheritDoc}
   */
  protected function getSubmitButton(): array {
    $button = parent::getSubmitButton();
    $button['#value'] = $this->t("Go back to site.");
    return $button;
  }

}
