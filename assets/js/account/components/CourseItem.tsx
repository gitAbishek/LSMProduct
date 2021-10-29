import {
	Badge,
	Box,
	Button,
	Heading,
	Icon,
	Image,
	Progress,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiStar, BiTime } from 'react-icons/bi';

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
		<Box borderWidth="1px" borderColor="gray.100">
			<Image
				src={imageUrl}
				alt={`${title} image`}
				height="200px"
				objectFit="cover"
			/>
			<Stack direction="column" p="6" spacing="3">
				<Stack direction="column" spacing="6">
					<Stack direction="column" spacing="3">
						<Stack direction="row" spacing="3">
							<Stack direction="row" spacing="1">
								{Array(5)
									.fill('')
									.map((_, i) => (
										<Icon as={BiStar} key={i} />
									))}
							</Stack>
							<Badge
								borderRadius="full"
								bg="pink.500"
								fontSize="xx-small"
								color="white">
								{tag}
							</Badge>
						</Stack>

						<Heading as="h3" fontSize="lg">
							{title}
						</Heading>
					</Stack>

					<Stack direction="row" justify="space-between" align="center">
						<Stack direction="row" align="center" spacing="1">
							<Icon as={BiTime} />
							<Text
								fontSize={13}
								color={'gray.400'}
								fontWeight={'500'}
								_focus={{
									bg: 'gray.200',
								}}>
								{time}
								{__('hrs', 'masteriyo')}
							</Text>
						</Stack>
						<Text
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
						<Progress rounded="full" value={progressValue} size="xs" />
					</Box>

					<Stack
						direction="row"
						spacing="4"
						justify="space-between"
						align="center">
						<Text
							fontSize={12}
							color={'gray.500'}
							_focus={{
								bg: 'gray.200',
							}}>
							{'Started ' + started}
						</Text>
						<Button colorScheme="blue" size="sm" rounded="full">
							{__('Continue', 'masteriyo')}
						</Button>
					</Stack>
				</Stack>
			</Stack>
		</Box>
	);
};

export default CourseItem;
