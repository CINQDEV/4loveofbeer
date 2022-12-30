<?php

namespace ACA\JetEngine\Export;

use ACA\JetEngine\Column;
use ACA\JetEngine\Field\Field;
use ACA\JetEngine\Field\Type;
use ACP;

final class ModelFactory {

	/**
	 * @param Column\Meta $column
	 * @param Field       $field
	 *
	 * @return ACP\Export\Model|false
	 */
	public function create( Column\Meta $column, Field $field ) {
		switch ( true ) {
			case $field instanceof Type\DateTime:
			case $field instanceof Type\Date:
				return new ACP\Export\Model\Value( $column );

			case $field instanceof Type\Select:
			case $field instanceof Type\Checkbox:
			case $field instanceof Type\Media:
			case $field instanceof Type\Posts:
				return new ACP\Export\Model\StrippedValue( $column );

			case $field instanceof Type\Repeater:
				return false;

			default:
				return new ACP\Export\Model\RawValue( $column );
		}
	}

}