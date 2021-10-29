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
import data from './OrderData';

interface Props {
	order: string;
	date: string;
	status: string;
	total: number;
}

const OrderHistory: React.FC<Props> = ({ order, date, status, total }) => {
	return (
		<Container maxWidth="xl" py={10}>
			<Box bg="white" shadow="box">
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
						{data.map((data, key) => {
							<Tr key={key}>
								<Td>{data.order}</Td>
								<Td>{data.date}</Td>
								<Td>{data.status}</Td>
								<Td>{data.total} for 1 item</Td>
								<Td>{__('View', 'masteriyo')}</Td>
							</Tr>;
						})}
					</Tbody>
				</Table>
			</Box>
		</Container>
	);
};

export default OrderHistory;
