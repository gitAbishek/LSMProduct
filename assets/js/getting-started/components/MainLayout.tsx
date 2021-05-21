import { Box, Container, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';

import Footer from './Footer';
import Logo from './Logo';
import MainTab from './MainTab';

const MainLayout: React.FC = () => {
	return (
		<Box id="masteriyo-onboarding">
			<Stack direction="column" spacing="2">
				<Logo />

				<Box w="full">
					<Container maxW="container.lg" centerContent>
						<Box w="full">
							<MainTab />
						</Box>
					</Container>
				</Box>

				<Footer />
			</Stack>
		</Box>
	);
};

export default MainLayout;
