<?php
/**
 * Abstract post type class.
 */

namespace Masteriyo\PostType;

class PostType {
	/**
	 * Post slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * An array of labels for this post type. If not set, post labels are inherited for non-hierarchical types and page labels for hierarchical ones.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $labels = array();

	/**
	 * Array or string of arguments for registering a post type.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $args = array();

	/**
	 * Register post type.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register() {
		register_post_type( $this->slug, $this->args );
	}

	/**
	 * Get label.
	 *
	 * @since 1.0.0
	 *
	 * @param string $label Label. (e.g. name, singular_name, menu_name, etc )
	 *
	 * @return mixed|null
	 */
	public function get_label( $lable ) {
		if ( isset( $this->labels[ $label ] ) ) {
			return $this->labels[ $label ];
		}

		return null;
	}

	/**
	 * Get label.
	 *
	 * @since 1.0.0
	 *
	 * @param string $arg Arguments. (e.g. label, supports, menu_position, etc )
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
	 * @since 1.0.0
	 *
	 * @param string $label Label. (e.g. name, singular_name, menu_name, etc )
	 * @param string $value Label text/value.
	 *
	 * @return Masteriyo\Masteriyo\PostType
	 */
	public function set_label( $label, $value ) {
		$this->labels[ $label ] = $value;
		$this->args['labels']   = $this->labels;
		return $this;
	}

	/**
	 * Set arg.
	 *
	 * @since 1.0.0
	 *
	 * @param string $arg Arguments. (e.g. label, supports, menu_position, etc )
	 * @param string $value Arguments value.
	 *
	 * @return Masteriyo\Masteriyo\PostType
	 */
	public function set_arg( $arg, $value ) {
		$this->args[ $arg ] = $value;
		return $this;
	}

	/**
	 * Get labels.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_labels() {
		return $this->labels;
	}

	/**
	 * Get args.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_args() {
		return $this->args;
	}
}
