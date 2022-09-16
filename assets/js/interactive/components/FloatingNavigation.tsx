import {
	Box,
	Center,
	HStack,
	Icon,
	Link,
	Stack,
	Text,
	useDisclosure,
} from '@chakra-ui/react';
import React from 'react';
import {
	BiAlignLeft,
	BiChevronLeft,
	BiChevronRight,
	BiPlay,
	BiTimer,
} from 'react-icons/bi';
import { Link as RouterLink } from 'react-router-dom';
import { getNavigationRoute } from '../../back-end/utils/nav';
import { isRightDir } from '../../back-end/utils/utils';
import { ContentNavigationSchema } from '../schemas';

interface Props {
	navigation: ContentNavigationSchema;
	courseId: number;
	isSidebarOpened: boolean;
}

const FloatingNavigation: React.FC<Props> = (props) => {
	const { navigation, courseId, isSidebarOpened } = props;

	const {
		isOpen: isPrevOpen,
		onOpen: onPrevOpen,
		onClose: onPrevClose,
	} = useDisclosure();
	const {
		isOpen: isNextOpen,
		onOpen: onNextOpen,
		onClose: onNextClose,
	} = useDisclosure();

	const getContentIcon = (
		itemType: 'quiz' | 'lesson' | string,
		video: boolean
	) => {
		if (itemType === 'quiz') {
			return BiTimer;
		}

		if (itemType === 'lesson') {
			if (video) {
				return BiPlay;
			} else {
				return BiAlignLeft;
			}
		}
	};

	return (
		<>
			{navigation?.previous && (
				<>
					<Box
						bg="white"
						shadow="box"
						position="fixed"
						p="6"
						ps="80px"
						top="50vh"
						left={isSidebarOpened ? '300px' : 0}
						transition="all 0.35s ease-in-out"
						transform={
							isPrevOpen ? 'translate(0, -50%)' : 'translate(-100%, -50%)'
						}
						rounded="xs">
						<HStack spacing="4">
							<Stack direction="row" spacing="2" align="center">
								<Stack direction="column" spacing="0">
									<Text fontSize="xs" color="gray.400">
										{navigation?.previous?.parent?.name}
									</Text>
									<Text fontWeight="bold">{navigation?.previous.name}</Text>
								</Stack>
								<Center
									p="2"
									bg="white"
									rounded="full"
									w="42px"
									h="42px"
									shadow="0 0 10px rgba(0,0,0,0.1)"
									fontSize="x-large"
									color="primary.500">
									<Icon
										as={getContentIcon(
											navigation?.previous?.type,
											navigation?.previous?.video
										)}
									/>
								</Center>
							</Stack>
						</HStack>
					</Box>
					<Link
						as={RouterLink}
						to={getNavigationRoute(
							navigation?.previous?.id,
							navigation?.previous?.type,
							courseId
						)}
						position="fixed"
						transition="all 0.35s ease-in-out"
						top="50%"
						transform="translateY(-50%)"
						left={isSidebarOpened ? '300px' : 0}
						cursor="pointer"
						color="gray.200"
						onMouseEnter={onPrevOpen}
						onMouseLeave={onPrevClose}
						_hover={{ color: 'primary.500' }}>
						<Icon
							as={isRightDir ? BiChevronRight : BiChevronLeft}
							fontSize={['3rem', null, '5rem']}
						/>
					</Link>
				</>
			)}

			{navigation?.next && (
				<>
					<Box
						bg="white"
						shadow="box"
						position="fixed"
						p="6"
						pe="80px"
						top="50vh"
						zIndex="99"
						right="0"
						transition="all 0.35s ease-in-out"
						transform={
							isNextOpen ? 'translate(0, -50%)' : 'translate(100%, -50%)'
						}
						rounded="xs">
						<HStack spacing="4">
							<Stack direction="row" spacing="2" align="center">
								<Center
									p="2"
									bg="white"
									rounded="full"
									w="42px"
									h="42px"
									shadow="0 0 10px rgba(0,0,0,0.1)"
									fontSize="x-large"
									color="primary.500">
									<Icon
										as={getContentIcon(
											navigation?.next?.type,
											navigation?.next?.video
										)}
									/>
								</Center>
								<Stack direction="column" spacing="0">
									<Text fontSize="xs" color="gray.400">
										{navigation?.next?.parent?.name}
									</Text>
									<Text fontWeight="bold">{navigation?.next?.name}</Text>
								</Stack>
							</Stack>
						</HStack>
					</Box>
					<Link
						as={RouterLink}
						to={getNavigationRoute(
							navigation?.next?.id,
							navigation?.next?.type,
							courseId
						)}
						position="fixed"
						top="50%"
						transform="translateY(-50%)"
						right="0"
						cursor="pointer"
						zIndex="999"
						color="gray.200"
						onMouseEnter={onNextOpen}
						onMouseLeave={onNextClose}
						_hover={{ color: 'primary.500' }}>
						<Icon
							as={isRightDir ? BiChevronLeft : BiChevronRight}
							fontSize={['3rem', null, '5rem']}
						/>
					</Link>
				</>
			)}
		</>
	);
};

export default FloatingNavigation;
