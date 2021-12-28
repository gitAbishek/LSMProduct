import { Heading, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Col, Row } from 'react-grid-system';
import { useQuery } from 'react-query';
import { useLocation } from 'react-router-dom';
import urls from '../../../back-end/constants/urls';
import API from '../../../back-end/utils/api';

const OrderDetails: React.FC = () => {
	const ordersAPI = new API(urls.orders);
	const { orderId }: any = useLocation();
	const orderQuery = useQuery([`myOrder`, orderId], () => ordersAPI.list());

	return (
		<Stack direction="column" spacing="8" width="full">
			<Heading as="h4" size="md" fontWeight="bold" color="blue.900" px="8">
				{__('Order History', 'masteriyo')}
			</Heading>
			<Row>
				<Col md={4}></Col>
				<Col md={4}></Col>
				<Col md={4}></Col>
			</Row>
		</Stack>
	);
};

export default OrderDetails;
