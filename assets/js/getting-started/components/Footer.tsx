import { Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';

const Footer: React.FC = () => {
	return (
		<Text color="gray.500" fontSize="sm" align="center">
			{__(`MASTERIYO LMS. Designed by Masteriyo`, 'masteriyo')}
		</Text>
	);
};

export default Footer;
