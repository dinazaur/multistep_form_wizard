<?php

namespace Drupal\multistep_form_wizard\Wizard;

use Drupal\Core\DependencyInjection\ClassResolverInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\multistep_form_wizard\Exception\MissingMandatoryParameterException;
use Drupal\multistep_form_wizard\Step\StepInterface;
use Drupal\multistep_form_wizard\Step\StepPackage;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Default class used for multistep_form plugins.
 */
class Wizard extends PluginBase implements WizardInterface {

  /**
   * Form state.
   *
   * @var \Drupal\Core\Form\FormStateInterface
   *   Form state.
   */
  protected $formState;

  /**
   * Steps.
   *
   * @var array
   */
  protected $steps;

  /**
   * Class resolver.
   *
   * @var \Drupal\Core\DependencyInjection\ClassResolverInterface
   */
  protected $classResolver;

  /**
   * Indicates if ajax enabled.
   *
   * @var bool
   */
  protected $isAjaxEnabled;

  /**
   * {@inheritDoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ClassResolverInterface $class_resolver) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    if (!isset($configuration['form_state']) || empty($this->pluginDefinition['steps'])) {
      throw new MissingMandatoryParameterException();
    }

    $steps = [];

    $this->formState = $configuration['form_state'];
    $this->isAjaxEnabled = (bool) $this->pluginDefinition['ajax'];

    foreach ($this->pluginDefinition['steps'] as $step_definitions) {
      $steps[] = StepPackage::createFromDefinition($step_definitions);
    }

    $this->formState->set('steps', $steps);
    $this->classResolver = $class_resolver;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('class_resolver'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm($form, FormStateInterface $form_state): array {
    $this->setFormState($form_state);
    return $this->getCurrentStepInstance()->form($form, $form_state);
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $this->setFormState($form_state);
    return $this->getCurrentStepInstance()->validateForm($form, $form_state);
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(&$form, FormStateInterface $form_state) {
    $this->setFormState($form_state);
    $this->getCurrentStepInstance()->submitForm($form, $form_state);
  }

  /**
   * Get current step instance.
   *
   * @return \Drupal\multistep_form_wizard\Step\StepInterface
   *   Step.
   */
  public function getCurrentStepInstance(): StepInterface {
    $step = $this->classResolver->getInstanceFromDefinition($this->getCurrentStep()->getClass());
    assert($step instanceof StepInterface);
    $step->setWizard($this);
    return $step;
  }

  /**
   * {@inheritDoc}
   */
  public function getCurrentStep(): StepPackage {
    return $this->getSteps()[$this->getCurrentStepOffset()];
  }

  /**
   * Gets current step offset.
   *
   * @return int
   *   Current step.
   */
  protected function getCurrentStepOffset(): int {
    // In case if step is not set in form step then take first item from steps.
    return $this->formState->get('step') ?? array_key_first($this->getSteps());
  }

  /**
   * Sets current step.
   *
   * @param int $step
   *   Step.
   */
  protected function setCurrentStep(int $step): void {
    $this->formState->set('step', $step);
  }

  /**
   * {@inheritDoc}
   */
  public function setCurrentStepById(string $id): void {
    // @todo Implement validation?
    foreach ($this->getSteps() as $offset => $step) {
      if ($step->getId() === $id) {
        $this->setCurrentStep($offset);
      }
    }
  }

  /**
   * Checks if current step is last.
   */
  public function isLastStep(): bool {
    return $this->getCurrentStepOffset() === array_key_last($this->getSteps());
  }

  /**
   * Checks if current step is first.
   */
  public function isFirstStep(): bool {
    return $this->getCurrentStepOffset() === array_key_first($this->getSteps());
  }

  /**
   * Gets form state.
   */
  public function getFormState(): FormStateInterface {
    return $this->formState;
  }

  /**
   * Sets form state.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   */
  public function setFormState(FormStateInterface $form_state) {
    $this->formState = $form_state;
  }

  /**
   * Sets next step.
   */
  public function setNextStep() {
    $current = $this->getCurrentStepOffset();

    if (isset($this->getSteps()[$current + 1])) {
      $this->setCurrentStep($current + 1);
    }
  }

  /**
   * Sets previous step.
   */
  public function setPreviousStep() {
    $current = $this->getCurrentStepOffset();

    if (isset($this->getSteps()[$current - 1])) {
      $this->setCurrentStep($current - 1);
    }
  }

  /**
   * Gets steps.
   *
   * @return \Drupal\multistep_form_wizard\Step\StepPackage[]
   *   Steps.
   */
  public function getSteps(): array {
    return $this->formState->get('steps');
  }

  /**
   * {@inheritDoc}
   */
  public function isAjaxEnabled(): bool {
    return $this->isAjaxEnabled;
  }

}
