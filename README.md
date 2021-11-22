# Multistep Form Wizard Module for Drupal 8

This module provides the ability to create multistep forms in convenient way.

## Architecture

The module provides Yaml plugin like *.links.task.yml, in that way it allows
for developers to choose whatever namespaces for their steps classes, that
increase maintainability.

## How to use:

The module itself does not provide any UI. You should create your forms programmatically.
If you want the UI driven modules you can check [Webform](https://www.drupal.org/project/webform) or [Form Step](https://www.drupal.org/project/forms_steps)

1. Create your form, and extend `\Drupal\multistep_form_wizard\Form\MultistepForm` with it.
  * The only required thing is to set form id `::getFormId`.
2. Add wizard yml file to your project MODULE_NAME.multistep_wizard.yml As a reference check out: </br> `multistep_form_wizard_examples/multistep_form_wizard_examples.multistep_wizard.yml`
3. Create your steps.

For development examples please check out multistep_form_wizard_examples module.
