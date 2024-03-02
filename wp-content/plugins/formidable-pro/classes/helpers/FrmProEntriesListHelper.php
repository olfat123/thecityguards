<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmProEntriesListHelper extends FrmEntriesListHelper {

	/**
	 * @since 6.6
	 * @return void
	 */
	public function prepare_items() {
		if ( ! $this->meets_lite_check() ) {
			parent::prepare_items();
			return;
		}
		global $per_page;

		$this->set_per_page();
		$form_id = $this->params['form'];
		$s_query = array();

		$join_form_in_query = false;

		$this->items = $this->get_entry_items( $s_query, $join_form_in_query );
		$this->set_total_items( $s_query );

		$embedded_form_ids = FrmProFormsHelper::get_embedded_form_ids( $form_id );
		$order             = $this->get_order_by();
		$limit             = $this->get_limit( $per_page );

		if ( $embedded_form_ids ) {
			$s_query_embedded                      = $this->get_search_query( $join_form_in_query );
			$s_query_embedded['it.form_id']        = $embedded_form_ids;
			$s_query_embedded['it.parent_item_id'] = array_keys( $this->items );

			$this->items       += FrmEntry::getAll( $s_query_embedded, $order, $limit, true, $join_form_in_query );
			$this->total_items += FrmEntry::getRecordCount( $s_query_embedded );
		}
		$this->merge_repeater_and_embedded_entries_with_parent( $order, $limit );

		$this->prepare_pagination();
	}

	/**
	 * @since 6.6
	 * @return bool
	 */
	private function meets_lite_check() {
		return is_callable( array( $this, 'get_entry_items' ) );
	}

	/**
	 * @since 6.6
	 *
	 * @param int $form_id
	 * @return array
	 */
	protected function get_form_ids( $form_id ) {
		if ( ! $this->meets_lite_check() ) {
			return $form_id;
		}
		$form_ids   = FrmProFormsHelper::get_repeater_form_ids( $form_id );
		$form_ids[] = $form_id;

		return $form_ids;
	}

	/**
	 * This function makes sure entries from repeaters and embedded forms in a form are searched for.
	 *
	 * @param string $order
	 * @param string $limit
	 *
	 * @return void
	 */
	private function merge_repeater_and_embedded_entries_with_parent( $order, $limit ) {
		$items = $this->items;
		$items_having_parent = array_filter(
			$items,
			function( $item ) {
				return ! empty( $item->parent_item_id );
			}
		);

		if ( empty( $items_having_parent ) ) {
			return;
		}

		$where = array(
			'it.id' => wp_list_pluck( $items_having_parent, 'parent_item_id' ),
		);

		$parent_items = FrmEntry::getAll( $where, $order, $limit, true );
		foreach ( $items_having_parent as $key => $item ) {
			if ( ! isset( $items[ $item->parent_item_id ] ) ) {
				$items[ $item->parent_item_id ] = $parent_items[ $item->parent_item_id ];
			}
			if ( ! empty( $item->metas ) && is_array( $item->metas ) ) {
				$items[ $item->parent_item_id ]->metas += $item->metas;
			}
			unset( $items[ $key ] );
		}

		$this->items = $items;
	}

	public function get_bulk_actions() {
		$actions = array(
			'bulk_delete' => __( 'Delete', 'formidable-pro' ),
		);

		if ( ! current_user_can('frm_delete_entries') ) {
			unset($actions['bulk_delete']);
		}

		//$actions['bulk_export'] = __( 'Export to XML', 'formidable-pro' );
		if ( $this->params['form'] ) {
			$actions['bulk_csv'] = __( 'Export to CSV', 'formidable-pro' );
		}

		return $actions;
	}

	protected function extra_tablenav( $which ) {
		parent::extra_tablenav( $which );
		$is_footer    = ( $which !== 'top' );
		$entries_args = array(
			'entries_count'                    => $this->total_items,
			'bulk_delete_confirmation_message' => $this->confirm_bulk_delete(),
		);
		FrmProEntriesHelper::before_table( $is_footer, $this->params['form'], $entries_args );
	}

	/**
	 * @since 6.6
	 *
	 * @param int $form_id
	 * @return array
	 */
	private function get_entries_search_box_where( $form_id ) {
		if ( $this->meets_lite_check() ) {
			$form_ids = FrmProEntriesHelper::get_searchable_form_ids( $form_id );
			$where    = array(
				array(
					'or'                => 1,
					'fi.form_id'        => $form_ids,
					'fr.parent_form_id' => $form_id,
				),
			);
		} else {
			$where = array(
				array(
					'fi.form_id' => $form_id,
				),
			);
		}

		return $where;
	}

	public function search_box( $text, $input_id ) {
		if ( ! $this->has_items() && ! isset( $_REQUEST['s'] ) ) {
			return;
		}

		if ( isset( $this->params['form'] ) ) {
			$form = FrmForm::getOne( $this->params['form'] );
		} else {
			$form = FrmForm::get_published_forms( array(), 1 );
		}

		if ( ! $form ) {
			return;
		}

		$where = $this->get_entries_search_box_where( $form->id );

		$where['fi.type not'] = FrmField::no_save_fields();

		$field_list = FrmField::getAll( $where, 'field_order' );

		$fid = isset( $_REQUEST['fid'] ) ? sanitize_title( stripslashes( $_REQUEST['fid'] ) ) : '';
		$input_id = $input_id . '-search-input';
		$search_str = isset( $_REQUEST['s'] ) ? sanitize_text_field( stripslashes( $_REQUEST['s'] ) ) : '';

		foreach ( array( 'orderby', 'order' ) as $get_var ) {
			if ( ! empty( $_REQUEST[ $get_var ] ) ) {
				echo '<input type="hidden" name="' . esc_attr( $get_var ) . '" value="' . esc_attr( $_REQUEST[ $get_var ] ) . '" />';
			}
		}

		$options = self::get_entry_search_options( $field_list );
?>
<div class="frm-search">
	<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_attr( $text ); ?>:</label>
	<?php FrmProAppHelper::icon_by_class( 'frm_icon_font frm_search_icon' ); ?>
	<input type="text" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php echo esc_attr( $search_str ); ?>" class="frm-search-input" />
	<?php
	if ( empty( $field_list ) ) {
			submit_button( $text, 'button', false, false, array( 'id' => 'search-submit' ) );
			echo '</div>';
			return;
	}
	?>
	<select name="fid" class="hide-if-js">
		<?php
		foreach ( $options as $v => $opt ) {
			?>
			<option value="<?php echo esc_attr( $v ); ?>" <?php selected( $fid, $v ); ?>>
				<?php echo esc_html( $opt ); ?>
			</option>
			<?php
		}
		?>
	</select>

	<div class="button dropdown hide-if-no-js" id="search-submit">
		<a href="#" id="frm-fid-search" class="frm-dropdown-toggle" data-toggle="dropdown">
			<?php esc_html_e( 'Search', 'formidable-pro' ); ?>
			<b class="caret"></b>
		</a>
		<ul class="frm-dropdown-menu <?php echo esc_attr( is_rtl() ? 'dropdown-menu-left' : 'dropdown-menu-right' ); ?>" id="frm-fid-search-menu" role="menu" aria-labelledby="frm-fid-search">
			<?php
			foreach ( $options as $v => $opt ) {
				?>
			<li>
				<a href="#" id="fid-<?php echo esc_attr( $v ); ?>">
					<?php echo esc_html( $opt ); ?>
				</a>
			</li>
				<?php
			}
			?>
		</ul>
	</div>
		<?php
		submit_button( $text, 'button hide-if-js', false, false, array( 'id' => 'search-submit' ) );
		?>

</div>
<?php
	}

	/**
	 * @since 4.04.02
	 */
	private static function get_entry_search_options( $field_list ) {
		$options = array(
			''           => '&mdash; ' . __( 'All Fields', 'formidable-pro' ) . ' &mdash;',
			'created_at' => __( 'Entry creation date', 'formidable-pro' ),
			'id'         => __( 'Entry ID', 'formidable-pro' ),
		);

		foreach ( $field_list as $f ) {
			$value = ( $f->type == 'user_id' ) ? 'user_id' : $f->id;
			$options[ $value ] = FrmAppHelper::truncate( $f->name, 30 );
		}

		return apply_filters( 'frm_admin_search_options', $options, compact( 'field_list' ) );
	}
}
