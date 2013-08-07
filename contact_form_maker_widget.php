<?php
global $contact_form_maker_function__once;
class contact_form_maker_widget extends WP_Widget {

  // Constructor //
  function contact_form_maker_widget() {
    $widget_ops = array(
      'classname' => 'contact_form_maker_widget',
      'description' => 'Add Form Maker widget.'
    ); // Widget Settings
    $control_ops = array('id_base' => 'contact_form_maker_widget'); // Widget Control Settings
    $this->WP_Widget('contact_form_maker_widget', 'Contact Form Maker', $widget_ops, $control_ops); // Create the widget
  }

  // Extract Args
  function widget($args, $instance) {
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    // Before widget
    echo $before_widget;
    // Title of widget
    if ($title) {
      echo $before_title . $title . $after_title;
    }
    // echo $title;
    global $contact_form_maker_function__once;
    if ($contact_form_maker_function__once) {
      $contact_form_maker_function__once = 0;
    }
    require_once("front_end_contact_form.php");
    echo contact_form_front_end($instance['form_id']);
    // After widget
    echo $after_widget;
  }

  // Update Settings //
  function update($new_instance, $old_instance) {
    $instance['title'] = $new_instance['title'];
    $instance['form_id'] = $new_instance['form_id'];
    return $instance;
  }

  // Widget Control Panel //
  function form($instance) {
    $defaults = array(
      'title' => '',
      'form_id' => 0
    );
    $instance = wp_parse_args((array)$instance, $defaults);
    global $wpdb; ?>
  <p>
    <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
           name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>"/>
    <label for="<?php echo $this->get_field_id('form_id'); ?>">Select a form:</label>
    <select name="<?php echo $this->get_field_name('form_id'); ?>'" id="<?php echo $this->get_field_id('form_id'); ?>"
            style="width:225px;text-align:center;">
      <option style="text-align:center" value="0">- Select a Form -</option>
      <?php
      $ids_contact_form_maker = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "formmaker order by `id` DESC", 0);
      foreach ($ids_contact_form_maker as $arr_contact_form_maker) {
        ?>
        <option
          value="<?php echo $arr_contact_form_maker->id; ?>" <?php if ($arr_contact_form_maker->id == $instance['form_id']) {
          echo "SELECTED";
        } ?>><?php echo $arr_contact_form_maker->title; ?></option>
        <?php }?>
    </select>
  <?php
  }
}

add_action('widgets_init', create_function('', 'return register_widget("contact_form_maker_widget");'));
?>