import {
	Center,
	CircularProgress,
	CircularProgressLabel,
	Text,
	VStack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect } from 'react';
import { useTimer } from 'react-timer-hook';

interface Props {
	duration: number;
	quizId: number;
	startedOn: any;
	onQuizeExpire: () => void;
	quizeAboutToExpire: any;
}
const FloatingTimer: React.FC<Props> = (props) => {
	const { duration, startedOn, onQuizeExpire, quizeAboutToExpire } = props;
	const time = new Date(startedOn);
	const formatDate = time.setMinutes(time.getMinutes() + duration);

	const { hours, seconds, minutes } = useTimer({
		expiryTimestamp: formatDate,
		onExpire: () => {
			onQuizeExpire();
		},
	});

	const quizCounterTime = hours * 60 * 60 + minutes * 60 + seconds;

	useEffect(() => {
		if (quizCounterTime <= 30) {
			quizeAboutToExpire(true);
		}
	}, [quizCounterTime, quizeAboutToExpire]);

	return (
		<Center
			position="fixed"
			right="40px"
			top="140px"
			w="110px"
			h="110px"
			bg="white"
			shadow="boxl"
			rounded="full">
			<CircularProgress
				value={quizCounterTime}
				max={duration * 60}
				capIsRound
				color={quizCounterTime <= 30 ? 'red.500' : 'primary.500'}
				size="140px"
				trackColor="transparent"
				thickness="5px">
				<CircularProgressLabel fontSize="lg">
					<VStack spacing="0">
						<Text fontSize="lg" fontWeight="bold" color="gray.700">
							{hours}:{minutes}:{seconds}
						</Text>
						<Text fontSize="10px" color="gray.500">
							{__('Time Left', 'masteriyo')}
						</Text>
					</VStack>
				</CircularProgressLabel>
			</CircularProgress>
		</Center>
	);
};

export default FloatingTimer;
