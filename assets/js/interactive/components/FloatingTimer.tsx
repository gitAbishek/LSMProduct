import React from 'react';
import {
	Center,
	CircularProgress,
	CircularProgressLabel,
	Text,
	VStack,
} from '@chakra-ui/react';
import { useTimer } from 'react-timer-hook';

interface Props {
	expiryTimestamp: any;
}
const FloatingTimer: React.FC<Props> = (props) => {
	const { expiryTimestamp } = props;

	const { seconds, minutes } = useTimer({
		expiryTimestamp,
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
				value={minutes}
				max={10}
				capIsRound
				color="blue.500"
				size="140px"
				trackColor="transparent"
				thickness="5px">
				<CircularProgressLabel fontSize="lg">
					<VStack spacing="0">
						<Text fontSize="lg" fontWeight="bold" color="gray.700">
							{minutes}:{seconds}
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
