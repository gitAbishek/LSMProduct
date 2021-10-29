import {
	Badge,
	Box,
	Button,
	HStack,
	Image,
	Progress,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { AiFillStar } from 'react-icons/ai';
import { WiTime4 } from 'react-icons/wi';

interface Props {
	id: number;
	title: string;
	imageUrl: string;
	tag: string;
	time: number;
	percent: number;
	progressValue: number;
	started: string;
}

const CourseItem: React.FC<Props> = ({
	title,
	imageUrl,
	tag,
	started,
	time,
	percent,
	progressValue,
}) => {
	return (
		<Box maxW="sm" borderWidth="1px" borderColor="gray.100" overflow="hidden">
			<Image src={imageUrl} alt={`${title} image`} />
			<Box p="6">
				<Stack direction={'row'} spacing={4}>
					{Array(5)
						.fill('')
						.map((_, i) => (
							<AiFillStar key={i} />
						))}

					<Badge borderRadius="full" px="2" colorScheme="pink" color="white">
						{tag}
					</Badge>
				</Stack>

				<Box mt="2" fontWeight="bold" as="h4" lineHeight="tight" isTruncated>
					{title}
				</Box>

				<Stack mt={8} direction={'row'} spacing={10}>
					<HStack>
						<WiTime4 />
						<Text
							flex={1}
							fontSize={13}
							color={'gray.400'}
							fontWeight={'500'}
							_focus={{
								bg: 'gray.200',
							}}>
							{time}
							{__('hrs', 'masteriyo')}
						</Text>
					</HStack>
					<Text
						flex={1}
						fontSize={13}
						fontWeight={'500'}
						color={'gray.500'}
						_focus={{
							bg: 'gray.200',
						}}>
						{percent}% {__('Complete', 'masteriyo')}
					</Text>
				</Stack>
				<Box mt={5}>
					<Progress value={progressValue} size="xs" />
				</Box>

				<Stack mt={8} direction={'row'} spacing={4}>
					<Text
						flex={1}
						fontSize={12}
						color={'gray.500'}
						_focus={{
							bg: 'gray.200',
						}}>
						{'Started ' + started}
					</Text>
					<Button
						flex={1}
						fontSize={'sm'}
						rounded={'full'}
						bg={'blue.600'}
						color={'white'}
						_hover={{
							bg: 'blue.500',
						}}
						_focus={{
							bg: 'blue.500',
						}}>
						Continue
					</Button>
				</Stack>
			</Box>
		</Box>
	);
};

export default CourseItem;
