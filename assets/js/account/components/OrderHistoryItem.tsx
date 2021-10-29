import { Td, Tr } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Link } from 'react-router-dom';

interface Props {
	order: string;
	date: string;
	status: string;
	total: number | string;
}

const OrderHistoryItem: React.FC<Props> = ({ order, date, status, total }) => {
	return (
		<Tr>
			<Td>
				<Link to="#" style={{ color: 'blue' }}>
					{order}
				</Link>
			</Td>
			<Td>{date}</Td>
			<Td>{status}</Td>
			<Td>{total} for 1 item</Td>
			<Td>
				<Link to="#" style={{ color: 'blue' }}>
					{__('View', 'masteriyo')}
				</Link>
			</Td>
		</Tr>
	);
};

export default OrderHistoryItem;
