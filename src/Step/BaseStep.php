<?php

namespace Drupal\multistep_form_wizard\Step;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\multistep_form_wizard\Wizard\WizardInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base step implementation.
 */
abstract class BaseStep implements StepInterface {

  use DependencySerializationTrait;
  use StringTranslationTrait;

  /**
   * Wizard.
   *
   * @var \Drupal\multistep_form_wizard\Wizard\Wizard
   */
  protected $wizard;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static();
  }

  /**
   * {@inheritDoc}
   */
  public function setWizard(WizardInterface $wizard) {
    $this->wizard = $wizard;
  }

  /**
   * {@inheritDoc}
   */
  public function form(array $form, FormStateInterface $form_state): array {
    $form += $this->buildForm($form, $form_state);

    $actions = $this->getActions($form, $form_state);

    if (!empty($actions)) {
      $form['actions'] = $this->getActions($form, $form_state);
    }

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  abstract public function buildForm(array $form, FormStateInterface $form_state): array;

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritDoc}
   */
  public function getActions(array $form, FormStateInterface $form_state): array {
    $actions = [
      '#type' => 'actions',
      '#attributes' => ['class' => ['m-actions']],
    ];

    if (!$this->wizard->isFirstStep()) {
      $actions[self::BACK] = $this->getBackButton();
    }

    if (!$this->wizard->isLastStep()) {
      $actions[self::NEXT] = $this->getNextButton();
    }

    if ($this->wizard->isLastStep()) {
      $actions[self::SUBMIT] = $this->getSubmitButton();
    }

    if ($this->wizard->isAjaxEnabled()) {
      foreach (Element::children($actions) as $child) {
        $actions[$child]['#ajax']['callback'] = '::ajaxSubmit';
        if (isset($form['#id'])) {
          $actions[$child]['#ajax']['wrapper'] = $form['#id'];
          $actions[$child]['#ajax'] += $this->getDefaultAjaxOptions();
        }
      }
    }

    return $actions;
  }

  /**
   * Submit action.
   */
  public function submitAction(array &$form, FormStateInterface $form_state) {}

  /**
   * Back action.
   */
  public function backAction(array &$form, FormStateInterface $form_state) {
    $this->wizard->setPreviousStep();
    $form_state->setRebuild(TRUE);
  }

  /**
   * Next action.
   */
  public function nextAction(array &$form, FormStateInterface $form_state) {
    $this->wizard->setNextStep();
    $form_state->setRebuild(TRUE);
  }

  /**
   * Gets render array for the back button.
   *
   * @return array
   *   Render array.
   */
  protected function getNextButton(): array {
    return [
      '#type' => 'submit',
      '#value' => $this->t('Next'),
      '#attributes' => ['class' => ['next']],
      '#submit' => ['::nextAction'],
    ];
  }

  /**
   * Gets back button.
   *
   * @return array
   *   Render array.
   */
  protected function getBackButton(): array {
    return [
      '#type' => 'submit',
      '#value' => $this->t('Previous'),
      '#attributes' => ['class' => ['back']],
      // If that's the "back" action then by default validation is not needed.
      '#limit_validation_errors' => [],
      '#submit' => ['::backAction'],
    ];
  }

  /**
   * Gets submit button.
   *
   * @return array
   *   Render array.
   */
  protected function getSubmitButton(): array {
    return [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#attributes' => ['class' => ['submit']],
      '#submit' => ['::submitAction'],
    ];
  }

  /**
   * Default ajax options.
   */
  protected function getDefaultAjaxOptions() {
    return [
      'progress' => [
        'type' => 'throbber',
        'message' => '',
      ],
    ];
  }

  /**
   * Ajax handler.
   *
   * @return array|\Drupal\Core\Ajax\AjaxResponse
   *   Form.
   */
  public function ajaxSubmit(array $form) {
    return $form;
  }

}
