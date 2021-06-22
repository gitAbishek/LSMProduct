import {
	Box,
	Icon,
	Text,
	Center,
	HStack,
	useDisclosure,
} from '@chakra-ui/react';
import { BiAlarm, BiChevronLeft, BiChevronRight } from 'react-icons/bi';
import React from 'react';
import { Stack } from '@chakra-ui/react';
import { ContentNavigationSchema } from '../schemas';

interface Props {
	navigation: ContentNavigationSchema;
}

const FloatingNavigation: React.FC<Props> = (props) => {
	const { navigation } = props;

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
			{navigation.previous && (
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
									<Icon as={BiAlarm} />
								</Center>
							</Stack>
						</HStack>
					</Box>
					<Box
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
					</Box>
				</>
			)}

			<Box
				bg="white"
				shadow="box"
				position="fixed"
				p="6"
				pr="80px"
				top="50vh"
				right="0"
				transition="all 0.35s ease-in-out"
				transform={isNextOpen ? 'translate(0, -50%)' : 'translate(100%, -50%)'}
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
							<Icon as={BiAlarm} />
						</Center>
						<Stack direction="column" spacing="0">
							<Text fontSize="xs" color="gray.400">
								Introduction
							</Text>
							<Text fontWeight="bold">Short Quiz</Text>
						</Stack>
					</Stack>
				</HStack>
			</Box>
			<Box
				position="fixed"
				top="50%"
				transform="translateY(-50%)"
				right="0"
				cursor="pointer"
				color="gray.200"
				onMouseEnter={onNextOpen}
				onMouseLeave={onNextClose}
				_hover={{ color: 'blue.500' }}>
				<Icon as={BiChevronRight} fontSize="5rem" />
			</Box>
		</>
	);
};

export default FloatingNavigation;
