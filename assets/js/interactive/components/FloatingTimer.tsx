import React from 'react';
import {
	Center,
	CircularProgress,
	CircularProgressLabel,
	Text,
} from '@chakra-ui/react';
import { useTimer } from 'react-timer-hook';

interface Props {
	expiryTimestamp: any;
}
const FloatingTimer: React.FC<Props> = (props) => {
	const { expiryTimestamp } = props;

	const {
		seconds,
		minutes,
		hours,
		days,
		isRunning,
		start,
		pause,
		resume,
		restart,
	} = useTimer({
		expiryTimestamp,
		onExpire: () => console.warn('onExpire called'),
	});

	return (
		<Center
			position="fixed"
			right="40px"
			top="140px"
			w="120px"
			h="120px"
			bg="white"
			rounded="full">
			<CircularProgress
				value={minutes}
				max={60}
				capIsRound
				color="blue.500"
				size="140px"
				trackColor="transparent"
				thickness="5px">
				<CircularProgressLabel fontSize="lg">
					<Text fontSize="lg" fontWeight="bold">
						{minutes}:{seconds}
					</Text>
					<Text fontSize="10px">ANSWERED: 1:5</Text>
				</CircularProgressLabel>
			</CircularProgress>
		</Center>
	);
};

export default FloatingTimer;
