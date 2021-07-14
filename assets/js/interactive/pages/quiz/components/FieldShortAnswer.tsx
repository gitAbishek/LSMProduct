import { FormControl, FormErrorMessage, Textarea } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	questionId: any;
}

const FieldShortAnswer: React.FC<Props> = (props) => {
	const { questionId } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();

	return (
		<>
			<FormControl isInvalid={errors[questionId]}>
				<Textarea
					{...register(questionId, {
						required: __('Answer is required', 'masteriyo'),
					})}
				/>

				{errors[questionId] && (
					<FormErrorMessage fontSize="xs">
						{errors[questionId].message}
					</FormErrorMessage>
				)}
			</FormControl>
		</>
	);
};

export default FieldShortAnswer;
