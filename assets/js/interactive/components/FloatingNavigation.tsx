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
	BiAlarm,
	BiAlignLeft,
	BiChevronLeft,
	BiChevronRight,
} from 'react-icons/bi';
import { Link as RouterLink } from 'react-router-dom';
import routes from '../constants/routes';
import { ContentNavigationSchema } from '../schemas';

interface Props {
	navigation: ContentNavigationSchema;
	courseId: number;
}

export const getNavigationRoute = (
	id: number,
	type: string,
	courseId: number
) => {
	if (type === 'lesson') {
		return routes.lesson
			.replace(':lessonId', id.toString())
			.replace(':courseId', courseId.toString());
	} else if (type === 'quiz') {
		return routes.quiz
			.replace(':quizId', id.toString())
			.replace(':courseId', courseId.toString());
	}
	return '';
};

const FloatingNavigation: React.FC<Props> = (props) => {
	const { navigation, courseId } = props;

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

	return (
		<>
			{navigation?.previous && (
				<>
					<Box
						bg="white"
						shadow="box"
						position="fixed"
						p="6"
						pl="80px"
						top="50vh"
						left="0"
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
									color="blue.500">
									<Icon
										as={
											navigation?.previous?.type === 'quiz'
												? BiAlarm
												: BiAlignLeft
										}
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
						top="50%"
						transform="translateY(-50%)"
						left="0"
						cursor="pointer"
						color="gray.200"
						onMouseEnter={onPrevOpen}
						onMouseLeave={onPrevClose}
						_hover={{ color: 'blue.500' }}>
						<Icon as={BiChevronLeft} fontSize="5rem" />
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
						pr="80px"
						top="50vh"
						zIndex="999"
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
									color="blue.500">
									<Icon
										as={
											navigation?.next?.type === 'quiz' ? BiAlarm : BiAlignLeft
										}
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
						_hover={{ color: 'blue.500' }}>
						<Icon as={BiChevronRight} fontSize="5rem" />
					</Link>
				</>
			)}
		</>
	);
};

export default FloatingNavigation;
