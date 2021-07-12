import { FormControl, FormLabel } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Editor from 'Components/common/Editor';
import React from 'react';

interface Props {
	defaultValue?: string;
}

const Description: React.FC<Props> = (props) => {
	const { defaultValue } = props;

	return (
		<FormControl>
			<FormLabel>{__('Lesson Description', 'masteriyo')}</FormLabel>
			<Editor name="description" defaultValue={defaultValue} />
		</FormControl>
	);
};

export default Description;
