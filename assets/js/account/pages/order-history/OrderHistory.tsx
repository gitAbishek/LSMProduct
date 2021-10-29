import {
	Box,
	Container,
	Table,
	Tbody,
	Td,
	Th,
	Thead,
	Tr,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';

const OrderHistory = () => {
	return (
		<Container maxWidth="xl" py={10}>
			<Box bg="white" shadow="box" w="full">
				<Table variant="simple">
					<Thead>
						<Tr>
							<Th>{__('Order', 'masteriyo')}</Th>
							<Th>{__('Date', 'masteriyo')}</Th>
							<Th>{__('Status', 'masteriyo')}</Th>
							<Th>{__('Total', 'masteriyo')}</Th>
							<Th>{__('Actions', 'masteriyo')}</Th>
						</Tr>
					</Thead>
					<Tbody>
						<Tr>
							<Td>666</Td>
							<Td>October 29, 2021</Td>
							<Td>On Hold</Td>
							<Td>$39.90 for 1 item</Td>
							<Td>View</Td>
						</Tr>
					</Tbody>
				</Table>
			</Box>
		</Container>
	);
};

export default OrderHistory;
