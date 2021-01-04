<?php
/**
 * User model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models;
 */

namespace ThemeGrill\Masteriyo\Models;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Repository\UserRepository;
use ThemeGrill\Masteriyo\Helper\Utils;
use ThemeGrill\Masteriyo\Cache\CacheInterface;

defined( 'ABSPATH' ) || exit;

/**
 * User model.
 *
 * @since 0.1.0
 */
class User extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'user';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'users';

	/**
	 * Stores user data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'user_login'           => '',
		'user_pass'            => '',
		'user_nicename'        => '',
		'user_email'           => '',
		'user_url'             => '',
		'user_registered'      => null,
		'user_activation_key'  => '',
		'user_status'          => 0,
		'display_name'         => '',
		'nickname'             => '',
		'first_name'           => '',
		'last_name'            => '',
		'description'          => '',
		'rich_editing'         => true,
		'syntax_highlighting'  => true,
		'comment_shortcuts'    => false,
		'use_ssl'              => 0,
		'show_admin_bar_front' => true,
		'locale'               => '',
		'roles'                => array(),
		'allcaps'              => array(),
	);

	/**
	 * Get the user if ID
	 *
	 * @since 0.1.0
	 *
	 * @param UserRepository $user_repository User Repository.
	 */
	public function __construct( UserRepository $user_repository ) {
		$this->repository = $user_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get user_login.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_login( $context = 'view' ) {
		return $this->get_prop( 'user_login', $context );
	}

	/**
	 * Get user_pass.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_pass( $context = 'view' ) {
		return $this->get_prop( 'user_pass', $context );
	}

	/**
	 * Get user_nicename.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_nicename( $context = 'view' ) {
		return $this->get_prop( 'user_nicename', $context );
	}

	/**
	 * Get user_email.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_email( $context = 'view' ) {
		return $this->get_prop( 'user_email', $context );
	}

	/**
	 * Get user_url.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_url( $context = 'view' ) {
		return $this->get_prop( 'user_url', $context );
	}

	/**
	 * Get user_registered.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_registered( $context = 'view' ) {
		return $this->get_prop( 'user_registered', $context );
	}

	/**
	 * Get user_activation_key.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_activation_key( $context = 'view' ) {
		return $this->get_prop( 'user_activation_key', $context );
	}

	/**
	 * Get user_status.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_status( $context = 'view' ) {
		return $this->get_prop( 'user_status', $context );
	}

	/**
	 * Get display_name.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_display_name( $context = 'view' ) {
		return $this->get_prop( 'display_name', $context );
	}

	/**
	 * Get nickname.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_nickname( $context = 'view' ) {
		return $this->get_prop( 'nickname', $context );
	}

	/**
	 * Get first_name.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_first_name( $context = 'view' ) {
		return $this->get_prop( 'first_name', $context );
	}

	/**
	 * Get last_name.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_last_name( $context = 'view' ) {
		return $this->get_prop( 'last_name', $context );
	}

	/**
	 * Get description.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_description( $context = 'view' ) {
		return $this->get_prop( 'description', $context );
	}

	/**
	 * Get rich_editing.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_rich_editing( $context = 'view' ) {
		return $this->get_prop( 'rich_editing', $context );
	}

	/**
	 * Get syntax_highlighting.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_syntax_highlighting( $context = 'view' ) {
		return $this->get_prop( 'syntax_highlighting', $context );
	}

	/**
	 * Get comment_shortcuts.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_comment_shortcuts( $context = 'view' ) {
		return $this->get_prop( 'comment_shortcuts', $context );
	}

	/**
	 * Get use_ssl.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_use_ssl( $context = 'view' ) {
		return $this->get_prop( 'use_ssl', $context );
	}

	/**
	 * Get show_admin_bar_front.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_show_admin_bar_front( $context = 'view' ) {
		return $this->get_prop( 'show_admin_bar_front', $context );
	}

	/**
	 * Get locale.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_locale( $context = 'view' ) {
		return $this->get_prop( 'locale', $context );
	}

	/**
	 * Get roles.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_roles( $context = 'view' ) {
		return $this->get_prop( 'roles', $context );
	}

	/**
	 * Get allcaps.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_allcaps( $context = 'view' ) {
		return $this->get_prop( 'allcaps', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set user_login.
	 *
	 * @since 0.1.0
	 *
	 * @param string $user_login User's user_login.
	 */
	public function set_user_login( $user_login ) {
		$this->set_prop( 'user_login', $user_login );
	}

	/**
	 * Set user_pass.
	 *
	 * @since 0.1.0
	 *
	 * @param string $user_pass User's user_pass.
	 */
	public function set_user_pass( $user_pass ) {
		$this->set_prop( 'user_pass', $user_pass );
	}

	/**
	 * Set user_nicename.
	 *
	 * @since 0.1.0
	 *
	 * @param string $user_nicename User's user_nicename.
	 */
	public function set_user_nicename( $user_nicename ) {
		$this->set_prop( 'user_nicename', $user_nicename );
	}

	/**
	 * Set user_email.
	 *
	 * @since 0.1.0
	 *
	 * @param string $user_email User's user_email.
	 */
	public function set_user_email( $user_email ) {
		$this->set_prop( 'user_email', $user_email );
	}

	/**
	 * Set user_url.
	 *
	 * @since 0.1.0
	 *
	 * @param string $user_url User's user_url.
	 */
	public function set_user_url( $user_url ) {
		$this->set_prop( 'user_url', $user_url );
	}

	/**
	 * Set user_registered.
	 *
	 * @since 0.1.0
	 *
	 * @param string $user_registered User's user_registered.
	 */
	public function set_user_registered( $user_registered ) {
		$this->set_prop( 'user_registered', $user_registered );
	}

	/**
	 * Set user_activation_key.
	 *
	 * @since 0.1.0
	 *
	 * @param string $user_activation_key User's user_activation_key.
	 */
	public function set_user_activation_key( $user_activation_key ) {
		$this->set_prop( 'user_activation_key', $user_activation_key );
	}

	/**
	 * Set user_status.
	 *
	 * @since 0.1.0
	 *
	 * @param string $user_status User's user_status.
	 */
	public function set_user_status( $user_status ) {
		$this->set_prop( 'user_status', $user_status );
	}

	/**
	 * Set display_name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $display_name User's display_name.
	 */
	public function set_display_name( $display_name ) {
		$this->set_prop( 'display_name', $display_name );
	}

	/**
	 * Set nickname.
	 *
	 * @since 0.1.0
	 *
	 * @param string $nickname User's nickname.
	 */
	public function set_nickname( $nickname ) {
		$this->set_prop( 'nickname', $nickname );
	}

	/**
	 * Set first_name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $first_name User's first_name.
	 */
	public function set_first_name( $first_name ) {
		$this->set_prop( 'first_name', $first_name );
	}

	/**
	 * Set last_name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $last_name User's last_name.
	 */
	public function set_last_name( $last_name ) {
		$this->set_prop( 'last_name', $last_name );
	}

	/**
	 * Set description.
	 *
	 * @since 0.1.0
	 *
	 * @param string $description User's description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set rich_editing.
	 *
	 * @since 0.1.0
	 *
	 * @param string $rich_editing User's rich_editing.
	 */
	public function set_rich_editing( $rich_editing ) {
		$this->set_prop( 'rich_editing', $rich_editing );
	}

	/**
	 * Set syntax_highlighting.
	 *
	 * @since 0.1.0
	 *
	 * @param string $syntax_highlighting User's syntax_highlighting.
	 */
	public function set_syntax_highlighting( $syntax_highlighting ) {
		$this->set_prop( 'syntax_highlighting', $syntax_highlighting );
	}

	/**
	 * Set comment_shortcuts.
	 *
	 * @since 0.1.0
	 *
	 * @param string $comment_shortcuts User's comment_shortcuts.
	 */
	public function set_comment_shortcuts( $comment_shortcuts ) {
		$this->set_prop( 'comment_shortcuts', $comment_shortcuts );
	}

	/**
	 * Set use_ssl.
	 *
	 * @since 0.1.0
	 *
	 * @param string $use_ssl User's use_ssl.
	 */
	public function set_use_ssl( $use_ssl ) {
		$this->set_prop( 'use_ssl', $use_ssl );
	}

	/**
	 * Set show_admin_bar_front.
	 *
	 * @since 0.1.0
	 *
	 * @param string $show_admin_bar_front User's show_admin_bar_front.
	 */
	public function set_show_admin_bar_front( $show_admin_bar_front ) {
		$this->set_prop( 'show_admin_bar_front', $show_admin_bar_front );
	}

	/**
	 * Set locale.
	 *
	 * @since 0.1.0
	 *
	 * @param string $locale User's locale.
	 */
	public function set_locale( $locale ) {
		$this->set_prop( 'locale', $locale );
	}

	/**
	 * Set roles.
	 *
	 * @since 0.1.0
	 *
	 * @param string $roles User's roles.
	 */
	public function set_roles( $roles ) {
		$this->set_prop( 'roles', $roles );
	}

	/**
	 * Set allcaps.
	 *
	 * @since 0.1.0
	 *
	 * @param string $allcaps User's allcaps.
	 */
	public function set_allcaps( $allcaps ) {
		$this->set_prop( 'allcaps', $allcaps );
	}
}
