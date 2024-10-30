<?php
class Hijri_Calendar_Widget extends WP_Widget{
	/*
	 *	Plugin Constructor
	 */
	 public function __construct(){
		parent::__construct(
			'hijri_calendar_widget', //Base ID
			__('Hijri Calendar Widget','text_domain'), //Name
			array('description' => __('Wordpress plugin for historically accurate Hijri conversions.','text_domain'))
		);
	 }
	 
	 /*
	  *	Frontend Display
	  */
  	 public function widget($args, $instance){
		$title 			= apply_filters( 'widget_title', $instance['title'] );
		$timezone 		= $instance['timezone'];

		
		echo $args['before_widget'];
		if(!empty($title))
			echo $args['before_title'] . $title . $args['after_title'];
		//Display Form
		echo $this->getForm($timezone);
		echo $args['after_widget'];
	}
	
	/*
	 *	Backend Form
	 */
	 public function form($instance){
		if(isset($instance['title'])){
			$title = $instance['title'];
		} else {
			$title = __('Hijri Calendar Widget','text_domain');
		}
		$timezone = $instance['timezone'];
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('timezone' ); ?>"><?php _e( 'Time Zone:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'timezone' ); ?>" name="<?php echo $this->get_field_name( 'timezone' ); ?>" type="text" value="<?php echo esc_attr( $timezone ); ?>">
		</p>

		<?php
	 }
	 
	 /*
	  *	Update Method
	  */
	  public function update($new_instance, $old_instance){
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['timezone'] = ( ! empty( $new_instance['timezone'] ) ) ? strip_tags( $new_instance['timezone'] ) : '';
		
		return $instance;
	  }
	  
	 /*
	  *	Display Calendar Form
	  */
		public function getForm($timezone){
			$output = '
				<form id="hijri-calendar" method="post" action="'. plugins_url().'/hijri-calendar-widget/hijri.php">
    				<div class="field">
        				<label for="name">Enter Gregorian Date:</label>
       					<input type="date" id="date" name="date" pattern="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/" placeholder="YYYY-MM-DD" min="1852-10-15" value="'.date("Y-m-d").'" required>
    				</div>

					<input name="timezone" id="timezone" type="hidden" value="America/New_York">
					<div class="field">
						<br>
       		 			<input name="calendar_submit" type="submit" value="Find Concordance">
    				</div>
				</form>
				<br>
				<div id="form-messages"></div>';
		
		//Return Outpur String
		return $output;
		}
}	