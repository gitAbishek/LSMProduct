import {
	Alert,
	AlertIcon,
	Box,
	Flex,
	Heading,
	Input,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { sectionHeaderStyles } from 'Config/styles';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	answersData?: any;
}

const ShortAnswer: React.FC<Props> = () => {
	const { register } = useFormContext();

	return (
		<Stack direction="column" spacing="6">
			<Flex sx={sectionHeaderStyles}>
				<Heading fontSize="lg" fontWeight="semibold">
					{__('Answers', 'masteriyo')}
				</Heading>
			</Flex>
			<Input type="hidden" {...register('answers')} defaultValue="" />
			<Box>
				<Alert status="info" fontSize="sm">
					<AlertIcon />
					{__("Short answer doesn't require any fields", 'masteriyo')}
				</Alert>
			</Box>
		</Stack>
	);
};

export default ShortAnswer;
