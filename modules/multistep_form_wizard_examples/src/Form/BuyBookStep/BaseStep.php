<?php

namespace Drupal\multistep_form_wizard_examples\Form\BuyBookStep;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\multistep_form_wizard\EntityWidgetsTrait;
use Drupal\multistep_form_wizard\Step\BaseStep as WizardBaseStep;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base step.
 */
abstract class BaseStep extends WizardBaseStep {

  use EntityWidgetsTrait;
  use MessengerTrait;

  /**
   * Wizard.
   *
   * @var \Drupal\multistep_form_wizard_examples\BookWizard
   */
  protected $wizard;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->setEntityTypeManager($container->get('entity_type.manager'));
    $instance->setMessenger($container->get('messenger'));
    return $instance;
  }

  /**
   * Gets step title.
   *
   * Provides ability to set title per step.
   */
  abstract protected function getTitle(): string;

  /**
   * {@inheritDoc}
   */
  public function form(array $form, FormStateInterface $form_state): array {
    // Adds step id as class for each step.
    $form['#attributes']['class'][] = Html::cleanCssIdentifier($this->wizard->getCurrentStep()->getId());

    // Per step title.
    $form['title'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $this->getTitle(),
    ];

    return parent::form($form, $form_state);
  }

}
