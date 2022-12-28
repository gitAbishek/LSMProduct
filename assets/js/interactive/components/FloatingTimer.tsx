import {
	Center,
	chakra,
	CircularProgress,
	CircularProgressLabel,
	shouldForwardProp,
	Text,
	VStack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { isValidMotionProp, motion } from 'framer-motion';
import React from 'react';
import { useTimer } from 'react-timer-hook';

interface Props {
	duration: number;
	quizId: number;
	startedOn: any;
	onQuizeExpire: () => void;
}
const FloatingTimer: React.FC<Props> = (props) => {
	const { duration, startedOn, onQuizeExpire } = props;
	const time = new Date(startedOn);
	const formatDate = time.setMinutes(time.getMinutes() + duration);

	const { hours, seconds, minutes } = useTimer({
		expiryTimestamp: formatDate,
		onExpire: () => {
			onQuizeExpire();
		},
	});

	const quizCounterTime = hours * 60 * 60 + minutes * 60 + seconds;
	const timingOut = quizCounterTime <= 30;

	const CircularBox = chakra(motion.div, {
		shouldForwardProp: (prop) =>
			isValidMotionProp(prop) || shouldForwardProp(prop),
	});
	return (
		<CircularBox
			animate={
				timingOut && {
					scale: [1, 1.1, 1, 1.5],
				}
			}
			// @ts-ignore
			transition={{
				duration: 3,
				ease: 'easeInOut',
				repeat: Infinity,
				repeatType: 'loop',
			}}
			position="fixed"
			right="40px"
			top="140px">
			<Center bg="white" shadow="boxl" rounded="full" w="110px" h="110px">
				<CircularProgress
					value={quizCounterTime}
					max={duration * 60}
					capIsRound
					color={timingOut ? 'red.500' : 'primary.500'}
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
		</CircularBox>
	);
};

export default FloatingTimer;
