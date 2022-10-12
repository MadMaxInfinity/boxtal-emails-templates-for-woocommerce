<?php
/**
 * Email Addresses (plain)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails\Plain
 * @version 5.6.0
 */

defined( 'ABSPATH' ) || exit;

echo "\n" . esc_html( wc_strtoupper( esc_html__( 'Billing address', 'woocommerce' ) ) ) . "\n\n";
echo preg_replace( '#<br\s*/?>#i', "\n", $order->get_formatted_billing_address() ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

if ( $order->get_billing_phone() ) {
	echo $order->get_billing_phone() . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

if ( $order->get_billing_email() ) {
	echo $order->get_billing_email() . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) {
	$shipping = '';
	// If boxtal was used, set choosen parcelpoint as shipping address
	if ( $order->has_shipping_method( 'boxtal_connect' ) && defined( 'BOXTAL_CONNECT_VERSION' ) ) {
		global $plugin;
		$object = new Boxtal\BoxtalConnectWoocommerce\Util\Order_Util ( $plugin );
		$parcelpoint = $object->get_parcelpoint( $order );
		if ( null !== $parcelpoint ) {
			$has_address = null !== $parcelpoint->name
				&& null !== $parcelpoint->address
				&& null !== $parcelpoint->zipcode
				&& null !== $parcelpoint->city
				&& null !== $parcelpoint->country;

			if ( $has_address ) {
				$shipping = $parcelpoint->name . '<br>' . $parcelpoint->address . '<br>' . $parcelpoint->zipcode .'&nbsp;' . $parcelpoint->city . '<br>' . $parcelpoint->country;
			}
		}
	}
	else {
		$shipping = $order->get_formatted_shipping_address();
	}
	if ( $shipping ) {
		echo "\n" . esc_html( wc_strtoupper( esc_html__( 'Shipping address', 'woocommerce' ) ) ) . "\n\n";
		echo preg_replace( '#<br\s*/?>#i', "\n", $shipping ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $order->get_shipping_phone() ) {
			echo $order->get_shipping_phone() . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}
