import { Box, Container, Stack } from '@chakra-ui/react';
import MainToolbar from 'Layouts/MainToolbar';
import React from 'react';

import MasteriyoRouter from '../../router/MasteriyoRouter';

const Main: React.FC = () => {
	return (
		<Box id="masteriyo">
			<Stack direction="column" spacing="8">
				<MainToolbar />
				<Container>
					<Box>
						<MasteriyoRouter />
					</Box>
				</Container>
			</Stack>
		</Box>
	);
};

export default Main;
