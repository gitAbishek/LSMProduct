import { Circle, Icon, Stack, Text } from '@chakra-ui/react';
import React from 'react';
import { BiPlus } from 'react-icons/bi';

const AddNewButton: React.FC = (props) => {
	return (
		<button>
			<Stack direction="row" spacing="3" align="center" role="group">
				<Circle
					w="8"
					h="8"
					bg="blue.500"
					color="white"
					fontSize="x-large"
					transition="all 0.35s ease-in-out"
					_groupHover={{ bg: 'blue.700' }}>
					<Icon as={BiPlus} />
				</Circle>
				<Text
					fontWeight="semibold"
					transition="all 0.35s ease-in-out"
					color="gray.600"
					fontSize="sm"
					_groupHover={{ color: 'blue.700' }}>
					{props.children}
				</Text>
			</Stack>
		</button>
	);
};

export default AddNewButton;
