<?php

namespace ACA\Types\Field;

use AC;
use ACA\Types\Export;
use ACA\Types\Field;
use ACA\Types\Filtering;
use ACP\Editing;
use ACP\Search;
use ACP\Sorting;
use ACP\Sorting\Type\DataType;

class Date extends Field {

	public function get_value( $id ) {
		$time = $this->get_raw_value( $id );

		if ( ! $time ) {
			return false;
		}

		return $this->column->get_formatted_value( date( 'c', $time ) );
	}

	public function editing() {
		$storage = new Editing\Storage\Meta( $this->get_meta_key(), new AC\MetaType( $this->get_meta_type() ) );

		return $this->has_time()
			? new Editing\Service\DateTime( ( new Editing\View\DateTime() )->set_clear_button( true ), $storage, 'U' )
			: new Editing\Service\Date( ( new Editing\View\Date() )->set_clear_button( true ), $storage, 'U' );
	}

	public function filtering() {
		return new Filtering\Date( $this->column );
	}

	public function sorting() {
		return ( new Sorting\Model\MetaFactory() )->create( $this->get_meta_type(), $this->get_meta_key(), new DataType( DataType::NUMERIC ) );
	}

	public function export() {
		return new Export\Field\Date( $this->column );
	}

	public function search() {
		return new Search\Comparison\Meta\DateTime\Timestamp( $this->column->get_meta_key(), $this->column->get_meta_type() );
	}

	/**
	 * @return bool
	 */
	private function has_time() {
		return 'and_time' === $this->column->get_field()->get( 'date_and_time' );
	}

	public function get_dependent_settings() {
		return [
			new AC\Settings\Column\Date( $this->column ),
		];
	}

}