<?php

namespace Drupal\multistep_form_wizard_examples\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\multistep_form_wizard\Form\MultistepForm;
use Drupal\multistep_form_wizard\Wizard\WizardInterface;
use Drupal\multistep_form_wizard_examples\BookWizard;
use Drupal\node\Entity\Node;

/**
 * Provides a multistep_form_wizard_examples form.
 */
class BookBuyMultistepForm extends MultistepForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    // Form id will be used as plugin id.
    // @see multistep_form_wizard_examples.multistep_wizard.yml
    return 'multistep_form_wizard_examples_book_buy_miltistep';
  }

  /**
   * {@inheritDoc}
   */
  protected function prepareWizard(array $form, FormStateInterface $form_state): WizardInterface {
    $wizard = parent::prepareWizard($form, $form_state);
    assert($wizard instanceof BookWizard);

    $wizard->setBook(Node::create([
      'type' => 'book',
    ]));
    return $wizard;
  }

}
