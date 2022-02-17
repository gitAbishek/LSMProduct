<?php
/**
 * Checkout Ajax handler.
 *
 * @since x.x.x
 * @package Masteriyo\AjaxHandlers
 */

namespace Masteriyo\AjaxHandlers;

use Masteriyo\Abstracts\AjaxHandler;

/**
 * Checkout ajax handler.
 */
class CheckoutAjaxHandler extends AjaxHandler {

	/**
	 * Checkout ajax action.
	 *
	 * @since x.x.x
	 * @var string
	 */
	public $action = 'masteriyo_checkout';

	/**
	 * Process checkout ajax request.
	 *
	 * @since x.x.x
	 */
	public function register() {
		add_action( "wp_ajax_{$this->action}", array( $this, 'checkout' ) );
	}

	/**
	 * Process ajax checkout form.
	 *
	 * @since x.x.x
	 */
	public function checkout() {
		masteriyo_maybe_define_constant( 'MASTERIYO_CHECKOUT', true );
		masteriyo( 'checkout' )->process_checkout();
		wp_die( 0 );
	}
}
