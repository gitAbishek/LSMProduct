import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	Heading,
	Image,
	Link,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';

import CoverImg from '../../../../img/onboard-finish-cover.png';

const Welcome: React.FC = () => {
	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<Box align="center">
						<Image src={CoverImg} alt="Masteriyo Logo" />
					</Box>

					<Stack spacing={6} align="center">
						<Heading as="h2" fontSize="24px">
							{__(`Congratulation, you're all set!`, 'masteriyo')}
						</Heading>

						<Text fontSize="sm" maxW="500px" align="center">
							{__(
								`Massa sed integer amet consequat. Est ultricies nisi, 
								consectetur pellentesque metus, sit dolor urna. Malesuada 
								venenatis, nulla quis ac.`,
								'masteriyo'
							)}
						</Text>
						<Link
							textDecoration="underline"
							fontSize="12px"
							color="#78A6FF"
							href="#">
							{__('Visit your site', 'masteriyo')}
						</Link>
					</Stack>
					<Flex justify="space-between" align="center">
						<Button rounded="3px" colorScheme="blue" variant="outline">
							{__('Back to dashboard', 'masteriyo')}
						</Button>

						<ButtonGroup>
							<Button variant="ghost">
								{__('Install sample course', 'masteriyo')}
							</Button>
							<Button rounded="3px" colorScheme="blue">
								{__('Create a new course', 'masteriyo')}
							</Button>
						</ButtonGroup>
					</Flex>
				</Stack>
			</Box>
		</Box>
	);
};

export default Welcome;
