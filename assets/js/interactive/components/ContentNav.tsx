import {
	Box,
	Button,
	Center,
	Flex,
	Heading,
	HStack,
	Icon,
	Link,
	SimpleGrid,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiCheck, BiChevronLeft, BiChevronRight } from 'react-icons/bi';
import { Link as RouterLink } from 'react-router-dom';
import MobileHidden from '../../back-end/components/common/MobileHidden';
import { getNavigationRoute } from '../../back-end/utils/nav';
import { isRightDir } from '../../back-end/utils/utils';
import { ContentNavigationSchema } from '../schemas';

interface Props {
	courseId: number;
	navigation: ContentNavigationSchema;
	onCompletePress: any;
	isButtonLoading?: boolean;
	isButtonDisabled?: boolean;
	type?: 'lesson' | 'quiz';
	quizStarted?: boolean;
}

const ContentNav: React.FC<Props> = (props) => {
	const {
		navigation,
		courseId,
		onCompletePress,
		isButtonLoading,
		isButtonDisabled,
		type,
		quizStarted,
	} = props;

	const cirlceStyles = {
		w: '30px',
		h: '30px',
		bg: 'primary.500',
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
			<SimpleGrid columns={[1, 1, 3]} minChildWidth="100px">
				<MobileHidden>
					<Box>
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
										<Icon as={isRightDir ? BiChevronRight : BiChevronLeft} />
									</Center>
									<Stack direction="column" spacing="0">
										<Text fontSize="xs" color="gray.500">
											{__('Previous', 'masteriyo')}
										</Text>
										<Heading fontSize="xs">{navigation?.previous.name}</Heading>
									</Stack>
								</HStack>
							</Link>
						)}
					</Box>
				</MobileHidden>

				{(quizStarted || type !== 'quiz') && (
					<Flex align="center" justify="center">
						<Button
							onClick={onCompletePress}
							isLoading={isButtonLoading}
							isDisabled={isButtonDisabled}
							rounded="full"
							fontWeight="bold"
							colorScheme="primary"
							leftIcon={
								<Icon
									fontSize="xl"
									d={isButtonDisabled ? 'block' : 'none'}
									as={BiCheck}
								/>
							}
							textTransform="uppercase">
							{isButtonDisabled
								? __('Completed', 'masteriyo')
								: type === 'quiz'
								? __('Submit Quiz', 'masteriyo')
								: __('Mark as Complete', 'masteriyo')}
						</Button>
					</Flex>
				)}
				<MobileHidden>
					<Box>
						{navigation?.next && (
							<Link
								as={RouterLink}
								to={getNavigationRoute(
									navigation?.next?.id,
									navigation?.next?.type,
									courseId
								)}
								sx={navLinkStyles}>
								<HStack spacing="4" justify="flex-end">
									<Stack direction="column" spacing="0">
										<Text fontSize="xs" color="gray.500">
											{__('Next', 'masteriyo')}
										</Text>
										<Heading fontSize="xs">{navigation?.next.name}</Heading>
									</Stack>
									<Center sx={cirlceStyles}>
										<Icon as={isRightDir ? BiChevronLeft : BiChevronRight} />
									</Center>
								</HStack>
							</Link>
						)}
					</Box>
				</MobileHidden>
			</SimpleGrid>
		</Box>
	);
};

export default ContentNav;
