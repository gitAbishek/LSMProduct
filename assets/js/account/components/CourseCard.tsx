import { TimeIcon } from '@chakra-ui/icons';
import {
	Badge,
	Box,
	Button,
	Image,
	Progress,
	Stack,
	Text,
} from '@chakra-ui/react';
import moment from 'moment';
import React from 'react';
import { default as StarEmptyIcon } from '../../../img/svgs/star-empty.js';
import { default as StarFullIcon } from '../../../img/svgs/star-full.js';
import { default as StarHalfIcon } from '../../../img/svgs/star-half.js';

interface Props {
	id: number;
	title: string;
	imageUrl: string;
	tag: string;
	rating: number;
	time: number;
	percent: number;
	progressValue: number;
	started: Date;
}

const CourseCard: React.FC<Props> = ({
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
			// borderRadius="lg"
			borderRadius="none"
			borderColor="hsl(240deg 8% 93% / 60%)"
			overflow="hidden">
			<Image src={imageUrl} alt={`${title} image`} />
			<Box p="6">
				<Stack direction={'row'} spacing={4}>
					{Array(noOfFullStars)
						.fill('')
						.map((_, i) => (
							<StarFullIcon key={i} color={'green'} />
						))}

					{Array(noOfHalfStars)
						.fill('')
						.map((_, i) => (
							<StarHalfIcon key={i} color={'green'} />
						))}
					{Array(noOfEmptyStars)
						.fill('')
						.map((_, i) => (
							<StarEmptyIcon key={i} color={'green'} />
						))}
					<Box>
						<Badge borderRadius="full" px="2" colorScheme="pink" color="white">
							{tag}
						</Badge>
					</Box>
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
						{'Started ' + moment(started).format('MMMM D, YYYY')}
					</Text>
					<Button
						flex={1}
						fontSize={'sm'}
						rounded={'full'}
						bg={'blue.600'}
						color={'white'}
						boxShadow={
							'0px 1px 25px -5px rgb(66 153 225 / 48%), 0 10px 10px -5px rgb(66 153 225 / 43%)'
						}
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

export default CourseCard;
