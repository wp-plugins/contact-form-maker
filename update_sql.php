<?php

function contact_form_check_update() {
  global $wpdb;
  if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "formmaker_sessions'") != $wpdb->prefix . "formmaker_sessions") {
    if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "formmaker'") == $wpdb->prefix . "formmaker") {
      $form_properties = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "formmaker", ARRAY_A);
      foreach ($form_properties as $prop) {
        $exists_paypal_mode = (($prop['Field'] == 'paypal_mode') ? 1 : 0);
        $exists_checkout_mode = (($prop['Field'] == 'checkout_mode') ? 1 : 0);
        $exists_paypal_email = (($prop['Field'] == 'paypal_email') ? 1 : 0);
        $exists_payment_currency = (($prop['Field'] == 'payment_currency') ? 1 : 0);
        $exists_tax = (($prop['Field'] == 'tax') ? 1 : 0);
        $exists_script_mail = (($prop['Field'] == 'script_mail') ? 1 : 0);
        $exists_script_mail_user = (($prop['Field'] == 'script_mail_user') ? 1 : 0);
        $exists_label_order_current = (($prop['Field'] == 'label_order_current') ? 1 : 0);
      }
      if (!$exists_paypal_mode) {
        $wpdb->query("ALTER TABLE " . $wpdb->prefix . "formmaker ADD `paypal_mode` int(11) NOT NULL AFTER `recaptcha_theme`");
      }
      if (!$exists_checkout_mode) {
        $wpdb->query("ALTER TABLE " . $wpdb->prefix . "formmaker ADD `checkout_mode` varchar(20) NOT NULL AFTER `recaptcha_theme`");
      }
      if (!$exists_paypal_email) {
        $wpdb->query("ALTER TABLE " . $wpdb->prefix . "formmaker ADD `paypal_email` varchar(128) NOT NULL AFTER `recaptcha_theme`");
      }
      if (!$exists_payment_currency) {
        $wpdb->query("ALTER TABLE " . $wpdb->prefix . "formmaker ADD `payment_currency` varchar(20) NOT NULL AFTER `recaptcha_theme`");
      }
      if (!$exists_tax) {
        $wpdb->query("ALTER TABLE " . $wpdb->prefix . "formmaker ADD `tax` int(11) NOT NULL AFTER `recaptcha_theme`");
      }
      if (!$exists_script_mail) {
        $wpdb->query("ALTER TABLE " . $wpdb->prefix . "formmaker ADD `script_mail` text NOT NULL AFTER `recaptcha_theme`");
      }
      if (!$exists_script_mail_user) {
        $wpdb->query("ALTER TABLE " . $wpdb->prefix . "formmaker ADD `script_mail_user` text NOT NULL AFTER `recaptcha_theme`");
      }
      if (!$exists_label_order_current) {
        $wpdb->query("ALTER TABLE " . $wpdb->prefix . "formmaker ADD `label_order_current` text NOT NULL AFTER `recaptcha_theme`");
      }
    }
    $form_maker_sessions_table = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "formmaker_sessions` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `form_id` int(11) NOT NULL,
      `group_id` int(11) NOT NULL,
      `ip` varchar(20) NOT NULL,
      `ord_date` varchar(20) NOT NULL,
      `ord_last_modified` varchar(20) NOT NULL,
      `status` varchar(50) NOT NULL,
      `full_name` varchar(256) NOT NULL,
      `email` varchar(256) NOT NULL,
      `phone` varchar(50) NOT NULL,
      `mobile_phone` varchar(255) NOT NULL,
      `fax` varchar(255) NOT NULL,
      `address` varchar(300) NOT NULL,
      `paypal_info` text NOT NULL,
      `without_paypal_info` text NOT NULL,
      `ipn` varchar(20) NOT NULL,
      `checkout_method` varchar(20) NOT NULL,
      `tax` varchar(50) NOT NULL,
      `shipping` varchar(50) NOT NULL,
      `shipping_type` varchar(200) NOT NULL,
      `read` int(11) NOT NULL,
      `total` varchar(200) NOT NULL,
      `currency` varchar(24) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17";
    $wpdb->query($form_maker_sessions_table);
    $form_rows = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "formmaker");
    foreach ($form_rows as $form_row) {
      $wpdb->update($wpdb->prefix . "formmaker", array(
        'paypal_mode' => 0,
        'checkout_mode' => 'testmode',
        'paypal_email' => '',
        'payment_currency' => '',
        'tax' => 0,
        'script_mail' => $form_row->script1 . '%all%' . $form_row->script2,
        'script_mail_user' => $form_row->script_user1 . '%all%' . $form_row->script_user2,
        'label_order_current' => $form_row->label_order,
      ), array(
        'id' => $form_row->id,
      ), array(
        '%d',
        '%s',
        '%s',
        '%s',
        '%d',
        '%s',
        '%s',
        '%s',
      ), array(
        '%d',
      ));
    }
    if (!get_site_option('contact_formmaker_cureent_version')) {
      // if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "formmaker_themes'") == $wpdb->prefix . "formmaker_themes")
        add_option('contact_formmaker_cureent_version', '2.4.4');
    }
    else {
      // if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "formmaker_themes'") == $wpdb->prefix . "formmaker_themes")
        update_option('contact_formmaker_cureent_version', '2.4.4');
    }
  }
  $form_properties = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "formmaker", ARRAY_A);
  foreach ($form_properties as $prop) {
    $exist_from_mail = (($prop['Field'] == 'from_mail') ? 1 : 0);
    $exist_from_name = (($prop['Field'] == 'from_name') ? 1 : 0);
  }
  if (!$exist_from_mail) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "formmaker ADD `from_mail` varchar(255) NOT NULL AFTER `recaptcha_theme`");
  }
  if (!$exist_from_name) {
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "formmaker ADD `from_name` varchar(255) NOT NULL AFTER `recaptcha_theme`");
  }
  $form_rows = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "formmaker");
  foreach ($form_rows as $form_row) {
    $wpdb->update($wpdb->prefix . "formmaker", array(
      'paypal_mode' => (($form_row->paypal_mode == '') ? 0 : $form_row->paypal_mode),
      'checkout_mode' => (($form_row->checkout_mode == '') ? 'testmode' : $form_row->checkout_mode),
      'tax' => (($form_row->tax == '') ? 0 : $form_row->tax),
      'script_mail' => (($form_row->script_mail == '') ? $form_row->script1 . '%all%' . $form_row->script2 : $form_row->script_mail),
      'script_mail_user' => (($form_row->script_mail_user == '') ? $form_row->script_user1 . '%all%' . $form_row->script_user2 : $form_row->script_mail_user),
      'label_order_current' => $form_row->label_order,
    ), array(
      'id' => $form_row->id,
    ), array(
      '%d',
      '%s',
      '%d',
      '%s',
      '%s',
      '%s',
    ), array(
      '%d',
    ));
  }
}

?>