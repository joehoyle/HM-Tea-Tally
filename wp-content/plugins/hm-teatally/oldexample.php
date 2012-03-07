<?php 


define( 'HMTT_PATH', dirname( __FILE__ ) . '/' );
define( 'HMTT_URL', str_replace( ABSPATH, site_url( '/' ), HMTT_PATH ) );

function hm_tea_table_draw() {

	$data = hm_tea_table_get_data();
	
	?> 
	<ul class="hm_tea_table">
	<?php foreach ( $data as $name => $blips ): ?>
	
		<li class="hm_tea_table_person">
			
			<?php echo $name; ?> 
			
			<ul class="hm_tea_table_blips">
			
				<?php for ( $i = 0; $i < $blips; $i++ ): ?>
				
					<li class="hm_tea_table_blip">o</li>
				
				<?php endfor; ?>
			
			</ul>
		
			<a class="button" href="<?php echo "?person=" . $name . "&action=add"; ?>" >Add</a>
			<a class="button" style="color:red;" href="<?php echo "?person=" . $name . "&action=delete" ?>" >delete</a>

		</li>
		
	<?php endforeach; ?>

	</ul>
	<form method="post"> 
		<select name="tea_maker" style="width: 145px" >	
			<?php foreach ( $data as $key => $blips ): ?>
				<option value="<?php echo $key;?>"><?php echo $key; ?></option>
			<?php endforeach; ?>
		</select> <br />
		
		<?php foreach ( $data as $key => $blips ):?>
		
			<?php echo $key; ?> <input type="checkbox" name="tea_makee[]" value="<?php echo $key; ?>" /> <br /> 
		
		<?php endforeach;?>
		
		<input type="submit" />
		
	</form>
	
<?php	

}

function hm_tea_table_get_data(){

	$people = array( 'tom' => 0, 'joe' => 0, 'theo' => 0, 'owain' => 0, 'matt' => 0 );

	foreach ( $people as $key => $person ) {
	
		$blips = get_option( 'hm_tea_table_' . $key );
		
		if ( $blips )
			$people[$key] = (int) $blips;
			
	}

	return $people;
	

}

function hm_tea_table_load_styles(){

	wp_enqueue_style( 'tea_tally_styles', HMTT_URL . 'tea_table.css' );

}
add_filter( 'init', 'hm_tea_table_load_styles' );


function hm_tea_table_handle_actions (){
	
	if ( ! isset( $_GET['person'] ) || ! isset( $_GET['action'] ) )
		return;

			
	$action = $_GET['action'];
	$option_name = 'hm_tea_table_' . $_GET['person'];
	
		
	switch ( $action ){
		
		case 'add':
			update_option( $option_name, (int) get_option( $option_name ) +1 );
			break;
			
		case 'delete':
		
			if ( (int) get_option( $option_name ) < 1 )
				continue;
				
			update_option( $option_name, (int) get_option( $option_name ) -1 );
			
			break;	
	
	}
		
}

add_filter( 'init', 'hm_tea_table_handle_actions' );

function hm_tea_table_handle_actions_new() {



	if ( ! isset( $_POST['tea_maker'] ) || ! isset( $_POST['tea_makee'] ) )
		return;
		
	$tea_maker = $_POST['tea_maker'];
	$tea_makees = $_POST['tea_makee'];
			
	foreach ( $tea_makees as $key => $name ){
	
		update_tea_maker( 'hm_tea_table_' . $name, (int) get_option( 'hm_tea_table_' . $name ) + 1 );
	
	}
	
	update_option ( 'hm_tea_table_' . $tea_maker, ( (int) get_option( 'hm_tea_table_' . $tea_maker ) - count( $tea_makees ) ) );	


}
add_filter( 'init', 'hm_tea_table_handle_actions_new' );





?>

