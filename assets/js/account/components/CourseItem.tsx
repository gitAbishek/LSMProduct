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
import { MyCoursesSchema } from '../schemas';

interface Props {
	courseData: MyCoursesSchema;
}

const CourseItem: React.FC<Props> = (props) => {
	const { id, course, type, status, started_at } = props.courseData;
	return (
		<Box borderWidth="1px" borderColor="gray.100">
			<Box as="figure" pos="relative">
				<Image
					src={course?.featured_image_url}
					alt={course?.name}
					height="180px"
					objectFit="cover"
				/>
				{course?.difficulty && (
					<Tag
						pos="absolute"
						top="3"
						left="3"
						bg="blue.500"
						color="white"
						borderRadius="full"
						fontWeight="medium"
						size="sm">
						<TagLabel>{course?.difficulty?.name}</TagLabel>
					</Tag>
				)}
			</Box>
			<Stack direction="column" p="6" spacing="3">
				<Stack direction="column" spacing="6">
					<Stack direction="column" spacing="3">
						<Stack direction="row" spacing="1">
							{course?.categories?.map(
								(category: { id: number; name: string; slug: string }) => (
									<Tag
										key={category.id}
										size="sm"
										borderRadius="full"
										colorScheme="blue"
										border="1px"
										borderColor="gray.200">
										<TagLabel>{category.name}</TagLabel>
									</Tag>
								)
							)}
						</Stack>

						<Heading as="h3" fontSize="lg">
							{course.name}
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
							2% {__('Complete', 'masteriyo')}
						</Text>
					</Stack>
					<Box mt={5}>
						<Progress rounded="full" value={20} size="xs" />
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
							{'Started ' + started_at}
						</Text>
						<Button colorScheme="blue" size="sm" borderRadius="full">
							{__('Continue', 'masteriyo')}
						</Button>
					</Stack>
				</Stack>
			</Stack>
		</Box>
	);
};

export default CourseItem;
