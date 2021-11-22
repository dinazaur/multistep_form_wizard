<?php

namespace Drupal\multistep_form_wizard_examples\Form\BuyBookStep;

use Drupal\Core\Form\FormStateInterface;

/**
 * Description book step.
 */
class Description extends BookAttributes {

  /**
   * {@inheritDoc}
   */
  protected function getTitle(): string {
    return $this->t('What book do you want to share?');
  }

  /**
   * {@inheritDoc}
   */
  protected function getFieldsToRender() {
    return [
      'body',
      'status',
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function nextAction(array &$form, FormStateInterface $form_state) {
    parent::nextAction($form, $form_state);
    $book = $this->wizard->getBook();
    $book->setTitle('Awesome!');
    $book->save();

    $this->messenger()->addStatus($this->t('Your awesome book hase been saved!'));
  }

  /**
   * {@inheritDoc}
   */
  public function getActions(array $form, FormStateInterface $form_state): array {
    $actions = parent::getActions($form, $form_state);

    $actions['go_to_first_step'] = [
      '#type' => 'submit',
      '#value' => $this->t('Go to first step! I forgot something :('),
      '#submit' => ['::goToFirstStep'],
    ];

    return $actions;
  }

  /**
   * Submit handler.
   */
  public function goToFirstStep(array $form, FormStateInterface $form_state) {
    $this->wizard->setCurrentStepById('greetings');
    $form_state->setRebuild();
  }

  /**
   * {@inheritDoc}
   */
  protected function getNextButton(): array {
    $button = parent::getNextButton();
    $button['#value'] = $this->t("Save");
    return $button;
  }

}
