import {
	Box,
	Button,
	ButtonGroup,
	Heading,
	Image,
	Link,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { onboardCover } from '../../../back-end/constants/images';

interface Props {
	dashboardURL: string;
	nextStep: () => void;
}

const Welcome: React.FC<Props> = (props) => {
	const { dashboardURL, nextStep } = props;
	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<Box align="center">
						<Image src={onboardCover} alt="Masteriyo Logo" />
					</Box>

					<Stack spacing={6} align="center">
						<Heading as="h2" fontSize="24px">
							{__('Welcome To MASTERIYO', 'masteriyo')}
						</Heading>
						<Heading as="h2" fontSize="18px">
							{__('A Simple and Powerful LMS', 'masteriyo')}
						</Heading>
						<Text fontSize="sm" maxW="500px" align="center">
							{__(
								`Thank you for choosing MASTERIYO LMS for your online 
								courses. This short setup will guide you through the 
								basic settings and configure MASTERIYO LMS so you can 
								get started creating courses faster`,
								'masteriyo'
							)}
						</Text>
						<ButtonGroup>
							<Button onClick={nextStep} colorScheme="blue">
								{__('Start Now', 'masteriyo')}
							</Button>
							<Link href={dashboardURL ? dashboardURL : '#'}>
								<Button variant="ghost">
									{__('Skip to Dashboard', 'masteriyo')}
								</Button>
							</Link>
						</ButtonGroup>
					</Stack>
				</Stack>
			</Box>
		</Box>
	);
};

export default Welcome;
