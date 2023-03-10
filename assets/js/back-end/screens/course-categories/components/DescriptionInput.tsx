import { FormControl, FormLabel } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import Editor from '../../../components/common/Editor';

interface Props {
	defaultValue?: string;
}

const DescriptionInput: React.FC<Props> = (props) => {
	const { defaultValue } = props;

	return (
		<FormControl>
			<FormLabel>{__('Description', 'masteriyo')}</FormLabel>
			<Editor
				id="mto-category-description"
				name="description"
				defaultValue={defaultValue}
			/>
		</FormControl>
	);
};

export default DescriptionInput;
