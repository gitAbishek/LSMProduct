import { Td, Tr } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';

interface Props {
	order: string;
	date: string;
	status: string;
	total: number | string;
}

const OrderHistoryItem: React.FC<Props> = ({ order, date, status, total }) => {
	return (
		<Tr>
			<Td>{order}</Td>
			<Td>{date}</Td>
			<Td>{status}</Td>
			<Td>{total} for 1 item</Td>
			<Td>{__('View', 'masteriyo')}</Td>
		</Tr>
	);
};

export default OrderHistoryItem;
