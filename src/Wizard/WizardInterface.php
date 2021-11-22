<?php

namespace Drupal\multistep_form_wizard\Wizard;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\multistep_form_wizard\Step\StepInterface;
use Drupal\multistep_form_wizard\Step\StepPackage;

/**
 * Provides wizard interface.
 */
interface WizardInterface extends ContainerFactoryPluginInterface {

  /**
   * Sets previous step.
   */
  public function setPreviousStep();

  /**
   * Sets next step.
   */
  public function setNextStep();

  /**
   * Gets current step package.
   */
  public function getCurrentStep(): StepPackage;

  /**
   * Validate handler.
   */
  public function validateForm(array &$form, FormStateInterface $form_state);

  /**
   * Checks if current step is first.
   */
  public function isFirstStep(): bool;

  /**
   * Sets form state.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   */
  public function setFormState(FormStateInterface $form_state);

  /**
   * Checks if current step is last.
   */
  public function isLastStep(): bool;

  /**
   * Submit handler.
   */
  public function submitForm(&$form, FormStateInterface $form_state);

  /**
   * Gets form state.
   */
  public function getFormState(): FormStateInterface;

  /**
   * Build step form.
   */
  public function buildForm($form, FormStateInterface $form_state): array;

  /**
   * Checks if ajax is enabled.
   *
   * @return bool
   *   True if yes, false otherwise.
   */
  public function isAjaxEnabled(): bool;

  /**
   * Get current step instance.
   *
   * @return \Drupal\multistep_form_wizard\Step\StepInterface
   *   Step.
   */
  public function getCurrentStepInstance(): StepInterface;

  /**
   * Sets current step by provided id.
   *
   * @param string $id
   *   Step id.
   */
  public function setCurrentStepById(string $id): void;

}
