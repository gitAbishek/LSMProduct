import { Box, Icon, Text, Center, HStack } from '@chakra-ui/react';
import { BiAlarm, BiChevronRight } from 'react-icons/bi';
import React from 'react';
import { Stack } from '@chakra-ui/react';

const FloatingNavigation = () => {
	return (
		<>
			<Box
				bg="white"
				shadow="box"
				position="fixed"
				pl="4"
				py="2"
				top="50vh"
				right="0"
				rounded="xs"
				transform="translateY(-50%)">
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
					<Icon as={BiChevronRight} fontSize="5rem" color="gray.200" />
				</HStack>
			</Box>
		</>
	);
};

export default FloatingNavigation;
