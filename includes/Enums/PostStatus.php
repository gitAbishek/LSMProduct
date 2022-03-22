<?php
/**
 * Post status enums.
 *
 * @since x.x.x
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Post status enum class.
 *
 * @since x.x.x
 */
class PostStatus {
	/**
	 * Post any status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const ANY = 'any';

	/**
	 * Post publish status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const PUBLISH = 'publish';

	/**
	 * Post future status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const FUTURE = 'future';

	/**
	 * Post masteriyo draft status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const DRAFT = 'draft';

	/**
	 * Post pending status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const PENDING = 'pending';

	/**
	 * Post private status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const PVT = 'private';

	/**
	 * Post trash status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const TRASH = 'trash';

	/**
	 * Post auto draft status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const AUTO_DRAFT = 'auto-draft';

	/**
	 * Post inherit status.
	 *
	 * @since x.x.x
	 * @var string
	 */
	const INHERIT = 'inherit';

	/**
	 * Return all the post statuses.
	 *
	 * @since x.x.x
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			apply_filters(
				'masteriyo_post_statuses',
				array(
					self::ANY,
					self::PUBLISH,
					self::FUTURE,
					self::DRAFT,
					self::PENDING,
					self::PVT,
					self::TRASH,
					self::AUTO_DRAFT,
					self::INHERIT,
				)
			)
		);
	}
}
