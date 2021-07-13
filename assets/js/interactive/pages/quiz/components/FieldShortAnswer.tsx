import { FormControl, FormErrorMessage, Textarea } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	index: any;
}

const FieldShortAnswer: React.FC<Props> = (props) => {
	const { index } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();

	return (
		<>
			<FormControl isInvalid={errors[index]}>
				<Textarea
					{...register(index, {
						required: __('Answer is required', 'masteriyo'),
					})}
				/>

				{errors[index] && (
					<FormErrorMessage fontSize="xs">
						{errors[index].message}
					</FormErrorMessage>
				)}
			</FormControl>
		</>
	);
};

export default FieldShortAnswer;
