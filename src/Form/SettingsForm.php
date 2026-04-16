<?php

namespace Drupal\simple_auto_title\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;
use Drupal\Core\Render\Element;
use Drupal\Core\Url;

/**
 * Formulario de configuración del módulo Simple Auto Title.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['simple_auto_title.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'simple_auto_title_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('simple_auto_title.settings');

    // Obtener todos los tipos de contenido disponibles.
    $content_types = NodeType::loadMultiple();
    $options = [];
    foreach ($content_types as $type) {
      $options[$type->id()] = $type->label();
    }

    // Si no hay tipos de contenido, mostrar un mensaje de advertencia.
    if (empty($options)) {
      $this->messenger()->addWarning($this->t('No hay tipos de contenido disponibles. Crea al menos un tipo de contenido antes de configurar este módulo.'));
      return $form;
    }

    // Sección 1: Tipos de contenido activados.
    $form['enabled_content_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Tipos de contenido con título automático'),
      '#description' => $this->t('Selecciona los tipos de contenido donde quieres generar el título automáticamente.'),
      '#options' => $options,
      '#default_value' => $config->get('enabled_content_types') ?: [],
    ];

    // Sección 2: Patrón del título.
    $form['title_pattern'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Patrón del título'),
      '#description' => $this->t('Introduce el patrón para generar el título automáticamente. Puedes usar tokens como <strong>[node:nid]</strong>, <strong>[node:title]</strong>, <strong>[date:short]</strong>, etc.'),
      '#default_value' => $config->get('title_pattern') ?: '[node:nid] - [node:created:custom:Y-m-d]',
      '#required' => TRUE,
    ];

    // Enlace a la página de tokens disponibles.
    $form['token_help'] = [
      '#type' => 'markup',
      '#markup' => $this->t('Ver <a href=":url" target="_blank">tokens disponibles</a>', [
        ':url' => Url::fromRoute('token.tree', ['token_type' => 'node'])->toString(),
      ]),
    ];

    // Sección 3: Ocultar el campo título.
    $form['hide_title_field'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Ocultar el campo título en el formulario de edición'),
      '#description' => $this->t('Si marcas esta opción, los usuarios no verán el campo título al crear o editar contenido. El título se generará automáticamente.'),
      '#default_value' => $config->get('hide_title_field') ?: FALSE,
    ];

    // Sección 4: Advertencia importante.
    $form['warning'] = [
      '#type' => 'details',
      '#title' => $this->t('⚠️ Importante'),
      '#open' => FALSE,
      '#weight' => 100,
    ];
    $form['warning']['message'] = [
      '#markup' => $this->t('<p><strong>¿Qué debes saber?</strong></p>
      <ul>
        <li>El título se generará automáticamente al GUARDAR el contenido, no mientras escribes.</li>
        <li>Si ocultas el campo título, los usuarios no podrán editarlo manualmente.</li>
        <li>Si dejas el campo título visible, el título automático actuará como "valor por defecto" y el usuario podrá modificarlo.</li>
        <li>Los títulos ya existentes NO se actualizarán automáticamente. Solo afecta a contenido nuevo o editado.</li>
      </ul>'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('simple_auto_title.settings');
    
    // Limpiar el valor de los checkboxes (eliminar los elementos vacíos).
    $enabled_types = array_filter($form_state->getValue('enabled_content_types'));
    
    $config->set('enabled_content_types', array_values($enabled_types))
      ->set('title_pattern', $form_state->getValue('title_pattern'))
      ->set('hide_title_field', $form_state->getValue('hide_title_field'))
      ->save();
    
    parent::submitForm($form, $form_state);
    
    // Mostrar mensaje de éxito.
    $this->messenger()->addStatus($this->t('Configuración de Simple Auto Title guardada correctamente.'));
  }

}