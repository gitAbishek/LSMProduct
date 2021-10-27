import { StarIcon, TimeIcon } from '@chakra-ui/icons';
import {
	Badge,
	Box,
	Button,
	Image,
	Progress,
	Stack,
	Text,
} from '@chakra-ui/react';
import React from 'react';
import { BsStarHalf } from 'react-icons/bs';
import { ImStarEmpty } from 'react-icons/im';
interface Props {
	id: number;
	title: string;
	imageUrl: string;
	tag: string;
	rating: number;
	time: number;
	percent: number;
	progressValue: number;
	started: string;
}

const Course: React.FC<Props> = ({
	id,
	title,
	imageUrl,
	tag,
	rating,
	started,
	time,
	percent,
	progressValue,
}) => {
	const totalNumberOfStars = 5;
	const roundedRating =
		(Math.floor(rating / (totalNumberOfStars / 10)) * totalNumberOfStars) / 10;
	const noOfFullStars = Math.floor(roundedRating);
	const noOfHalfStars = noOfFullStars !== roundedRating ? 1 : 0;
	const noOfEmptyStars = Math.floor(
		totalNumberOfStars - noOfFullStars - noOfHalfStars
	);

	console.log(noOfFullStars, noOfHalfStars, noOfEmptyStars, roundedRating);

	return (
		<Box
			maxW="sm"
			borderWidth="1px"
			borderRadius="none"
			borderColor="gray.100"
			overflow="hidden">
			<Image src={imageUrl} alt={`${title} image`} />
			<Box p="6">
				<Stack direction={'row'} spacing={4}>
					{Array(noOfFullStars)
						.fill('')
						.map((_, i) => (
							<StarIcon key={i} />
						))}

					{Array(noOfHalfStars)
						.fill('')
						.map((_, i) => (
							<BsStarHalf key={i} />
						))}
					{Array(noOfEmptyStars)
						.fill('')
						.map((_, i) => (
							<ImStarEmpty key={i} />
						))}
					<Badge borderRadius="full" px="2" colorScheme="pink" color="white">
						{tag}
					</Badge>
				</Stack>

				<Box mt="1" fontWeight="bold" as="h4" lineHeight="tight" isTruncated>
					{title}
				</Box>

				<Stack mt={8} direction={'row'} spacing={4}>
					<Text
						flex={1}
						fontSize={13}
						color={'#424360'}
						fontWeight={'500'}
						_focus={{
							bg: 'gray.200',
						}}>
						<TimeIcon /> {time} hrs
					</Text>
					<Text
						flex={1}
						fontSize={13}
						fontWeight={'500'}
						color={'#7C7D8F'}
						_focus={{
							bg: '#424360',
						}}>
						{percent}% Complete
					</Text>
				</Stack>
				<Box mt={5}>
					<Progress value={progressValue} size="xs" />
				</Box>

				<Stack mt={8} direction={'row'} spacing={4}>
					<Text
						flex={1}
						fontSize={12}
						color={'#7C7D8F'}
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

export default Course;
