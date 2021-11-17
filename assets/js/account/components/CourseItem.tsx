import {
	Avatar,
	Box,
	Button,
	Heading,
	Icon,
	Image,
	Progress,
	Stack,
	Tag,
	TagLabel,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiTime } from 'react-icons/bi';
import { IoMdStar, IoMdStarHalf, IoMdStarOutline } from 'react-icons/io';

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
	started,
	time,
	percent,
	progressValue,
}) => {
	return (
		<Box borderWidth="1px" borderColor="gray.100">
			<Box as="figure" pos="relative">
				<Image
					src={imageUrl}
					alt={`${title} image`}
					height="180px"
					objectFit="cover"
				/>
				<Tag
					pos="absolute"
					top="3"
					left="3"
					bg="blue.500"
					color="white"
					borderRadius="full"
					fontWeight="medium"
					size="sm">
					<TagLabel>Beginner</TagLabel>
				</Tag>
			</Box>
			<Stack direction="column" p="6" spacing="3">
				<Stack direction="column" spacing="6">
					<Stack direction="column" spacing="3">
						<Stack direction="row" spacing="1">
							<Tag
								size="sm"
								borderRadius="full"
								colorScheme="blue"
								border="1px"
								borderColor="gray.200">
								<TagLabel>Art</TagLabel>
							</Tag>
							<Tag
								size="sm"
								borderRadius="full"
								colorScheme="blue"
								border="1px"
								borderColor="gray.200">
								<TagLabel>Drawing</TagLabel>
							</Tag>
						</Stack>

						<Heading as="h3" fontSize="lg">
							{title}
						</Heading>
						<Stack
							direction="row"
							spacing="3"
							align="center"
							justify="space-between">
							<Stack direction="row" spacing="1" align="center">
								<Avatar size="xs" />
								<Text fontSize="xs" fontWeight="bold">
									John Doe
								</Text>
							</Stack>
							<Stack direction="row" spacing="0">
								<Icon as={IoMdStar} />
								<Icon as={IoMdStar} />
								<Icon as={IoMdStar} />
								<Icon as={IoMdStarHalf} />
								<Icon as={IoMdStarOutline} />
							</Stack>
						</Stack>
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
						<Button colorScheme="blue" size="sm">
							{__('Continue', 'masteriyo')}
						</Button>
					</Stack>
				</Stack>
			</Stack>
		</Box>
	);
};

export default CourseItem;
