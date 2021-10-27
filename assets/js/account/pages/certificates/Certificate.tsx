import { ChevronRightIcon } from '@chakra-ui/icons';
import { Box, Button, Flex, Heading, Spacer, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import CertificateCard from '../../components/CertificateCard';
import data from './CertificateData';

const Certificate = () => {
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
				return <CertificateCard key={key} {...itemProps} />;
			})}

			<Box px={10} py={10}>
				<Button
					rightIcon={<ChevronRightIcon boxSize={6} />}
					colorScheme="gray.100"
					color="#7C7D8F"
					borderRadius="20px"
					variant="outline">
					{__('SHOW ALL CERTIFICATES', 'masteriyo')}
				</Button>
			</Box>
		</>
	);
};

export default Certificate;
