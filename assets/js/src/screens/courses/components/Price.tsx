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

interface Props {
	register: any;
	setValue: any;
}
const Price: React.FC<Props> = (props) => {
	const { register, setValue } = props;
	const [priceValue, setPriceValue] = useState(0);
	const handleChange = (priceValue: number) => setPriceValue(priceValue);

	useEffect(() => {
		setValue('price', priceValue);
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
				<NumberInput w="32" ref={register} name="price" value={priceValue}>
					<NumberInputField rounded="sm" />
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
