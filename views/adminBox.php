<?php

function add_box($posts, $freelancer_id, $param) {
?>
        <select class="widefat" id="freelancer-id" name="freelancer_id">
<?php
        foreach ($posts as $freelancer):
        $selected = ($freelancer->ID == $freelancer_id) ? ' selected="selected"' : '';
?>
            <option value="<?php echo $freelancer->ID; ?>"<?php echo $selected; ?>><?php echo esc_html($freelancer->post_title); ?></option>
<?php
        endforeach;
?>
        </select>
<?php
}

?>