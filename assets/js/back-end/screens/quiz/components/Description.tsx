import { FormControl, FormLabel } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Editor from 'Components/common/Editor';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	defaultValue?: string;
}

const Description: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const { control } = useFormContext();

	return (
		<FormControl>
			<FormLabel>{__('Quiz Description', 'masteriyo')}</FormLabel>
			<Editor
				name="description"
				defaultValue={defaultValue}
				control={control}
			/>
		</FormControl>
	);
};

export default Description;
