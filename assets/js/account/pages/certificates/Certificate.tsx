import { ChevronRightIcon } from '@chakra-ui/icons';
import { Box, Button, Flex, Heading, Spacer, Stack } from '@chakra-ui/react';
import React from 'react';
import CertificateCard from '../../components/CertificateCard';
import data from './CertificateData';

const Certificate = () => {
	return (
		<>
			<Box py={5}>
				<Stack px={10}>
					<Heading>My Certificates</Heading>
				</Stack>
			</Box>
			<Flex px={5}>
				<Box p="4" fontWeight={'bold'}>
					COURSES
				</Box>
				<Spacer />
				<Box p="4" fontWeight={'bold'}>
					CERTIFICATE
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
					SHOW ALL Certificates
				</Button>
			</Box>
		</>
	);
};

export default Certificate;
