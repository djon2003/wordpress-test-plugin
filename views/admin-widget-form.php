<?php /* Variables: $dropdown_options */ ?>
<p>
	<label for="<?php echo $this->get_field_id( 'show_type' ); ?>"><?php __( 'What to show', 'text_domain' ); ?></label>
	<select name="<?php echo $this->get_field_name( 'show_type' ); ?>" id="<?php echo $this->get_field_id( 'show_type' ); ?>" class="widefat">
	<?php
	foreach ( $dropdown_options['show_type'] as $value => $text ) {
		echo '<option value="' . esc_attr( $value ) . '" id="' . esc_attr( $value ) . '" '. selected( $show_type, $value, false ) . '>'. $text . '</option>';

	} ?>
	</select>
</p>