import {
	Box,
	Heading,
	HStack,
	Icon,
	Image,
	Stack,
	Switch,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiLock } from 'react-icons/bi';

interface Props {
	addOnName: string;
	addOnDescription: string;
	thumbnailSrc: string;
}

const AddonItem: React.FC<Props> = (props) => {
	const { addOnName, addOnDescription, thumbnailSrc } = props;
	return (
		<Box
			borderColor="gray.100"
			rounded="sm"
			bg="white"
			mb="30px"
			shadow="sm"
			minH="xs">
			<Image
				src={thumbnailSrc}
				borderTopRightRadius="sm"
				borderTopLeftRadius="sm"
				w="full"
			/>
			<Box
				position="absolute"
				top="1"
				right="5"
				bg="lightseagreen"
				borderRadius="md"
				px="1"
				textAlign="center">
				<Text color="white" fontSize="xs" p="1">
					{__('Pro', 'masteriyo')}
				</Text>
			</Box>
			<Box p="6">
				<Stack direction="column" spacing="4">
					<Stack
						display={['flex', 'flex', 'block', 'flex']}
						direction={['row', 'row', 'column', 'row']}
						justify="space-between"
						align="center">
						<Heading fontSize="sm" fontWeight="semibold" color="gray.700">
							{addOnName}
						</Heading>
						<HStack>
							<Switch
								isChecked={false}
								colorScheme="green"
								onChange={(e) => {
									window.open(
										'https://masteriyo.com/wordpress-lms/pricing/',
										'_blank'
									);
								}}
							/>
							<Icon as={BiLock} boxSize="5" color="gray.300" />
						</HStack>
					</Stack>
					<Text color="gray.500">{addOnDescription}</Text>
				</Stack>
			</Box>
		</Box>
	);
};

export default AddonItem;
