<?php

/**
 * @package Form Maker
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
// Direct access must be allowed
///////////////////////////////////////////////////////////////////// wd_captcha
function form_contact_wd_captcha() {
  if (isset($_GET['action']) && esc_html($_GET['action']) == 'formcontactwdcaptcha') {
    if (isset($_GET["i"]))
      $i = (int)$_GET["i"];
    else
      $i = '';
    if (isset($_GET['r2']))
      $r2 = (int)$_GET['r2'];
    else
      $r2 = 0;
    if (isset($_GET['r']))
      $rrr = (int)$_GET['r'];
    else
      $rrr = 0;
    $randNum = 0 + $r2 + $rrr;
    if (isset($_GET["digit"])) {
      $digit = (int)$_GET["digit"];
    }
    else {
      $digit = 6;
    }
    $cap_width = $digit * 10 + 15;
    $cap_height = 30;
    $cap_quality = 100;
    $cap_length_min = $digit;
    $cap_length_max = $digit;
    $cap_digital = 1;
    $cap_latin_char = 1;
    function code_generic($_length, $_digital = 1, $_latin_char = 1) {
      $dig = array(
        0,
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9
      );
      $lat = array(
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'j',
        'k',
        'l',
        'm',
        'n',
        'o',
        'p',
        'q',
        'r',
        's',
        't',
        'u',
        'v',
        'w',
        'x',
        'y',
        'z'
      );
      $main = array();
      if ($_digital)
        $main = array_merge($main, $dig);
      if ($_latin_char)
        $main = array_merge($main, $lat);
      shuffle($main);
      $pass = substr(implode('', $main), 0, $_length);
      return $pass;
    }

    $l = rand($cap_length_min, $cap_length_max);
    $code = code_generic($l, $cap_digital, $cap_latin_char);
    @session_start();
    $_SESSION[$i . '_wd_captcha_code'] = $code;
    $canvas = imagecreatetruecolor($cap_width, $cap_height);
    $c = imagecolorallocate($canvas, rand(150, 255), rand(150, 255), rand(150, 255));
    imagefilledrectangle($canvas, 0, 0, $cap_width, $cap_height, $c);
    $count = strlen($code);
    $color_text = imagecolorallocate($canvas, 0, 0, 0);
    for ($it = 0; $it < $count; $it++) {
      $letter = $code[$it];
      imagestring($canvas, 6, (10 * $it + 10), $cap_height / 4, $letter, $color_text);
    }
    for ($c = 0; $c < 150; $c++) {
      $x = rand(0, $cap_width - 1);
      $y = rand(0, 29);
      $col = '0x' . rand(0, 9) . '0' . rand(0, 9) . '0' . rand(0, 9) . '0';
      imagesetpixel($canvas, $x, $y, $col);
    }
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
    header('Content-Type: image/jpeg');
    imagejpeg($canvas, NULL, $cap_quality);
    die('');
  }
}

/////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////// function post or page window php
function form_contact_window_php() {
  if (isset($_GET['action']) && esc_html($_GET['action']) == 'formcontactwindow') {
    global $wpdb;
    ?>
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Contact Form</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script language="javascript" type="text/javascript"
            src="<?php echo get_option("siteurl"); ?>/wp-includes/js/jquery/jquery.js"></script>
    <script language="javascript" type="text/javascript"
            src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
    <link rel="stylesheet"
          href="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/themes/advanced/skins/wp_theme/dialog.css?ver=342-20110630100">
    <script language="javascript" type="text/javascript"
            src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
    <script language="javascript" type="text/javascript"
            src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
    <base target="_self">
  </head>
  <body id="link" style="" dir="ltr" class="forceColors">
  <div class="tabs" role="tablist" tabindex="-1">
    <ul>
      <li id="form_maker_tab" class="current" role="tab" tabindex="0"><span><a
        href="javascript:mcTabs.displayTab('Single_product_tab','Single_product_panel');" onMouseDown="return false;"
        tabindex="-1">Contact Form</a></span></li>
    </ul>
  </div>
  <style>
    .panel_wrapper {
      height: 170px !important;
    }
  </style>
  <div class="panel_wrapper">
    <div id="Single_product_panel" class="panel current">
      <table>
        <tr>
          <td style="height:100px; width:100px; vertical-align:top;">
            Select a Form
          </td>
          <td style="vertical-align:top">
            <select name="Form_Makername" id="Form_Makername" style="width:250px; text-align:center">
              <option style="text-align:center" value="- Select Form -" selected="selected">- Select a Form -</option>
              <?php    $ids_Form_Maker = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "formmaker WHERE `id` IN(" . get_option('contact_form_forms', 0) . ") order by `id` DESC", 0);
              foreach ($ids_Form_Maker as $arr_Form_Maker) {
                ?>
                <option value="<?php echo $arr_Form_Maker->id; ?>"><?php echo $arr_Form_Maker->title; ?></option>
                <?php }?>
            </select>
          </td>
        </tr>
      </table>
    </div>
  </div>
  <div class="mceActionPanel">
    <div style="float: left">
      <input type="button" id="cancel" name="cancel" value="Cancel" onClick="tinyMCEPopup.close();"/>
    </div>
    <div style="float:right">
      <input type="submit" id="insert" name="insert" value="Insert" onClick="insert_Form_Maker();"/>
    </div>
  </div>
  <script type="text/javascript">
    function insert_Form_Maker() {
      if (document.getElementById('Form_Makername').value == '- Select Form -') {
        tinyMCEPopup.close();
      }
      else {
        var tagtext;
        tagtext = '[wd_contact_form id="' + document.getElementById('Form_Makername').value + '"]';
        window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
        tinyMCEPopup.editor.execCommand('mceRepaint');
        tinyMCEPopup.close();
      }
    }
  </script>
  </body>
  </html>
  <?php
    die('');
  }
}

// Form preview from product options page.
function contact_form_preview_product_option() {
  global $wpdb;
  if (isset($_GET['id'])) {
    $getparams = (int) $_GET['id'];
  }
  if (isset($_GET['form_id'])) {
    $form_id = (int) $_GET['form_id'];
  }
  $query = "SELECT css FROM " . $wpdb->prefix . "formmaker_themes WHERE id=" . $getparams;
  $css = $wpdb->get_var($query);
  $query = "SELECT form_front FROM " . $wpdb->prefix . "formmaker WHERE id=" . $form_id;
  $form = $wpdb->get_var($query);
  html_contact_form_preview_product_option($css, $form);
}

function html_contact_form_preview_product_option($css, $form) {
  $cmpnt_js_path = plugins_url('js', __FILE__);
  $id = 'form_id_temp';
  ?>
  <script src="<?php echo $cmpnt_js_path . "/if_gmap_back_end.js"; ?>"></script>
  <script src="<?php echo $cmpnt_js_path . "/main.js"; ?>"></script>
  <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
  <style>
      <?php
      $cmpnt_js_path = plugins_url('', __FILE__);
      echo str_replace('[SITE_ROOT]', $cmpnt_js_path, $css);
      ?>
  </style>
  <form id="form_preview"><?php echo $form ?></form>
  <?php
  die();
}
