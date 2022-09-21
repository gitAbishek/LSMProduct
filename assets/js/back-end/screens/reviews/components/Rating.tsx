import { FormErrorMessage, FormLabel, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import FormControlTwoCol from '../../../components/common/FormControlTwoCol';
import RatingButton from '../../../components/common/RatingButton';

const Rating: React.FC = () => {
	const {
		register,
		formState: { errors },
		setValue,
		watch,
	} = useFormContext();
	const rating = watch('rating');
	const buttons = [];

	const onClick = (idx: number) => {
		if (!isNaN(idx)) {
			// allow user to click first icon and set rating to zero if rating is already 1
			if (rating === 1 && idx === 1) {
				setValue('rating', 0);
			} else {
				setValue('rating', idx);
			}
		}
	};

	for (let i = 1; i <= 5; i++) {
		buttons.push(
			<RatingButton key={i} onClick={() => onClick(i)} fill={i <= rating} />
		);
	}

	return (
		<FormControlTwoCol isInvalid={!!errors?.title}>
			<FormLabel>{__('Review Rating', 'masteriyo')}</FormLabel>
			<Stack
				direction="row"
				width="100%"
				spacing="0"
				color="orange.300"
				cursor={'pointer'}>
				<input type="hidden" {...register('rating')} />
				{buttons}
			</Stack>
			<FormErrorMessage>
				{errors?.rating && errors?.rating?.message}
			</FormErrorMessage>
		</FormControlTwoCol>
	);
};

export default Rating;
