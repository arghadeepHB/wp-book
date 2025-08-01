<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Book
 * @subpackage Wp_Book/includes
 * @author     Arghadeep Dey <deyarghadeep23@gmail.com>
 */
class Wp_Book {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Book_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_BOOK_VERSION' ) ) {
			$this->version = WP_BOOK_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-book';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		add_action('init',[$this,'register_book_post_type']);
		add_action('init',[$this, 'register_book_taxonomies']);
		add_action('add_meta_boxes',[$this, 'add_book_meta_box']); 
		add_action('save_post',[$this,'save_book_meta']);
		add_action( 'admin_menu', [ $this, 'add_book_settings_page' ] );
		add_action( 'admin_init', [ $this, 'register_book_settings' ] );
		add_shortcode( 'book', [ $this, 'render_book_shortcode' ] );

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Book_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Book_i18n. Defines internationalization functionality.
	 * - Wp_Book_Admin. Defines all hooks for the admin area.
	 * - Wp_Book_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-book-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-book-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-book-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-book-public.php';

		$this->loader = new Wp_Book_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Book_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Book_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Book_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Book_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Book_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function register_book_post_type(){
		register_post_type('book',[
			'labels'=>[
				'name'=>__('Books', 'wp-book'),
				'singular_name'=>__('Book', 'wp-book'),
			],
			'public'=>true,
			'has_archive'=>true,
			'menu_icon'=>'dashicons-book',
			'supports'=>['title','editor','thumbnail'],
			'show_in_rest'=>true,
		]);
	}

	public function register_book_taxonomies(){

		register_taxonomy('book_category','book',[
			'labels'=>[
				'name' => __('Book Categories','wp-book'),
				'singular_name' => __( 'Book Category','wp-book'),
				'search_items'=>__('Search Book Categories','wp-book'),
				'all_items'=>__('All book categories','wp-book'),
				'parent_item'=>__('Parent Book Category','wp-book'),
				'edit_item'=>__('Edit Book Cateogry','wp-book'),
				'update-item'=>__('Update Book Category','wp-book'),
				'add_new_item'=>__('Add New Book Category','wp-book'),
				'new_item_name'=>__('New Book Category Name','wp-book')
			],
			'hierarchical'=>true, 
			'public'=>true, 
			'show_in_rest'=>true 
		]);

		// Book Tag (Non-Hierarchical)
		register_taxonomy('book_tag','book',[
			'labels'=>[
			'name'=>__('Book Tags','wp-book'),
			'singular_name'=>__('Book Tag','wp-book'),
			'search_items'=>__('Search Book Tags','wp-book'),
			'all_items'=>__('All Book Tags','wp-book'),
			'edit_item'=>__('Edit Book Tag','wp-book'), 
			'update_item'=>__('Update Book tag','wp-book'),
			'add_new_item'=>__('Add New Book Tag','wp-book'),
			'new_item_name'=>__('New Book Tag Name','wp-book')
		],
		'hierarchical'=>false, 
		'public'=>true,
		'show_in_rest'=>true
	]);
		
	}

	public function add_book_meta_box(){
		add_meta_box(
			'book_meta_box',
			__('Book Details','wp-book'),
			[$this,'render_book_meta_box'],
			'book',
			'normal',
			'default'
		);
	}
	public function render_book_meta_box($post){
		// adding the nonce
		wp_nonce_field(basename(__FILE__),'book_meta_nonce');

		$author = get_post_meta($post->ID,'_book_author',true);
		$price = get_post_meta($post->ID,'_book_price',true); 
		$publisher = get_post_meta($post->ID, '_book_publisher',true);
		$year = get_post_meta($post->ID, '_book_year',true);
		$edition = get_post_meta($post->ID,'_book_edition',true); 
		$url = get_post_meta($post->ID, '_book_url',true); 
		?>
			<p>
				<label for="">Author Name:</label>
				<br>
				<input type="text" name="book_author" value="<?php echo esc_attr($author);?>" class="widefat">
			</p>
			<p>
				<label for="">Price:</label>
				<br>
				<input type="text" name="book_price" value="<?php echo esc_attr($price);?>" class="widefat">
			</p>
			<p>
				<label for="">Publisher:</label>
				<br>
				<input type="text" name="book_publisher" value="<?php echo esc_attr($publisher);?>" class="widefat">
			</p>
			<p>
				<label for="">Year:</label>
				<br>
				<input type="text" name="book_year" value="<?php echo esc_attr($year);?>" class="widefat">
			</p>
			<p>
				<label for="">Edition:</label>
				<br>
				<input type="text" name="book_edition" value="<?php echo esc_attr($edition);?>" class="widefat">
			</p>
			<p>
				<label for="">URL:</label>
				<br>
				<input type="url" name="book_url" value="<?php echo esc_attr($url);?>" class="widefat">
			</p>
		<?php 
	}

	public function save_book_meta( $post_id ) 
	{
		if ( ! isset( $_POST['book_meta_nonce'] ) || ! wp_verify_nonce( $_POST['book_meta_nonce'], basename( __FILE__ ) ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( get_post_type( $post_id ) !== 'book' || ! current_user_can( 'edit_post', $post_id ) ) return;

		global $wpdb;
		$table = $wpdb->prefix . 'book_meta';

		$data = [
			'author'    => sanitize_text_field( $_POST['book_author'] ?? '' ),
			'price'     => sanitize_text_field( $_POST['book_price'] ?? '' ),
			'publisher' => sanitize_text_field( $_POST['book_publisher'] ?? '' ),
			'year'      => sanitize_text_field( $_POST['book_year'] ?? '' ),
			'edition'   => sanitize_text_field( $_POST['book_edition'] ?? '' ),
			'url'       => esc_url_raw( $_POST['book_url'] ?? '' ),
		];

		$exists = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE book_id = %d", $post_id ) );

		if ( $exists ) {
			$wpdb->update( $table, $data, [ 'book_id' => $post_id ] );
		} else {
			$data['book_id'] = $post_id;
			$wpdb->insert( $table, $data );
		}
	}

	public function add_book_settings_page() 
	{
		add_submenu_page(
			'edit.php?post_type=book',
			__( 'Book Settings', 'wp-book' ),
			__( 'Settings', 'wp-book' ),
			'manage_options',
			'wp-book-settings',
			[ $this, 'render_book_settings_page' ]
		);
	}

	public function render_book_settings_page() 
	{
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Book Settings', 'wp-book' ); ?></h1>
			<form method="post" action="options.php">
				<?php
					settings_fields( 'wp_book_settings' );
					do_settings_sections( 'wp-book-settings' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}


	public function register_book_settings() 
	{
		register_setting( 'wp_book_settings', 'wp_book_currency' );
		register_setting( 'wp_book_settings', 'wp_book_per_page' );

		add_settings_section(
			'wp_book_main_section',
			__( 'General Settings', 'wp-book' ),
			null,
			'wp-book-settings'
		);

		add_settings_field(
			'wp_book_currency',
			__( 'Currency', 'wp-book' ),
			function () {
				$value = get_option( 'wp_book_currency', 'USD' );
				echo '<input type="text" name="wp_book_currency" value="' . esc_attr( $value ) . '" />';
			},
			'wp-book-settings',
			'wp_book_main_section'
		);

		add_settings_field(
			'wp_book_per_page',
			__( 'Books Per Page', 'wp-book' ),
			function () {
				$value = get_option( 'wp_book_per_page', 10 );
				echo '<input type="number" name="wp_book_per_page" value="' . esc_attr( $value ) . '" min="1" />';
			},
			'wp-book-settings',
			'wp_book_main_section'
		);
	}

	public function render_book_shortcode( $atts ) {
    $atts = shortcode_atts( [
        'id'           => '',
        'author_name'  => '',
        'year'         => '',
        'category'     => '',
        'tag'          => '',
        'publisher'    => '',
    ], $atts, 'book' );

    global $wpdb;

    $query_args = [
        'post_type' => 'book',
        'post_status' => 'publish',
        'posts_per_page' => get_option( 'wp_book_per_page', 10 ),
    ];

    if ( $atts['id'] ) {
        $query_args['p'] = intval( $atts['id'] );
    }

    if ( $atts['category'] ) {
        $query_args['tax_query'][] = [
            'taxonomy' => 'book_category',
            'field'    => 'slug',
            'terms'    => sanitize_title( $atts['category'] ),
        ];
    }

    if ( $atts['tag'] ) {
        $query_args['tax_query'][] = [
            'taxonomy' => 'book_tag',
            'field'    => 'slug',
            'terms'    => sanitize_title( $atts['tag'] ),
        ];
    }

    $posts = get_posts( $query_args );
    $output = '';

    foreach ( $posts as $post ) {
        $meta = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}book_meta WHERE book_id = %d", $post->ID
        ) );

        if (
            ($atts['author_name'] && stripos( $meta->author ?? '', $atts['author_name'] ) === false) ||
            ($atts['year'] && $meta->year != $atts['year']) ||
            ($atts['publisher'] && stripos( $meta->publisher ?? '', $atts['publisher'] ) === false)
        ) {
            continue;
        }

        $currency = esc_html( get_option( 'wp_book_currency', 'USD' ) );
        $output .= '<div class="book">';
        $output .= '<h3>' . esc_html( get_the_title( $post ) ) . '</h3>';
        $output .= '<p><strong>Author:</strong> ' . esc_html( $meta->author ?? '' ) . '</p>';
        $output .= '<p><strong>Price:</strong> ' . esc_html( $currency . ' ' . ( $meta->price ?? '' ) ) . '</p>';
        $output .= '<p><strong>Publisher:</strong> ' . esc_html( $meta->publisher ?? '' ) . '</p>';
        $output .= '<p><strong>Year:</strong> ' . esc_html( $meta->year ?? '' ) . '</p>';
        $output .= '<p><strong>Edition:</strong> ' . esc_html( $meta->edition ?? '' ) . '</p>';
        if ( $meta->url ) {
            $output .= '<p><a href="' . esc_url( $meta->url ) . '" target="_blank">More Info</a></p>';
        }
        $output .= '</div><hr>';
    }

    return $output ?: '<p>No books found.</p>';
}


	


}
