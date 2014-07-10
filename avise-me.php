<?php

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

global $woocommerce;

$errors         = array();  	// array to hold validation errors
$data  		= array(); 		// array to pass back data
$product = $_POST['id'];
// validate the variables ======================================================
	// if any of these variables don't exist, add an error to our $errors array

	
	if (empty($_POST['email']))
		$errors['email'] = 'Email is required.';

        
// return a response ===========================================================

	// if there are any errors in our errors array, return a success boolean of false
	if ( ! empty($errors)) {

		// if there are items in our errors array, return those errors
		$data['success'] = false;
		$data['errors']  = $errors;
	} else {

		// if there are no errors process our form, then return a message

		// DO ALL YOUR FORM PROCESSING HERE
		// THIS CAN BE WHATEVER YOU WANT TO DO (LOGIN, SAVE, UPDATE, WHATEVER)

    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $subject = sprintf( 'Foi solicitado o produto' .' '. $product );
 
      //$sku = ($product) ? '(' . $product . ') ' : '';
          
      $message = $title . __( 'is out of stock.', 'woocommerce' );
 
         //  CC, BCC, additional headers
     $headers = 'From: Interativa Shop <example@moskatus.com.br>';

     // Attachments
      //$attachments = apply_filters('woocommerce_email_attachments', '', 'no_stock', $product);
 
     $attachments;
         // Send the mail
    $mail = wp_mail( get_option('woocommerce_stock_email_recipient'), $subject, $message, $headers );

            
		// show a message of success and provide a true success variable
		$data['success'] = true;
		$data['message'] = 'Mensagem Enviada!';
	}

	// return all our data to an AJAX call
	echo json_encode($data);
