import {
	Center,
	CircularProgress,
	CircularProgressLabel,
	Text,
	VStack,
} from '@chakra-ui/react';
import React from 'react';
import { useTimer } from 'react-timer-hook';

interface Props {
	duration: number;
	quizId: number;
	startedOn: any;
}
const FloatingTimer: React.FC<Props> = (props) => {
	const { duration, startedOn } = props;
	const time = new Date(startedOn);
	const formatDate = time.setMinutes(time.getMinutes() + duration);

	const { hours, seconds, minutes } = useTimer({
		expiryTimestamp: formatDate,
		onExpire: () => console.warn('onExpire called'),
	});

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
				value={hours * 60 * 60 + minutes * 60 + seconds}
				max={duration * 60}
				capIsRound
				color="blue.500"
				size="140px"
				trackColor="transparent"
				thickness="5px">
				<CircularProgressLabel fontSize="lg">
					<VStack spacing="0">
						<Text fontSize="lg" fontWeight="bold" color="gray.700">
							{hours}:{minutes}:{seconds}
						</Text>
						<Text fontSize="10px" color="gray.500">
							ANSWERED:{' '}
							<Text as="span" color="gray.700">
								1
							</Text>
							:5
						</Text>
					</VStack>
				</CircularProgressLabel>
			</CircularProgress>
		</Center>
	);
};

export default FloatingTimer;
