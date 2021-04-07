import { Skeleton, Td, Tr } from '@chakra-ui/react';
import React from 'react';

export const SkeletonCourseList: React.FC = () => {
	const lengths = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
	return (
		<>
			{lengths.map((index) => (
				<Tr key={index}>
					<Td>
						<Skeleton h="3" />
					</Td>
					<Td>
						<Skeleton h="3" />
					</Td>
					<Td>
						<Skeleton h="3" />
					</Td>
					<Td>
						<Skeleton h="3" />
					</Td>
				</Tr>
			))}
		</>
	);
};
