<?php

namespace Drupal\multistep_form_wizard\Step;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\multistep_form_wizard\Wizard\WizardInterface;

/**
 * Provides interface for the step form.
 */
interface StepInterface extends ContainerInjectionInterface {

  /**
   * Default actions.
   */
  public const NEXT = 'next';
  public const BACK = 'back';
  public const SUBMIT = 'submit';

  /**
   * Sets wizard.
   *
   * @param \Drupal\multistep_form_wizard\Wizard\WizardInterface $wizard
   *   Wizard.
   */
  public function setWizard(WizardInterface $wizard);

  /**
   * Gets actual form.
   */
  public function form(array $form, FormStateInterface $form_state): array;

  /**
   * Builds the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state): array;

  /**
   * Validates the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state);

  /**
   * Submit handler.
   */
  public function submitForm(array &$form, FormStateInterface $form_state);

  /**
   * Gets form actions.
   */
  public function getActions(array $form, FormStateInterface $form_state): array;

}
