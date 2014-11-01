<?php

/**
 * Plugin Name: Search On Github
 * Plugin URI: http://vndis.com
 * Description: Search on github
 * Version: 1.0
 * Author: Vndis
 * Author URI: http://vndis.com
 * License: A "Slug" license name e.g. GPL2
 */
class Vndis_Github extends WP_Widget
{

	/**
	 * Sets up the widgets name etc
	 */
	private $text_domain;
	private $base_path;
	private $base_url;
	private $github;
	private $username = 'chidungdekiemtra';
	private $token = ' 679f26ab5d42a6741074c8954cfef0ddb044815d';
	public function __construct()
	{
		$this->text_domain = 'vndis-github';
		$this->base_path = dirname(__FILE__);
		$this->base_url  = plugins_url('', __FILE__);
		add_action('init', array($this, 'load_style') );
		add_action('init', array($this, 'load_script') );

		add_action( 'wp_ajax_nopriv_github_search', array($this, 'search') );
		add_action( 'wp_ajax_github_search', array($this, 'search') );

		require_once $this->base_path . '/lib/Github.php';
		$this->github = new Github();
		$this->github->authenticate($this->username, $this->token);
		parent::__construct(
			'vndis_github', // Base ID
			__('Vndis Github', $this->text_domain), // Name
			array( 'description' => __( 'Add github to widget', $this->text_domain ), ) // Args
		);

	}
	public function search()
	{

		$keyword = isset($_POST['keyword'])  ? $_POST['keyword']  : '';
		$lang    = isset($_POST['language']) ? $_POST['language'] : '';
		//$page    = isset($_POST['page'])     ? (int)$_POST['page']: -1;
		$page = -1;
		$result =  $this->github->search($keyword, $lang, $page);
		$respond = new stdClass();
		$respond->e = 0;
		if($result == null) $respond->e = -1;
		$respond->data = $result;
		echo json_encode($respond);
		exit();
	}
	public function load_style()
	{
		wp_register_style('vndis-github-css', $this->base_url. '/static/css/vndis-github.css');
	}
	public function load_script()
	{
		wp_register_script('vndis-github-js', $this->base_url. '/static/js/vndis-github.js');
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance )
	{
		wp_enqueue_style('vndis-github-css');
		wp_enqueue_script('vndis-github-js');
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		?>
		<div>
            <form id="vndis-github-search" name="vndis" action="" method="post">
	            <label><?php _e('Keyword:', $this->text_domain); ?></label>
	            <input id="vndis-github-search-keyword" type="text" name="keyword"/>
	            <label><?php _e('Language:', $this->text_domain); ?></label>
	            <select name="language" id="vndis-github-search-language">
		            <option value="php">php</option>
		            <option value="java">java</option>
		            <option value="perl">perl</option>
		            <option value="javascript">javascript</option>
		            <option value="ruby">ruby</option>
		            <option value="c++">c++</option>
		            <option value="c#">c#</option>
	            </select>
	            <input type="submit" value="<?php _e('Search', $this->text_domain); ?>"/>
            </form>
		</div>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance )
	{
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else
		{
			$title = __( 'Title', $this->text_domain );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' , $this->text_domain); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
	<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance )
	{
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
}

add_action( 'widgets_init', function ()
{
	register_widget( 'Vndis_Github' );
} );