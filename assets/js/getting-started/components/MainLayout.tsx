import { Box, Container, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';

import Footer from './Footer';
import MainLogo from './Logo';
import SetupWizard from './SetupWizard';

const MainLayout: React.FC = () => {
	return (
		<Box id="masteriyo-setup-wizard">
			<Stack direction="column" spacing="2">
				<MainLogo />

				<Box w="full">
					<Container maxW="container.xl">
						<SetupWizard />
					</Container>
				</Box>

				<Footer />
			</Stack>
		</Box>
	);
};

export default MainLayout;
