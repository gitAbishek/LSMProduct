import { FormControl, FormErrorMessage, Textarea } from '@chakra-ui/react';
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
				<Textarea {...register(questionId)} />

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
