import { Box, Container, Image } from '@chakra-ui/react';
import React from 'react';
import { Logo } from '../../back-end/constants/images';

const MainLogo: React.FC = () => {
	return (
		<Container maxW="container.xl" centerContent>
			<Box m="10">
				<Image src={Logo} alt="Masteriyo Logo" w="60px" />
			</Box>
		</Container>
	);
};

export default MainLogo;
