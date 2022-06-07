import { SkeletonText } from '@chakra-ui/react';
import React from 'react';
import { Td, Tr } from 'react-super-responsive-table';

const QuizInfoSkeleton: React.FC = () => {
	return (
		<Tr>
			<Td>
				<SkeletonText width="120px" noOfLines={1} />
			</Td>
			<Td>
				<SkeletonText width="120px" noOfLines={4} />
			</Td>
			<Td>
				<SkeletonText width="120px" noOfLines={4} />
			</Td>
			<Td></Td>
		</Tr>
	);
};

export default QuizInfoSkeleton;
