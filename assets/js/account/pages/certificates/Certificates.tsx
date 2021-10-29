import { Box, Button, Heading, Stack, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { IoIosArrowForward } from 'react-icons/io';
import Certificate from '../../components/Certificate';
import data from './CertificateData';

const Certificates = () => {
	return (
		<>
			<Heading as="h1" size="xl">
				{__('My Certificates', 'masteriyo')}
			</Heading>

			<Stack direction="row" spacing="2" justify="space-between">
				<Text fontWeight={'bold'}>
					{__('COURSES', 'masteriyo')}
				</Text>

				<Text fontWeight={'bold'}>
					{__('CERTIFICATE', 'masteriyo')}
				</Text>
			</Stack>

			{data.map((itemProps, key) => {
				return <Certificate key={key} {...itemProps} />;
			})}

			<Box>
				<Button
					rightIcon={<IoIosArrowForward size={15} color={'gray.500'} />}
					color="gray.500"
					borderRadius="md"
					borderColor="gray.400"
					variant="outline">
					{__('SHOW ALL CERTIFICATES', 'masteriyo')}
				</Button>
			</Box>
		</>
	);
};

export default Certificates;
