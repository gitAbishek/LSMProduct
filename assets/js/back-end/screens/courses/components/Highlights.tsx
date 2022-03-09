import { Box, FormControl, FormLabel } from '@chakra-ui/react';
import { RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	defaultValue?: string;
}

const Highlights: React.FC<Props> = (props) => {
	const { setValue } = useFormContext();
	const { defaultValue } = props;

	const formattedDefaultValue = defaultValue
		?.replaceAll('<ul>', '')
		.replaceAll('</ul>', '')
		.replaceAll('<p>', '')
		.replaceAll('</p>', '');

	return (
		<FormControl>
			<FormLabel>{__('Course Highlights', 'masteriyo')}</FormLabel>
			<Box
				border="1px"
				fontSize="sm"
				borderColor="gray.200"
				shadow="input"
				rounded="sm"
				minH="28"
				pl="5"
				sx={{
					ul: {
						listStyleType: 'disc',
						outline: 'none',
					},
				}}>
				<RichText
					identifier="values"
					tagName="ul"
					multiline="li"
					value={formattedDefaultValue || ''}
					onChange={(val) => setValue('highlights', val)}
				/>
			</Box>
		</FormControl>
	);
};

export default Highlights;
