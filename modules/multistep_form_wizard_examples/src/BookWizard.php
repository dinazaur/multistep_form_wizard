<?php

namespace Drupal\multistep_form_wizard_examples;

use Drupal\multistep_form_wizard\Wizard\Wizard;
use Drupal\node\NodeInterface;

/**
 * Book wizard.
 *
 * You can use this class to provide a more convenient way to get or set
 * information into form state.
 */
class BookWizard extends Wizard {

  /**
   * Sets book.
   */
  public function setBook(NodeInterface $book) {
    $this->formState->set('book', $book);
  }

  /**
   * Gets book.
   */
  public function getBook(): NodeInterface {
    return $this->formState->get('book');
  }

}
