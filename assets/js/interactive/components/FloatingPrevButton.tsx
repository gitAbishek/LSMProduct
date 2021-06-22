import {
	Box,
	Icon,
	Text,
	Center,
	HStack,
	useDisclosure,
} from '@chakra-ui/react';
import { BiAlarm, BiChevronLeft } from 'react-icons/bi';
import React from 'react';
import { Stack } from '@chakra-ui/react';

const FloatingPrevButton = () => {
	const { isOpen, onOpen, onClose } = useDisclosure();

	return (
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
				transform={isOpen ? 'translate(0, -50%)' : 'translate(-100%, -50%)'}
				rounded="xs">
				<HStack spacing="4">
					<Stack direction="row" spacing="2" align="center">
						<Stack direction="column" spacing="0">
							<Text fontSize="xs" color="gray.400">
								Introduction
							</Text>
							<Text fontWeight="bold">Short Quiz</Text>
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
				onMouseEnter={onOpen}
				onMouseLeave={onClose}
				_hover={{ color: 'blue.500' }}>
				<Icon as={BiChevronLeft} fontSize="5rem" />
			</Box>
		</>
	);
};

export default FloatingPrevButton;
