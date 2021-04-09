<?php
/**
 * Permalink page.
 *
 * Handle to display and store fields in permalink page.
 *
 * @version 0.1.0
 * @package ThemeGrill\Masteriyo\Classes
 */

namespace ThemeGrill\Masteriyo;

use ThemeGrill\Masteriyo\Traits\Singleton;

defined( 'ABSPATH' ) || exit;

/**
 * PermalinkSettings Class.
 */
class PermalinkSettings {
	use Singleton;

	/**
	 * Initialize.
	 *
	 * @since 0.1.0
	 */
	public function init() {
		add_action( 'load-options-permalink.php', array( $this, 'display_taxonomy_permalink_fields' ) );

		add_action( 'init', array( $this, 'settings_save' ) );
	}

	/**
	 * Display permalink fields.
	 *
	 * @since 0.1.0
	 */
	public function display_taxonomy_permalink_fields() {
		add_settings_section(
			'masteriyo-permalink',
			__( 'Course permalinks', 'masteriyo' ),
			array( $this, 'display_course_permalink_structure_settings' ),
			'permalink'
		);

		add_settings_field(
			"masteriyo_course_category_base",
			__( 'Course category base', 'masteriyo' ),
			array( $this, 'display_course_category_base_input' ),
			'permalink',
			'optional'
		);

		add_settings_field(
			'masteriyo_course_tag_base',
			__( 'Course tag base', 'masteriyo' ),
			array( $this, 'display_course_tag_base_input' ),
			'permalink',
			'optional'
		);

		add_settings_field(
			'masteriyo_course_difficulty_base',
			__( 'Course difficulty base', 'masteriyo' ),
			array( $this, 'display_course_difficulty_base_input' ),
			'permalink',
			'optional'
		);
	}

	/**
	 * Display course category base input.
	 *
	 * @since 0.1.0
	 */
	public function display_course_category_base_input() {
		$input = sprintf(
			'<input type="text" class="regular-text code" id="%s"  name="%s" value="%s" />',
			esc_attr__( 'masteriyo_course_category_base'),
			esc_attr__( 'masteriyo_course_category_base'),
			\masteriyo_get_permalink_structure( 'course_category_base' )
		);
		echo $input;
	}

	/**
	 * Display course tag base input.
	 *
	 * @since 0.10
	 */
	public function display_course_tag_base_input() {
		$input = sprintf(
			'<input type="text" class="regular-text code" id="%s"  name="%s" value="%s" />',
			esc_attr__( 'masteriyo_course_tag_base'),
			esc_attr__( 'masteriyo_course_tag_base'),
			\masteriyo_get_permalink_structure( 'course_tag_base' )
		);
		echo $input;
	}

	/**
	 * Display course difficulty input.
	 *
	 * @since 0.10
	 */
	public function display_course_difficulty_base_input() {
		$input = sprintf(
			'<input type="text" class="regular-text code" id="%s"  name="%s" value="%s" />',
			esc_attr__( 'masteriyo_course_difficulty_base'),
			esc_attr__( 'masteriyo_course_difficulty_base'),
			\masteriyo_get_permalink_structure( 'course_difficulty_base' )
		);
		echo $input;
	}

	public function settings_save() {
		if ( ! is_admin() ) {
			return;
		}

		if ( ! isset( $_POST['masteriyo-permalinks-nonce'] ) ) {
			return;
		}

		if ( ! \wp_verify_nonce( wp_unslash( $_POST['masteriyo-permalinks-nonce'] ), 'masteriyo-permalinks' ) ) {
			return;
		}

		// Bail early if the there is no data in $_POST.
		if ( ! isset(
			$_POST['masteriyo_course_category_base' ],
			$_POST['masteriyo_course_tag_base' ],
			$_POST['masteriyo_course_difficulty_base' ]
		) ) return;

		\masteriyo_switch_to_site_locale();

		$permalinks['course_category_base']   = masteriyo_sanitize_permalink( wp_unslash( $_POST['masteriyo_course_category_base'] ) );
		$permalinks['course_tag_base']        = masteriyo_sanitize_permalink( wp_unslash( $_POST['masteriyo_course_tag_base'] ) );
		$permalinks['course_difficulty_base'] = masteriyo_sanitize_permalink( wp_unslash( $_POST['masteriyo_course_difficulty_base'] ) );
		            $course_base              = isset( $_POST['course_permalink'] ) ? masteriyo_clean( wp_unslash( $_POST['course_permalink'] ) ) : '';

		if ( 'custom' === $course_base ) {
			if ( isset( $_POST['course_permalink_structure'] ) ) {
				$course_base = preg_replace( '#/+#', '/', '/' . str_replace( '#', '', trim( wp_unslash( $_POST['course_permalink_structure'] ) ) ) );
			} else {
				$course_base = '/';
			}

			// This is an invalid base structure and breaks pages.
			if ( '/%course_cat%/' === trailingslashit( $course_base ) ) {
				$course_base = '/' . _x( 'course', 'slug', 'masteriyo' ) . $course_base;
			}
		} elseif ( empty( $course_base ) ) {
			$course_base = _x( 'course', 'slug', 'masteriyo' );
		}

		$permalinks['course_base'] = masteriyo_sanitize_permalink( $course_base );

		// course_list base may require verbose page rules if nesting pages.
		$course_list_page_id   = masteriyo_get_page_id( course_list );
		$course_list_permalink = ( $course_list_page_id > 0 && get_post( $course_list_page_id ) ) ? get_page_uri( $course_list_page_id ) : _x( 'course-list', 'default-slug', 'masteriyo' );

		if ( $course_list_page_id && stristr( trim( $permalinks['course_base'], '/' ), $course_list_permalink ) ) {
			$permalinks['use_verbose_page_rules'] = true;
		}


		$saved_permalinks = masteriyo_get_permalink_structure();
		$permalinks       = wp_parse_args( $permalinks, $saved_permalinks );

		update_option( 'masteriyo_permalinks', $permalinks );
		\masteriyo_restore_locale();
	}

	public function display_course_permalink_structure_settings() {
		/* translators: %s: Home URL */
		echo wp_kses_post( wpautop( sprintf( __( 'If you like, you may enter custom structures for your course URLs here. For example, using <code>course-list</code> would make your course links like <code>%scourse-list/sample-course/</code>. This setting affects course URLs only, not things such as course categories.', 'masteriyo' ), esc_url( home_url( '/' ) ) ) ) );

		$course_list_page_id = masteriyo_get_page_id( 'course_list' );
		$base_slug            = urldecode( ( $course_list_page_id > 0 && get_post( $course_list_page_id ) ) ? get_page_uri( $course_list_page_id ) : _x( 'Courses list', 'default-base', 'masteriyo' ) );
		$course_base          = _x( 'course', 'default-base', 'masteriyo' );

		$saved_course_base = trailingslashit( \masteriyo_get_permalink_structure( 'course_base' ) );
		$structures = array(
			0 => '',
			1 => '/' . trailingslashit( $base_slug ),
			2 => '/' . trailingslashit( $base_slug ) . trailingslashit( '%course_cat%' ),
		);
		?>
		<table class="form-table masteriyo-permalink-structure">
			<tbody>
				<tr>
					<th>
						<label>
							<input name="course_permalink" type="radio"
								class="masteriyotog"
								value="<?php echo esc_attr( $structures[0] ); ?>"
								<?php checked( $structures[0], $saved_course_base ); ?> />
							<?php esc_html_e( 'Default', 'masteriyo' ); ?>
						</label>
					</th>
					<td>
						<code class="default-example">
							<?php echo esc_html( home_url() ); ?>/?course=sample-course
						</code>
						<code class="non-default-example"><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $course_base ); ?>/sample-course/
						</code>
					</td>
				</tr>
				<?php if ( $course_list_page_id ) : ?>
					<tr>
						<th>
							<label>
								<input name="course_permalink" type="radio" class="masteriyotog"
									value="<?php echo esc_attr( $structures[1] ); ?>"
									<?php checked( $structures[1], $saved_course_base ); ?> />
								<?php esc_html_e( 'Course list base', 'masteriyo' ); ?>
							</label>
						</th>
						<td>
							<code>
								<?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $base_slug ); ?>/sample-course/
							</code>
						</td>
					</tr>
					<tr>
						<th>
							<label>
								<input name="course_permalink" type="radio" class="masteriyotog"
									value="<?php echo esc_attr( $structures[2] ); ?>"
									<?php checked( $structures[2], $saved_course_base ); ?> />
								<?php esc_html_e( 'Course list base with category', 'masteriyo' ); ?>
							</label>
						</th>
						<td>
							<code>
								<?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $base_slug ); ?>/course-category/sample-course/
							</code>
						</td>
					</tr>
				<?php endif; ?>
				<tr>
					<th>
						<label>
							<input name="course_permalink" id="masteriyo_custom_selection"
								type="radio" value="custom" class="tog"
								<?php checked( in_array( $saved_course_base, $structures, true ), false ); ?> />
							<?php esc_html_e( 'Custom base', 'masteriyo' ); ?>
						</label>
					</th>
					<td>
						<input name="course_permalink_structure"
							id="masteriyo_permalink_structure" type="text"
							value="<?php echo esc_attr( $saved_course_base ? trailingslashit( $saved_course_base ) : '' ); ?>"
							class="regular-text code" />
						<span class="description">
							<?php esc_html_e( 'Enter a custom base to use. A base must be set or WordPress will use default instead.', 'masteriyo' ); ?>
						</span>
					</td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field( 'masteriyo-permalinks', 'masteriyo-permalinks-nonce' ); ?>
		<?php

	}
}


