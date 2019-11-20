<?php

namespace Drupal\views_url_alias_filter\Plugin\views\argument_default;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Path\AliasManagerInterface;
use Drupal\views\Plugin\views\argument_default\ArgumentDefaultPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Convert an URL alias to it's entity ID.
 *
 * @ViewsArgumentDefault(
 *   id = "views_url_alias_entity_id",
 *   title = @Translation("URL alias to entity ID")
 * )
 */
class URLAliasFilter extends ArgumentDefaultPluginBase implements CacheableDependencyInterface {

  /**
   * A instance of the alias manager.
   *
   * @var \Drupal\Core\Path\AliasManagerInterface
   */
  protected $aliasManager;

  /**
   * The alias for which we want to get the type.
   *
   * @var string
   */
  protected $aliasValue;

  /**
   * {@inheritdoc}
   */
  public function __construct(
   array $configuration,
   $plugin_id,
   $plugin_definition,
   AliasManagerInterface $alias_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->aliasManager = $alias_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
     $configuration,
     $plugin_id,
     $plugin_definition,
     $container->get('path.alias_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['query_param'] = ['default' => ''];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['query_param'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Query parameter'),
      '#description' => $this->t('The query parameter to use.'),
      '#default_value' => $this->options['query_param'],
      '#required' => TRUE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getArgument() {
    $current_request = $this->view->getRequest();

    if ($current_request->query->has($this->options['query_param'])) {
      $param = $current_request->query->get($this->options['query_param']);

      // Getting the internal node path value from path alias.
      $internal_path = $this->aliasManager->getPathByAlias($param);
      if (!empty($internal_path)) {
        if (preg_match('/node\/(\d+)/', $internal_path, $matches)) {
          return $matches[1];
        }
      }
      else {
        return "Enter the correct query parameter.";
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return Cache::PERMANENT;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return ['url'];
  }

}
