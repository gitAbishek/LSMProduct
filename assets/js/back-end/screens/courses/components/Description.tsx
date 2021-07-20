import { FormControl, FormLabel } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Editor from 'Components/common/Editor';
import React from 'react';

interface Props {
	defaultValue?: string;
	hasImageUpload?: boolean;
}

const Description: React.FC<Props> = (props) => {
	const { defaultValue, hasImageUpload } = props;

	return (
		<FormControl>
			<FormLabel>{__('Course Description', 'masteriyo')}</FormLabel>
			<Editor
				name="description"
				defaultValue={defaultValue}
				hasImageUpload={hasImageUpload}
			/>
		</FormControl>
	);
};

export default Description;
