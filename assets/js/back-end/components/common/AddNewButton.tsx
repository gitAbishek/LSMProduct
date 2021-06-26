import { Circle, Icon, Stack, Text } from '@chakra-ui/react';
import React from 'react';
import { BiPlus } from 'react-icons/bi';

interface Props extends React.ComponentPropsWithRef<'button'> {}

const AddNewButton = React.forwardRef<HTMLButtonElement, Props>(
	(props, ref) => {
		const { children, ...other } = props;
		return (
			<button ref={ref} {...other}>
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
						{children}
					</Text>
				</Stack>
			</button>
		);
	}
);

AddNewButton.displayName = 'AddNewButton';
export default AddNewButton;
