<?php

namespace Drupal\multistep_form_wizard\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\multistep_form_wizard\Wizard\WizardInterface;
use Drupal\multistep_form_wizard\WizardPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides basic object for wizard forms.
 */
abstract class MultistepForm extends FormBase {

  /**
   * Wizard.
   *
   * @var \Drupal\multistep_form_wizard\Wizard\WizardInterface
   */
  protected $wizard;

  /**
   * Wizard manager.
   *
   * @var \Drupal\multistep_form_wizard\WizardPluginManager
   */
  protected $wizardManager;

  /**
   * Contracts new MultistepForm.
   */
  public function __construct(WizardPluginManager $wizard_manager) {
    $this->wizardManager = $wizard_manager;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('plugin.manager.multistep_wizard'));
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#id'] = Html::cleanCssIdentifier($this->getFormId());
    if (!$this->wizard) {
      $this->wizard = $this->prepareWizard($form, $form_state);
    }

    return $this->wizard->buildForm($form, $form_state);
  }

  /**
   * Prepare wizard.
   */
  protected function prepareWizard(array $form, FormStateInterface $form_state): WizardInterface {
    $wizard = $this->wizardManager->createInstance($this->getFormId(), ['form_state' => $form_state]);
    assert($wizard instanceof WizardInterface);
    return $wizard;
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $this->wizard->validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->wizard->submitForm($form, $form_state);
  }

  /**
   * Provides ability to use ::callback for the step objects.
   *
   * @throws \Exception
   */
  public function __call($name, $arguments) {
    $step_instance = $this->wizard->getCurrentStepInstance();

    if (method_exists($step_instance, $name)) {
      // Make sure wizard's form state is up-to-date.
      if (isset($arguments[1]) && $arguments[1] instanceof FormStateInterface) {
        $this->wizard->setFormState($arguments[1]);
      }
      return call_user_func_array([$step_instance, $name], $arguments);
    }

    throw new \Exception("Unknown method/function '$name'.");
  }

}
