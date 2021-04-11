import { FormControl, FormLabel, Textarea } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
interface Props {
	defaultValue?: string;
}
const Description: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const { register } = useFormContext();

	return (
		<FormControl>
			<FormLabel>{__('Course Description', 'masteriyo')}</FormLabel>
			<Textarea
				defaultValue={defaultValue}
				placeholder={__('Your Course Description', 'masteriyo')}
				{...register('description')}
			/>
		</FormControl>
	);
};

export default Description;
