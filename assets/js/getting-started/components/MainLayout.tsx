import { Box, Container, Stack } from '@chakra-ui/react';
import React from 'react';
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
			</Stack>
		</Box>
	);
};

export default MainLayout;
