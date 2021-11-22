<?php

namespace Drupal\multistep_form_wizard;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Plugin\Discovery\YamlDiscovery;
use Drupal\Core\Plugin\Factory\ContainerFactory;
use Drupal\multistep_form_wizard\Wizard\Wizard;

/**
 * Defines a plugin manager to deal with multistep_form.
 *
 * Modules can define multistep_form in a MODULE_NAME.multistep_wizard.yml file
 * contained in the module's base directory. Each multistep_wizard has the
 * following structure:
 *
 * @code
 *   MACHINE_NAME:
 *     steps:
 *       -
 *         id: start
 *         class: \Some\Awesome\Form\Step\Start
 *       -
 *         id: middle
 *         class: \Some\Awesome\Form\Step\Middle
 *      ajax: TRUE
 * @endcode
 *
 * @see \Drupal\multistep_form_wizard\Wizard\Wizard
 * @see plugin_api
 */
class WizardPluginManager extends DefaultPluginManager {

  /**
   * {@inheritdoc}
   */
  protected $defaults = [
    // The multistep_form id. Set by the plugin system based on the top-level
    // YAML key.
    'id' => '',
    // Default plugin class.
    'class' => Wizard::class,
    // Form steps
    // The array value should have id and class of the step.
    'steps' => [],
    // If true form ajax will be enabled.
    'ajax' => FALSE,
  ];

  /**
   * Constructs OStepPluginManager object.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   */
  public function __construct(ModuleHandlerInterface $module_handler, CacheBackendInterface $cache_backend) {
    $this->factory = new ContainerFactory($this);
    $this->moduleHandler = $module_handler;
    $this->alterInfo('multistep_wizard_info');
    $this->setCacheBackend($cache_backend, 'multistep_wizard_plugins');
  }

  /**
   * {@inheritdoc}
   */
  protected function getDiscovery() {
    if (!isset($this->discovery)) {
      $this->discovery = new YamlDiscovery('multistep_wizard', $this->moduleHandler->getModuleDirectories());
    }
    return $this->discovery;
  }

}
