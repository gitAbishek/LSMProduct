import {
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	defaultValue?: string;
}

const Slug: React.FC<Props> = (props) => {
	const { defaultValue } = props;

	const {
		register,
		formState: { errors },
	} = useFormContext();
	return (
		<FormControl isInvalid={!!errors?.slug}>
			<FormLabel>{__('Slug', 'masteriyo')}</FormLabel>

			<Input
				defaultValue={defaultValue}
				placeholder={__('Course Slug', 'masteriyo')}
				{...register('slug')}
			/>
			<FormErrorMessage>
				{errors?.slug && errors?.slug?.message}
			</FormErrorMessage>
		</FormControl>
	);
};

export default Slug;
