import {
	Box,
	Container,
	Skeleton,
	SkeletonText,
	Stack,
} from '@chakra-ui/react';
import React from 'react';

const CategorySkeleton: React.FC = () => (
	<Container maxW="container.xl">
		<Box bg="white" p="10">
			<Stack direction="column" spacing="8">
				<Skeleton height="40px" width="100px" />
				<Stack direction="column" display="flex" spacing="6">
					<SkeletonText noOfLines={1} width="50px" />
					<Skeleton height="40px" />
				</Stack>
				<Stack direction="column" display="flex" spacing="6">
					<SkeletonText noOfLines={1} width="50px" />
					<Skeleton height="40px" />
				</Stack>
				<Stack direction="column" display="flex" spacing="6">
					<SkeletonText noOfLines={1} width="50px" />
					<Skeleton height="40px" />
				</Stack>
				<Stack direction="column" display="flex" spacing="6">
					<SkeletonText noOfLines={1} width="50px" />
					<Skeleton height="200px" />
				</Stack>
			</Stack>
		</Box>
	</Container>
);

export default CategorySkeleton;
