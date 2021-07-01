import {
	Box,
	Button,
	ButtonGroup,
	Center,
	Heading,
	HStack,
	Icon,
	Link,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiChevronLeft, BiChevronRight } from 'react-icons/bi';
import { Link as RouterLink } from 'react-router-dom';
import { ContentNavigationSchema } from '../schemas';
import { getNavigationRoute } from './FloatingNavigation';

interface Props {
	courseId: number;
	navigation: ContentNavigationSchema;
	onCompletePress: any;
	isButtonLoading?: boolean;
}

const ContentNav: React.FC<Props> = (props) => {
	const { navigation, courseId, onCompletePress, isButtonLoading } = props;

	const cirlceStyles = {
		w: '30px',
		h: '30px',
		bg: 'blue.500',
		color: 'white',
		fontSize: 'xx-large',
		rounded: 'full',
	};

	const navLinkStyles = {
		p: '4',
		w: 'full',
		d: 'block',
		_hover: {
			bg: 'white',
			shadow: 'box',
		},
	};

	return (
		<Box as="nav" w="full" p="6">
			<ButtonGroup d="flex" justifyContent="space-between" alignItems="center">
				<Box w="200px">
					{navigation?.previous && (
						<Link
							as={RouterLink}
							to={getNavigationRoute(
								navigation?.previous?.id,
								navigation?.previous?.type,
								courseId
							)}
							sx={navLinkStyles}>
							<HStack spacing="4">
								<Center sx={cirlceStyles}>
									<Icon as={BiChevronLeft} />
								</Center>
								<Stack direction="column" spacing="0">
									<Text fontSize="xs" color="gray.500">
										Prev
									</Text>
									<Heading fontSize="xs">First Installation</Heading>
								</Stack>
							</HStack>
						</Link>
					)}
				</Box>

				<Button
					onClick={onCompletePress}
					isLoading={isButtonLoading}
					colorScheme="blue"
					rounded="full"
					fontWeight="bold"
					textTransform="uppercase">
					{__('Mark as Complete', 'masteriyo')}
				</Button>

				<Box w="200px">
					{navigation?.next && (
						<Link
							as={RouterLink}
							to={getNavigationRoute(
								navigation?.next?.id,
								navigation?.next?.type,
								courseId
							)}
							sx={navLinkStyles}>
							<HStack spacing="4">
								<Stack direction="column" spacing="0">
									<Text fontSize="xs" color="gray.500">
										Next
									</Text>
									<Heading fontSize="xs">Second Installation</Heading>
								</Stack>
								<Center sx={cirlceStyles}>
									<Icon as={BiChevronRight} />
								</Center>
							</HStack>
						</Link>
					)}
				</Box>
			</ButtonGroup>
		</Box>
	);
};

export default ContentNav;
