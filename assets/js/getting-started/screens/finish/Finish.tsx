import {
	Box,
	Button,
	Flex,
	Heading,
	Icon,
	Image,
	Link,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { motion } from 'framer-motion';
import React from 'react';
import { BiCheck } from 'react-icons/bi';
import { onboardFinishCover } from '../../../back-end/constants/images';

interface Props {
	dashboardURL: string;
	siteURL: string;
	pageBuilderURL: string;
}

const Welcome: React.FC<Props> = (props) => {
	const { dashboardURL, siteURL, pageBuilderURL } = props;

	const CircleBox = motion(Box);
	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<Box align="center">
						<Image src={onboardFinishCover} alt="Masteriyo Logo" />
					</Box>

					<Stack spacing={6} align="center">
						<CircleBox
							height="70px"
							w="70px"
							border="3px solid"
							borderColor="green.400"
							borderRadius="100%"
							d="flex"
							alignItems="center"
							justifyContent="center"
							fontSize="4xl"
							color="green.400"
							animate={{
								scale: [1, 1.5, 1, 1.2, 1],
							}}
							transition={{
								duration: 1,
								ease: 'easeInOut',
								times: [0, 0.2, 0.5, 0.8, 1],
							}}>
							<Icon as={BiCheck} />
						</CircleBox>

						<Heading as="h2" fontSize="24px">
							{__("Congratulations, you're all set!", 'masteriyo')}
						</Heading>

						<Link
							textDecoration="underline"
							fontSize="12px"
							color="primary.400"
							href={siteURL ? siteURL : '#'}>
							{__('Visit your site', 'masteriyo')}
						</Link>
					</Stack>
					<Flex justify="space-between" align="center">
						<Link href={dashboardURL ? dashboardURL : '#'}>
							<Button rounded="3px" variant="outline">
								{__('Back to dashboard', 'masteriyo')}
							</Button>
						</Link>

						<Link href={pageBuilderURL ? pageBuilderURL : '#'}>
							<Button rounded="3px">
								{__('Create a new course', 'masteriyo')}
							</Button>
						</Link>
					</Flex>
				</Stack>
			</Box>
		</Box>
	);
};

export default Welcome;
