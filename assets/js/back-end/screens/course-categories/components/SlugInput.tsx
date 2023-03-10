import {
	FormControl,
	FormErrorMessage,
	FormHelperText,
	FormLabel,
	Input,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext, useWatch } from 'react-hook-form';
import { makeSlug } from '../../../utils/categories';

interface Props {
	defaultValue?: string;
	defaultNameValue?: string;
}
const SlugInput: React.FC<Props> = (props) => {
	const { defaultValue, defaultNameValue } = props;
	const {
		register,
		formState: { errors },
		control,
	} = useFormContext();

	const watchedName = useWatch({
		name: 'name',
		defaultValue: defaultNameValue || '',
		control,
	});

	return (
		<FormControl isInvalid={!!errors?.slug}>
			<FormLabel>{__('Slug', 'masteriyo')}</FormLabel>
			<Input
				defaultValue={defaultValue}
				{...register('slug', {
					validate: (value) =>
						value.includes(' ')
							? __('Spaces are not allowed.', 'masteriyo')
							: true,
				})}
				placeholder={watchedName ? makeSlug(watchedName) : ''}
			/>
			<FormHelperText fontSize="xs">
				{__(
					'The “slug” is the URL-friendly version of the name. It should be all lowercase and contains only letters, numbers, and hyphens.',
					'masteriyo'
				)}
			</FormHelperText>
			<FormErrorMessage>
				{errors?.slug && errors?.slug?.message}
			</FormErrorMessage>
		</FormControl>
	);
};

export default SlugInput;
