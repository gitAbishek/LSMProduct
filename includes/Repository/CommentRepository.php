<?php
/**
 * Comment Repository class.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Repository;
 */

namespace ThemeGrill\Masteriyo\Repository;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Models\Comment;

/**
 * Comment Repository class.
 */
class CommentRepository extends AbstractRepository implements RepositoryInterface {

    /**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 0.1.0
	 * @var array
	 */
	protected $internal_meta_keys = array();

    /**
     * Create comment in database.
     *
     * @since 0.1.0
     *
     * @param Model $comment Comment object.
     */
    public function create( Model &$comment ) {
        $id = wp_insert_comment(
            apply_filters(
                'masteriyo_new_comment_data',
                array(
                    'comment_author'        => $comment->get_comment_author( 'edit' ),
                    'comment_author_email'  => $comment->get_comment_author_email( 'edit' ),
                    'comment_author_IP'     => $comment->get_comment_author_IP( 'edit' ),
                    'comment_date'          => $comment->get_comment_date( 'edit' ),
                    'comment_date_gmt'      => $comment->get_comment_date_gmt( 'edit' ),
                    'comment_content'       => $comment->get_comment_content( 'edit' ),
                    'comment_karma'         => $comment->get_comment_karma( 'edit' ),
                    'comment_approved'      => $comment->get_comment_approved( 'edit' ),
                    'comment_agent'         => $comment->get_comment_agent( 'edit' ),
                    'comment_type'          => $comment->get_comment_type( 'edit' ),
                    'comment_parent'        => $comment->get_comment_parent( 'edit' ),
                    'user_id'               => $comment->get_user_id( 'edit' ),
                ),
                $comment
            )
        );

        if ( $id && ! is_wp_error( $id ) ) {
			$comment->set_id( $id );
			$comment->apply_changes();

			do_action( 'masteriyo_new_comment', $id, $comment );
		}
    }

    /**
	 * Read a comment.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $comment comment object.
	 *
	 * @throws \Exception If invalid comment.
	 */
    public function read( Model &$comment ) {
        $comment_obj = get_comment( $comment->get_id() );

        if ( ! $comment->get_id() || ! $comment_obj ) {
            throw new \Exception( __( 'Invalid Comment.', 'masteriyo' ) );
        }

        $comment->set_props(
            array(
                'comment_author'       => $comment_obj->comment_author,
                'comment_author_email' => $comment_obj->comment_author_email,
                'comment_author_url'   => $comment_obj->comment_author_url,
                'comment_author_IP'    => $comment_obj->comment_author_IP,
                'comment_date'         => $comment_obj->comment_date,
                'comment_date_gmt'     => $comment_obj->comment_date_gmt,
                'comment_content'      => $comment_obj->comment_content,
                'comment_karma'        => $comment_obj->comment_karma,
                'comment_approved'     => $comment_obj->comment_approved,
                'comment_agent'        => $comment_obj->comment_agent,
                'comment_type'         => $comment_obj->comment_type,
                'comment_parent'       => $comment_obj->comment_parent,
                'user_id'              => $comment_obj->user_id,
            )
        );

        $this->read_comment_data( $comment_obj );
		$this->read_extra_data( $comment );
		$comment->set_object_read( true );

		do_action( 'masteriyo_comment_read', $comment->get_id(), $comment );
    }

    /**
	 * Update a comment in the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $comment comment object.
	 *
	 * @return void
	 */
    public function update( Model &$comment ) {
        $changes = $comment->get_changes();
        
        $comment_data_keys = array(
            'comment_author',
            'comment_author_email',
            'comment_author_url',
            'comment_author_IP',
            'comment_date',
            'comment_date_gmt',
            'comment_content',
            'comment_karma',
            'comment_approved',
            'comment_agent',
            'comment_type',
            'comment_parent',
            'user_id'
        );
        
        // Only update the comment when the comment data changes.
        if ( array_intersect( $comment_data_keys, array_keys( $changes ) ) ) {
            $comment_data = array(
                'comment_author'        => $comment->get_comment_author( 'edit' ),
                'comment_author_email'  => $comment->get_comment_author_email( 'edit' ),
                'comment_author_IP'     => $comment->get_comment_author_IP( 'edit' ),
                'comment_date'          => $comment->get_comment_date( 'edit' ),
                'comment_date_gmt'      => $comment->get_comment_date_gmt( 'edit' ),
                'comment_content'       => $comment->get_comment_content( 'edit' ),
                'comment_karma'         => $comment->get_comment_karma( 'edit' ),
                'comment_approved'      => $comment->get_comment_approved( 'edit' ),
                'comment_agent'         => $comment->get_comment_agent( 'edit' ),
                'comment_type'          => $comment->get_comment_type( 'edit' ),
                'comment_parent'        => $comment->get_comment_parent( 'edit' ),
                'user_id'               => $comment->get_user_id( 'edit' ),
            );
            
            wp_update_comment( array_merge( array( 'comment_ID' => $comment->get_id() ), $comment_data )  );
        }

        $comment->apply_changes();

		do_action( 'masteriyo_update_comment', $comment->get_id(), $comment );
    }

    /**
	 * Delete a comment from the database.
	 *
	 * @since 0.1.0
	 *
	 * @param Model $comment comment object.
	 * @param array $args Array of args to pass.alert-danger.
	 */
    public function delete( Model &$comment, $args = array() ) {
        $id          = $comment->get_id();
		$object_type = $comment->get_object_type();
		$args = array_merge( array(
			'force_delete' => false,
		), $args );

		if ( ! $id ) {
			return;
		}

        if ( $args['force_delete'] ) {
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $comment );
			wp_delete_comment( $id, true );
			$comment->set_id( 0 );
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $comment );
		} else {
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $comment );
			wp_trash_comment( $id );
			$comment->set_status( 'trash' );
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $comment );
		}
    }

    /**
	 * Read comment data. Can be overridden by child classes to load other props.
	 *
	 * @since 0.1.0
	 *
	 * @param User $comment Comment object.
	 */
	protected function read_comment_data( &$comment ) {}

    /**
	 * Read extra data associated with the comment.
	 *
	 * @since 0.1.0
	 *
	 * @param comment $comment comment object.
	 */
	protected function read_extra_data( &$comment ) {
		$meta_values = $this->read_meta( $comment );

		foreach ( $comment->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $comment, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$comment->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}
}