import { Box, FormControl, FormLabel } from '@chakra-ui/react';
import { RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	defaultValue?: string;
}
const Hightlights: React.FC<Props> = (props) => {
	const { setValue } = useFormContext();
	const { defaultValue } = props;

	return (
		<FormControl>
			<FormLabel>{__('Course Highlights', 'masteriyo')}</FormLabel>
			<Box
				border="1px"
				fontSize="sm"
				borderColor="gray.200"
				shadow="input"
				rounded="sm"
				pl="5">
				<RichText
					multiline="li"
					value={defaultValue || ''}
					onChange={(val) => setValue('highlights', val)}
				/>
			</Box>
		</FormControl>
	);
};

export default Hightlights;
