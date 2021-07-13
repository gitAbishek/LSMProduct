import { Box, Flex, Heading, Stack, Textarea } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { sectionHeaderStyles } from 'Config/styles';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	answersData?: any;
}

const ShortAnswer: React.FC<Props> = (props) => {
	const { answersData } = props;
	const { register } = useFormContext();

	return (
		<Stack direction="column" spacing="6">
			<Flex sx={sectionHeaderStyles}>
				<Heading fontSize="lg" fontWeight="semibold">
					{__('Answers', 'masteriyo')}
				</Heading>
			</Flex>
			<Box>
				<Textarea
					placeholder={__('Write your short answer', 'masteriyo')}
					defaultValue={answersData}
					{...register('answer', { required: true })}
				/>
			</Box>
		</Stack>
	);
};

export default ShortAnswer;
