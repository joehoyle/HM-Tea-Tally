<?php 
 /*
Plugin Name: The Humanmade Tea Tally 
Plugin URI: hmd.md
Description: With this insanely useful plugin it is now easy to track who's turn it is to make tea!
Version: 0.2
Author: Owain Cuvelier & Humanmade

*/
add_action('admin_head', 'hm_tt_custom_css');

add_action('admin_menu', 'hm_tt_menu');

add_action( 'init', function() {
	
	// if not set return end of function.
	if( ! isset( $_GET['delete'] ) )
		return;
	
	hm_tt_delete_user( $_GET['delete'] );
	
	wp_redirect( remove_query_arg( 'delete', add_query_arg( 'deleted', 'success' ) ) );
	
	exit;

} );


/**
 * hm_tt_delete_user function takes the DB entry, and unsets the value passed to it by $_GET
 * 
 * @access public
 * @param mixed $delete
 * @return void
 */
 
function hm_tt_delete_user( $delete ) {

	$tally = hm_tt_get_tally();

	unset($tally[$delete]);

	update_option( 'hm-tea-tally', $tally );


}


add_action( 'init', function() {

	if ( empty( $_POST['hm_tt_action'] ) )
		return;
		
	if ( $_POST['hm_tt_action'] == 'add_member' ) {

		hm_tt_add_member( $_POST['hm_member'], (int) $_POST['blips'] );

	} elseif ( $_POST['hm_tt_action'] == 'tally_update' ) { 
	
		
		$tally = hm_tt_get_tally();

		// Remove dots from person who made tea
		$tally[$_POST['tea_maker']] = $tally[$_POST['tea_maker']] - count( $_POST['member'] );
		
		// add dots to everyone who got tea
		foreach ( $_POST['member'] as $member )
			$tally[$member] = $tally[$member] + 1;
			
		update_option( 'hm-tea-tally', $tally );
		
	} elseif ($_POST['hm_tt_action'] == 'member_update' ) {
	
		$tally = hm_tt_get_tally();
		
		$tally[$_POST['member_updatee']] = (int)  $_POST['hm_tt_update_amount'];
		
		update_option( 'hm-tea-tally', $tally);
		
	}

		
	wp_redirect( add_query_arg( 'posted', 'success' ) );
	exit;
} );
		

add_shortcode('hmtt', 'hm_tt_draw');

/**
 * hm_tt_custom_css function pulls in Custom CSS file.
 * 
 * @access public
 * @return void
 */
 
function hm_tt_custom_css() {
    $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/hm_tt_style.css';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}


/**
 * hm_tt_menu function adds a menu page to WordPress admin panel.
 * 
 * @access public
 * @return void
 
 
function hm_tt_menu() {
	add_management_page( 'HM Tea Tally', 'HM Tea Tally', 'edit_posts', 'my_plugin_options', 'hm_tt_draw');
}

function my_plugin_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
}
*/


/**
 * hm_tt_draw function draws the table of people & dots. Followed by the forms.
 *	Drop down menu post value is tea_maker 
 * 	Check Box values are an array member[]
 *
 * &#9749 <- cup symbol.  
 *
 *
 * @access public
 * @return void
 */


function hm_tt_draw() {
	?>		
	<div class="hm_tt_data_wrap">
	<div class="hm_tt_people_block">
						  
			<?php foreach( hm_tt_get_tally() as $name => $dots ): ?>
			 	<div class="hm_tt_people"><?php echo $name; echo " ($dots)"?>
			 		<a href="<?php echo add_query_arg( 'delete', $name ) ?>">x</a>			 	
			 	</div>
			<?php endforeach ?>
	</div>		
	<div class="hm_tt_tea_block">
		<?php foreach( hm_tt_get_tally() as $name => $dots ): ?>
		    <div class="hm_tt_tea">
		    
		    	<?php $class = 'black'; ?>
		    	
		    	<?php if ( $dots >= 9 ) {
		    		
		    		$class = 'red';
		    		
		    	}elseif ( $dots < 0 ){
		    		
		    		$class = 'green';
		    		$dots = $dots * -1;
		    	}?>					
		    	
		    	<?php for ( $i = 0; $i < $dots ; $i++ ): ?>
		    	
			    	<span class="<?php echo $class; ?>"> &#9749 </span>
		    	
		    	<?php endfor; ?>
		    	
		    </div>
		    
		<?php endforeach;  ?>
	</div>
	</div>
		<div class="hm_tt_form1">
		<form method="post">
		
			<input type="hidden" name="hm_tt_action" value="tally_update" />
			<select name="tea_maker">
			<?php foreach( hm_tt_get_tally() as $name => $dots ): ?>
				<option><?php echo $name ?></option>
			<?php endforeach ?>
			</select>
			<br />
			<?php foreach( hm_tt_get_tally() as $name => $dots ): ?>
				<input class='button-secondary' type="checkbox" name="member[]" value="<?php echo $name ?>" /> <?php echo $name ?>
			<?php endforeach ?>
			<input class="button-primary" type="submit" value="Save" />
			
		</form>
		</div>
	
		<div class="hm_tt_form2">
		<form method="post">
		
			<input type="text" name="hm_member" />
			<input type="hidden" name="hm_tt_action" value="add_member" />
			<input type="text" name="blips" value="0" />
			<br />
			<input class="button-primary" type="submit" ?>
			
		</form>
		</div>
		<div class="hm_tt_form3">
		<form method="post">
		
		<input type="hidden" name="hm_tt_action" value="member_update" />
			<select name="member_updatee">
			<?php foreach( hm_tt_get_tally() as $name => $dots ): ?>
				<option><?php echo $name ?></option>
			<?php endforeach ?>
			</select>
		<input type="number" name="hm_tt_update_amount" />
		<br />
		<input class="button-primary" type="submit" value="Save" />
		</form>
		</div>
	</div>	
	<?php 	
		
}


/**
 * This function requires the arguments $name and $blips. 
 * Wirtten if statement which uses the function and adds the $_POST data to function arguements.
 * 
 * @access public
 * @param string $name
 * @param int $blips (default: 1)
 * @return bool
 */
 
function hm_tt_add_member( $name, $blips = 1 ) {
	
	$tally = hm_tt_get_tally();	

	$tally[$name] = $blips;
		
	update_option( 'hm-tea-tally', $tally );
		
}


/**
 * hm_tt_get_tally function get Database array of users an tea counts.
 * 
 * @access public
 * @return void
 */
 
function hm_tt_get_tally() {
	
	// takes the form information and creates an array.

	$tally = get_option( 'hm-tea-tally' );
	
	return $tally;
	
}



/**
 * curPageURL function creates a variable string with the current URL as the value.
 * 
 * @access public
 * @return void
 */
 
// a:8:{s:3:"Tom";i:6;s:5:"Poppy";i:2;s:4:"Jane";i:5;s:3:"Joe";i:2;s:4:"Theo";i:7;s:4:"Matt";i:6;s:5:"Keith";i:7;s:7:"Richard";i:1;}
