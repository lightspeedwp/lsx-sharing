<div class="uix-field-wrapper">
	<ul class="ui-tab-nav">
		<li><a href="#ui-general" class="active"><?php esc_html_e( 'General', 'lsx-sharing' ); ?></a></li>
		<li><a href="#ui-buttons"><?php esc_html_e( 'Buttons', 'lsx-sharing' ); ?></a></li>
	</ul>

	<div id="ui-general" class="ui-tab active">
		<table class="form-table">
			<tbody>
				<?php do_action( 'lsx_framework_sharing_tab_content', 'general' ); ?>
			</tbody>
		</table>
	</div>

	<div id="ui-buttons" class="ui-tab">
		<table class="form-table">
			<tbody>
				<?php do_action( 'lsx_framework_sharing_tab_content', 'buttons' ); ?>
			</tbody>
		</table>
	</div>

	<?php do_action( 'lsx_framework_sharing_tab_bottom', 'display' ); ?>
</div>
