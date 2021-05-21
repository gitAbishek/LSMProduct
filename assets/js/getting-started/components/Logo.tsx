import { Box, Container, Image } from '@chakra-ui/react';
import React from 'react';

import LogoImg from '../../../img/onboard-logo.png';

const Logo: React.FC = () => {
	return (
		<Container maxW="container.xl" centerContent>
			<Box m="10">
				<Image src={LogoImg} alt="Masteriyo Logo" w="260px" />
			</Box>
		</Container>
	);
};

export default Logo;
