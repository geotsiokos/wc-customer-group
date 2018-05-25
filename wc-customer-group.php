<?php
/**
 * wc-customer-group.php
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * This header and all notices must be kept intact.
 *
 * @author gtsiokos
 * @package wc-customer-group
 * @since 1.0.0
 *
 * Plugin Name: WooCommerce Customer Group
 * Description: Displays the Customer Group in the new order email notification
 * Version: 1.0.0
 * Author: George Tsiokos
 * Author URI: http://www.netpad.gr
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'woocommerce_email_order_meta_fields', 'custom_woocommerce_email_order_meta_fields', 10, 3 );

/**
 * Adds customer groups in mail notifications
 *
 * @param array $fields
 * @param bool $sent_to_admin
 * @param WC_Order $order
 * @return array
 */
function custom_woocommerce_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
	$customer_id = $order->get_customer_id();
	if ( $customer = get_user_by( 'ID', $customer_id ) ) {
		$user_groups = get_user_groups( $customer_id );
	    $fields['meta_key'] = array(
	        'label' => __( 'Customer Group(s)' ),
	        'value' => $user_groups,
	    );
	}
    return $fields;
}

/**
 * Helper function that returns user groups
 *
 * @param int $user_id
 * @return string
 */
function get_user_groups ( $user_id ) {
	$group_names = '';
	if ( class_exists( 'Groups_User' ) ) {
		$groups_user = new Groups_User( $user_id );
		// get group objects
		$user_groups = $groups_user->groups;
		if ( is_array( $user_groups ) && count( $user_groups ) > 0 ) {
			foreach ( $user_groups as $user_group ) {
				$group_names .= $user_group->name;
				$group_names .= ' ';
			}
		}
	}
	
	return $group_names;
}