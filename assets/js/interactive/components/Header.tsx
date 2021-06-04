import { Box, Image, Stack } from '@chakra-ui/react';
import React from 'react';
import { Logo } from '../../back-end/constants/images';

const Header = () => {
	return (
		<Box bg="white" shadow="box">
			<Stack direction="row" spacing="4">
				<Image src={Logo} />
			</Stack>
		</Box>
	);
};

export default Header;
