import { Box, Container, Stack } from '@chakra-ui/react';
import MainToolbar from 'Layouts/MainToolbar';
import React from 'react';

import MasteriyoRouter from '../../router/MasteriyoRouter';

const Main: React.FC = () => {
	return (
		<Box id="masteriyo">
			<Stack direction="column" spacing="12">
				<MainToolbar />

				<Box w="full">
					<Container maxW="container.xl" centerContent>
						<Box w="full">
							<MasteriyoRouter />
						</Box>
					</Container>
				</Box>
			</Stack>
		</Box>
	);
};

export default Main;
