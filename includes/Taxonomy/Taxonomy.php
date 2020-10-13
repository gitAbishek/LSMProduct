<?php
/**
 * Abstract taxonomy class.
 */

namespace ThemeGrill\Masteriyo\Taxonomy;

abstract class Taxonomy {
	/**
	 * Taxonomy.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $taxonomy;

	/**
	 * An array of labels for this taxonomy. If not set, taxonomy labels are inherited for non-hierarchicals and page labels for hierarchical ones.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $labels;

	/**
	 * Array or string of arguments for registering a taxonomy.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param string $taxonomy Taxonomy taxonomy.
	 * @param array $labels	An array of labels for this taxonomy. If not set, taxonomy labels are inherited for non-hierarchicals and page labels for hierarchical ones.
	 * @param array $args	Array or string of arguments for registering a taxonomy.
	 *
	 * @return Masteriyo\Masteriyo\Taxonomy
	 */
	public function __construct( $taxonomy, $labels = array(), $args = array() ) {
		$this->taxonomy = $taxonomy;
		$this->labels   = array_merge( $this->get_labels(), $labels );
		$this->args     = array_merge( $this->get_args( $this->labels ), $args );

		return $this;
	}

	/**
	 * Register taxonomy.
	 *
	 * @since 0.1.0
	 *
	 * @param string|array $object_type (Required) Object type or array of object types with which the taxonomy should be associated.
	 *
	 * @return void
	 */
	public function register( $object_type = 'post' ) {
		register_taxonomy( $this->taxonomy, $object_type, $this->args );
	}

	/**
	 * Get label.
	 *
	 * @since 0.1.0
	 *
	 * @param string $label Label. (e.g. name, singular_name, menu_name, etc )
	 *
	 * @return mixed|null
	 */
	public function get_label( $label ) {
		if ( isset( $this->labels[ $label ] ) ) {
			return $this->labels[ $label ];
		}

		return null;
	}

	/**
	 * Get label.
	 *
	 * @since 0.1.0
	 *
	 * @param string $arg Arguments. (e.g. label, args, menu_position, etc )
	 *
	 * @return mixed|null
	 */
	public function get_arg( $arg ) {
		if ( isset( $this->args[ $arg ] ) ) {
			return $this->args[ $arg ];
		}

		return null;
	}

	/**
	 * Set label.
	 *
	 * @since 0.1.0
	 *
	 * @param string $label Label. (e.g. name, singular_name, menu_name, etc )
	 * @param string $value Label text/value.
	 * @param bool $strict	Strict check the label.(Default: true)
	 *
	 * @return Masteriyo\Masteriyo\Taxonomy
	 */
	public function set_label( $label, $value, $strict = true ) {
		if ( $strict && ! isset( $this->labels[ $label ] ) ) {
			throw new \Exception( 'Invalid label name.' );
		}

		$this->labels[ $label ] = $value;
		$this->args[ 'labels' ] = $this->labels;
		return $this;
	}

	/**
	 * Set args.
	 *
	 * @since 0.1.0
	 *
	 * @param string $arg Arguments. (e.g. label, args, menu_position, etc )
	 * @param string $value Arguments value.
	 * @param bool $strict	Strict check the label.(Default: true)
	 *
	 * @return Masteriyo\Masteriyo\Taxonomy
	 */
	public function set_arg( $arg, $value, $strict = true ) {
		if ( $strict && ! isset( $this->args[ $arg ] ) ) {
			throw new \Exception( 'Invalid args name.' );
		}

		$this->args[ $arg ] = $value;
		return $this;
	}

	/**
	 * Get labels.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_labels() {
		return array();
	}

	/**
	 * Get args.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_args( $labels ) {
		// phpcs:disable WordPress.WP.I18n.TextDomainMismatch
		return array(
			'labels'             => $labels,
			'description'        => '',
			'hierarchical'       => false,
			'public'             => true,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'show_admin_column'  => true,
			'show_in_nav_menus'  => true,
			'show_in_quick_edit' => true,
			'show_tagcloud'      => true,
		);
		// phpcs:enable
	}
}
