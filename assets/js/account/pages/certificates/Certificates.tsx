import { Box, Button, Flex, Heading, Spacer, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { IoIosArrowForward } from 'react-icons/io';
import Certificate from '../../components/Certificate';
import data from './CertificateData';

const Certificates = () => {
	return (
		<>
			<Box py={5}>
				<Stack px={10}>
					<Heading as="h1" size="xl">
						{__('My Certificates', 'masteriyo')}
					</Heading>
				</Stack>
			</Box>
			<Flex px={5}>
				<Box p="4" fontWeight={'bold'}>
					{__('COURSES', 'masteriyo')}
				</Box>
				<Spacer />
				<Box p="4" fontWeight={'bold'}>
					{__('CERTIFICATE', 'masteriyo')}
				</Box>
			</Flex>

			{data.map((itemProps, key) => {
				return <Certificate key={key} {...itemProps} />;
			})}

			<Box px={10} py={10}>
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
