<?php

namespace App\Services;

use App\Enums\SensorDataType;
use App\Enums\SpecificationType;
use Maantje\Charts\Annotations\YAxis\YAxisLineAnnotation;
use Maantje\Charts\Annotations\YAxis\YAxisRangeAnnotation;

class GraphSpecVisualiserService
{
	public function __construct(
		public SensorDataType $type,
		public bool $compact = false
	) {}
	protected function minmax()
	{
		return [new YAxisRangeAnnotation (
			y1: $this->type->getSpecs()['min'],
			y2: $this->type->getSpecs()['max'],
			color: 'var(--color-complementary)',
			fontSize: $this->compact ? 0 : null,
			label: $this->type->getSpecLabel(),
			labelColor: 'var(--color-black)',
			labelBackgroundColor: $this->compact ? 'var(--color-clear)' : '',


		)];
	}

	protected function many()
	{
		$entries = $this->type->getSpecs()['entries'];
		$lines = [];

		foreach($entries as $key => $value) {
			$lines[] = new YAxisLineAnnotation(
				y: $value,
				color: 'var(--color-complementary)',
				size: 3,
				fontSize: $this->compact ? 0 : null,
				label: $this->type->getSpecLabel() . $key,
				labelColor: 'var(--color-black)',
				labelBackgroundColor: $this->compact ? 'var(--color-clear)' : '',
			);
		}
		return $lines;
	}
	public function getYAxisAnnotations(): array
	{
		return match($this->type->getSpecs()['type']) {
		 	SpecificationType::MINMAX 	=> $this->minmax(),
			SpecificationType::MANY 	=> $this->many(),
			SpecificationType::NULL 	=> [],
		};
	}
}