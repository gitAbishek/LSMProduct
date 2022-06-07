import {
	Box,
	Container,
	Flex,
	Skeleton,
	SkeletonText,
	Stack,
} from '@chakra-ui/react';
import React from 'react';

const OrderSkeleton: React.FC = () => {
	return (
		<Container maxW="container.xl">
			<Box bg="white" p="10">
				<Stack display="flex" direction="column" spacing="8">
					<Skeleton height="40px" width="100px" />
					<Flex flexWrap="nowrap" gap="5">
						<Stack display="flex" flexDirection="column" flex="33%">
							<SkeletonText noOfLines={1} width="30px" />
							<Skeleton height="40px" />
						</Stack>
						<Stack display="flex" flexDirection="column" flex="33%">
							<SkeletonText noOfLines={1} width="30px" />
							<Skeleton height="40px" />
						</Stack>
						<Stack display="flex" flexDirection="column" flex="33%">
							<SkeletonText noOfLines={1} width="30px" />
							<Skeleton height="40px" />
						</Stack>
					</Flex>
					<Flex flexWrap="nowrap" gap="5">
						<Stack display="flex" flexDirection="column" flex="1">
							<SkeletonText noOfLines={1} width="30px" />
							<Skeleton height="40px" />
						</Stack>
						<Stack display="flex" flexDirection="column" flex="2">
							<SkeletonText noOfLines={1} width="30px" />
							<Skeleton height="40px" />
						</Stack>
					</Flex>
					<Stack display="flex" flexDirection="column" flex="1">
						<SkeletonText noOfLines={1} width="30px" />
						<Skeleton height="40px" />
					</Stack>
					<Stack display="flex" flexDirection="column" flex="1">
						<SkeletonText noOfLines={1} width="30px" />
						<Skeleton height="40px" />
					</Stack>
				</Stack>
			</Box>
		</Container>
	);
};

export default OrderSkeleton;
