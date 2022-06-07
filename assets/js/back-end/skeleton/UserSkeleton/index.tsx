import { Flex, Skeleton, SkeletonText, Stack } from '@chakra-ui/react';
import React from 'react';

const UserSkeleton: React.FC = () => {
	return (
		<Stack display="flex" direction="column" spacing="8">
			<Flex flexWrap="nowrap" gap="5">
				<Stack display="flex" flexDirection="column" flex="2">
					<SkeletonText noOfLines={1} width="30px" />
					<Skeleton height="40px" />
				</Stack>
				<Stack display="flex" flexDirection="column" flex="1">
					<SkeletonText noOfLines={1} width="30px" />
					<Skeleton height="40px" />
				</Stack>
			</Flex>
			<Flex flexWrap="nowrap" gap="5">
				<Stack display="flex" flexDirection="column" flex="2">
					<SkeletonText noOfLines={1} width="30px" />
					<Skeleton height="40px" />
				</Stack>
				<Stack display="flex" flexDirection="column" flex="1">
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
	);
};

export default UserSkeleton;
