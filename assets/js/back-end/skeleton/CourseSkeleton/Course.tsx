import { Box, Skeleton, SkeletonText, Stack } from '@chakra-ui/react';
import React from 'react';

const Course = () => (
	<Stack direction="row" w="100%">
		<Box
			bg="white"
			p={['4', null, '10']}
			shadow="box"
			d="flex"
			flex="2"
			flexDirection="column"
			justifyContent="space-between">
			<Stack direction="column" spacing="6">
				<Stack direction="column">
					<SkeletonText noOfLines={1} width="70px" />
					<Skeleton height="40px" />
				</Stack>
				<Stack direction="column">
					<SkeletonText noOfLines={1} width="70px" />
					<Skeleton height="350px" />
				</Stack>
			</Stack>
		</Box>
		<Box
			bg="white"
			p={['4', null, '10']}
			shadow="box"
			d="flex"
			flex="1"
			flexDirection="column"
			gap="20px">
			<Stack direction="column">
				<SkeletonText noOfLines={1} width="70px" />
				<Skeleton width="100%" height="100px" />
			</Stack>
			<Stack direction="column">
				<SkeletonText noOfLines={1} width="70px" />
				<Skeleton width="100%" height="50px" />
			</Stack>
			<Stack direction="column">
				<SkeletonText noOfLines={1} width="70px" />
				<Skeleton width="100%" height="50px" />
			</Stack>
		</Box>
	</Stack>
);

export default Course;
