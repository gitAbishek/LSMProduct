import { Box, Container, Stack } from '@chakra-ui/react';
import MainToolbar from 'Layouts/MainToolbar';
import React from 'react';

const Main: React.FC = (props) => {
	return (
		<Box id="masteriyo">
			<Stack direction="column" spacing="8">
				<MainToolbar />
				<Container>
					<Box>{props.children}</Box>
				</Container>
			</Stack>
		</Box>
	);
};

export default Main;
