import { SkeletonText } from '@chakra-ui/react';
import React from 'react';
import { Td, Tr } from 'react-super-responsive-table';

const QuizOverviewSkeleton: React.FC = () => {
	let length: number[] = [1, 2, 3];
	return (
		<>
			{length.map((x) => (
				<Tr key={x}>
					<Td>
						<SkeletonText width="50px" noOfLines={1} />
					</Td>
					<Td>
						<SkeletonText width="80px" noOfLines={2} />
					</Td>
					<Td>
						<SkeletonText width="80px" noOfLines={1} />
					</Td>
					<Td>
						<SkeletonText width="20px" noOfLines={1} float="right" />
					</Td>
				</Tr>
			))}
		</>
	);
};

export default QuizOverviewSkeleton;
