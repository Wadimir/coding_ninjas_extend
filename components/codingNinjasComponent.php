<?php

class CodingNinjasComponent {
    	
	public function __construct() {
		add_action('admin_notices', [$this, 'admin_notices'], 10);
        add_action('init', [$this, 'init']);
        add_action('save_post', [$this, 'save_post'], 1, 3);
        add_action('cn_after_content', [$this, 'cn_after_content']);
        add_action('wp_enqueue_scripts', [$this, 'wp_enqueue_scripts'], 100);
        add_action('wp_ajax_add_new_task', [$this, 'add_new_task']);
        add_action('wp_ajax_nopriv_add_new_task', [$this, 'add_new_task']);
        add_action('manage_task_posts_custom_column' , [$this, 'task_posts_custom_column'], 1, 2);
        add_action('wp_trash_post', [$this, 'wp_trash_post']);
        add_action('untrash_post', [$this, 'wp_trash_post']);
		add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
  
        add_filter('manage_task_posts_columns', [$this, 'task_posts_columns'], 1);
        add_filter('pre_get_document_title', [$this, 'pre_get_document_title'], 1, 1);
        add_filter('cn_menu', [$this, 'cn_menu'], 1, 1);
		add_filter('cn_tasks_thead_cols', [$this, 'cn_tasks_thead_cols'], 1, 1);
		add_filter('cn_tasks_tbody_row_cols', [$this, 'cn_tasks_tbody_row_cols'], 1, 2);

        add_shortcode('cn_dashboard', [$this, 'cn_dashboard']);
		
		require_once plugin_dir_path(__file__). '../views/adminBox.php';
		require_once plugin_dir_path(__file__). '../views/modal.php';
		require_once plugin_dir_path(__file__). '../views/notice.php';
    }
	
	function init() {
	   $labels = [
		'name' => __('Freelancers'),
		'singular_name' => __('Freelancer'),
		'menu_name' => __('Freelancers'),
		'name_admin_bar' => __('Freelancer'),
		'add_new' => __('Add New'),
		'add_new_item' => __('Add New Freelancer'),
		'new_item' => __('New Freelancer'),
		'edit_item' => __('Edit Freelancer'),
		'view_item' => __('View Freelancer'),
		'all_items' => __('All Freelancers'),
		'search_items' => __('Search Freelancers'),
		'parent_item_colon' => __('Parent Freelancers:'),
		'not_found' => __('No Freelancers found'),
		'not_found_in_trash' => __('No Freelancers found in Trash')
	   ];

	   $args = [
		'labels' => $labels,
        'description' => __('Manage Freelancers list'),
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => ['slug' => 'freelancer'],
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => ['title', 'editor', 'thumbnail'],
        'menu_icon' => 'dashicons-id'
	   ];
	   register_post_type('freelancer', $args);
    }
	
	function wp_enqueue_scripts() {
        wp_enqueue_style('data-tables',  plugins_url('../styles/datatables.css', __file__));
        wp_enqueue_script('data-tables', plugins_url('datatables.js', __file__ ), ['jquery']);
		wp_enqueue_style('cnext-frontend',  plugins_url('../styles/frnd.css', __file__));
		wp_enqueue_script('cnext-frontend', plugins_url('frnd.js', __file__ ), ['jquery']);
    }
	
	function cn_dashboard($atts, $content) {
        ob_start();
        require_once plugin_dir_path(__file__). '../views/dashboardPage.php';
        return ob_get_clean();
    }
	
    function wp_trash_post($post_id) {
        $freelancer_id = $this->get_freelancer_id($post_id);
        $this->update_freelancer($freelancer_id);
    }

    function get_freelancer_name($freelancer_id) {
        if ($freelancer_id == 0) {
            return __('Not assigned');
        }
        $freelancer = $this->get_freelancer($freelancer_id);
        return $freelancer->post_title;
    }

    function task_posts_columns($columns) {
        $columns['freelancer_id'] = __('Freelancer');
        return $columns;
    }

    function task_posts_custom_column($column, $post_id) {
        if ($column == 'freelancer_id') {
            echo esc_html($this->get_freelancer_name($this->get_freelancer_id($post_id)));
        }
    }

    function get_freelancer_id($post_id) {
        return intval(get_post_meta($post_id, 'freelancer_id', true));
    }

    function cn_tasks_tbody_row_cols($cols, $task) {
        $task_id = intval(str_replace('#', '', $task->id()));
        $cols[] = esc_html($this->get_freelancer_name($this->get_freelancer_id($task_id)));
        return $cols;
    }

    function cn_tasks_thead_cols($cols) {
        $cols[] = __('Freelancer');
        return $cols;
    }

    function get_freelancer($freelancer_id) {
        return get_post($freelancer_id);
    }

    function get_freelancers() {
        $freelancers = get_posts([
            'post_type' => 'freelancer',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => [['key' => 'task_count', 'value' => 3, 'compare' => '<', 'type' => 'UNSIGNED']]
            ]);
        return $freelancers;
    }

    function cn_after_content() {
		$freelancers = $this->get_freelancers();
		update_modal($freelancers);	
    }

    function add_new_task() {
        $post =
        [
            'post_title' => $_POST['task_title'],
            'post_type' => 'task',
            'post_status' => 'publish'
        ];
        $post_id = wp_insert_post($post, true);
        if (!is_wp_error($post_id)) {
            update_post_meta($post_id, 'freelancer_id', $_POST['task_freelancer']);
            $this->update_freelancer($_POST['task_freelancer']);
        }		
    }

    function cn_menu($menu) {
        $menu['#add-new-task'] = ['title' => __('Add new task'), 'icon' => 'fa-plus-circle']; 
        return $menu;
    }

    function task_count($freelancer_id) {
        $query = new \WP_Query(['meta_key' => 'freelancer_id', 'meta_value' => $freelancer_id, 'post_type'=>'task', 'post_status'=>'publish']);
        return $query->found_posts;
    }

    function get_total($what) {
        $query = new \WP_Query(['post_type' => $what, 'post_status' => 'publish']);
        return $query->found_posts;
    }

    function update_freelancer($freelancer_id) {
        if (intval($freelancer_id) < 1) {
            return false;
        }
        update_post_meta($freelancer_id, 'task_count', $this->task_count($freelancer_id));
        return true;
    }

    function save_post($post_id, $post, $update) {	
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (wp_is_post_revision($post_id)) {
            return;            
        }
        if (isset($_POST['freelancer_id'])) {
            $prev_freelancer = $this->get_freelancer_id($post_id);
            $next_freelancer = intval($_POST['freelancer_id']);
            update_post_meta($post_id, 'freelancer_id', $next_freelancer);
            $this->update_freelancer($prev_freelancer);
            $this->update_freelancer($next_freelancer);
        } 
    }

    function pre_get_document_title($title) {
        $url = parse_url($_SERVER['REQUEST_URI']);
        switch ($url['path']) {
            case '/dashboard': $title = 'Dashboard'; break;
            case '/tasks': $title = 'Tasks'; break;
        }
        return $title;
    }
	
	function add_meta_boxes() {
        add_meta_box('task-freelancer', __('Freelancer'), [$this, 'to_box'], 'task', 'side', 'high');
    }

    function to_box($post) {
		$posts = get_posts(['post_type'=>'freelancer', 'post_status'=>'publish', 'posts_per_page'=>-1]);
        $freelancer_id = $this->get_freelancer_id($post->ID);
		$param = _e('Select Freelancer');
		add_box($posts, $freelancer_id, $param);
    }
	
	function admin_notices() {
        if ($this->coding_ninjas_active()) {
            return;
        }
		show_notice();
    }
	
	function coding_ninjas_active() {
        return class_exists('codingninjas\App');
    }
}

?>