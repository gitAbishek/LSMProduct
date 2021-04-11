import {
	ButtonGroup,
	Skeleton,
	SkeletonText,
	Stack,
	Td,
	Tr,
} from '@chakra-ui/react';
import Box from 'Components/layout/Box';
import React from 'react';

export const SkeletonCourseList: React.FC = () => {
	const lengths = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
	return (
		<>
			{lengths.map((index) => (
				<Tr key={index}>
					<Td>
						<SkeletonText noOfLines={1} />
					</Td>
					<Td>
						<SkeletonText noOfLines={1} />
					</Td>
					<Td>
						<SkeletonText noOfLines={1} />
					</Td>
					<Td>
						<Stack direction="row">
							<Skeleton h="3" w="6" />
							<Skeleton h="3" w="6" />
						</Stack>
					</Td>
				</Tr>
			))}
		</>
	);
};

export const SkeletonEditCourse: React.FC = () => {
	return (
		<Stack direction="row" spacing="8" w="full">
			<Box
				flex="1"
				bg="white"
				p="10"
				shadow="box"
				d="flex"
				flexDirection="column"
				justifyContent="space-between">
				<Skeleton h="6" w="full" />
				<Skeleton h="6" w="full" />
				<Skeleton h="6" w="full" />
			</Box>
			<Box w="400px" bg="white" p="10" shadow="box">
				<Skeleton h="6" />
				<Skeleton h="6" />
			</Box>
		</Stack>
	);
};
