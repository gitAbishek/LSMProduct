import { Td, Text, Tr } from '@chakra-ui/react';
import React from 'react';

interface Props {
	message: string;
}

const EmptyInfo: React.FC<Props> = (props) => {
	const { message } = props;
	return (
		// Adjust in table structure of backend courselist, orders and categories.
		<Tr>
			<Td>
				<Text as="span" fontWeight="medium" color="gray.600">
					{message}
				</Text>
			</Td>
			<Td></Td>
			<Td></Td>
			<Td></Td>
			<Td></Td>
			<Td></Td>
		</Tr>
	);
};

export default EmptyInfo;
