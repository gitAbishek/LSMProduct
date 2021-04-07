import {
	ButtonGroup,
	Skeleton,
	SkeletonText,
	Stack,
	Td,
	Tr,
} from '@chakra-ui/react';
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
