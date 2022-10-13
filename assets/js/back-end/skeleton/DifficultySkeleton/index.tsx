import {
	Box,
	Container,
	Skeleton,
	SkeletonText,
	Stack,
} from '@chakra-ui/react';
import React from 'react';

const EditDifficultySkeleton: React.FC = () => (
	<Container maxW="container.xl">
		<Stack direction={['column', null, 'row']} spacing="8">
			<Box
				flex="1"
				bg="white"
				p={['4', null, '10']}
				shadow="box"
				d="flex"
				flexDirection="column"
				justifyContent="space-between">
				<Stack direction="column" spacing="8">
					<SkeletonText noOfLines={1} width="50px" />
					<Skeleton height="40px" />
					<Skeleton height="200px" />
				</Stack>
			</Box>
			<Box
				w={['100%', null, '400px']}
				bg="white"
				p={['4', null, '10']}
				shadow="box">
				<Stack direction="column" spacing="6">
					<Skeleton height="40px" />
				</Stack>
			</Box>
		</Stack>
	</Container>
);

export default EditDifficultySkeleton;
