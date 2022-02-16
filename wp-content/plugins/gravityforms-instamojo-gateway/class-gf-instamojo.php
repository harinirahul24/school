<?php

add_action( 'wp', array( 'GF_Instamojo', 'maybe_thankyou_page' ), 5 );

GFForms::include_payment_addon_framework();

class GF_Instamojo extends GFPaymentAddOn {

	protected $_version = '1.0.0';
	protected $_min_gravityforms_version = '2.3.0';
	protected $_slug = 'instamojo';
	protected $_full_path = __FILE__;
	protected $_title = 'Instamojo Add-On';
	protected $_short_title = 'Instamojo';
	protected $_supports_callbacks = false;

	private $production_url = 'https://www.instamojo.com/api/1.1/';
	private $sandbox_url = 'https://test.instamojo.com/api/1.1/';

	private $post_data = array();
	private $gateway_error;
	protected $_api;

	// Members plugin integration
	protected $_capabilities = array( 'gravityforms_instamojo', 'gravityforms_instamojo_uninstall' );

	// Permissions
	protected $_capabilities_settings_page = 'gravityforms_instamojo';
	protected $_capabilities_form_settings = 'gravityforms_instamojo';
	protected $_capabilities_uninstall = 'gravityforms_instamojo_uninstall';

	private static $_instance = null;

	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new GF_Instamojo();
		}
		return self::$_instance;
	}

	public function init_frontend() {
		parent::init_frontend();
		add_filter( 'gform_disable_post_creation', array( $this, 'delay_post' ), 10, 3 );
	}

	public function delay_post( $is_disabled, $form, $entry ) {
		$feed            = $this->get_payment_feed( $entry );
		$submission_data = $this->get_submission_data( $feed, $form, $entry );

		if ( ! $feed || empty( $submission_data['payment_amount'] ) ) {
			return $is_disabled;
		}
		return ! rgempty( 'delayPost', $feed['meta'] );
	}

	private function __clone() {
	} /* do nothing */

	public function feed_list_message() {

		if ( GFCommon::get_currency() != 'INR' ) {
			$settings_link  = add_query_arg( array( 'page' => 'gf_settings', 'subview' => 'settings' ), admin_url( 'admin.php' ) );
			return sprintf( __( 'Instamojo supports only INR currency. %1$s Click here%2$s to navigate to the main settings page and change it.', 'gf-instamojo' ), '<a href="' . $settings_link . '">', '</a>' );
		}

		return parent::feed_list_message();
	}

	public function feed_settings_fields() {
		$default_settings = parent::feed_settings_fields();
		$form = $this->get_current_form();

		// Add Instamojo authentication settings
		$gateway_settings = array(
			array(
				'name'          => 'mode',
				'label'         => esc_html__( 'Mode', 'gf-instamojo' ),
				'type'          => 'radio',
				'choices'       => array(
					array( 'id' => 'gf_instamojo_mode_production', 'label' => esc_html__( 'Production', 'gf-instamojo' ), 'value' => 'production' ),
					array( 'id' => 'gf_instamojo_mode_test', 'label' => esc_html__( 'Sandbox', 'gf-instamojo' ), 'value' => 'test' ),
				),
				'horizontal'    => true,
				'default_value' => 'production',
				'tooltip'       => '<h6>' . esc_html__( 'Mode', 'gf-instamojo' ) . '</h6>' . esc_html__( 'Select Production to receive live payments. Select Sandbox for testing purposes when using the Instamojo development sandbox environment at <a href="https://test.instamojo.com" target="_blank">https://test.instamojo.com</a>.', 'gf-instamojo' )
			),
			array(
				'name'     => 'instamojoApiKey',
				'label'    => esc_html__( 'API Key', 'gf-instamojo' ),
				'type'     => 'text',
				'class'    => 'medium',
				'required' => true,
				'tooltip'  => '<h6>' . esc_html__( 'API Key', 'gf-instamojo' ) . '</h6>' . esc_html__( 'Enter the API Key provided by Instamojo.', 'gf-instamojo' )
			),
			array(
				'name'     => 'instamojoAuthToken',
				'label'    => esc_html__( 'Auth Token', 'gf-instamojo' ),
				'type'     => 'text',
				'class'    => 'medium',
				'required' => true,
				'tooltip'  => '<h6>' . esc_html__( 'Auth Token', 'gf-instamojo' ) . '</h6>' . esc_html__( 'Enter the API Key provided by Instamojo.', 'gf-instamojo' )
			),
		);
		$default_settings = parent::add_field_after( 'feedName', $gateway_settings, $default_settings );

		// Neglect subscription type
		$transaction_type = parent::get_field( 'transactionType', $default_settings );
		$choices = $transaction_type['choices'];
		$subscription = array_search( 'subscription', array_column( $choices, 'value' ) );

		if( $subscription !== false ) {
			unset( $choices[$subscription] );
		}
		$transaction_type['choices'] = $choices;
		$default_settings = $this->replace_field( 'transactionType', $transaction_type, $default_settings );

		// Add post fields if form has a post
		if ( GFCommon::has_post_field( $form['fields'] ) ) {
			$post_settings = array(
				'name'    => 'post_checkboxes',
				'label'   => esc_html__( 'Posts', 'gf-instamojo' ),
				'type'    => 'checkbox',
				'tooltip' => '<h6>' . esc_html__( 'Posts', 'gf-instamojo' ) . '</h6>' . esc_html__( 'Enable this option if you would like to only create the post after payment has been received.', 'gf-instamojo' ),
				'choices' => array(
					array( 'label' => esc_html__( 'Create post only when payment is received.', 'gf-instamojo' ), 'name' => 'delayPost' ),
				),
			);
			$default_settings = $this->add_field_after( 'billingInformation', $post_settings, $default_settings );
		}

		// Add customer notification options.
		$notification_options = array(
			array(
				'name'		=> 'paymentLink',
				'label' 	=> esc_html__( 'Send Payment Link By', 'gf-instamojo' ),
				'type'		=> 'select',
				'choices' 	=> array(

					array(
						'label' => esc_html__( 'Do not send', 'gf-instamojo' ),
						'value'  => '',
					),
					array(
						'label' => esc_html__( 'Email', 'gf-instamojo' ),
						'value'  => 'email',
					),
					array(
						'label' => esc_html__( 'SMS', 'gf-instamojo' ),
						'value'  => 'sms',
					),
					array(
						'label' => esc_html__( 'Email and SMS', 'gf-instamojo' ),
						'value'  => 'email-sms',
					),
				),
				'tooltip'	=> '<h6>' . esc_html__( 'Send Payment Link By', 'gf-instamojo' ) . '</h6>' . esc_html__( 'Send the Instamojo payment link via email and/or SMS to the customer. Please make sure the email address or the phone field is mandatory in your form to send email or SMS respectively otherwise Instamojo will return an error.', 'gf-instamojo' ),
			),

		);
		$default_settings = $this->add_field_after( 'billingInformation', $notification_options, $default_settings );

		// Prepare customer information fields.
		$customer_info_field = array(
			'name'       => 'customerInformation',
			'label'      => esc_html__( 'Customer Information', 'gf-instamojo' ),
			'type'       => 'field_map',
			'field_map'  => array(
				array(
					'name'     	=> 'buyer_name',
					'label'    	=> esc_html__( 'Buyer Name', 'gf-instamojo' ),
					'required' 	=> false,
				),
				array(
					'name'      => 'phone',
					'label'     => esc_html__( 'Phone', 'gf-instamojo' ),
					'required'  => false,
					'validation_callback' => array( $this, 'validate_payment_link_notification' ),
				),
				array(
					'name'      => 'email',
					'label'     => esc_html__( 'Email', 'gf-instamojo' ),
					'required'  => false,
					'validation_callback' => array( $this, 'validate_payment_link_notification' ),
				),
				array(
					'name'      => 'purpose',
					'label'     => esc_html__( 'Purpose', 'gf-instamojo' ),
					'tooltip'	=> '<h6>' . esc_html__( 'Purpose', 'gf-instamojo' ) . '</h6>' . esc_html__( 'The purpose of payment. Defaults to form name by the plugin.', 'gf-instamojo' ),
				),
			),
		);
		// Replace default billing information fields with customer information fields.
		$default_settings = $this->replace_field( 'billingInformation', $customer_info_field, $default_settings );

		/**
		 * Filter through the feed settings fields for the Instamojo feed
		 *
		 * @param array $default_settings The Default feed settings
		 * @param array $form The Form object to filter through
		 */
		return apply_filters( 'gform_instamojo_feed_settings_fields', $default_settings, $form );
	}

	public function field_map_title() {
		return esc_html__( 'Instamojo Field', 'gf-instamojo' );
	}

	/**
	 * Prevent the GFPaymentAddOn version of the options field being added to the feed settings.
	 *
	 * @return bool
	 */
	public function option_choices() {
		return false;
	}

	//------ SENDING TO Instamojo -----------//

	public function redirect_url( $feed, $submission_data, $form, $entry ) {

		//updating lead's payment_status to Processing
		GFAPI::update_entry_property( $entry['id'], 'payment_status', 'Processing' );
		GFAPI::update_entry_property( $entry['id'], 'payment_amount', $submission_data['payment_amount'] );

		gform_update_meta( $entry['id'], 'payment_request_id', rgar( $submission_data, 'payment_request_id' ) );
		gform_update_meta( $entry['id'], 'payment_url', rgar( $submission_data, 'payment_url' ) );
		gform_update_meta( $entry['id'], 'post_data', rgar( $submission_data, 'post_data' ) );

		$payment_url = rgar( $submission_data, 'payment_url' );

		return $payment_url;

	}

	// Validating form details and process payment request
	public function validation( $validation_result ) {
		$validation_result = parent::validation( $validation_result );
		$submission_data = $this->current_submission_data;

		if( empty( $submission_data ) ) {
			return $validation_result;
		}

		$this->log_debug( __METHOD__ . '(): Instamojo processing commences.' );

		$form = $validation_result['form'];
		$feed = $this->current_feed;
		$entry = $submission_data['entry'];

		$purpose = $this->get_field_value( $form, $entry, rgars( $feed, 'meta/customerInformation_purpose' ) );

		$this->post_data['amount'] = $submission_data['payment_amount'];
		$this->post_data['purpose'] = $purpose ? $purpose : urlencode( $form['title'] );
		$this->post_data['buyer_name'] = $this->get_field_value( $form, $entry, rgars( $feed, 'meta/customerInformation_buyer_name' ) );
		$this->post_data['email'] = $this->get_field_value( $form, $entry, rgars( $feed, 'meta/customerInformation_email' ) );
		$this->post_data['phone'] = $this->get_field_value( $form, $entry, rgars( $feed, 'meta/customerInformation_phone' ) );
		$this->post_data['redirect_url'] = $this->return_url( $form['id'], $entry['id'] );
		$this->post_data['allow_repeated_payments'] = false;
		$this->post_data['send_email'] = strpos( rgars( $feed, 'meta/paymentlink' ), 'email' ) !== false;
		$this->post_data['send_sms'] = strpos( rgars( $feed, 'meta/paymentlink' ), 'sms' ) !== false;

		$this->get_instamojo_api( $feed );

		try {

			$response = $this->_api->paymentRequestCreate( $this->post_data );

			$this->log_debug( __METHOD__ . "(): Sending to Instamojo: {$response['longurl']}" );

			$this->current_submission_data['payment_url'] = gf_apply_filters( 'gform_instamojo_request', $form['id'], $response['longurl'], $form, $entry, $feed, $submission_data );
			$this->current_submission_data['post_data'] = $this->post_data;
			$this->current_submission_data['payment_request_id'] = $response['id'];

		} catch( Exception $e ) {
			$errors = json_decode( $e->getMessage(), true );
			$field_errors = array();
			$this->log_error( __METHOD__ . '(): NOT sending to Instamojo. Reason: ' . $e->getMessage() );

			$validation_result['is_valid'] = false;
			if( rgar( $errors, 'non_field_errors' ) ) {
				$this->gateway_error = __( 'There was an error processing your details. Please contact the site administrator.', 'gf-instamojo' );
			} else {
				foreach( $errors as $error_key => $messages ) {
					if( $field_id = rgars( $feed, 'meta/customerInformation_' . $error_key ) ) {
						$field_errors[floor( $field_id )] = $messages[0];
					}
				}
				if( rgar( $errors, 'amount' ) ) {
					$total_field_id = $this->get_payment_total_field_id( $form, $feed );
					$field_errors[floor( $total_field_id )] = rgars( $errors, 'amount/0' );
				}
				foreach ( $validation_result['form']['fields'] as &$field ) {
					if( array_key_exists( $field->id, $field_errors ) ) {
						$field->failed_validation  = true;
						$field->validation_message = $field_errors[$field->id];
					}
				}
			}

			add_filter( 'gform_validation_message_' . $form['id'], array( $this, 'get_validation_message' ), 10, 2 );
		}

		return $validation_result;
	}

	public function get_validation_message( $message, $form ) {
		return $this->gateway_error ? "<div class='validation_error'>" . esc_html__( 'Gateway Error:', 'gf-instamojo' ) . ' ' . $this->gateway_error . '</div>' : $message;
	}

	public function get_submission_data( $feed, $form, $entry ) {
		$submission_data = parent::get_submission_data( $feed, $form, $entry );
		$submission_data['entry'] = $entry;
		return $submission_data;
	}

	public function get_instamojo_api( $feed ) {
		// If Instamojo class does not exist, load Instamojo API library.
		if ( ! class_exists( '\Instamojo\Instamojo' ) ) {
			require_once( $this->get_base_path() . '/api/src/Instamojo.php' );
		}

		$url = rgars( $feed, 'meta/mode' ) == 'production' ? $this->production_url : $this->sandbox_url;

		// Set Instamojo API key.
		$this->_api = new \Instamojo\Instamojo( rgars( $feed, 'meta/instamojoApiKey' ), rgars( $feed, 'meta/instamojoAuthToken' ), $url );

		/**
		 * Run post Instamojo API initialization action.
		 *
		 * @since 2.0.10
		 */
		do_action( 'gform_instamojo_post_include_api' );
	}

	public function get_instamojo_feed_id( $form_id ) {
		$feeds = $this->get_feeds( $form_id );

		if ( ! $feeds ) {
			return false;
		}
		$active_feed = false;
		foreach ( $feeds as $feed ) {
			if ( isset( $feed['addon_slug'] ) && $feed['addon_slug'] == $this->_slug && $feed['is_active'] ) {
				$active_feed = $feed['id'];
			}
		}

		return $active_feed;
	}

	public function return_url( $form_id, $lead_id, $type = false ) {
		$pageURL = GFCommon::is_ssl() ? 'https://' : 'http://';

		$server_port = apply_filters( 'gform_instamojo_return_url_port', $_SERVER['SERVER_PORT'] );

		if ( $server_port != '80' ) {
			$pageURL .= $_SERVER['SERVER_NAME'] . ':' . $server_port . $_SERVER['REQUEST_URI'];
		} else {
			$pageURL .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		}

		if( $type == 'cancel' ) {
			$url = remove_query_arg( 'gf_instamojo_return', $pageURL );
			return apply_filters( 'gform_instamojo_cancel_url', $url, $form_id, $lead_id );
		}

		$ids_query = "ids={$form_id}|{$lead_id}";
		$ids_query .= '&hash=' . wp_hash( $ids_query );

		$url = add_query_arg( 'gf_instamojo_return', base64_encode( $ids_query ), $pageURL );

		$query = 'gf_instamojo_return=' . base64_encode( $ids_query );
		/**
		 * Filters Instamojo's return URL, which is the URL that users will be sent to after completing the payment on Instamojo's site.
		 * Useful when URL isn't created correctly (could happen on some server configurations using PROXY servers).
		 *
		 * @since 2.4.5
		 *
		 * @param string  $url 	The URL to be filtered.
		 * @param int $form_id	The ID of the form being submitted.
		 * @param int $entry_id	The ID of the entry that was just created.
		 * @param string $query	The query string portion of the URL.
		 */
		return apply_filters( 'gform_instamojo_return_url', $url, $form_id, $lead_id, $query );

	}

	public static function maybe_thankyou_page() {
		$instance = self::get_instance();

		if ( ! $instance->is_gravityforms_supported() ) {
			return;
		}

		if ( $str = rgget( 'gf_instamojo_return' ) ) {
			$str = base64_decode( $str );
			$instance->log_debug( __METHOD__ . '(): callback request received. Starting to process => ' . print_r( $_GET, true ) );

			parse_str( $str, $query );
			$result = $error = false;

			if ( wp_hash( 'ids=' . $query['ids'] ) != $query['hash'] ) {
				$instance->log_error( __METHOD__ . '(): Callback request invalid. Aborting.' );
				return false;
			} else {
				list( $form_id, $lead_id ) = explode( '|', $query['ids'] );

				$form = GFAPI::get_form( $form_id );
				if( !$lead_id ) {
					$lead_id = $instance->get_entry_id_by_request();
				}
				$entry = GFAPI::get_entry( $lead_id );

				$callback_action = $instance->instamojo_callback( $form, $entry );

				$instance->log_debug( __METHOD__ . '(): Result from gateway callback => ' . print_r( $callback_action, true ) );
			}

			if ( is_wp_error( $callback_action ) ) {
				$error = $instance->authorization_error( $callback_action->get_error_message() );
			} elseif ( $callback_action && is_array( $callback_action ) && rgar( $callback_action, 'type' ) && ! rgar( $callback_action, 'abort_callback' ) ) {

				$result = $instance->instamojo_process_callback_action( $callback_action );

				$instance->log_debug( __METHOD__ . '(): Result of callback action => ' . print_r( $result, true ) );

				if ( is_wp_error( $result ) ) {
					$error = $instance->authorization_error( $result->get_error_message() );
				} elseif ( ! $result ) {
					$error = $instance->authorization_error( __( 'Unable to validate your payment, please try again.', 'gf-instamojo' ) );
				} elseif ( rgar( $callback_action, 'type' ) != 'complete_payment' ) {
					$error = $instance->authorization_error( rgpost( 'error_Message' ) );
				} else {
					$instance->instamojo_post_callback( $callback_action, $result );
				}
			} else {
				$error = $instance->authorization_error( __( 'Unable to validate your payment, please try again.', 'gf-instamojo' ) );
			}

			if ( ! class_exists( 'GFFormDisplay' ) ) {
				require_once( GFCommon::get_base_path() . '/form_display.php' );
			}

			if( $error ) {
				$amount = isset( $callback_action['amount'] ) ? $callback_action['amount'] : $entry['payment_amount'];

				$cancel_url = $instance->return_url( $form_id, $lead_id, 'cancel' );

				ob_start();
				?>
				<div class="gform_wrapper">
					<p><?php printf( __( 'Amount: %s', 'gf-instamojo' ), GFCommon::to_money( $amount, $entry['currency'] ) ); ?></p>
					<div class="validation_error"><?php echo $error['error_message']; ?></div>
					<div class="gform_footer top_label">
           				<input class="gform_button button" value="<?php _e( 'Cancel', 'gf-instamojo' ); ?>" type="button" onclick="location.href='<?php echo $cancel_url; ?>';" />
        			</div>
				</div>
				<?php $confirmation = ob_get_clean();
				GFFormDisplay::$submission[ $form_id ] = array( 'is_confirmation' => true, 'confirmation_message' => $confirmation, 'form' => $form, 'lead' => $entry );
				return;
			}

			$confirmation = GFFormDisplay::handle_confirmation( $form, $entry, false );

			if ( is_array( $confirmation ) && isset( $confirmation['redirect'] ) ) {
				header( "Location: {$confirmation['redirect']}" );
				exit;
			}

			GFFormDisplay::$submission[ $form_id ] = array( 'is_confirmation' => true, 'confirmation_message' => $confirmation, 'form' => $form, 'lead' => $entry );

		}
	}

	private function instamojo_process_callback_action( $action ) {
		$this->log_debug( __METHOD__ . '(): Processing callback action.' );
		$action = wp_parse_args(
			$action, array(
				'type'             => false,
				'amount'           => false,
				'transaction_type' => false,
				'transaction_id'   => false,
				'subscription_id'  => false,
				'entry_id'         => false,
				'payment_status'   => false,
				'note'             => false,
			)
		);

		$result = false;

		if ( rgar( $action, 'id' ) && $this->is_duplicate_callback( $action['id'] ) ) {
			return new WP_Error( 'duplicate', sprintf( esc_html__( 'This webhook has already been processed (Event Id: %s)', 'gravityforms' ), $action['id'] ) );
		}

		$entry = GFAPI::get_entry( $action['entry_id'] );
		if ( ! $entry || is_wp_error( $entry ) ) {
			return $result;
		}

		/**
		 * Performs actions before the the payment action callback is processed.
		 *
		 * @since Unknown
		 *
		 * @param array $action The action array.
		 * @param array $entry  The Entry Object.
		 */
		do_action( 'gform_action_pre_payment_callback', $action, $entry );
		if ( has_filter( 'gform_action_pre_payment_callback' ) ) {
			$this->log_debug( __METHOD__ . '(): Executing functions hooked to gform_action_pre_payment_callback.' );
		}

		switch ( $action['type'] ) {
			case 'complete_payment':
				$result = $this->complete_payment( $entry, $action );
				break;
			default:
				// Handle custom events.
				if ( is_callable( array( $this, rgar( $action, 'callback' ) ) ) ) {
					$result = call_user_func_array( array( $this, $action['callback'] ), array( $entry, $action ) );
				}
				break;
		}

		if ( rgar( $action, 'id' ) && $result ) {
			$this->register_callback( $action['id'], $action['entry_id'] );
		}

		/**
		 * Fires right after the payment callback.
		 *
		 * @since Unknown
		 *
		 * @param array $entry The Entry Object
		 * @param array $action {
		 *     The action performed.
		 *
		 *     @type string $type             The callback action type. Required.
		 *     @type string $transaction_id   The transaction ID to perform the action on. Required if the action is a payment.
		 *     @type string $amount           The transaction amount. Typically required.
		 *     @type int    $entry_id         The ID of the entry associated with the action. Typically required.
		 *     @type string $transaction_type The transaction type to process this action as. Optional.
		 *     @type string $payment_status   The payment status to set the payment to. Optional.
		 *     @type string $note             The note to associate with this payment action. Optional.
		 * }
		 * @param mixed $result The Result Object.
		 */
		do_action( 'gform_post_payment_callback', $entry, $action, $result );
		if ( has_filter( 'gform_post_payment_callback' ) ) {
			$this->log_debug( __METHOD__ . '(): Executing functions hooked to gform_post_payment_callback.' );
		}

		return $result;
	}

	public function get_entry_id_by_request() {
		global $wpdb;

		$entry_meta_table_name = self::get_entry_meta_table_name();

		$payment_request_id = rgget( 'payment_request_id' );
		$sql      = $wpdb->prepare( "SELECT entry_id FROM {$entry_meta_table_name} WHERE meta_key = 'payment_request_id' AND meta_value = '%s'", $payment_request_id );
		$entry_id = $wpdb->get_var( $sql );

		if ( ! $entry_id ) {
			$sql      = $wpdb->prepare( "SELECT lead_id FROM {$entry_meta_table_name} WHERE meta_key = 'payment_request_id' AND meta_value = '%s'", $payment_request_id );
			$entry_id = $wpdb->get_var( $sql );
		}

		return $entry_id ? $entry_id : false;

	}

	//------- PROCESSING Instamojo Callback -----------//
	public function instamojo_callback( $form, $entry ) {

		if ( ! $entry || $entry['form_id'] != $form['id'] ) {
			$this->log_error( __METHOD__ . '(): Entry could not be found. Aborting.' );

			return false;
		}

		$this->log_debug( __METHOD__ . '(): Entry has been found => ' . print_r( $entry, true ) );

		if ( $entry['status'] == 'spam' ) {
			$this->log_error( __METHOD__ . '(): Entry is marked as spam. Aborting.' );

			return false;
		}

		$feed = $this->get_payment_feed( $entry );

		//Ignore Callback messages from forms that are no longer configured with the Instamojo add-on
		if ( ! $feed || ! rgar( $feed, 'is_active' ) ) {
			$this->log_error( __METHOD__ . "(): Form no longer is configured with Instamojo Addon. Form ID: {$entry['form_id']}. Aborting." );

			return false;
		}
		$this->log_debug( __METHOD__ . "(): Form {$entry['form_id']} is properly configured." );

		//----- Making sure this Callback can be processed -------------------------------------//
		if ( ! $this->can_process_callback( $feed, $entry ) ) {
			$this->log_debug( __METHOD__ . '(): Callback cannot be processed.' );

			return false;
		}

		//----- Processing Callback ------------------------------------------------------------//
		$this->log_debug( __METHOD__ . '(): Processing Callback...' );
		$action = $this->process_callback( $feed, $entry, rgget( 'payment_status' ), rgget( 'payment_id' ), rgget( 'payment_request_id' ) );
		$this->log_debug( __METHOD__ . '(): Callback processing complete.' );

		if ( rgempty( 'entry_id', $action ) ) {
			return false;
		}

		return $action;

	}

	public function get_payment_feed( $entry, $form = false ) {

		$feed = parent::get_payment_feed( $entry, $form );

		if ( empty( $feed ) && ! empty( $entry['id'] ) ) {
			//looking for feed created by legacy versions
			$feed = $this->get_instamojo_feed_by_entry( $entry['id'] );
		}

		$feed = apply_filters( 'gform_instamojo_get_payment_feed', $feed, $entry, $form ? $form : GFAPI::get_form( $entry['form_id'] ) );

		return $feed;
	}

	private function get_instamojo_feed_by_entry( $entry_id ) {

		$feed_id = gform_get_meta( $entry_id, 'instamojo_feed_id' );
		$feed    = $this->get_feed( $feed_id );

		return ! empty( $feed ) ? $feed : false;
	}

	public function instamojo_post_callback( $callback_action, $callback_result ) {
		if ( is_wp_error( $callback_action ) || ! $callback_action ) {
			return false;
		}

		//run the necessary hooks
		$entry          = GFAPI::get_entry( $callback_action['entry_id'] );
		$feed           = $this->get_payment_feed( $entry );
		$transaction_id = rgar( $callback_action, 'transaction_id' );
		$amount         = rgar( $callback_action, 'amount' );

		//run gform_instamojo_fulfillment only in certain conditions
		if ( rgar( $callback_action, 'ready_to_fulfill' ) && ! rgar( $callback_action, 'abort_callback' ) ) {
			$this->fulfill_order( $entry, $transaction_id, $amount, $feed );
		} else {
			if ( rgar( $callback_action, 'abort_callback' ) ) {
				$this->log_debug( __METHOD__ . '(): Callback processing was aborted. Not fulfilling entry.' );
			} else {
				$this->log_debug( __METHOD__ . '(): Entry is already fulfilled or not ready to be fulfilled, not running gform_instamojo_fulfillment hook.' );
			}
		}

		do_action( 'gform_instamojo_post_callback', $_POST, $entry, $feed, false );
		if ( has_filter( 'gform_instamojo_post_callback' ) ) {
			$this->log_debug( __METHOD__ . '(): Executing functions hooked to gform_instamojo_post_callback.' );
		}
	}

	private function process_callback( $config, $entry, $status, $transaction_id, $payment_request_id ) {
		$amount = $entry['payment_amount'];
		$this->log_debug( __METHOD__ . "(): Payment status: {$status} - Transaction ID: {$transaction_id} - Payment Request ID: {$payment_request_id}" );

		$action = array();

		//creates transaction
		$action['id']               = $transaction_id . '_' . $status;
		$action['type']             = 'complete_payment';
		$action['transaction_id']   = $transaction_id;
		$action['amount']           = $amount;
		$action['entry_id']         = $entry['id'];
		$action['payment_date']     = gmdate( 'y-m-d H:i:s' );
		$action['payment_method']	= 'Instamojo';
		$action['ready_to_fulfill'] = ! $entry['is_fulfilled'] ? true : false;

		return $action;
	}

	public function can_process_callback( $feed, $entry ) {

		$this->log_debug( __METHOD__ . '(): Checking that callback can be processed.' );

		//Only process test messages coming fron SandBox and only process production messages coming from production Instamojo
		if ( ! rgget( 'payment_id' ) ) {
			$this->log_error( __METHOD__ . "(): Invalid response. 'payment_id' parameter does not exist." );
			return false;
		}

		$txn_request_id = gform_get_meta( $entry['id'], 'payment_request_id' );
		$callback_txn_request_id = rgget( 'payment_request_id' );
		if ( strtolower( trim( $txn_request_id ) ) != strtolower( trim( $callback_txn_request_id ) ) ) {
			$this->log_error( __METHOD__ . '(): Instamojo merchant generated transaction request id does not match. transaction request id sent from Instamojo merchant:' . strtolower( trim( $txn_request_id ) ) . ' - Key from callback message: ' . $callback_txn_request_id );
			return false;
		}

		$this->get_instamojo_api( $feed );

		try {
			$response = $this->_api->paymentRequestPaymentStatus( rgget( 'payment_request_id' ), rgget( 'payment_id' ) );

			$this->log_debug( __METHOD__ . "(): Payment details received from Instamojo for payment ID (" . rgget( 'payment_id' ) ."): " . print_r( $response, 1 ) );

			if( rgar( $entry, 'payment_amount' ) == $response['payment']['unit_price'] && rgget( 'payment_status' ) == $response['payment']['status'] ) {
				return true;
			}

		} catch ( Exception $e ) {
			$this->log_error( __METHOD__ . '(): Returned payment ID is invalid: ' . $e->getMessage() );
			return false;
		}

	}

	public function modify_post( $post_id, $action ) {

		$result = false;

		if ( ! $post_id ) {
			return $result;
		}

		switch ( $action ) {
			case 'draft':
				$post = get_post( $post_id );
				$post->post_status = 'draft';
				$result = wp_update_post( $post );
				$this->log_debug( __METHOD__ . "(): Set post (#{$post_id}) status to \"draft\"." );
				break;
			case 'delete':
				$result = wp_delete_post( $post_id );
				$this->log_debug( __METHOD__ . "(): Deleted post (#{$post_id})." );
				break;
		}

		return $result;
	}

	//------- ADMIN FUNCTIONS/HOOKS -----------//

	public function init_admin() {

		parent::init_admin();

		//add actions to allow the payment status to be modified
		add_action( 'gform_payment_status', array( $this, 'admin_edit_payment_status' ), 3, 3 );
		add_action( 'gform_payment_date', array( $this, 'admin_edit_payment_date' ), 3, 3 );
		add_action( 'gform_payment_transaction_id', array( $this, 'admin_edit_payment_transaction_id' ), 3, 3 );
		add_action( 'gform_payment_amount', array( $this, 'admin_edit_payment_amount' ), 3, 3 );
		add_action( 'gform_after_update_entry', array( $this, 'admin_update_payment' ), 4, 2 );
	}

	/**
	 * Add supported notification events.
	 *
	 * @param array $form The form currently being processed.
	 *
	 * @return array
	 */
	public function supported_notification_events( $form ) {
		if ( ! $this->has_feed( $form['id'] ) ) {
			return false;
		}

		return array(
			'complete_payment' => esc_html__( 'Payment Completed', 'gf-instamojo' ),
		);
	}

	public function admin_edit_payment_status( $payment_status, $form, $entry ) {
		if ( $this->payment_details_editing_disabled( $entry ) ) {
			return $payment_status;
		}

		//create drop down for payment status
		$payment_string = gform_tooltip( 'instamojo_edit_payment_status', '', true );
		$payment_string .= '<select id="payment_status" name="payment_status">';
		$payment_string .= '<option value="' . $payment_status . '" selected>' . $payment_status . '</option>';
		$payment_string .= '<option value="Paid">Paid</option>';
		$payment_string .= '</select>';

		return $payment_string;
	}

	public function admin_edit_payment_date( $payment_date, $form, $entry ) {
		if ( $this->payment_details_editing_disabled( $entry ) ) {
			return $payment_date;
		}

		$payment_date = $entry['payment_date'];
		if ( empty( $payment_date ) ) {
			$payment_date = gmdate( 'y-m-d H:i:s' );
		}

		$input = '<input type="text" id="payment_date" name="payment_date" value="' . $payment_date . '">';

		return $input;
	}

	public function admin_edit_payment_transaction_id( $transaction_id, $form, $entry ) {
		if ( $this->payment_details_editing_disabled( $entry ) ) {
			return $transaction_id;
		}

		$input = '<input type="text" id="instamojo_transaction_id" name="instamojo_transaction_id" value="' . $transaction_id . '">';

		return $input;
	}

	public function admin_edit_payment_amount( $payment_amount, $form, $entry ) {
		if ( $this->payment_details_editing_disabled( $entry ) ) {
			return $payment_amount;
		}

		if ( empty( $payment_amount ) ) {
			$payment_amount = GFCommon::get_order_total( $form, $entry );
		}

		$input = '<input type="text" id="payment_amount" name="payment_amount" class="gform_currency" value="' . $payment_amount . '">';

		return $input;
	}

	public function admin_update_payment( $form, $entry_id ) {
		check_admin_referer( 'gforms_save_entry', 'gforms_save_entry' );

		//update payment information in admin, need to use this function so the lead data is updated before displayed in the sidebar info section
		$entry = GFFormsModel::get_lead( $entry_id );

		if ( $this->payment_details_editing_disabled( $entry, 'update' ) ) {
			return;
		}

		//get payment fields to update
		$payment_status = rgpost( 'payment_status' );
		//when updating, payment status may not be editable, if no value in post, set to lead payment status
		if ( empty( $payment_status ) ) {
			$payment_status = $entry['payment_status'];
		}

		$payment_amount      = GFCommon::to_number( rgpost( 'payment_amount' ) );
		$payment_transaction = rgpost( 'instamojo_transaction_id' );
		$payment_date        = rgpost( 'payment_date' );

		$status_unchanged = $entry['payment_status'] == $payment_status;
		$amount_unchanged = $entry['payment_amount'] == $payment_amount;
		$id_unchanged     = $entry['transaction_id'] == $payment_transaction;
		$date_unchanged   = $entry['payment_date'] == $payment_date;

		if ( $status_unchanged && $amount_unchanged && $id_unchanged && $date_unchanged ) {
			return;
		}

		if ( empty( $payment_date ) ) {
			$payment_date = gmdate( 'y-m-d H:i:s' );
		} else {
			//format date entered by user
			$payment_date = date( 'Y-m-d H:i:s', strtotime( $payment_date ) );
		}

		global $current_user;
		$user_id   = 0;
		$user_name = 'System';
		if ( $current_user && $user_data = get_userdata( $current_user->ID ) ) {
			$user_id   = $current_user->ID;
			$user_name = $user_data->display_name;
		}

		$entry['payment_status'] = $payment_status;
		$entry['payment_amount'] = $payment_amount;
		$entry['payment_date']   = $payment_date;
		$entry['transaction_id'] = $payment_transaction;

		// if payment status does not equal approved/paid or the lead has already been fulfilled, do not continue with fulfillment
		if ( ( $payment_status == 'Approved' || $payment_status == 'Paid' ) && ! $entry['is_fulfilled'] ) {
			$action['id']             = $payment_transaction;
			$action['type']           = 'complete_payment';
			$action['transaction_id'] = $payment_transaction;
			$action['amount']         = $payment_amount;
			$action['entry_id']       = $entry['id'];

			$this->complete_payment( $entry, $action );
			$this->fulfill_order( $entry, $payment_transaction, $payment_amount );
		}
		//update lead, add a note
		GFAPI::update_entry( $entry );
		GFFormsModel::add_note( $entry['id'], $user_id, $user_name, sprintf( esc_html__( 'Payment information was manually updated. Status: %s. Amount: %s. Transaction ID: %s. Date: %s', 'gf-instamojo' ), $entry['payment_status'], GFCommon::to_money( $entry['payment_amount'], $entry['currency'] ), $payment_transaction, $entry['payment_date'] ) );
	}

	public function fulfill_order( &$entry, $transaction_id, $amount, $feed = null ) {

		if ( ! $feed ) {
			$feed = $this->get_payment_feed( $entry );
		}

		$form = GFFormsModel::get_form_meta( $entry['form_id'] );
		if ( rgars( $feed, 'meta/delayPost' ) ) {
			$this->log_debug( __METHOD__ . '(): Creating post.' );
			$entry['post_id'] = GFFormsModel::create_post( $form, $entry );
			$this->log_debug( __METHOD__ . '(): Post created.' );
		}

		do_action( 'gform_instamojo_fulfillment', $entry, $feed, $transaction_id, $amount );
		if ( has_filter( 'gform_instamojo_fulfillment' ) ) {
			$this->log_debug( __METHOD__ . '(): Executing functions hooked to gform_instamojo_fulfillment.' );
		}

	}


	public function instamojo_fulfillment( $entry, $instamojo_config, $transaction_id, $amount ) {
		//no need to do anything for instamojo when it runs this function, ignore
		return false;
	}

	/**
	 * Editing of the payment details should only be possible if the entry was processed by Instamojo, if the payment status is Pending or Processing, and the transaction was not a subscription.
	 *
	 * @param array $entry The current entry
	 * @param string $action The entry detail page action, edit or update.
	 *
	 * @return bool
	 */
	public function payment_details_editing_disabled( $entry, $action = 'edit' ) {
		if ( ! $this->is_payment_gateway( $entry['id'] ) ) {
			// Entry was not processed by this add-on, don't allow editing.
			return true;
		}

		$payment_status = rgar( $entry, 'payment_status' );
		if ( $payment_status == 'Approved' || $payment_status == 'Paid' || rgar( $entry, 'transaction_type' ) == 2 ) {
			// Editing not allowed for this entries transaction type or payment status.
			return true;
		}

		if ( $action == 'edit' && rgpost( 'screen_mode' ) == 'edit' ) {
			// Editing is allowed for this entry.
			return false;
		}

		if ( $action == 'update' && rgpost( 'screen_mode' ) == 'view' && rgpost( 'action' ) == 'update' ) {
			// Updating the payment details for this entry is allowed.
			return false;
		}

		// In all other cases editing is not allowed.

		return true;
	}

	public function get_payment_total_field_id( $form, $feed ) {
		if( !isset( $feed['meta']['paymentAmount'] ) ) {
			return false;
		}
		if( rgars( $feed, 'meta/paymentAmount' ) == 'form_total' ) {
			$fields = GFAPI::get_fields_by_type( $form, array( 'total' ) );
			$field_id = empty( $fields ) ? false : $fields[0]->id;
		} else {
			$field_id = absint( rgars( $feed, 'meta/paymentAmount' ) );
		}

		return $field_id;
	}

	public function validate_payment_link_notification( $field ) {
		// Get posted settings.
		$settings = $this->get_posted_settings();
		if( rgar( $field, 'name' ) == 'customerInformation_phone' && strpos( rgar( $settings, 'paymentLink' ), 'sms' ) !== false && rgempty( 'customerInformation_phone', $settings ) ) {
			$this->set_field_error( $field, esc_html__( 'Please map a Phone field or remove SMS from the Payment Link option below.', 'gf-instamojo' ) );
		}
		if( rgar( $field, 'name' ) == 'customerInformation_email' && strpos( rgar( $settings, 'paymentLink' ), 'email' ) !== false && rgempty( 'customerInformation_email', $settings ) ) {
			$this->set_field_error( $field, esc_html__( 'Please map a field against the Email field or remove email from the Payment Link option below.', 'gf-instamojo' ) );
		}
	}

}