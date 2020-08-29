<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\Registry;

  class ht_cookieconsent2
  {
    public $code;
    public $group;
    public string $title;
    public string $description;
    public ?int $sort_order = 0;
    public bool $enabled = false;

    public function __construct()
    {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_header_tags_cookieconsent2_title');
      $this->description = CLICSHOPPING::getDef('module_header_tags_cookieconsent2_description');

      if (defined('MODULE_HEADER_TAGS_COOKIECONSENT2_STATUS')) {
        $this->sort_order = MODULE_HEADER_TAGS_COOKIECONSENT2_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_COOKIECONSENT2_STATUS == 'True');
      }
    }

    public function execute()
    {
      $CLICSHOPPING_Template = Registry::get('Template');

      $footer = '<script src="' . CLICSHOPPING::link('ext/javascript/cookieconsent2/cookieconsent.min.js', null, false) . '"></script>';
      $CLICSHOPPING_Template->addBlock($footer, 'footer_scripts');


      $message = CLICSHOPPING::getdef('module_header_tags_cookieconsent2_message_text');
      $dismiss = CLICSHOPPING::getdef('module_header_tags_cookieconsent2_dismiss_text');
      $more = CLICSHOPPING::getdef('module_header_tags_cookieconsent2_more_text');
      $link = CLICSHOPPING::link(MODULE_HEADER_TAGS_COOKIECONSENT2_PAGE, '');
      $theme = CLICSHOPPING::link('ext/javascript/cookieconsent2/' . MODULE_HEADER_TAGS_COOKIECONSENT2_THEME . '.css', null, false);

      $output = <<<EOD
<script>window.cookieconsent_options = {"message":"{$message}", "dismiss":"{$dismiss}", "learnMore":"{$more}", "link":"{$link}", "theme":"{$theme}"};</script>
EOD;

      $CLICSHOPPING_Template->addBlock($output . "\n", $this->group);
    }

    public function isEnabled()
    {
      return $this->enabled;
    }

    public function check()
    {
      return defined('MODULE_HEADER_TAGS_COOKIECONSENT2_STATUS');
    }

    public function install()
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
        'configuration_title' => 'Enable Cookie Compliance Module',
        'configuration_key' => 'MODULE_HEADER_TAGS_COOKIECONSENT2_STATUS',
        'configuration_value' => 'True',
        'configuration_description' => 'Do you want to enable this module?',
        'configuration_group_id' => '6',
        'sort_order' => '1',
        'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
        'date_added' => 'now()'
      ]);

      $CLICSHOPPING_Db->save('configuration', [
        'configuration_title' => 'Theme',
        'configuration_key' => 'MODULE_HEADER_TAGS_COOKIECONSENT2_THEME',
        'configuration_value' => 'dark-top',
        'configuration_description' => 'Select Theme.',
        'configuration_group_id' => '6',
        'sort_order' => '1',
        'set_function' => 'clic_cfg_set_boolean_value(array(\'dark-top\', \'dark-floating\', \'dark-bottom\', \'light-floating\', \'light-top\', \'light-bottom\'))',
        'date_added' => 'now()'
      ]);

      $CLICSHOPPING_Db->save('configuration', [
        'configuration_title' => 'Cookie Policy Page',
        'configuration_key' => 'MODULE_HEADER_TAGS_COOKIECONSENT2_PAGE',
        'configuration_value' => 'index.php?Info&Content&pagesId=5',
        'configuration_description' => 'The Page on your site that has details of your Cookie Policy.',
        'configuration_group_id' => '6',
        'sort_order' => '0',
        'date_added' => 'now()'
      ]);

      $CLICSHOPPING_Db->save('configuration', [
        'configuration_title' => 'Sort Order',
        'configuration_key' => 'MODULE_HEADER_TAGS_COOKIECONSENT2_SORT_ORDER',
        'configuration_value' => '900',
        'configuration_description' => 'Sort order of display. Lowest is displayed first.',
        'configuration_group_id' => '6',
        'sort_order' => '0',
        'date_added' => 'now()'
      ]);

      return $CLICSHOPPING_Db->save('configuration', ['configuration_value' => '1'],
        ['configuration_key' => 'WEBSITE_MODULE_INSTALLED']
      );
    }

    function remove()
    {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    function keys()
    {
      return array('MODULE_HEADER_TAGS_COOKIECONSENT2_STATUS',
        'MODULE_HEADER_TAGS_COOKIECONSENT2_THEME',
        'MODULE_HEADER_TAGS_COOKIECONSENT2_PAGE',
        'MODULE_HEADER_TAGS_COOKIECONSENT2_SORT_ORDER'
      );
    }
  }
