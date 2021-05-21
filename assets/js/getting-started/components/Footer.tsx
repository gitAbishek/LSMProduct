import { Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';

const Footer: React.FC = () => {
	return (
		<Text color="#7C7D8F" fontSize="sm" align="center">
			{__(`MASTERIYO LMS. Designed by ThemeGrill`, 'masteriyo')}
		</Text>
	);
};

export default Footer;
