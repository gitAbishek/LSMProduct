import {
	FormControl,
	FormHelperText,
	FormLabel,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
	Slider,
	SliderFilledTrack,
	SliderThumb,
	SliderTrack,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	defaultValue?: any;
}
const Price: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const [priceValue, setPriceValue] = useState<number>(defaultValue || '0');
	const handleChange = (priceValue: number) => setPriceValue(priceValue);
	const { register, setValue } = useFormContext();

	useEffect(() => {
		setValue('regular_price', priceValue);
	}, [priceValue]);

	return (
		<FormControl>
			<FormLabel>{__('Featured ', 'masteriyo')}</FormLabel>

			<Stack direction="row" spacing="6">
				<Slider value={priceValue} onChange={handleChange}>
					<SliderTrack>
						<SliderFilledTrack />
					</SliderTrack>
					<SliderThumb />
				</Slider>
				<NumberInput w="32" name="regular_price" value={priceValue}>
					<NumberInputField rounded="sm" {...register('regular_price')} />
					<NumberInputStepper>
						<NumberIncrementStepper />
						<NumberDecrementStepper />
					</NumberInputStepper>
				</NumberInput>
			</Stack>
			{priceValue === 0 && (
				<FormHelperText>
					{__('Setting price 0 will make your course free', 'masteriyo')}
				</FormHelperText>
			)}
		</FormControl>
	);
};

export default Price;
